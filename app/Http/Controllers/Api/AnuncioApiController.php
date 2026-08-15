<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use Illuminate\Http\Request;

class AnuncioApiController extends Controller
{
    public function index(Request $request)
    {
        // TODO: cuando haya más de un municipio, usar el municipio real del
        // usuario logueado en vez de dejarlo fijo en Zacapoaxtla (id 1).
        $municipioId = $request->input('municipio_id', 1);

        $anuncios = Anuncio::with('imagenes')
            ->where('municipio_id', $municipioId)
            ->where('estado', 'activo')
            ->orderBy('posicion')
            ->orderBy('orden')
            ->get()
            ->filter(fn($a) => $a->imagenes->count() > 0)
            ->values()
            ->map(fn($a) => [
                'id' => $a->id,
                'posicion' => $a->posicion,
                'orden' => $a->orden,
                'imagenes' => $a->imagenes->map(fn($img) => ['imagen' => $img->imagen])->values(),
            ]);

        return response()->json($anuncios);
    }
}
