<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Contratacion;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'required|exists:subcategorias,id',
            'descripcion' => 'required|string',
            'ubicacion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:4096',
        ]);

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = '/storage/' . $request->file('imagen')->store('servicios', 'public');
        }

        $servicio = Servicio::create([
            'usuario_id' => Auth::id(),
            'categoria_id' => $request->input('categoria_id'),
            'subcategoria_id' => $request->input('subcategoria_id'),
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'ubicacion' => $request->input('ubicacion'),
            'telefono' => $request->input('telefono'),
            'whatsapp' => $request->input('whatsapp'),
            'precio' => $request->input('precio'),
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

    public function update(Request $request, $id)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($id);

        $categoriaBloqueada = $servicio->contrataciones()->exists() || $servicio->calificaciones()->exists();

        $reglas = [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ubicacion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:4096',
        ];

        // Solo se valida/permite cambiar categoría y subcategoría si el
        // servicio todavía no tiene ninguna contratación ni calificación.
        if (!$categoriaBloqueada) {
            $reglas['categoria_id'] = 'required|exists:categorias,id';
            $reglas['subcategoria_id'] = 'required|exists:subcategorias,id';
        }

        $request->validate($reglas);

        $rutaImagen = $servicio->imagen;
        if ($request->hasFile('imagen')) {
            $rutaImagen = '/storage/' . $request->file('imagen')->store('servicios', 'public');
        }

        $datos = [
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'ubicacion' => $request->input('ubicacion'),
            'telefono' => $request->input('telefono'),
            'whatsapp' => $request->input('whatsapp'),
            'precio' => $request->input('precio'),
            'imagen' => $rutaImagen,
        ];

        // Si NO está bloqueada, sí se actualiza la categoría/subcategoría
        // con lo que mande el formulario. Si SÍ está bloqueada, se ignora
        // cualquier intento de cambiarla (se queda con la que ya tenía),
        // sin importar qué se haya mandado en la petición.
        if (!$categoriaBloqueada) {
            $datos['categoria_id'] = $request->input('categoria_id');
            $datos['subcategoria_id'] = $request->input('subcategoria_id');
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

        $yaSolicitado = false;
        if (!$esPropio) {
            $yaSolicitado = Contratacion::where('servicio_id', $id)
                ->where('contratante_id', Auth::id())
                ->whereIn('estado', ['pendiente', 'aceptado'])
                ->exists();
        }

        return view('usuario.ver_servicio', [
            'servicio' => $servicio,
            'esPropio' => $esPropio,
            'yaSolicitado' => $yaSolicitado,
        ]);
    }

    public function solicitar($id)
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

        Contratacion::create([
            'servicio_id' => $id,
            'contratante_id' => Auth::id(),
            'trabajador_id' => $servicio->usuario_id,
            'estado' => 'pendiente',
        ]);

        return response()->json(['ok' => true]);
    }
}
