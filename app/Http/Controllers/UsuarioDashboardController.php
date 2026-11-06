<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Localidad;
use App\Models\Servicio;
use App\Services\BusquedaTolerante;
use App\Services\UbicacionServicios;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UsuarioDashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $misServiciosCount = Servicio::where('usuario_id', $usuario->id)->count();
        $serviciosActivosCount = Servicio::where('estado', 'activo')->count();
        $empresasActivasCount = Servicio::where('estado', 'activo')->distinct('usuario_id')->count('usuario_id');

        $servicios = Servicio::with('usuario')
            ->withAvg('calificaciones', 'estrellas')
            ->withCount('calificaciones')
            ->where('estado', 'activo')
            ->latest()
            ->take(8)
            ->get();

        return view('usuario.index', [
            'usuario' => $usuario,
            'misServiciosCount' => $misServiciosCount,
            'serviciosActivosCount' => $serviciosActivosCount,
            'empresasActivasCount' => $empresasActivasCount,
            'servicios' => $servicios,
        ]);
    }

    public function verEmpleos(Request $request)
    {
        $usuario = Auth::user();
        $base = '/usuario/verEmpleos';

        $misServiciosCount = Servicio::where('usuario_id', $usuario->id)->count();
        $serviciosActivosCount = Servicio::where('estado', 'activo')->count();
        $empresasActivasCount = Servicio::where('estado', 'activo')->distinct('usuario_id')->count('usuario_id');

        $categorias = Categoria::orderBy('nombre')->get();
        $localidades = Localidad::with('municipio:id,nombre')->where('municipio_id', 1)->orderBy('nombre')->get();
        $localidadesPorId = $localidades->keyBy('id');

        // ---- Lo que la persona eligió en el filtro (todo se limpia aquí) ----
        $q = mb_substr(trim((string) $request->input('q')), 0, 100);
        $categoriaId = $categorias->contains('id', (int) $request->input('categoria_id'))
            ? (int) $request->input('categoria_id') : null;
        $localidadId = $localidadesPorId->has((int) $request->input('localidad_id'))
            ? (int) $request->input('localidad_id') : null;

        $precioMin = $this->precioDelFiltro($request->input('precio_min'));
        $precioMax = $this->precioDelFiltro($request->input('precio_max'));
        if ($precioMin !== null && $precioMax !== null && $precioMin > $precioMax) {
            [$precioMin, $precioMax] = [$precioMax, $precioMin];
        }

        $orden = $request->input('orden', 'recientes');
        if (!in_array($orden, ['recientes', 'precio_asc', 'precio_desc', 'cercania'], true)) {
            $orden = 'recientes';
        }

        // La localidad de la persona (la de su perfil) sirve para "Cerca de mí".
        $miLocalidadId = $usuario->localidad_id && $localidadesPorId->has($usuario->localidad_id)
            ? (int) $usuario->localidad_id : null;
        $cercaSinLocalidad = $orden === 'cercania' && !$miLocalidadId;
        if ($cercaSinLocalidad) {
            $orden = 'recientes';
        }

        // ---- Servicios ----
        $servicios = Servicio::with('usuario')
            ->withAvg('calificaciones', 'estrellas')
            ->withCount('calificaciones')
            ->where('estado', 'activo')
            ->when($q !== '', fn($query) => $query->whereIn('id', BusquedaTolerante::ids(
                Servicio::where('estado', 'activo')->get(['id', 'titulo', 'descripcion']), $q, ['titulo', 'descripcion']
            )))
            ->when($categoriaId, fn($query) => $query->where('categoria_id', $categoriaId))
            ->when($precioMin !== null, fn($query) => $query->where('precio', '>=', $precioMin))
            ->when($precioMax !== null, fn($query) => $query->where('precio', '<=', $precioMax))
            ->when($orden === 'precio_asc', fn($query) => $query->orderBy('precio', 'asc'))
            ->when($orden === 'precio_desc', fn($query) => $query->orderBy('precio', 'desc'))
            ->latest()
            ->latest('id')
            ->get();

        // ---- Cercanía: en qué localidad está cada servicio ----
        $ubicador = new UbicacionServicios($localidades, $localidades->first()->municipio->nombre ?? 'Zacapoaxtla');
        $serviciosActivos = Servicio::with('usuario:id,localidad_id')
            ->where('estado', 'activo')
            ->get(['id', 'usuario_id', 'ubicacion']);
        $localidadDeServicio = $ubicador->mapa($serviciosActivos);

        if ($localidadId) {
            $servicios = $servicios->filter(fn($s) => ($localidadDeServicio[$s->id] ?? null) === $localidadId)->values();
        }

        if ($orden === 'cercania') {
            // Primero lo que está en mi localidad; el resto, de lo más nuevo a lo más viejo.
            $servicios = $servicios
                ->sortBy(fn($s, $i) => [($localidadDeServicio[$s->id] ?? null) === $miLocalidadId ? 0 : 1, $i])
                ->values();
        }

        // Localidades que sí tienen servicios (con cuántos), para el desplegable.
        $conteoPorLocalidad = array_count_values(array_filter($localidadDeServicio));
        $opcionesLocalidad = $localidades
            ->filter(fn($l) => ($conteoPorLocalidad[$l->id] ?? 0) > 0 || (int) $l->id === $miLocalidadId || (int) $l->id === $localidadId)
            ->map(fn($l) => ['id' => (int) $l->id, 'nombre' => $l->nombre, 'total' => $conteoPorLocalidad[$l->id] ?? 0])
            ->values();

        // ---- Filtros activos (cada uno con su enlace para quitarlo) ----
        $params = array_filter([
            'q' => $q,
            'categoria_id' => $categoriaId,
            'localidad_id' => $localidadId,
            'precio_min' => $precioMin,
            'precio_max' => $precioMax,
            'orden' => $orden !== 'recientes' ? $orden : null,
        ], fn($v) => $v !== null && $v !== '');

        $sin = function (array $claves) use ($params, $base) {
            $resto = Arr::except($params, $claves);

            return $base . ($resto ? '?' . http_build_query($resto) : '');
        };

        $dinero = fn($n) => '$' . number_format($n, floor($n) == $n ? 0 : 2);
        $nombresOrden = [
            'precio_asc' => 'Precio: menor a mayor',
            'precio_desc' => 'Precio: mayor a menor',
            'cercania' => 'Más cercanos a mí',
        ];

        $chips = [];
        if ($q !== '') {
            $chips[] = ['icono' => 'mdi-magnify', 'texto' => "«{$q}»", 'quitar' => $sin(['q'])];
        }
        if ($categoriaId) {
            $chips[] = ['icono' => 'mdi-tag-outline', 'texto' => $categorias->firstWhere('id', $categoriaId)->nombre, 'quitar' => $sin(['categoria_id'])];
        }
        if ($localidadId) {
            $chips[] = ['icono' => 'mdi-map-marker', 'texto' => $localidadesPorId[$localidadId]->nombre, 'quitar' => $sin(['localidad_id'])];
        }
        if ($precioMin !== null || $precioMax !== null) {
            $texto = match (true) {
                $precioMin !== null && $precioMax !== null => $dinero($precioMin) . ' a ' . $dinero($precioMax),
                $precioMin !== null => 'Desde ' . $dinero($precioMin),
                default => 'Hasta ' . $dinero($precioMax),
            };
            $chips[] = ['icono' => 'mdi-cash', 'texto' => $texto, 'quitar' => $sin(['precio_min', 'precio_max'])];
        }
        if (isset($nombresOrden[$orden])) {
            $chips[] = ['icono' => 'mdi-sort', 'texto' => $nombresOrden[$orden], 'quitar' => $sin(['orden'])];
        }

        // Enlace del atajo "Cerca de mí": enciende o apaga el orden por cercanía.
        $enlaceCerca = $orden === 'cercania'
            ? $sin(['orden'])
            : $base . '?' . http_build_query(array_merge(Arr::except($params, ['orden']), ['orden' => 'cercania']));

        return view('usuario.verEmpleos', [
            'servicios' => $servicios,
            'categorias' => $categorias,
            'q' => $q,
            'categoriaId' => $categoriaId,
            'localidadId' => $localidadId,
            'precioMin' => $precioMin,
            'precioMax' => $precioMax,
            'orden' => $orden,
            'opcionesLocalidad' => $opcionesLocalidad,
            'localidadesPorId' => $localidadesPorId,
            'localidadDeServicio' => $localidadDeServicio,
            'miLocalidadId' => $miLocalidadId,
            'cercaSinLocalidad' => $cercaSinLocalidad,
            'chips' => $chips,
            'enlaceCerca' => $enlaceCerca,
            'enlaceLimpiar' => $base,
            'misServiciosCount' => $misServiciosCount,
            'serviciosActivosCount' => $serviciosActivosCount,
            'empresasActivasCount' => $empresasActivasCount,
        ]);
    }

    /**
     * Precio escrito en el filtro: solo vale si es un número (con o sin
     * $ y comas) de 0 en adelante. Si no, se ignora.
     */
    private function precioDelFiltro(mixed $valor): int|float|null
    {
        if (!is_scalar($valor)) {
            return null;
        }

        $limpio = str_replace([',', '$', ' '], '', (string) $valor);
        if (!preg_match('/^\d{1,7}(\.\d{1,2})?$/', $limpio)) {
            return null;
        }

        return $limpio + 0;
    }
}
