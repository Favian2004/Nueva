<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\Vacante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioPostulanteController extends Controller
{
    public function index(Request $request)
    {
        $vacanteId = $request->input('vacante');
        $estado = $request->input('estado');
        $q = $request->input('q');

        $postulaciones = Postulacion::with(['postulante', 'vacante', 'curriculum'])
            ->where('estado', '!=', 'borrador')
            ->whereHas('vacante', function ($query) {
                $query->where('empleador_id', Auth::id());
            })
            ->when($vacanteId, fn($query) => $query->where('vacante_id', $vacanteId))
            ->when($estado, fn($query) => $query->where('estado', $estado))
            ->when($q, fn($query) => $query->whereHas('postulante', function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%");
            }))
            ->latest()
            ->get();

        $vacantes = Vacante::where('empleador_id', Auth::id())->orderBy('titulo')->get();

        $statTotal = $postulaciones->count();
        $statPendientes = $postulaciones->where('estado', 'pendiente')->count();
        $statContratados = $postulaciones->where('estado', 'contratado')->count();
        $statRechazados = $postulaciones->where('estado', 'rechazado')->count();

        $misPostulaciones = Postulacion::with(['vacante.empleador'])
            ->where('postulante_id', Auth::id())
            ->where('estado', '!=', 'borrador')
            ->latest()
            ->get();

        return view('usuario.postulantes', [
            'postulaciones' => $postulaciones,
            'misPostulaciones' => $misPostulaciones,
            'vacantes' => $vacantes,
            'vacanteId' => $vacanteId,
            'estado' => $estado,
            'q' => $q,
            'statTotal' => $statTotal,
            'statPendientes' => $statPendientes,
            'statContratados' => $statContratados,
            'statRechazados' => $statRechazados,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,contratado,rechazado',
        ]);

        $postulacion = Postulacion::whereHas('vacante', function ($query) {
            $query->where('empleador_id', Auth::id());
        })->findOrFail($id);

        $postulacion->estado = $request->input('estado');
        $postulacion->save();

        return response()->json(['ok' => true]);
    }

    public function eliminar($id)
    {
        // Solo se puede eliminar si ya fue rechazada, y solo si el usuario
        // es el postulante (la envió) o el empleador de la vacante (la recibió).
        $postulacion = Postulacion::where(function ($query) {
                $query->where('postulante_id', Auth::id())
                      ->orWhereHas('vacante', function ($sub) {
                          $sub->where('empleador_id', Auth::id());
                      });
            })
            ->where('estado', 'rechazado')
            ->findOrFail($id);

        $postulacion->delete();

        return response()->json(['ok' => true]);
    }
}
