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

        $postulaciones = Postulacion::with(['postulante.localidad', 'vacante', 'curriculum'])
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
        $statEntrevistas = $postulaciones->where('estado', 'entrevista')->count();
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
            'statEntrevistas' => $statEntrevistas,
            'statContratados' => $statContratados,
            'statRechazados' => $statRechazados,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,entrevista,contratado,rechazado',
        ]);

        $postulacion = Postulacion::whereHas('vacante', function ($query) {
            $query->where('empleador_id', Auth::id());
        })->findOrFail($id);

        $nuevo = $request->input('estado');

        // El camino normal: pendiente -> entrevista -> contratado. Rechazar se puede en cualquier punto.
        // Contratar directo (sin entrevista) sigue permitido para no romper lo que ya se hacía.
        if ($postulacion->estado === 'borrador') {
            return response()->json(['ok' => false, 'error' => 'Esta postulación todavía no está enviada.'], 422);
        }
        if ($nuevo === 'entrevista' && $postulacion->estado !== 'pendiente') {
            return response()->json(['ok' => false, 'error' => 'Solo se puede aceptar para entrevista una postulación pendiente.'], 422);
        }
        if ($postulacion->estado === 'rechazado' || $postulacion->estado === 'contratado') {
            return response()->json(['ok' => false, 'error' => 'Esta postulación ya tiene una decisión final.'], 422);
        }

        try {
            $postulacion->estado = $nuevo;
            $postulacion->save();
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['ok' => false, 'error' => 'No se pudo guardar el cambio. Si acabas de actualizar el sitio, falta correr el SQL de postulaciones.'], 500);
        }

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
