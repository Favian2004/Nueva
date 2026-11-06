<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Localidad;
use App\Models\Municipio;
use App\Models\Subcategoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Datos que necesita el asistente de voz para entender lo que se le dice:
 *  - quién es la persona (si entró, su nombre, su localidad, si ya confirmó su correo);
 *  - las localidades, categorías y subcategorías reales de la base de datos,
 *    para reconocer "en Xaltipan" o "plomería" aunque se digan con errores.
 *
 * Es público a propósito: también lo usan quienes todavía no inician sesión
 * (solo devuelve datos que ya se ven en la página principal).
 */
class AsistenteController extends Controller
{
    public function datos(Request $request): JsonResponse
    {
        $usuario = $request->user();

        $municipio = Municipio::find(1);
        $localidades = Localidad::where('municipio_id', 1)->orderBy('nombre')->get(['id', 'nombre']);

        $persona = null;
        if ($usuario) {
            $localidad = $usuario->localidad_id ? $localidades->firstWhere('id', $usuario->localidad_id) : null;
            $persona = [
                'nombre' => trim(explode(' ', trim((string) $usuario->nombre))[0] ?? ''),
                'admin' => $usuario->rol === 'admin',
                'verificado' => $usuario->hasVerifiedEmail(),
                'localidad_id' => $localidad ? (int) $localidad->id : null,
                'localidad' => $localidad ? $localidad->nombre : null,
            ];
        }

        return response()->json([
            'logueado' => $persona !== null,
            'persona' => $persona,
            'municipio' => $municipio->nombre ?? 'Zacapoaxtla',
            'localidades' => $localidades,
            'categorias' => Categoria::orderBy('nombre')->get(['id', 'nombre']),
            'subcategorias' => Subcategoria::orderBy('nombre')->get(['id', 'categoria_id', 'nombre']),
        ])->header('Cache-Control', 'no-store, private');
    }
}
