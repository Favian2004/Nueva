<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarServicioRequest;
use App\Models\Categoria;
use App\Models\Contratacion;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Services\ModeracionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class UsuarioServicioController extends Controller
{
    public function create()
    {
        $categorias = Categoria::with('subcategorias')->orderBy('nombre')->get();
        $usuario = Auth::user();

        return view('usuario.publicarEmpleo', [
            'categorias' => $categorias,
            'usuario' => $usuario,
            'servicio' => null,
            'categoriaBloqueada' => false,
        ]);
    }

    public function store(GuardarServicioRequest $request, ModeracionService $moderacion)
    {
        // Las reglas (tipos de dato, largos, teléfono de 10 dígitos, precio...)
        // viven en GuardarServicioRequest.
        $datos = $request->datosParaGuardar();

        $revision = $moderacion->revisar(
            $datos['titulo'] . "\n" . $datos['descripcion'],
            $request->file('imagen')
        );
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'message' => $revision['mensaje']], 422);
        }

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = '/storage/' . $request->file('imagen')->store('servicios', 'public');
        }

        $servicio = Servicio::create($datos + [
            'usuario_id' => Auth::id(),
            'imagen' => $rutaImagen,
            'estado' => 'activo',
        ]);

        return response()->json(['ok' => true, 'servicioId' => $servicio->id]);
    }

    public function edit($id)
    {
        $categorias = Categoria::with('subcategorias')->orderBy('nombre')->get();
        $usuario = Auth::user();
        $servicio = Servicio::with('curriculum')->where('usuario_id', Auth::id())->findOrFail($id);
        $tieneCv = $servicio->curriculum && ($servicio->curriculum->tieneContenido || $servicio->curriculum->archivo_subido);
        $tieneSolicitud = (bool) $servicio->solicitud_empleo;

        $categoriaBloqueada = $servicio->contrataciones()->exists() || $servicio->calificaciones()->exists();

        return view('usuario.publicarEmpleo', [
            'categorias' => $categorias,
            'usuario' => $usuario,
            'servicio' => $servicio,
            'categoriaBloqueada' => $categoriaBloqueada,
            'tieneCv' => $tieneCv,
            'tieneSolicitud' => $tieneSolicitud,
        ]);
    }

    public function update(GuardarServicioRequest $request, $id, ModeracionService $moderacion)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($id);

        $categoriaBloqueada = $servicio->contrataciones()->exists() || $servicio->calificaciones()->exists();

        // Las reglas viven en GuardarServicioRequest (si el servicio ya tiene
        // contrataciones o calificaciones, ahí mismo se deja de pedir la
        // categoría y subcategoría).
        $datos = $request->datosParaGuardar();

        // Si SÍ está bloqueada, se ignora cualquier intento de cambiar la
        // categoría/subcategoría (se queda con la que ya tenía), sin importar
        // qué se haya mandado en la petición.
        if ($categoriaBloqueada) {
            unset($datos['categoria_id'], $datos['subcategoria_id']);
        }

        $revision = $moderacion->revisar(
            $datos['titulo'] . "\n" . $datos['descripcion'],
            $request->file('imagen')
        );
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'message' => $revision['mensaje']], 422);
        }

        $datos['imagen'] = $servicio->imagen;
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = '/storage/' . $request->file('imagen')->store('servicios', 'public');
        }

        $servicio->update($datos);

        return response()->json(['ok' => true]);
    }

    public function misEmpleos()
    {
        $servicios = Servicio::with(['categoria', 'subcategoria'])
            ->where('usuario_id', Auth::id())
            ->latest()
            ->get();

        $total = $servicios->count();
        $activos = $servicios->where('estado', 'activo')->count();
        $inactivos = $servicios->where('estado', 'inactivo')->count();

        return view('usuario.misEmpleos', [
            'servicios' => $servicios,
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos,
        ]);
    }

    public function toggle($id)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($id);
        $servicio->estado = $servicio->estado === 'activo' ? 'inactivo' : 'activo';
        $servicio->save();

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($id);
        $servicio->delete();

        return response()->json(['ok' => true]);
    }

    public function subirSolicitudEmpleo(Request $request, $id)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($id);

        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:4096',
        ]);

        $servicio->solicitud_empleo = '/storage/' . $request->file('archivo')->store('solicitudes-empleo', 'public');
        $servicio->save();

        return response()->json(['ok' => true]);
    }

    public function show($id)
    {
        $servicio = Servicio::with(['usuario.localidad', 'curriculum', 'categoria', 'subcategoria'])->findOrFail($id);

        $esPropio = $servicio->usuario_id === Auth::id();

        $contactoVisible = $esPropio || Contratacion::contactoAbierto($id, Auth::id());
        $solicitudPendiente = !$esPropio && Contratacion::where('servicio_id', $id)
            ->where('contratante_id', Auth::id())
            ->where('estado', 'pendiente')
            ->exists();

        $yaSolicitado = false;
        if (!$esPropio) {
            $yaSolicitado = Contratacion::where('servicio_id', $id)
                ->where('contratante_id', Auth::id())
                ->whereIn('estado', ['pendiente', 'aceptado'])
                ->exists();
        }

        // Si ya le habían cancelado una solicitud, el formulario se abre con esos mismos datos.
        $previa = null;
        if (!$esPropio && !$yaSolicitado) {
            $anterior = Contratacion::where('servicio_id', $id)
                ->where('contratante_id', Auth::id())
                ->where('estado', 'cancelado')
                ->latest()
                ->first();
            $previa = $anterior ? $anterior->datosParaFormulario() : null;
        }

        return view('usuario.ver_servicio', [
            'previa' => $previa,
            'servicio' => $servicio,
            'esPropio' => $esPropio,
            'yaSolicitado' => $yaSolicitado,
            'contactoVisible' => $contactoVisible,
            'solicitudPendiente' => $solicitudPendiente,
        ]);
    }

    public function solicitar(Request $request, $id, ModeracionService $moderacion)
    {
        $servicio = Servicio::findOrFail($id);

        if ($servicio->usuario_id === Auth::id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes solicitar tu propio servicio.'], 422);
        }

        $yaExiste = Contratacion::where('servicio_id', $id)
            ->where('contratante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'aceptado'])
            ->exists();

        if ($yaExiste) {
            return response()->json(['ok' => false, 'error' => 'Ya tienes una solicitud activa para este servicio.'], 422);
        }

        // Formulario "Solicitud de servicio": el trabajador decide si acepta con estos datos.
        $datos = $request->validate([
            'necesidad' => 'required|string|min:10|max:500',
            'fecha_necesaria' => 'required|in:' . implode(',', array_keys(Contratacion::FECHAS)),
            'fecha_especifica' => 'nullable|required_if:fecha_necesaria,fecha|date_format:Y-m-d|after_or_equal:today',
            'presupuesto' => 'required|in:' . implode(',', array_keys(Contratacion::PRESUPUESTOS)),
            'contacto_preferido' => 'required|in:' . implode(',', array_keys(Contratacion::CONTACTOS)),
        ], [
            'necesidad.required' => 'Cuéntale al trabajador qué necesitas.',
            'necesidad.min' => 'Describe un poco más lo que necesitas (mínimo 10 letras).',
            'necesidad.max' => 'Tu descripción es muy larga (máximo 500 letras).',
            'fecha_especifica.required_if' => 'Elige la fecha en que lo necesitas.',
            'fecha_especifica.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
        ]);

        // Para que nadie insista sin parar a un mismo servicio: máximo 3 solicitudes por día.
        $limite = 'solicitud-servicio:' . Auth::id() . ':' . $id;
        if (RateLimiter::tooManyAttempts($limite, 3)) {
            return response()->json(['ok' => false, 'error' => 'Ya enviaste varias solicitudes a este servicio hoy. Intenta de nuevo mañana.'], 429);
        }

        $revision = $moderacion->revisar($datos['necesidad']);
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'error' => $revision['mensaje']], 422);
        }

        $fila = [
            'servicio_id' => $id,
            'contratante_id' => Auth::id(),
            'trabajador_id' => $servicio->usuario_id,
            'estado' => 'pendiente',
        ];

        // Si aún no se corrió el cambio de base de datos, la solicitud se guarda igual (sin los detalles).
        if (\Illuminate\Support\Facades\Schema::hasColumn('contrataciones', 'necesidad')) {
            $fila += [
                'necesidad' => trim($datos['necesidad']),
                'fecha_necesaria' => $datos['fecha_necesaria'] === 'fecha' ? $datos['fecha_especifica'] : $datos['fecha_necesaria'],
                'presupuesto' => $datos['presupuesto'],
                'contacto_preferido' => $datos['contacto_preferido'],
            ];
        }

        // Al volver a enviar, la solicitud cancelada anterior se reemplaza por la nueva
        // (así el trabajador recibe su aviso y no quedan tarjetas repetidas).
        DB::transaction(function () use ($id, $fila) {
            Contratacion::where('servicio_id', $id)
                ->where('contratante_id', Auth::id())
                ->where('estado', 'cancelado')
                ->delete();

            Contratacion::create($fila);
        });
        RateLimiter::hit($limite, 86400);

        return response()->json(['ok' => true]);
    }
}
