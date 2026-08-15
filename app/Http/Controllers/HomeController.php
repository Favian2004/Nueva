<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // TODO: cuando haya más de un municipio, esto debe depender de la
    // ubicación del visitante en vez de estar fijo en Zacapoaxtla (id 1).
    private function anunciosDelMunicipio()
    {
        $anuncios = Anuncio::with('imagenes')
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

    public function index(Request $request)
    {
        $q = $request->input('q');
        $categoriaId = $request->input('categoria_id');

        $servicios = Servicio::with('usuario')
            ->withAvg('calificaciones', 'estrellas')
            ->withCount('calificaciones')
            ->where('estado', 'activo')
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            }))
            ->when($categoriaId, fn($query) => $query->where('categoria_id', $categoriaId))
            ->latest()
            ->take(($q || $categoriaId) ? 50 : 8)
            ->get();

        $categorias = \App\Models\Categoria::orderBy('nombre')->get();

        return view('index', array_merge($this->anunciosDelMunicipio(), [
            'servicios' => $servicios,
            'categorias' => $categorias,
            'q' => $q,
            'categoriaId' => $categoriaId,
            'totalVisitas' => $this->contarVisita(),
        ]));
    }

    public function acceso()
    {
        return view('acceso', $this->anunciosDelMunicipio());
    }

    public function acercaDe()
    {
        return view('acerca_de', $this->anunciosDelMunicipio());
    }

    public function servicioCliente()
    {
        return view('servicio_cliente', $this->anunciosDelMunicipio());
    }

    public function terminos()
    {
        return view('terminos', $this->anunciosDelMunicipio());
    }
}
