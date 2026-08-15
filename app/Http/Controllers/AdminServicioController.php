<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class AdminServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with(['usuario', 'categoria', 'subcategoria'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.servicios', [
            'servicios' => $servicios,
        ]);
    }

    public function toggle($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->estado = $servicio->estado === 'activo' ? 'inactivo' : 'activo';
        $servicio->save();

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return response()->json(['ok' => true]);
    }
}
