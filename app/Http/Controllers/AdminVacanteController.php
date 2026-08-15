<?php

namespace App\Http\Controllers;

use App\Models\Vacante;
use Illuminate\Http\Request;

class AdminVacanteController extends Controller
{
    public function index()
    {
        $vacantes = Vacante::with('empleador')
            ->withCount('postulaciones')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.vacantes', [
            'vacantes' => $vacantes,
        ]);
    }

    public function cerrar($id)
    {
        $vacante = Vacante::findOrFail($id);
        $vacante->estado = 'cerrada';
        $vacante->save();

        return response()->json(['ok' => true]);
    }

    public function reactivar($id)
    {
        $vacante = Vacante::findOrFail($id);
        $vacante->estado = 'activa';
        $vacante->save();

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $vacante = Vacante::findOrFail($id);
        $vacante->delete();

        return response()->json(['ok' => true]);
    }
}
