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

        Servicio::create([
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

        return response()->json(['ok' => true]);
    }

    public function edit($id)
    {
        $categorias = Categoria::with('subcategorias')->orderBy('nombre')->get();
        $usuario = Auth::user();
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($id);

        return view('usuario.publicarEmpleo', [
            'categorias' => $categorias,
            'usuario' => $usuario,
            'servicio' => $servicio,
        ]);
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::where('usuario_id', Auth::id())->findOrFail($id);

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

        $rutaImagen = $servicio->imagen;
        if ($request->hasFile('imagen')) {
            $rutaImagen = '/storage/' . $request->file('imagen')->store('servicios', 'public');
        }

        $servicio->update([
            'categoria_id' => $request->input('categoria_id'),
            'subcategoria_id' => $request->input('subcategoria_id'),
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'ubicacion' => $request->input('ubicacion'),
            'telefono' => $request->input('telefono'),
            'whatsapp' => $request->input('whatsapp'),
            'precio' => $request->input('precio'),
            'imagen' => $rutaImagen,
        ]);

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

    public function show($id)
    {
        $servicio = Servicio::with(['usuario.localidad', 'categoria', 'subcategoria'])->findOrFail($id);

        $esPropio = $servicio->usuario_id === Auth::id();

        $yaSolicitado = false;
        if (!$esPropio) {
            $yaSolicitado = Contratacion::where('servicio_id', $id)
                ->where('contratante_id', Auth::id())
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
            ->exists();

        if ($yaExiste) {
            return response()->json(['ok' => false, 'error' => 'Ya solicitaste este servicio.'], 422);
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
