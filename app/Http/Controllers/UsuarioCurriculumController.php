<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Postulacion;
use App\Models\Servicio;
use App\Services\ModeracionService;
use App\Services\ValidadorDocumento;
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
            'contactoVisible' => $servicio->usuario_id === Auth::id()
                || \App\Models\Contratacion::contactoAbierto($servicioId, Auth::id()),
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
    public function guardar(Request $request, $servicioId, ModeracionService $moderacion)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($servicioId);

        $request->validate($this->reglasCv(), $this->mensajesCv());

        // Mismo moderador que el resto del sitio: textos del CV y la foto.
        if ($bloqueo = $this->bloqueoDeModeracion($request, $moderacion)) {
            return $bloqueo;
        }

        // Revisión por pasos: el formulario pregunta en cada "Continuar" sin guardar nada.
        if ($request->boolean('solo_moderar')) {
            return response()->json(['ok' => true]);
        }

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
     * Reglas del formulario del CV: tipo de dato y tamaño de cada campo.
     * (Compartidas por el CV de servicio y el de postulación.)
     */
    private function reglasCv(): array
    {
        $letras = fn (string $etiqueta) => function ($attr, $valor, $fail) use ($etiqueta) {
            if (! preg_match('/^[\pL\pM\s.\'’-]+$/u', (string) $valor)) {
                $fail("$etiqueta solo puede llevar letras (sin números ni símbolos).");
                return;
            }
            // Sin texto basura: la misma letra no más de 2 veces seguidas y palabras de hasta 15 letras.
            if (preg_match('/(\pL)\1{2,}/iu', (string) $valor)) {
                $fail("$etiqueta no puede repetir la misma letra 3 veces seguidas. Escríbelo bien.");
                return;
            }
            foreach (preg_split('/[\s.\'’-]+/u', (string) $valor, -1, PREG_SPLIT_NO_EMPTY) as $palabra) {
                if (mb_strlen($palabra) > 15) {
                    $fail("$etiqueta tiene una palabra de más de 15 letras. Revisa que esté bien escrito.");
                    return;
                }
            }
        };
        $telefono = fn (string $etiqueta) => function ($attr, $valor, $fail) use ($etiqueta) {
            // Celular de México: exactamente 10 dígitos (se toleran espacios y guiones).
            $limpio = preg_replace('/[\s-]/', '', (string) $valor);
            if (! preg_match('/^\d{10}$/', $limpio)) {
                $fail("$etiqueta debe tener exactamente 10 dígitos, solo números.");
            }
        };
        $anio = function ($attr, $valor, $fail) {
            if (! preg_match('/^\d{4}$/', (string) $valor) || (int) $valor < 1950 || (int) $valor > (int) date('Y') + 1) {
                $fail('El año del curso debe ser de 4 dígitos, por ejemplo 2024.');
            }
        };

        return [
            'nombre' => ['nullable', 'string', 'max:30', $letras('El nombre')],
            'apellidos' => ['nullable', 'string', 'max:40', $letras('Los apellidos')],
            'titulo' => 'nullable|string|max:80',
            'telefono' => ['nullable', 'string', 'max:14', $telefono('El teléfono')],
            'correo' => 'nullable|email|max:100',
            'foto' => 'nullable|image|max:2048',
            'resumen' => 'nullable|string|max:500',

            'formacion' => 'nullable|array|max:10',
            'formacion.*.titulo' => 'nullable|string|max:100',
            'formacion.*.institucion' => 'nullable|string|max:100',
            'formacion.*.fecha' => 'nullable|string|max:30',

            'experiencia' => 'nullable|array|max:10',
            'experiencia.*.puesto' => 'nullable|string|max:100',
            'experiencia.*.lugar' => 'nullable|string|max:100',
            'experiencia.*.fecha' => 'nullable|string|max:30',
            'experiencia.*.descripcion' => 'nullable|string|max:500',

            'cursos' => 'nullable|array|max:10',
            'cursos.*.nombre' => 'nullable|string|max:100',
            'cursos.*.institucion' => 'nullable|string|max:100',
            'cursos.*.anio' => ['nullable', $anio],

            'habilidades' => 'nullable|array|max:20',
            'habilidades.*' => 'nullable|string|max:40',

            'idiomas' => 'nullable|array|max:10',
            'idiomas.*.nombre' => ['nullable', 'string', 'max:40', $letras('El idioma')],
            'idiomas.*.nivel' => 'nullable|in:Básico,Intermedio,Avanzado,Nativo',

            'intereses' => 'nullable|array|max:20',
            'intereses.*' => 'nullable|string|max:40',

            'referencias' => 'nullable|array|max:5',
            'referencias.*.nombre' => ['nullable', 'string', 'max:80', $letras('El nombre de la referencia')],
            'referencias.*.relacion' => ['nullable', 'string', 'max:60', $letras('La relación de la referencia')],
            'referencias.*.telefono' => ['nullable', 'string', 'max:14', $telefono('El teléfono de la referencia')],

            'direccion' => 'nullable|string|max:120',
            // Edad: se calcula con la fecha de nacimiento (de 15 a 90 años).
            'fecha_nacimiento' => 'nullable|date|before_or_equal:' . now()->subYears(15)->toDateString() . '|after_or_equal:' . now()->subYears(90)->toDateString(),
            'nacionalidad' => ['nullable', 'string', 'max:40', $letras('La nacionalidad')],
            'estado_civil' => 'nullable|in:Soltero/a,Casado/a,Unión libre,Otro',
            'tipo_documento' => 'nullable|string|max:50',
            'detalles_documento' => 'nullable|string|max:100',
        ];
    }

    private function mensajesCv(): array
    {
        return [
            'max' => 'Un campo es demasiado largo (máximo :max caracteres). Acórtalo para continuar.',
            'correo.email' => 'Escribe un correo válido, por ejemplo nombre@correo.com.',
            'foto.image' => 'La foto debe ser una imagen (JPG o PNG).',
            'foto.max' => 'La foto pesa demasiado, el máximo es 2 MB.',
            'fecha_nacimiento.date' => 'Escribe una fecha de nacimiento válida.',
            'fecha_nacimiento.before_or_equal' => 'La edad mínima es de 15 años. Revisa tu fecha de nacimiento.',
            'fecha_nacimiento.after_or_equal' => 'Revisa tu fecha de nacimiento: la edad máxima es de 90 años.',
            'idiomas.*.nivel.in' => 'Elige un nivel de idioma de la lista.',
            'estado_civil.in' => 'Elige un estado civil de la lista.',
        ];
    }

    /**
     * Revisa con el moderador los textos libres del CV (no teléfonos, correos ni fechas) y la foto.
     * Devuelve null si todo está bien, o la respuesta JSON 422 con el motivo.
     */
    private function bloqueoDeModeracion(Request $request, ModeracionService $moderacion): ?\Illuminate\Http\JsonResponse
    {
        $partes = [];
        foreach (['nombre', 'apellidos', 'titulo', 'resumen', 'direccion', 'nacionalidad'] as $campo) {
            $partes[] = $request->input($campo);
        }
        foreach ([
            'formacion' => ['titulo', 'institucion'],
            'experiencia' => ['puesto', 'lugar', 'descripcion'],
            'cursos' => ['nombre', 'institucion'],
            'idiomas' => ['nombre'],
            'referencias' => ['nombre', 'relacion'],
        ] as $seccion => $campos) {
            foreach ((array) $request->input($seccion, []) as $fila) {
                foreach ($campos as $campo) {
                    $partes[] = is_array($fila) ? ($fila[$campo] ?? null) : null;
                }
            }
        }
        foreach (['habilidades', 'intereses'] as $lista) {
            foreach ((array) $request->input($lista, []) as $valor) {
                $partes[] = is_string($valor) ? $valor : null;
            }
        }

        $texto = collect($partes)->filter(fn ($v) => is_string($v) && trim($v) !== '')->implode("\n");

        $revision = $moderacion->revisar($texto, $request->file('foto'));
        if (! empty($revision['flagged'])) {
            return response()->json([
                'ok' => false,
                'error' => str_replace('Tu publicación', 'Tu CV', (string) $revision['mensaje']),
            ], 422);
        }

        return null;
    }

    /**
     * Alternativa: subir un CV ya hecho (PDF/Word) para ESE servicio, en
     * vez de usar el formulario guiado.
     */
    public function subirArchivo(Request $request, $servicioId, ValidadorDocumento $validador)
    {
        Servicio::where('usuario_id', Auth::id())->findOrFail($servicioId);

        // Que sea PDF/Word de verdad, sin contenido indebido y que parezca un CV.
        if ($error = $validador->respuestaSiInvalido($request, ValidadorDocumento::CV)) {
            return $error;
        }

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
            // El empleador ve el teléfono y correo del CV solo si ya aceptó a la persona.
            'contactoVisible' => $esPropio || $postulacion->contactoAbierto(),
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
    public function guardarPostulacion(Request $request, $postulacionId, ModeracionService $moderacion)
    {
        Postulacion::where('postulante_id', Auth::id())->findOrFail($postulacionId);

        $request->validate($this->reglasCv(), $this->mensajesCv());

        // Mismo moderador que el resto del sitio: textos del CV y la foto.
        if ($bloqueo = $this->bloqueoDeModeracion($request, $moderacion)) {
            return $bloqueo;
        }

        // Revisión por pasos: el formulario pregunta en cada "Continuar" sin guardar nada.
        if ($request->boolean('solo_moderar')) {
            return response()->json(['ok' => true]);
        }

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
    public function subirArchivoPostulacion(Request $request, $postulacionId, ValidadorDocumento $validador)
    {
        Postulacion::where('postulante_id', Auth::id())->findOrFail($postulacionId);

        // Que sea PDF/Word de verdad, sin contenido indebido y que parezca un CV.
        if ($error = $validador->respuestaSiInvalido($request, ValidadorDocumento::CV)) {
            return $error;
        }

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
            // CV hecho con el formulario de la plataforma (se puede volver a editar).
            'creadoEnPlataforma' => (bool) ($curriculum && ! empty($curriculum->nombre) && empty($curriculum->archivo_subido)),
        ]);
    }
}
