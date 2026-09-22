<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class AdminCategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $subcategorias = Subcategoria::with('categoria')->orderBy('nombre')->get();

        $categoriasJson = $categorias->map(fn($c) => [
            'id' => $c->id,
            'nombre' => $c->nombre,
        ]);

        $subcategoriasJson = $subcategorias->map(fn($s) => [
            'id' => $s->id,
            'nombre' => $s->nombre,
            'categoria_id' => $s->categoria_id,
        ]);

        return view('admin.categorias', [
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'categoriasJson' => $categoriasJson,
            'subcategoriasJson' => $subcategoriasJson,
        ]);
    }

    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        Categoria::create([
            'nombre' => $request->input('nombre'),
        ]);

        return response()->json(['ok' => true]);
    }

    public function destroyCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete(); // borra en cascada sus subcategorías (definido en la base de datos)

        return response()->json(['ok' => true]);
    }

    public function storeSubcategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        Subcategoria::create([
            'nombre' => $request->input('nombre'),
            'categoria_id' => $request->input('categoria_id'),
        ]);

        return response()->json(['ok' => true]);
    }

    public function destroySubcategoria($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        $subcategoria->delete();

        return response()->json(['ok' => true]);
    }
}
