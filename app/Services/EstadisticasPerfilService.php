<?php

namespace App\Services;

use App\Models\Calificacion;
use App\Models\Contratacion;
use App\Models\Postulacion;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\Vacante;
use Carbon\Carbon;

/**
 * Arma los números de la pestaña "Estadísticas" del perfil, con los datos
 * reales de la persona: lo que le solicitaron, sus reseñas, sus
 * postulaciones y (si publica vacantes) cómo van sus vacantes.
 */
class EstadisticasPerfilService
{
    private const MESES = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    public function para(Usuario $usuario): array
    {
        $eventos = [];

        // ------------------------------------------------------------
        // Como trabajador: solicitudes que le llegan a sus servicios
        // ------------------------------------------------------------
        $servicios = Servicio::where('usuario_id', $usuario->id)->get(['id', 'titulo'])->keyBy('id');

        $solicitudes = Contratacion::with('contratante:id,nombre')
            ->whereIn('servicio_id', $servicios->keys())
            ->get();

        $inicioMes = today()->startOfMonth();
        $solicitudesEsteMes = $solicitudes->filter(fn($c) => $c->created_at && $c->created_at->gte($inicioMes))->count();
        $terminados = $solicitudes->where('estado', 'finalizado')->count();

        foreach ($solicitudes as $c) {
            $servicio = $servicios[$c->servicio_id]->titulo ?? 'tu servicio';
            if ($c->created_at) {
                $quien = $c->contratante->nombre ?? 'Alguien';
                $eventos[] = $this->evento($c->created_at, 'mdi-inbox-arrow-down', 'o', "{$quien} solicitó «{$servicio}»");
            }
            if ($c->estado === 'finalizado' && $c->updated_at) {
                $eventos[] = $this->evento($c->updated_at, 'mdi-check', 'g', "Terminaste un trabajo de «{$servicio}»");
            }
        }

        // Servicio más solicitado
        $masSolicitado = null;
        $porServicio = $solicitudes->groupBy('servicio_id')->map->count()->sortDesc();
        if ($porServicio->isNotEmpty()) {
            $id = $porServicio->keys()->first();
            $masSolicitado = ['titulo' => $servicios[$id]->titulo ?? 'Servicio', 'total' => $porServicio->first()];
        }

        // Solicitudes por mes (últimos 6 meses, contando el actual)
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = today()->startOfMonth()->subMonthsNoOverflow($i);
            $meses[$mes->format('Y-m')] = ['nombre' => self::MESES[$mes->month - 1], 'total' => 0];
        }
        foreach ($solicitudes as $c) {
            $clave = $c->created_at?->format('Y-m');
            if ($clave && isset($meses[$clave])) {
                $meses[$clave]['total']++;
            }
        }
        $meses = array_values($meses);
        $maxMes = max(1, ...array_column($meses, 'total'));
        foreach ($meses as &$m) {
            $m['altura'] = $m['total'] > 0 ? max(12, (int) round($m['total'] / $maxMes * 100)) : 3;
        }
        unset($m);

        // ------------------------------------------------------------
        // Reseñas recibidas
        // ------------------------------------------------------------
        $resenas = Calificacion::with(['usuario:id,nombre', 'servicio:id,titulo'])
            ->whereIn('servicio_id', $servicios->keys())
            ->latest()
            ->get();

        $desglose = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($resenas as $r) {
            $e = (int) round($r->estrellas);
            if (isset($desglose[$e])) {
                $desglose[$e]++;
            }
        }
        $totalResenas = $resenas->count();
        $promedio = $totalResenas ? round($resenas->avg('estrellas'), 1) : null;

        foreach ($resenas->take(10) as $r) {
            if ($r->created_at) {
                $quien = $r->usuario->nombre ?? 'Alguien';
                $eventos[] = $this->evento($r->created_at, 'mdi-star', 'y', "{$quien} te calificó con " . (int) $r->estrellas . '★');
            }
        }

        // ------------------------------------------------------------
        // Postulaciones que la persona envió (las de borrador no cuentan)
        // ------------------------------------------------------------
        $enviadas = Postulacion::with('vacante:id,titulo')
            ->where('postulante_id', $usuario->id)
            ->where('estado', '!=', 'borrador')
            ->get();

        foreach ($enviadas as $p) {
            $vacante = $p->vacante->titulo ?? 'una vacante';
            if ($p->created_at) {
                $eventos[] = $this->evento($p->created_at, 'mdi-send', 'b', "Te postulaste a «{$vacante}»");
            }
            if ($p->estado === 'contratado' && $p->updated_at) {
                $eventos[] = $this->evento($p->updated_at, 'mdi-briefcase-check', 'g', "Te contrataron en «{$vacante}»");
            }
        }

        // ------------------------------------------------------------
        // Como empleador: sus vacantes y quién se postuló
        // ------------------------------------------------------------
        $vacantes = Vacante::where('empleador_id', $usuario->id)->get();
        $empleador = null;

        if ($vacantes->isNotEmpty()) {
            $recibidas = Postulacion::with(['postulante:id,nombre', 'vacante:id,titulo'])
                ->whereIn('vacante_id', $vacantes->pluck('id'))
                ->where('estado', '!=', 'borrador')
                ->get();

            foreach ($recibidas as $p) {
                $quien = $p->postulante->nombre ?? 'Alguien';
                $vacante = $p->vacante->titulo ?? 'tu vacante';
                if ($p->created_at) {
                    $eventos[] = $this->evento($p->created_at, 'mdi-account-plus', 'b', "{$quien} se postuló a «{$vacante}»");
                }
                if ($p->estado === 'contratado' && $p->updated_at) {
                    $eventos[] = $this->evento($p->updated_at, 'mdi-briefcase-check', 'g', "Contrataste a {$quien} en «{$vacante}»");
                }
            }

            $abiertas = $vacantes->filter(fn($v) => $v->esta_abierta);
            $porVencer = $abiertas
                ->filter(fn($v) => $v->fecha_limite && $v->fecha_limite->lte(today()->addDays(3)))
                ->sortBy('fecha_limite')
                ->values()
                ->map(fn($v) => [
                    'id' => $v->id,
                    'titulo' => $v->titulo,
                    'limite' => $v->fecha_limite,
                    'dias' => (int) today()->diffInDays($v->fecha_limite, false),
                ]);

            $empleador = [
                'total' => $vacantes->count(),
                'abiertas' => $abiertas->count(),
                'vencidas' => $vacantes->filter(fn($v) => $v->esta_vencida)->count(),
                'cerradas' => $vacantes->where('estado', 'cerrada')->count(),
                'postulantes' => $recibidas->count(),
                'contratados' => $recibidas->where('estado', 'contratado')->count(),
                'pendientes' => $recibidas->where('estado', 'pendiente')->count(),
                'por_vencer' => $porVencer,
            ];
        }

        // ------------------------------------------------------------
        // Actividad reciente: lo más nuevo primero
        // ------------------------------------------------------------
        usort($eventos, fn($a, $b) => $b['fecha']->timestamp <=> $a['fecha']->timestamp);

        return [
            'tarjetas' => [
                'solicitudes' => $solicitudes->count(),
                'solicitudes_mes' => $solicitudesEsteMes,
                'terminados' => $terminados,
                'postulaciones' => $enviadas->count(),
                'contrataron' => $enviadas->where('estado', 'contratado')->count(),
            ],
            'resenas' => [
                'total' => $totalResenas,
                'promedio' => $promedio,
                'desglose' => $desglose,
                'recientes' => $resenas->take(3)->values(),
            ],
            'meses' => $meses,
            'mas_solicitado' => $masSolicitado,
            'actividad' => array_slice($eventos, 0, 8),
            'empleador' => $empleador,
        ];
    }

    private function evento(Carbon $fecha, string $icono, string $color, string $texto): array
    {
        return ['fecha' => $fecha, 'icono' => $icono, 'color' => $color, 'texto' => $texto];
    }
}
