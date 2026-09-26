<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Servicio;
use App\Models\Vacante;
use App\Models\Localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // TODO: cuando haya más de un municipio, esto debe depender de la
    // ubicación del visitante en vez de estar fijo en Zacapoaxtla (id 1).
    private function anunciosDelMunicipio()
    {
        $anuncios = Anuncio::with('imagenes.solicitud')
            ->where('municipio_id', 1)
            ->where('estado', 'activo')
            ->orderBy('orden')
            ->get();

        return [
            'anunciosIzquierda' => $anuncios->where('posicion', 'izquierda')->values(),
            'anunciosDerecha' => $anuncios->where('posicion', 'derecha')->values(),
        ];
    }

    // Suma 1 al contador de visitas, solo una vez por sesión de navegador
    // (para que recargar la página no infle el número).
    private function contarVisita()
    {
        if (!session()->has('visita_contada')) {
            DB::table('visitas_contador')->where('id', 1)->increment('total');
            session(['visita_contada' => true]);
        }

        return DB::table('visitas_contador')->where('id', 1)->value('total') ?? 0;
    }

    // Busca por palabras sueltas (no la frase completa exacta) y sin
    // importar mayúsculas/minúsculas, en los campos indicados.
    private function aplicarBusqueda($query, string $texto, array $campos)
    {
        $palabras = array_filter(explode(' ', trim($texto)));

        return $query->where(function ($principal) use ($palabras, $campos) {
            foreach ($palabras as $palabra) {
                $principal->where(function ($grupo) use ($palabra, $campos) {
                    foreach ($campos as $campo) {
                        $grupo->orWhereRaw("LOWER({$campo}) LIKE ?", ['%' . mb_strtolower($palabra) . '%']);
                    }
                });
            }
        });
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $qVacante = $request->input('q_vacante');
        $categoriaId = $request->input('categoria_id');
        $localidadId = $request->input('localidad_id');
        $localidadVacanteId = $request->input('localidad_vacante_id');
        $hayFiltros = $q || $categoriaId || $localidadId;
        $hayFiltrosVacante = $qVacante || $localidadVacanteId;

        $categorias = \App\Models\Categoria::withCount(['servicios' => function ($query) {
            $query->where('estado', 'activo');
        }])->orderBy('nombre')->get();

        $localidades = Localidad::where('municipio_id', 1)->orderBy('nombre')->get();

        $servicios = Servicio::with('usuario')
            ->withAvg('calificaciones', 'estrellas')
            ->withCount('calificaciones')
            ->where('estado', 'activo')
            ->when($q, fn($query) => $this->aplicarBusqueda($query, $q, ['titulo', 'descripcion']))
            ->when($categoriaId, fn($query) => $query->where('categoria_id', $categoriaId))
            ->when($localidadId, fn($query) => $query->whereHas('usuario', function ($sub) use ($localidadId) {
                $sub->where('localidad_id', $localidadId);
            }))
            ->latest()
            ->take($hayFiltros ? 50 : 8)
            ->get();

        $vacantes = Vacante::where('estado', 'activa')
            ->when($qVacante, fn($query) => $this->aplicarBusqueda($query, $qVacante, ['titulo', 'descripcion', 'ubicacion']))
            ->when($localidadVacanteId, function ($query) use ($localidadVacanteId, $localidades) {
                $nombreLocalidad = optional($localidades->firstWhere('id', $localidadVacanteId))->nombre;
                if ($nombreLocalidad) {
                    $query->whereRaw('LOWER(ubicacion) LIKE ?', ['%' . mb_strtolower($nombreLocalidad) . '%']);
                }
            })
            ->latest()
            ->take($hayFiltrosVacante ? 50 : 8)
            ->get();

        return view('index', array_merge($this->anunciosDelMunicipio(), [
            'servicios' => $servicios,
            'vacantes' => $vacantes,
            'categorias' => $categorias,
            'localidades' => $localidades,
            'q' => $q,
            'qVacante' => $qVacante,
            'categoriaId' => $categoriaId,
            'localidadId' => $localidadId,
            'localidadVacanteId' => $localidadVacanteId,
            'totalVisitas' => $this->contarVisita(),
        ]));
    }

    public function acceso()
    {
        return view('acceso', $this->anunciosDelMunicipio());
    }

    // Vista pública de un servicio: cualquiera puede verla, sin necesidad
    // de tener cuenta. Solo muestra información básica; para contactar
    // hay que registrarse primero.
    public function verServicioPublico($id)
    {
        $servicio = Servicio::with('usuario')
            ->withAvg('calificaciones', 'estrellas')
            ->withCount('calificaciones')
            ->where('estado', 'activo')
            ->findOrFail($id);

        $categorias = \App\Models\Categoria::all();

        return view('servicio_publico', array_merge($this->anunciosDelMunicipio(), [
            'servicio' => $servicio,
            'categorias' => $categorias,
        ]));
    }

    // Igual que arriba, pero para una vacante.
    public function verVacantePublica($id)
    {
        $vacante = Vacante::where('estado', 'activa')->findOrFail($id);

        return view('vacante_publica', array_merge($this->anunciosDelMunicipio(), [
            'vacante' => $vacante,
        ]));
    }

    public function acercaDe()
    {
        $testimonios = \App\Models\Testimonio::with('usuario')
            ->where('estado', 'aprobado')
            ->latest()
            ->take(6)
            ->get();

        $miTestimonio = null;
        if (auth()->check()) {
            $miTestimonio = \App\Models\Testimonio::where('usuario_id', auth()->id())->first();
        }

        return view('acerca_de', array_merge($this->anunciosDelMunicipio(), [
            'testimonios' => $testimonios,
            'miTestimonio' => $miTestimonio,
        ]));
    }

    public function servicioCliente()
    {
        return view('servicio_cliente', $this->anunciosDelMunicipio());
    }

    public function terminos()
    {
        return view('terminos', $this->anunciosDelMunicipio());
    }

    // Sirve archivos de storage directo (por si el hosting no sigue el
    // enlace simbólico public_html/storage -> Laravel/storage/app/public).
    // Reemplaza a la ruta de closure anterior, que impedía cachear rutas
    // (php artisan route:cache no soporta closures).
    public function servirStorage($path)
    {
        $rutaCompleta = storage_path('app/public/' . $path);

        if (!file_exists($rutaCompleta)) {
            abort(404);
        }

        return response()->file($rutaCompleta);
    }

    // TEMPORAL: solo para probar si el correo funciona. Bórrala después.
    public function pruebaCorreo()
    {
        try {
            $solicitud = \App\Models\SolicitudAnuncio::first();

            if (!$solicitud) {
                return 'No hay ninguna solicitud en la base de datos para probar con datos reales.';
            }

            \Illuminate\Support\Facades\Mail::to('sinteczatedemo@gmail.com')
                ->send(new \App\Mail\AnuncioActivado($solicitud, now()->toDateString(), now()->addDays(15)->toDateString()));

            return 'OK: el correo (con la plantilla AnuncioActivado) se mandó sin errores. Revisa sinteczatedemo@gmail.com.';
        } catch (\Exception $e) {
            return 'ERROR: ' . $e->getMessage() . ' — en el archivo ' . $e->getFile() . ' línea ' . $e->getLine();
        }
    }
}
