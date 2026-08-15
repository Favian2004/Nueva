<?php

namespace App\Http\Controllers;

use App\Models\Contratacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioSolicitudController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->input('estado');

        $recibidas = Contratacion::with(['servicio', 'contratante'])
            ->where('trabajador_id', Auth::id())
            ->when($estado, fn($query) => $query->where('estado', $estado))
            ->latest()
            ->get();

        $enviadas = Contratacion::with(['servicio', 'trabajador'])
            ->where('contratante_id', Auth::id())
            ->latest()
            ->get();

        $statTotal = $recibidas->count();
        $statPendientes = $recibidas->where('estado', 'pendiente')->count();
        $statAceptadas = $recibidas->where('estado', 'aceptado')->count();
        $statFinalizadas = $recibidas->where('estado', 'finalizado')->count();

        return view('usuario.solicitudes', [
            'solicitudes' => $recibidas,
            'enviadas' => $enviadas,
            'estado' => $estado,
            'statTotal' => $statTotal,
            'statPendientes' => $statPendientes,
            'statAceptadas' => $statAceptadas,
            'statFinalizadas' => $statFinalizadas,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aceptado,finalizado,cancelado',
        ]);

        $solicitud = Contratacion::where('trabajador_id', Auth::id())->findOrFail($id);
        $solicitud->estado = $request->input('estado');
        $solicitud->save();

        return response()->json(['ok' => true]);
    }
}
