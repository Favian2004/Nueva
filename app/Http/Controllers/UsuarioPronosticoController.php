<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Services\PronosticoDemandaService;
use Illuminate\Http\Request;

class UsuarioPronosticoController extends Controller
{
    // Página "Pronóstico" del panel de empleador: en qué días y meses hay más
    // demanda de trabajo (en general o de una categoría / subcategoría), más un
    // ranking de las categorías que se vienen más fuertes.
    public function index(Request $request, PronosticoDemandaService $servicio)
    {
        $datos = $request->validate([
            'categoria_id' => 'nullable|integer',
            'subcategoria_id' => 'nullable|integer',
            'medida' => 'nullable|in:vacantes,trabajadores',
        ]);

        $categorias = Categoria::with('subcategorias:id,categoria_id,nombre')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $categoria = null;
        $subcategoria = null;

        if (!empty($datos['categoria_id'])) {
            $categoria = $categorias->firstWhere('id', (int) $datos['categoria_id']);
        }
        // La subcategoría solo cuenta si pertenece a la categoría elegida.
        if ($categoria && !empty($datos['subcategoria_id'])) {
            $subcategoria = $categoria->subcategorias->firstWhere('id', (int) $datos['subcategoria_id']);
        }

        $medida = $datos['medida'] ?? 'vacantes';

        $pronostico = $servicio->calcular(
            $categoria?->id,
            $subcategoria?->id,
            $medida
        );

        return view('usuario.pronostico', [
            'categorias' => $categorias,
            'categoria' => $categoria,
            'subcategoria' => $subcategoria,
            'medida' => $pronostico['medida'],
            'p' => $pronostico,
            'ranking' => $servicio->ranking($pronostico['medida']),
        ]);
    }
}
