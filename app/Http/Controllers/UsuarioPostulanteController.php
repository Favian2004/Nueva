<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\Vacante;
use App\Services\ModeracionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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

    public function cambiarEstado(Request $request, $id, ModeracionService $moderacion)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,entrevista,contratado,rechazado',
            // Solo se usa al rechazar: lo que el empleador le explica a la persona (opcional).
            'motivo' => 'nullable|string|min:5|max:300',
        ], [
            'motivo.min' => 'Si escribes un motivo, que tenga al menos 5 letras.',
            'motivo.max' => 'El motivo puede tener hasta 300 letras.',
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

        // Motivo del rechazo (opcional). Pasa por el mismo filtro de groserías que el resto del sitio,
        // porque lo va a leer otra persona.
        $motivo = null;
        if ($nuevo === 'rechazado') {
            $motivo = trim((string) $request->input('motivo', ''));
            $motivo = $motivo === '' ? null : $motivo;

            if ($motivo !== null) {
                $revision = $moderacion->revisar($motivo);
                if ($revision['flagged']) {
                    return response()->json(['ok' => false, 'error' => 'El motivo no se pudo enviar porque contiene lenguaje o contenido no permitido. Escríbelo de forma respetuosa.'], 422);
                }
            }
        }

        try {
            $postulacion->estado = $nuevo;
            // La columna se crea con el SQL "motivo-rechazo.sql"; si todavía no existe, el rechazo funciona igual (sin motivo).
            if ($nuevo === 'rechazado' && Schema::hasColumn('postulaciones', 'motivo_rechazo')) {
                $postulacion->motivo_rechazo = $motivo;
            }
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
