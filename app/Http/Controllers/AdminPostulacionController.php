<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\Vacante;
use Illuminate\Http\Request;

class AdminPostulacionController extends Controller
{
    public function index()
    {
        $postulaciones = Postulacion::with(['postulante', 'vacante'])
            ->orderBy('created_at', 'desc')
            ->get();

        $vacantes = Vacante::orderBy('titulo')->get();

        return view('admin.postulaciones', [
            'postulaciones' => $postulaciones,
            'vacantes' => $vacantes,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,contratado,rechazado',
        ]);

        $postulacion = Postulacion::findOrFail($id);
        $postulacion->estado = $request->input('estado');
        $postulacion->save();

        return response()->json(['ok' => true]);
    }
}
