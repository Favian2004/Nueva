<?php

namespace App\Http\Controllers;

use App\Models\Localidad;
use App\Models\Postulacion;
use App\Models\Vacante;
use App\Services\BusquedaTolerante;
use App\Services\SalarioVacante;
use App\Services\UbicacionServicios;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UsuarioEmpleadorController extends Controller
{
    public function index()
    {
        $vacantesActivas = Vacante::where('empleador_id', Auth::id())->abiertas()->count();

        $postulantesActivos = Postulacion::whereHas('vacante', function ($q) {
            $q->where('empleador_id', Auth::id());
        })->where('estado', 'pendiente')->count();

        $contratadosActivos = Postulacion::whereHas('vacante', function ($q) {
            $q->where('empleador_id', Auth::id());
        })->where('estado', 'contratado')->count();

        $vacantes = Vacante::with('empleador')
            ->withCount('postulaciones')
            ->abiertas()
            ->latest()
            ->get();

        $misPostulacionesIds = Postulacion::where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->pluck('vacante_id')
            ->toArray();

        return view('usuario.empleador', [
            'vacantesActivas' => $vacantesActivas,
            'postulantesActivos' => $postulantesActivos,
            'contratadosActivos' => $contratadosActivos,
            'vacantes' => $vacantes,
            'misPostulacionesIds' => $misPostulacionesIds,
        ]);
    }

    /**
     * Se llama apenas se abre el modal de "Postularme" en una vacante que
     * pide CV y/o Solicitud -- crea (o reutiliza) una postulación en estado
     * "borrador", solo para que el CV tenga a qué pegarse. Todavía no es
     * una postulación de verdad hasta que se llame a postularse().
     */
    public function iniciarBorrador($vacanteId)
    {
        $vacante = Vacante::findOrFail($vacanteId);

        if (!$vacante->esta_abierta) {
            return response()->json(['ok' => false, 'error' => 'Esta vacante ya no está recibiendo postulaciones (pasó su fecha límite o fue cerrada).'], 422);
        }

        if ($vacante->empleador_id === Auth::id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes postularte a tu propia vacante.'], 422);
        }

        $yaExiste = Postulacion::where('vacante_id', $vacanteId)
            ->where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->exists();

        if ($yaExiste) {
            return response()->json(['ok' => false, 'error' => 'Ya tienes una postulación activa para esta vacante.'], 422);
        }

        $postulacion = Postulacion::firstOrCreate([
            'vacante_id' => $vacanteId,
            'postulante_id' => Auth::id(),
            'estado' => 'borrador',
        ]);

        return response()->json(['ok' => true, 'postulacionId' => $postulacion->id]);
    }

    public function postularse(Request $request, $vacanteId)
    {
        $vacante = Vacante::findOrFail($vacanteId);

        if (!$vacante->esta_abierta) {
            return response()->json(['ok' => false, 'error' => 'Esta vacante ya no está recibiendo postulaciones (pasó su fecha límite o fue cerrada).'], 422);
        }

        if ($vacante->empleador_id === Auth::id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes postularte a tu propia vacante.'], 422);
        }

        $yaExiste = Postulacion::where('vacante_id', $vacanteId)
            ->where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->exists();

        if ($yaExiste) {
            return response()->json(['ok' => false, 'error' => 'Ya tienes una postulación activa para esta vacante.'], 422);
        }

        $request->validate([
            'mensaje' => 'nullable|string|max:1000',
        ]);

        // Si ya existía un borrador (porque el modal ya había creado uno
        // para poder subir CV/Solicitud), lo terminamos de completar aquí.
        // Si no existía (la vacante no pedía documentos), se crea directo.
        $postulacion = Postulacion::firstOrNew([
            'vacante_id' => $vacanteId,
            'postulante_id' => Auth::id(),
            'estado' => 'borrador',
        ]);
        $postulacion->vacante_id = $vacanteId;
        $postulacion->postulante_id = Auth::id();
        $postulacion->estado = 'pendiente';
        $postulacion->mensaje = $request->input('mensaje');
        $postulacion->save();

        return response()->json(['ok' => true, 'postulacionId' => $postulacion->id]);
    }

    /**
     * Subir la Solicitud de Empleo para una postulación específica (todavía
     * en borrador o ya confirmada).
     */
    public function subirSolicitudPostulacion(Request $request, $postulacionId)
    {
        $postulacion = Postulacion::where('postulante_id', Auth::id())->findOrFail($postulacionId);

        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:4096',
        ]);

        $postulacion->solicitud_empleo = '/storage/' . $request->file('archivo')->store('solicitudes-empleo', 'public');
        $postulacion->save();

        return response()->json(['ok' => true]);
    }

    public function buscarTalento(Request $request)
    {
        $usuario = Auth::user();
        $base = '/usuario/buscar-talento';

        $localidades = Localidad::with('municipio:id,nombre')->where('municipio_id', 1)->orderBy('nombre')->get();
        $localidadesPorId = $localidades->keyBy('id');

        // ---- Lo que la persona eligió en el filtro (todo se limpia aquí) ----
        $texto = fn(string $campo) => is_scalar($request->input($campo))
            ? mb_substr(trim((string) $request->input($campo)), 0, 100) : '';

        $q = $texto('q');
        $ubicacion = $texto('ubicacion');
        $experiencia = $texto('experiencia');
        $contrato = $texto('contrato');
        $tipoPago = $texto('tipo_pago');

        $localidadId = $localidadesPorId->has((int) $request->input('localidad_id'))
            ? (int) $request->input('localidad_id') : null;

        $salarioMin = SalarioVacante::numeroDelFiltro($request->input('salario_min'));
        $salarioMax = SalarioVacante::numeroDelFiltro($request->input('salario_max'));
        if ($salarioMin !== null && $salarioMax !== null && $salarioMin > $salarioMax) {
            [$salarioMin, $salarioMax] = [$salarioMax, $salarioMin];
        }

        $orden = $request->input('orden', 'recientes');
        if (!in_array($orden, ['recientes', 'cercania', 'salario_desc', 'salario_asc'], true)) {
            $orden = 'recientes';
        }

        // La localidad de la persona (la de su perfil) sirve para "Cerca de mí".
        $miLocalidadId = $usuario->localidad_id && $localidadesPorId->has($usuario->localidad_id)
            ? (int) $usuario->localidad_id : null;
        $cercaSinLocalidad = $orden === 'cercania' && !$miLocalidadId;
        if ($cercaSinLocalidad) {
            $orden = 'recientes';
        }

        // ---- Vacantes ----
        $vacantes = Vacante::with('empleador')
            ->withCount('postulaciones')
            ->abiertas()
            ->when($q !== '', fn($query) => $query->whereIn('id', BusquedaTolerante::ids(
                Vacante::abiertas()->get(['id', 'titulo', 'ubicacion']), $q, ['titulo', 'ubicacion']
            )))
            ->when($ubicacion !== '', fn($query) => $query->where('ubicacion', 'like', "%{$ubicacion}%"))
            ->when($experiencia !== '', fn($query) => $query->where('experiencia', $experiencia))
            ->when($contrato !== '', fn($query) => $query->where('contrato', $contrato))
            ->when($tipoPago !== '', fn($query) => $query->where('tipo_pago', $tipoPago))
            ->latest()
            ->latest('id')
            ->get();

        // ---- Cercanía: en qué localidad está cada vacante ----
        $ubicador = new UbicacionServicios($localidades, $localidades->first()->municipio->nombre ?? 'Zacapoaxtla');
        $abiertas = Vacante::with('empleador:id,localidad_id')
            ->abiertas()
            ->get(['id', 'empleador_id', 'ubicacion']);
        $localidadDeVacante = $ubicador->mapa($abiertas);

        if ($localidadId) {
            $vacantes = $vacantes->filter(fn($v) => ($localidadDeVacante[$v->id] ?? null) === $localidadId)->values();
        }

        // ---- Salario: el texto ("$1,500") se convierte en números ----
        $rangoSalario = $vacantes->mapWithKeys(fn($v) => [$v->id => SalarioVacante::rango($v->salario)])->all();
        $sinSalarioNumerico = 0;

        if ($salarioMin !== null || $salarioMax !== null) {
            // Las que dicen "A convenir" no se pueden comparar: se avisa cuántas quedaron fuera.
            $sinSalarioNumerico = collect($rangoSalario)->filter(fn($r) => $r === null)->count();
            $vacantes = $vacantes->filter(fn($v) => SalarioVacante::encaja($rangoSalario[$v->id], $salarioMin, $salarioMax))->values();
        }

        // ---- Orden ----
        if ($orden === 'cercania') {
            $vacantes = $vacantes
                ->sortBy(fn($v, $i) => [($localidadDeVacante[$v->id] ?? null) === $miLocalidadId ? 0 : 1, $i])
                ->values();
        } elseif ($orden === 'salario_desc' || $orden === 'salario_asc') {
            $desc = $orden === 'salario_desc';
            // Las que no tienen un monto ("A convenir") van al final en los dos sentidos.
            $vacantes = $vacantes
                ->sortBy(function ($v, $i) use ($rangoSalario, $desc) {
                    $r = $rangoSalario[$v->id];

                    return [$r === null ? 1 : 0, $r === null ? 0 : ($desc ? -$r[1] : $r[0]), $i];
                })
                ->values();
        }

        // Localidades que sí tienen vacantes (con cuántas), para el desplegable.
        $conteoPorLocalidad = array_count_values(array_filter($localidadDeVacante));
        $opcionesLocalidad = $localidades
            ->filter(fn($l) => ($conteoPorLocalidad[$l->id] ?? 0) > 0 || (int) $l->id === $miLocalidadId || (int) $l->id === $localidadId)
            ->map(fn($l) => ['id' => (int) $l->id, 'nombre' => $l->nombre, 'total' => $conteoPorLocalidad[$l->id] ?? 0])
            ->values();

        // ---- Filtros activos (cada uno con su enlace para quitarlo) ----
        $params = array_filter([
            'q' => $q,
            'ubicacion' => $ubicacion,
            'localidad_id' => $localidadId,
            'salario_min' => $salarioMin,
            'salario_max' => $salarioMax,
            'experiencia' => $experiencia,
            'contrato' => $contrato,
            'tipo_pago' => $tipoPago,
            'orden' => $orden !== 'recientes' ? $orden : null,
        ], fn($v) => $v !== null && $v !== '');

        $sin = function (array $claves) use ($params, $base) {
            $resto = Arr::except($params, $claves);

            return $base . ($resto ? '?' . http_build_query($resto) : '');
        };

        $dinero = fn($n) => '$' . number_format($n, floor($n) == $n ? 0 : 2);
        $nombresOrden = [
            'cercania' => 'Más cercanos a mí',
            'salario_desc' => 'Salario: mayor a menor',
            'salario_asc' => 'Salario: menor a mayor',
        ];

        $chips = [];
        if ($q !== '') {
            $chips[] = ['icono' => 'mdi-magnify', 'texto' => "«{$q}»", 'quitar' => $sin(['q'])];
        }
        if ($localidadId) {
            $chips[] = ['icono' => 'mdi-map-marker', 'texto' => $localidadesPorId[$localidadId]->nombre, 'quitar' => $sin(['localidad_id'])];
        }
        if ($ubicacion !== '') {
            $chips[] = ['icono' => 'mdi-map-search', 'texto' => $ubicacion, 'quitar' => $sin(['ubicacion'])];
        }
        if ($salarioMin !== null || $salarioMax !== null) {
            $textoSalario = match (true) {
                $salarioMin !== null && $salarioMax !== null => $dinero($salarioMin) . ' a ' . $dinero($salarioMax),
                $salarioMin !== null => 'Desde ' . $dinero($salarioMin),
                default => 'Hasta ' . $dinero($salarioMax),
            };
            $chips[] = ['icono' => 'mdi-cash', 'texto' => $textoSalario, 'quitar' => $sin(['salario_min', 'salario_max'])];
        }
        if ($experiencia !== '') {
            $chips[] = ['icono' => 'mdi-school', 'texto' => $experiencia, 'quitar' => $sin(['experiencia'])];
        }
        if ($contrato !== '') {
            $chips[] = ['icono' => 'mdi-file-document', 'texto' => $contrato, 'quitar' => $sin(['contrato'])];
        }
        if ($tipoPago !== '') {
            $chips[] = ['icono' => 'mdi-calendar-clock', 'texto' => $tipoPago, 'quitar' => $sin(['tipo_pago'])];
        }
        if (isset($nombresOrden[$orden])) {
            $chips[] = ['icono' => 'mdi-sort', 'texto' => $nombresOrden[$orden], 'quitar' => $sin(['orden'])];
        }

        // Enlace del atajo "Cerca de mí": enciende o apaga el orden por cercanía.
        $enlaceCerca = $orden === 'cercania'
            ? $sin(['orden'])
            : $base . '?' . http_build_query(array_merge(Arr::except($params, ['orden']), ['orden' => 'cercania']));

        $misPostulacionesIds = Postulacion::where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->pluck('vacante_id')
            ->toArray();

        return view('usuario.buscar-talento', [
            'vacantes' => $vacantes,
            'totalAbiertas' => $abiertas->count(),
            'misPostulacionesIds' => $misPostulacionesIds,
            'q' => $q,
            'ubicacion' => $ubicacion,
            'experiencia' => $experiencia,
            'contrato' => $contrato,
            'tipoPago' => $tipoPago,
            'localidadId' => $localidadId,
            'salarioMin' => $salarioMin,
            'salarioMax' => $salarioMax,
            'orden' => $orden,
            'opcionesLocalidad' => $opcionesLocalidad,
            'localidadesPorId' => $localidadesPorId,
            'localidadDeVacante' => $localidadDeVacante,
            'miLocalidadId' => $miLocalidadId,
            'cercaSinLocalidad' => $cercaSinLocalidad,
            'sinSalarioNumerico' => $sinSalarioNumerico,
            'chips' => $chips,
            'enlaceCerca' => $enlaceCerca,
            'enlaceLimpiar' => $base,
            'enlaceSinSalario' => $sin(['salario_min', 'salario_max']),
        ]);
    }
}
