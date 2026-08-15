<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Servicio;
use Illuminate\Http\Request;
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

        $misServiciosCount = Servicio::where('usuario_id', $usuario->id)->count();
        $serviciosActivosCount = Servicio::where('estado', 'activo')->count();
        $empresasActivasCount = Servicio::where('estado', 'activo')->distinct('usuario_id')->count('usuario_id');

        $q = $request->input('q');
        $categoriaId = $request->input('categoria_id');
        $orden = $request->input('orden', 'recientes');

        $servicios = Servicio::with('usuario')
            ->withAvg('calificaciones', 'estrellas')
            ->withCount('calificaciones')
            ->where('estado', 'activo')
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            }))
            ->when($categoriaId, fn($query) => $query->where('categoria_id', $categoriaId))
            ->when($orden === 'precio_asc', fn($query) => $query->orderBy('precio', 'asc'))
            ->when($orden === 'precio_desc', fn($query) => $query->orderBy('precio', 'desc'))
            ->when($orden === 'recientes', fn($query) => $query->latest())
            ->get();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('usuario.verEmpleos', [
            'servicios' => $servicios,
            'categorias' => $categorias,
            'q' => $q,
            'categoriaId' => $categoriaId,
            'orden' => $orden,
            'misServiciosCount' => $misServiciosCount,
            'serviciosActivosCount' => $serviciosActivosCount,
            'empresasActivasCount' => $empresasActivasCount,
        ]);
    }
}
