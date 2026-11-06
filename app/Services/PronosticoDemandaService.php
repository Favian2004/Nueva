<?php

namespace App\Services;

use App\Models\Categoria;
use App\Models\Vacante;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * Pronóstico de demanda de trabajo.
 *
 * Cuenta cuántas vacantes (o cuántos trabajadores solicitados) se han publicado
 * por día de la semana y por mes, opcionalmente solo de una categoría /
 * subcategoría, y proyecta los siguientes meses.
 *
 * NO es inteligencia artificial: es estadística simple y explicable.
 *  - Día de la semana: cuánto del total cae en cada día.
 *  - Meses: promedio ponderado de los últimos 3 meses completos (el más reciente
 *    pesa más) + una tendencia suavizada. Cuando ya hay más de un año de datos,
 *    se promedia con lo que pasó en ese mismo mes el año anterior (estacionalidad).
 *
 * Los meses anteriores a la primera vacante de TODA la plataforma no cuentan
 * como "cero demanda": simplemente todavía no había datos.
 */
class PronosticoDemandaService
{
    public const MESES_HISTORIAL = 12;
    public const MESES_PROYECCION = 3;

    // Umbrales para avisar que hay pocos datos.
    public const MIN_REGISTROS_DIAS = 20;
    public const MIN_REGISTROS_PROYECCION = 30;

    private const DIAS = [
        1 => ['Lunes', 'Lun'],
        2 => ['Martes', 'Mar'],
        3 => ['Miércoles', 'Mié'],
        4 => ['Jueves', 'Jue'],
        5 => ['Viernes', 'Vie'],
        6 => ['Sábado', 'Sáb'],
        7 => ['Domingo', 'Dom'],
    ];

    private const MESES = [
        1 => ['Enero', 'Ene'],
        2 => ['Febrero', 'Feb'],
        3 => ['Marzo', 'Mar'],
        4 => ['Abril', 'Abr'],
        5 => ['Mayo', 'May'],
        6 => ['Junio', 'Jun'],
        7 => ['Julio', 'Jul'],
        8 => ['Agosto', 'Ago'],
        9 => ['Septiembre', 'Sep'],
        10 => ['Octubre', 'Oct'],
        11 => ['Noviembre', 'Nov'],
        12 => ['Diciembre', 'Dic'],
    ];

    /**
     * @param string $medida 'vacantes' (cuenta publicaciones) o 'trabajadores' (suma los trabajadores solicitados)
     */
    public function calcular(
        ?int $categoriaId = null,
        ?int $subcategoriaId = null,
        string $medida = 'vacantes',
        ?CarbonInterface $ahora = null
    ): array {
        $tz = config('app.timezone');
        $medida = $medida === 'trabajadores' ? 'trabajadores' : 'vacantes';
        $ahora = $ahora ? CarbonImmutable::instance($ahora)->setTimezone($tz) : CarbonImmutable::now($tz);
        $mesActual = $ahora->startOfMonth();

        // ¿Desde cuándo hay datos en la plataforma? (no depende del filtro)
        $primeraGlobal = Vacante::min('created_at');
        $inicioPlataforma = $primeraGlobal
            ? CarbonImmutable::parse($primeraGlobal, $tz)->startOfMonth()
            : null;

        $filas = Vacante::query()
            ->when($categoriaId, fn($q) => $q->where('categoria_id', $categoriaId))
            ->when($subcategoriaId, fn($q) => $q->where('subcategoria_id', $subcategoriaId))
            ->get(['created_at', 'trabajadores_requeridos']);

        $porDia = array_fill(1, 7, 0);
        $porMes = [];
        $total = 0;
        $registros = 0;
        $primerRegistro = null;

        foreach ($filas as $fila) {
            if (!$fila->created_at) {
                continue;
            }
            $fecha = CarbonImmutable::instance($fila->created_at)->setTimezone($tz);
            $valor = $medida === 'trabajadores' ? max(1, (int) $fila->trabajadores_requeridos) : 1;

            $porDia[$fecha->dayOfWeekIso] += $valor;
            $clave = $fecha->format('Y-m');
            $porMes[$clave] = ($porMes[$clave] ?? 0) + $valor;
            $total += $valor;
            $registros++;

            if ($primerRegistro === null || $fecha->lt($primerRegistro)) {
                $primerRegistro = $fecha;
            }
        }

        // ---------- Días de la semana ----------
        $dias = [];
        $diaTop = null;
        foreach (self::DIAS as $n => [$nombre, $corto]) {
            $valor = $porDia[$n];
            $item = [
                'n' => $n,
                'nombre' => $nombre,
                'corto' => $corto,
                'valor' => $valor,
                'pct' => $total > 0 ? round($valor * 100 / $total) : 0,
                'es_top' => false,
            ];
            if ($valor > 0 && ($diaTop === null || $valor > $diaTop['valor'])) {
                $diaTop = $item;
            }
            $dias[] = $item;
        }
        if ($diaTop) {
            foreach ($dias as &$d) {
                $d['es_top'] = $d['n'] === $diaTop['n'];
            }
            unset($d);
            $diaTop['es_top'] = true;
        }

        // ---------- Últimos 12 meses (incluye el mes en curso) ----------
        $meses = $this->ventana($porMes, $mesActual, $inicioPlataforma);

        $mesTop = null;
        foreach ($meses as $m) {
            if (!$m['sin_datos'] && $m['valor'] > 0 && ($mesTop === null || $m['valor'] > $mesTop['valor'])) {
                $mesTop = $m;
            }
        }

        // ---------- Proyección ----------
        $pron = $this->proyectar($porMes, $meses, $mesActual, $inicioPlataforma);
        $proyeccion = $pron['proyeccion'];
        $metodo = $pron['metodo'];
        $nivel = $pron['nivel'];

        $proyeccionTop = null;
        foreach ($proyeccion as $p) {
            if ($proyeccionTop === null || $p['valor'] > $proyeccionTop['valor']) {
                $proyeccionTop = $p;
            }
        }

        $nivelRedondeado = $nivel !== null ? (int) round($nivel) : null;
        $variacionSiguiente = null; // % del próximo mes vs. el promedio reciente
        if ($proyeccion && $nivel > 0) {
            $variacionSiguiente = (int) round(($proyeccion[0]['valor'] - $nivel) * 100 / $nivel);
        }

        // ---------- Confianza ----------
        $mesesHistoria = $this->mesesDeHistoria($mesActual, $inicioPlataforma);

        $confianza = $this->confianza($registros, $mesesHistoria);

        $maxDias = max(array_column($dias, 'valor'));
        $maxMeses = max(array_merge(array_column($meses, 'valor'), array_column($proyeccion, 'valor')));

        return [
            'medida' => $medida,
            'unidad' => $medida === 'trabajadores' ? 'trabajadores solicitados' : 'vacantes publicadas',
            'total' => $total,
            'registros' => $registros,
            'primer_registro' => $primerRegistro ? $this->nombreMes($primerRegistro) : null,
            'meses_completos' => $mesesHistoria,

            'dias' => $dias,
            'dia_top' => $diaTop,
            'dias_pocos_datos' => $registros < self::MIN_REGISTROS_DIAS,
            'escala_dias' => $this->escala($maxDias),

            'meses' => $meses,
            'mes_top' => $mesTop,
            'proyeccion' => $proyeccion,
            'proyeccion_top' => $proyeccionTop,
            'proyeccion_metodo' => $metodo,
            'nivel_reciente' => $nivelRedondeado,
            'variacion_siguiente' => $variacionSiguiente,
            'escala_meses' => $this->escala($maxMeses),

            'confianza' => $confianza,
        ];
    }

    /**
     * Ranking de categorías: ¿qué tipo de trabajo se viene fuerte?
     *
     * Compara TODAS las categorías entre sí (sin elegir una):
     *  - este_mes: las que más han publicado en lo que va del mes.
     *  - proximo_mes: las que más se espera que publiquen el mes que entra
     *    (misma proyección que usa la página, calculada por categoría).
     *
     * Las vacantes sin categoría (las anteriores al campo) no entran.
     */
    public function ranking(string $medida = 'vacantes', ?CarbonInterface $ahora = null, int $limite = 5): array
    {
        $tz = config('app.timezone');
        $medida = $medida === 'trabajadores' ? 'trabajadores' : 'vacantes';
        $ahora = $ahora ? CarbonImmutable::instance($ahora)->setTimezone($tz) : CarbonImmutable::now($tz);
        $mesActual = $ahora->startOfMonth();
        $claveActual = $mesActual->format('Y-m');

        $primeraGlobal = Vacante::min('created_at');
        $inicioPlataforma = $primeraGlobal
            ? CarbonImmutable::parse($primeraGlobal, $tz)->startOfMonth()
            : null;

        $filas = Vacante::query()
            ->whereNotNull('categoria_id')
            ->get(['categoria_id', 'created_at', 'trabajadores_requeridos']);

        $porCategoria = [];   // categoria_id => ['Y-m' => valor]
        $registros = 0;
        foreach ($filas as $fila) {
            if (!$fila->created_at) {
                continue;
            }
            $fecha = CarbonImmutable::instance($fila->created_at)->setTimezone($tz);
            $valor = $medida === 'trabajadores' ? max(1, (int) $fila->trabajadores_requeridos) : 1;
            $clave = $fecha->format('Y-m');
            $porCategoria[$fila->categoria_id][$clave] = ($porCategoria[$fila->categoria_id][$clave] ?? 0) + $valor;
            $registros++;
        }

        $nombres = Categoria::pluck('nombre', 'id');

        $esteMes = [];
        $proximoMes = [];
        foreach ($porCategoria as $id => $porMes) {
            $nombre = $nombres[$id] ?? null;
            if ($nombre === null) {
                continue; // la categoría ya no existe
            }

            $valorActual = $porMes[$claveActual] ?? 0;
            if ($valorActual > 0) {
                $esteMes[] = ['id' => (int) $id, 'nombre' => $nombre, 'valor' => $valorActual];
            }

            $meses = $this->ventana($porMes, $mesActual, $inicioPlataforma);
            $pron = $this->proyectar($porMes, $meses, $mesActual, $inicioPlataforma);
            if ($pron['proyeccion'] && $pron['proyeccion'][0]['valor'] > 0) {
                $siguiente = $pron['proyeccion'][0]['valor'];
                $nivel = $pron['nivel'];
                $proximoMes[] = [
                    'id' => (int) $id,
                    'nombre' => $nombre,
                    'valor' => $siguiente,
                    // % vs. el promedio reciente de esa categoría
                    'variacion' => $nivel > 0 ? (int) round(($siguiente - $nivel) * 100 / $nivel) : null,
                ];
            }
        }

        $ordenar = function (array $lista) use ($limite): array {
            usort($lista, fn($a, $b) => [$b['valor'], $a['nombre']] <=> [$a['valor'], $b['nombre']]);
            $lista = array_slice($lista, 0, $limite);
            $max = $lista ? max(array_column($lista, 'valor')) : 0;
            foreach ($lista as $i => &$fila) {
                $fila['puesto'] = $i + 1;
                $fila['barra'] = $max > 0 ? round($fila['valor'] * 100 / $max, 1) : 0; // % del primero
            }
            unset($fila);

            return $lista;
        };

        $mesesHistoria = $this->mesesDeHistoria($mesActual, $inicioPlataforma);
        $siguienteMes = $mesActual->addMonth();

        return [
            'medida' => $medida,
            'unidad' => $medida === 'trabajadores' ? 'trabajadores' : 'vacantes',
            'mes_actual' => $this->nombreMes($mesActual),
            'mes_siguiente' => $this->nombreMes($siguienteMes),
            'mes_actual_corto' => self::MESES[$mesActual->month][0],
            'mes_siguiente_corto' => self::MESES[$siguienteMes->month][0],
            'dia_actual' => $ahora->day,
            'este_mes' => $ordenar($esteMes),
            'proximo_mes' => $ordenar($proximoMes),
            'registros' => $registros,
            'sin_categoria' => Vacante::whereNull('categoria_id')->count(),
            'confianza' => $this->confianza($registros, $mesesHistoria),
        ];
    }

    /**
     * Los últimos 12 meses (el actual incluido) con su valor. Los meses anteriores
     * a la primera vacante de la plataforma se marcan "sin_datos" (no son "cero").
     */
    private function ventana(array $porMes, CarbonImmutable $mesActual, ?CarbonImmutable $inicioPlataforma): array
    {
        $meses = [];
        for ($i = self::MESES_HISTORIAL - 1; $i >= 0; $i--) {
            $m = $mesActual->subMonths($i);
            $sinDatos = $inicioPlataforma === null || $m->lt($inicioPlataforma);
            $meses[] = $this->itemMes($m, $sinDatos ? 0 : ($porMes[$m->format('Y-m')] ?? 0), [
                'tipo' => 'real',
                'sin_datos' => $sinDatos,
                'en_curso' => $i === 0,
            ]);
        }

        return $meses;
    }

    /**
     * Proyección de los próximos meses a partir de la ventana de meses.
     *
     * @return array{proyeccion: array, metodo: ?string, nivel: ?float}
     */
    private function proyectar(array $porMes, array $meses, CarbonImmutable $mesActual, ?CarbonImmutable $inicioPlataforma): array
    {
        // Solo meses COMPLETOS con datos (el mes en curso aún no termina).
        $completos = array_values(array_filter(
            $meses,
            fn($m) => !$m['sin_datos'] && !$m['en_curso']
        ));
        $nCompletos = count($completos);

        $proyeccion = [];
        $metodo = null;
        $nivel = null;

        if ($nCompletos >= 1) {
            $valores = array_column($completos, 'valor');

            // Promedio ponderado de los últimos (hasta) 3 meses completos.
            $ultimos = array_slice($valores, -3);
            $pesos = array_slice([0.2, 0.3, 0.5], -count($ultimos));
            $nivel = 0.0;
            foreach ($ultimos as $i => $v) {
                $nivel += $v * $pesos[$i];
            }
            $nivel /= array_sum($pesos);

            // Tendencia suavizada (solo si hay al menos 4 meses completos).
            $pendiente = $nCompletos >= 4 ? 0.5 * $this->pendiente(array_slice($valores, -6)) : 0.0;

            // Crecimiento de los últimos 3 meses vs. los mismos 3 de hace un año (para estacionalidad).
            $crecimiento = $this->crecimientoAnual($porMes, $mesActual, $inicioPlataforma);

            for ($k = 1; $k <= self::MESES_PROYECCION; $k++) {
                $objetivo = $mesActual->addMonths($k);
                $pasos = $k + 1; // meses desde el último mes completo

                $base = $nivel + $pendiente * $pasos;
                $base = min(max($base, $nivel * 0.5), $nivel * 2);

                $mismoMesAnioPasado = $objetivo->subMonths(12);
                $hayReferencia = $inicioPlataforma !== null && $mismoMesAnioPasado->gte($inicioPlataforma);

                if ($hayReferencia) {
                    $referencia = ($porMes[$mismoMesAnioPasado->format('Y-m')] ?? 0) * $crecimiento;
                    $valor = 0.5 * $base + 0.5 * $referencia;
                    $metodo = 'tendencia_estacional';
                } else {
                    $valor = $base;
                    $metodo ??= 'tendencia';
                }

                $proyeccion[] = $this->itemMes($objetivo, (int) round(max(0, $valor)), [
                    'tipo' => 'proyeccion',
                    'sin_datos' => false,
                    'en_curso' => false,
                ]);
            }
        }

        return ['proyeccion' => $proyeccion, 'metodo' => $metodo, 'nivel' => $nivel];
    }

    // Meses completos transcurridos desde la primera vacante de la plataforma.
    private function mesesDeHistoria(CarbonImmutable $mesActual, ?CarbonImmutable $inicioPlataforma): int
    {
        return $inicioPlataforma
            ? max(0, ($mesActual->year - $inicioPlataforma->year) * 12 + ($mesActual->month - $inicioPlataforma->month))
            : 0;
    }

    private function confianza(int $registros, int $mesesHistoria): string
    {
        if ($registros < self::MIN_REGISTROS_PROYECCION || $mesesHistoria < 3) {
            return 'baja';
        }
        if ($registros >= 100 && $mesesHistoria >= 12) {
            return 'alta';
        }

        return 'media';
    }

    /**
     * Escala "bonita" para el eje Y: máximo y marcas redondas (hasta ~4 tramos).
     */
    public function escala(int|float $max): array
    {
        if ($max <= 0) {
            return ['max' => 4, 'ticks' => [0, 1, 2, 3, 4]];
        }

        // Entre los pasos "redondos" que dejan 4 tramos o menos, se usa el que
        // da el tope más bajo (para que las barras aprovechen la altura).
        $pasos = [1, 2, 5, 10, 20, 25, 50, 100, 200, 250, 500, 1000, 2000, 2500, 5000, 10000, 20000, 25000, 50000];
        $paso = null;
        $tope = null;
        foreach ($pasos as $p) {
            if (ceil($max / $p) > 4) {
                continue;
            }
            $candidato = (int) (ceil($max / $p) * $p);
            if ($tope === null || $candidato < $tope) {
                $tope = $candidato;
                $paso = $p;
            }
        }
        if ($paso === null) {
            $paso = 10 ** (int) ceil(log10($max / 4));
            $tope = (int) (ceil($max / $paso) * $paso);
        }

        $ticks = [];
        for ($t = 0; $t <= $tope; $t += $paso) {
            $ticks[] = $t;
        }

        return ['max' => $tope, 'ticks' => $ticks];
    }

    private function itemMes(CarbonImmutable $m, int $valor, array $extra): array
    {
        return array_merge([
            'clave' => $m->format('Y-m'),
            'corto' => self::MESES[$m->month][1],
            'nombre' => self::MESES[$m->month][0] . ' ' . $m->year,
            'mes' => self::MESES[$m->month][0],
            'anio' => $m->year,
            'enero' => $m->month === 1,
            'valor' => $valor,
        ], $extra);
    }

    private function nombreMes(CarbonImmutable $m): string
    {
        return self::MESES[$m->month][0] . ' ' . $m->year;
    }

    /**
     * Pendiente de la recta de mínimos cuadrados (cuánto sube o baja por mes).
     */
    private function pendiente(array $y): float
    {
        $n = count($y);
        if ($n < 2) {
            return 0.0;
        }
        $xm = ($n - 1) / 2;
        $ym = array_sum($y) / $n;
        $num = 0.0;
        $den = 0.0;
        foreach (array_values($y) as $i => $v) {
            $num += ($i - $xm) * ($v - $ym);
            $den += ($i - $xm) ** 2;
        }

        return $den > 0 ? $num / $den : 0.0;
    }

    /**
     * Cuánto creció (o bajó) la demanda: últimos 3 meses completos vs. los mismos 3 de un año antes.
     * Devuelve 1.0 (sin cambio) si no hay con qué comparar. Acotado entre 0.5 y 2.
     */
    private function crecimientoAnual(array $porMes, CarbonImmutable $mesActual, ?CarbonImmutable $inicioPlataforma): float
    {
        if ($inicioPlataforma === null) {
            return 1.0;
        }

        $ahora = 0;
        $antes = 0;
        for ($i = 1; $i <= 3; $i++) {
            $reciente = $mesActual->subMonths($i);
            $previo = $reciente->subMonths(12);
            if ($previo->lt($inicioPlataforma)) {
                return 1.0;
            }
            $ahora += $porMes[$reciente->format('Y-m')] ?? 0;
            $antes += $porMes[$previo->format('Y-m')] ?? 0;
        }

        if ($antes <= 0) {
            return 1.0;
        }

        return min(max($ahora / $antes, 0.5), 2.0);
    }
}
