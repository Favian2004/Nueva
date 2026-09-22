<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Postulacion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioCurriculumController extends Controller
{
    /**
     * Ver el CV de un servicio específico (lo ve cualquier usuario logueado
     * que entre a ese servicio, desde el inicio, sin esperar a que acepten
     * nada -- el CV no tiene datos de alto riesgo por diseño).
     */
    public function ver($servicioId)
    {
        $servicio = Servicio::with('usuario.localidad')->findOrFail($servicioId);
        $curriculum = Curriculum::where('servicio_id', $servicioId)->firstOrFail();

        return view('usuario.ver-cv', [
            'curriculum' => $curriculum,
            'servicio' => $servicio,
            'esPropio' => $servicio->usuario_id === Auth::id(),
        ]);
    }

    /**
     * Formulario de creación/edición del CV de UN servicio específico
     * (el asistente paso a paso).
     */
    public function crear($servicioId)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($servicioId);
        $curriculum = Curriculum::where('servicio_id', $servicioId)->first();
        $usuario = Auth::user()->load('localidad.municipio');

        $ubicacion = null;
        if ($usuario->localidad) {
            $ubicacion = $usuario->localidad->nombre . ($usuario->localidad->municipio ? ', ' . $usuario->localidad->municipio->nombre : '');
        }

        return view('usuario.crear-cv', [
            'curriculum' => $curriculum,
            'servicio' => $servicio,
            'ubicacion' => $ubicacion,
        ]);
    }

    /**
     * Guarda TODOS los pasos del asistente de un solo envío, ligado al
     * servicio específico desde el que se llenó.
     */
    public function guardar(Request $request, $servicioId)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($servicioId);

        $request->validate([
            'nombre' => 'nullable|string|max:150',
            'apellidos' => 'nullable|string|max:150',
            'titulo' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:150',
            'foto' => 'nullable|image|max:2048',
            'resumen' => 'nullable|string|max:500',

            'formacion' => 'nullable|array',
            'formacion.*.titulo' => 'nullable|string|max:150',
            'formacion.*.institucion' => 'nullable|string|max:150',
            'formacion.*.fecha' => 'nullable|string|max:50',

            'experiencia' => 'nullable|array',
            'experiencia.*.puesto' => 'nullable|string|max:150',
            'experiencia.*.lugar' => 'nullable|string|max:150',
            'experiencia.*.fecha' => 'nullable|string|max:50',
            'experiencia.*.descripcion' => 'nullable|string|max:500',

            'cursos' => 'nullable|array',
            'cursos.*.nombre' => 'nullable|string|max:150',
            'cursos.*.institucion' => 'nullable|string|max:150',
            'cursos.*.anio' => 'nullable|string|max:20',

            'habilidades' => 'nullable|array',
            'habilidades.*' => 'nullable|string|max:60',

            'idiomas' => 'nullable|array',
            'idiomas.*.nombre' => 'nullable|string|max:60',
            'idiomas.*.nivel' => 'nullable|in:Básico,Intermedio,Avanzado,Nativo',

            'intereses' => 'nullable|array',
            'intereses.*' => 'nullable|string|max:60',

            'referencias' => 'nullable|array',
            'referencias.*.nombre' => 'nullable|string|max:150',
            'referencias.*.relacion' => 'nullable|string|max:150',
            'referencias.*.telefono' => 'nullable|string|max:20',

            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'nacionalidad' => 'nullable|string|max:100',
            'estado_civil' => 'nullable|string|max:50',
            'tipo_documento' => 'nullable|string|max:50',
            'detalles_documento' => 'nullable|string|max:100',
        ]);

        $curriculum = Curriculum::firstOrNew(['servicio_id' => $servicioId]);

        $rutaFoto = $curriculum->foto;
        if ($request->hasFile('foto')) {
            $rutaFoto = '/storage/' . $request->file('foto')->store('cv-fotos', 'public');
        }

        // Cada sección repetible se limpia de filas totalmente vacías, así
        // no se guardan tarjetas fantasma cuando el usuario le dio "Agregar"
        // pero no llenó nada.
        $limpiar = fn($items, $camposClave) => collect($items ?? [])
            ->filter(fn($item) => collect($camposClave)->contains(fn($campo) => !empty($item[$campo] ?? null)))
            ->values()
            ->all();

        $curriculum->fill([
            'usuario_id' => Auth::id(),
            'servicio_id' => $servicioId,
            'nombre' => $request->input('nombre'),
            'apellidos' => $request->input('apellidos'),
            'titulo' => $request->input('titulo'),
            'foto' => $rutaFoto,
            'telefono' => $request->input('telefono'),
            'correo' => $request->input('correo'),
            'resumen' => $request->input('resumen'),
            'formacion' => $limpiar($request->input('formacion'), ['titulo', 'institucion']),
            'experiencia' => $limpiar($request->input('experiencia'), ['puesto', 'lugar']),
            'cursos' => $limpiar($request->input('cursos'), ['nombre', 'institucion']),
            'habilidades' => collect($request->input('habilidades', []))->filter()->values()->all(),
            'idiomas' => $limpiar($request->input('idiomas'), ['nombre']),
            'intereses' => collect($request->input('intereses', []))->filter()->values()->all(),
            'referencias' => $limpiar($request->input('referencias'), ['nombre', 'telefono']),
            'direccion' => $request->input('direccion'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'nacionalidad' => $request->input('nacionalidad'),
            'estado_civil' => $request->input('estado_civil'),
            'tipo_documento' => $request->input('tipo_documento'),
            'detalles_documento' => $request->input('detalles_documento'),
        ]);
        $curriculum->save();

        return response()->json(['ok' => true, 'redirect' => "/usuario/misEmpleos/{$servicioId}/editar"]);
    }

    /**
     * Alternativa: subir un CV ya hecho (PDF/Word) para ESE servicio, en
     * vez de usar el formulario guiado.
     */
    public function subirArchivo(Request $request, $servicioId)
    {
        Servicio::where('usuario_id', Auth::id())->findOrFail($servicioId);

        $request->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:4096',
        ]);

        $curriculum = Curriculum::firstOrNew(['servicio_id' => $servicioId]);
        $curriculum->usuario_id = Auth::id();
        $curriculum->servicio_id = $servicioId;
        $curriculum->archivo_subido = '/storage/' . $request->file('archivo')->store('cv-archivos', 'public');
        $curriculum->save();

        return response()->json(['ok' => true]);
    }

    public function eliminarArchivo($servicioId)
    {
        Servicio::where('usuario_id', Auth::id())->findOrFail($servicioId);

        $curriculum = Curriculum::where('servicio_id', $servicioId)->firstOrFail();
        $curriculum->archivo_subido = null;
        $curriculum->save();

        return response()->json(['ok' => true]);
    }

    // =========================================================================
    // Lo mismo de arriba, pero para el CV de UNA POSTULACIÓN específica
    // (cuando te postulas a una vacante que pide CV/Solicitud).
    // =========================================================================

    /**
     * Ver el CV de una postulación específica (lo ve el empleador dueño de
     * la vacante, al revisar a ese postulante).
     */
    public function verPostulacion($postulacionId)
    {
        $postulacion = Postulacion::with('vacante')->findOrFail($postulacionId);
        $curriculum = Curriculum::where('postulacion_id', $postulacionId)->firstOrFail();

        $esPropio = $postulacion->postulante_id === Auth::id();
        $esElEmpleador = $postulacion->vacante->empleador_id === Auth::id();

        abort_unless($esPropio || $esElEmpleador, 403);

        return view('usuario.ver-cv', [
            'curriculum' => $curriculum,
            'postulacion' => $postulacion,
            'esPropio' => $esPropio,
        ]);
    }

    /**
     * Formulario de creación/edición del CV de UNA postulación específica.
     */
    public function crearPostulacion($postulacionId)
    {
        $postulacion = Postulacion::with('vacante')->where('postulante_id', Auth::id())->findOrFail($postulacionId);
        $curriculum = Curriculum::where('postulacion_id', $postulacionId)->first();
        $usuario = Auth::user()->load('localidad.municipio');

        $ubicacion = null;
        if ($usuario->localidad) {
            $ubicacion = $usuario->localidad->nombre . ($usuario->localidad->municipio ? ', ' . $usuario->localidad->municipio->nombre : '');
        }

        return view('usuario.crear-cv', [
            'curriculum' => $curriculum,
            'postulacion' => $postulacion,
            'ubicacion' => $ubicacion,
        ]);
    }

    /**
     * Guarda el CV guiado, ligado a esa postulación específica.
     */
    public function guardarPostulacion(Request $request, $postulacionId)
    {
        Postulacion::where('postulante_id', Auth::id())->findOrFail($postulacionId);

        $request->validate([
            'nombre' => 'nullable|string|max:150',
            'apellidos' => 'nullable|string|max:150',
            'titulo' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:150',
            'foto' => 'nullable|image|max:2048',
            'resumen' => 'nullable|string|max:500',

            'formacion' => 'nullable|array',
            'formacion.*.titulo' => 'nullable|string|max:150',
            'formacion.*.institucion' => 'nullable|string|max:150',
            'formacion.*.fecha' => 'nullable|string|max:50',

            'experiencia' => 'nullable|array',
            'experiencia.*.puesto' => 'nullable|string|max:150',
            'experiencia.*.lugar' => 'nullable|string|max:150',
            'experiencia.*.fecha' => 'nullable|string|max:50',
            'experiencia.*.descripcion' => 'nullable|string|max:500',

            'cursos' => 'nullable|array',
            'cursos.*.nombre' => 'nullable|string|max:150',
            'cursos.*.institucion' => 'nullable|string|max:150',
            'cursos.*.anio' => 'nullable|string|max:20',

            'habilidades' => 'nullable|array',
            'habilidades.*' => 'nullable|string|max:60',

            'idiomas' => 'nullable|array',
            'idiomas.*.nombre' => 'nullable|string|max:60',
            'idiomas.*.nivel' => 'nullable|in:Básico,Intermedio,Avanzado,Nativo',

            'intereses' => 'nullable|array',
            'intereses.*' => 'nullable|string|max:60',

            'referencias' => 'nullable|array',
            'referencias.*.nombre' => 'nullable|string|max:150',
            'referencias.*.relacion' => 'nullable|string|max:150',
            'referencias.*.telefono' => 'nullable|string|max:20',

            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'nacionalidad' => 'nullable|string|max:100',
            'estado_civil' => 'nullable|string|max:50',
            'tipo_documento' => 'nullable|string|max:50',
            'detalles_documento' => 'nullable|string|max:100',
        ]);

        $curriculum = Curriculum::firstOrNew(['postulacion_id' => $postulacionId]);

        $rutaFoto = $curriculum->foto;
        if ($request->hasFile('foto')) {
            $rutaFoto = '/storage/' . $request->file('foto')->store('cv-fotos', 'public');
        }

        $limpiar = fn($items, $camposClave) => collect($items ?? [])
            ->filter(fn($item) => collect($camposClave)->contains(fn($campo) => !empty($item[$campo] ?? null)))
            ->values()
            ->all();

        $curriculum->fill([
            'usuario_id' => Auth::id(),
            'postulacion_id' => $postulacionId,
            'nombre' => $request->input('nombre'),
            'apellidos' => $request->input('apellidos'),
            'titulo' => $request->input('titulo'),
            'foto' => $rutaFoto,
            'telefono' => $request->input('telefono'),
            'correo' => $request->input('correo'),
            'resumen' => $request->input('resumen'),
            'formacion' => $limpiar($request->input('formacion'), ['titulo', 'institucion']),
            'experiencia' => $limpiar($request->input('experiencia'), ['puesto', 'lugar']),
            'cursos' => $limpiar($request->input('cursos'), ['nombre', 'institucion']),
            'habilidades' => collect($request->input('habilidades', []))->filter()->values()->all(),
            'idiomas' => $limpiar($request->input('idiomas'), ['nombre']),
            'intereses' => collect($request->input('intereses', []))->filter()->values()->all(),
            'referencias' => $limpiar($request->input('referencias'), ['nombre', 'telefono']),
            'direccion' => $request->input('direccion'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'nacionalidad' => $request->input('nacionalidad'),
            'estado_civil' => $request->input('estado_civil'),
            'tipo_documento' => $request->input('tipo_documento'),
            'detalles_documento' => $request->input('detalles_documento'),
        ]);
        $curriculum->save();

        return response()->json(['ok' => true, 'redirect' => '/usuario/postulantes']);
    }

    /**
     * Alternativa: subir un CV ya hecho (PDF/Word) para ESA postulación.
     */
    public function subirArchivoPostulacion(Request $request, $postulacionId)
    {
        Postulacion::where('postulante_id', Auth::id())->findOrFail($postulacionId);

        $request->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:4096',
        ]);

        $curriculum = Curriculum::firstOrNew(['postulacion_id' => $postulacionId]);
        $curriculum->usuario_id = Auth::id();
        $curriculum->postulacion_id = $postulacionId;
        $curriculum->archivo_subido = '/storage/' . $request->file('archivo')->store('cv-archivos', 'public');
        $curriculum->save();

        return response()->json(['ok' => true]);
    }

    /**
     * Consulta directo a la base de datos si esa postulación ya tiene CV
     * y/o Solicitud de Empleo. El modal de "Postularme" pregunta esto cada
     * pocos segundos mientras espera a que termines de completarlos en la
     * otra pestaña -- así siempre refleja el estado real guardado, sin
     * depender de que las pestañas se puedan comunicar entre sí.
     */
    public function estadoPostulacion($postulacionId)
    {
        $postulacion = Postulacion::where('postulante_id', Auth::id())->findOrFail($postulacionId);
        $curriculum = Curriculum::where('postulacion_id', $postulacionId)->first();

        return response()->json([
            'ok' => true,
            'tieneCv' => (bool) ($curriculum && ($curriculum->tieneContenido || $curriculum->archivo_subido)),
            'tieneSolicitud' => (bool) $postulacion->solicitud_empleo,
        ]);
    }
}
