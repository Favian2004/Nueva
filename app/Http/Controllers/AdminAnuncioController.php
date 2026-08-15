<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\AnuncioImagen;
use App\Models\Municipio;
use Illuminate\Http\Request;

class AdminAnuncioController extends Controller
{
    public function index()
    {
        $municipios = Municipio::orderBy('nombre')->get();

        $anuncios = Anuncio::with('imagenes')
            ->orderBy('posicion')
            ->orderBy('orden')
            ->get();

        return view('admin.anuncios', [
            'municipios' => $municipios,
            'anuncios' => $anuncios,
        ]);
    }

    public function toggle($id)
    {
        $anuncio = Anuncio::findOrFail($id);
        $anuncio->estado = $anuncio->estado === 'activo' ? 'inactivo' : 'activo';
        $anuncio->save();

        return response()->json(['ok' => true]);
    }

    public function storeImagen(Request $request, $id)
    {
        $anuncio = Anuncio::with('imagenes')->findOrFail($id);

        if ($anuncio->imagenes->count() >= 5) {
            return response()->json(['ok' => false, 'error' => 'Este espacio ya tiene el máximo de 5 imágenes.'], 422);
        }

        $request->validate([
            'imagen' => 'required|image|max:4096',
        ]);

        $ruta = $request->file('imagen')->store('anuncios', 'public');

        $nuevaImagen = AnuncioImagen::create([
            'anuncio_id' => $anuncio->id,
            'imagen' => '/storage/' . $ruta,
            'orden' => $anuncio->imagenes->count() + 1,
        ]);

        return response()->json(['ok' => true, 'imagen' => $nuevaImagen]);
    }

    public function destroyImagen($id)
    {
        $imagen = AnuncioImagen::findOrFail($id);
        $imagen->delete();

        return response()->json(['ok' => true]);
    }
}
