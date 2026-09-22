<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\Vacante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioEmpleadorController extends Controller
{
    public function index()
    {
        $vacantesActivas = Vacante::where('empleador_id', Auth::id())->where('estado', 'activa')->count();

        $postulantesActivos = Postulacion::whereHas('vacante', function ($q) {
            $q->where('empleador_id', Auth::id());
        })->where('estado', 'pendiente')->count();

        $contratadosActivos = Postulacion::whereHas('vacante', function ($q) {
            $q->where('empleador_id', Auth::id());
        })->where('estado', 'contratado')->count();

        $vacantes = Vacante::with('empleador')
            ->withCount('postulaciones')
            ->where('estado', 'activa')
            ->latest()
            ->get();

        $misPostulacionesIds = Postulacion::where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->pluck('vacante_id')
            ->toArray();

        return view('usuario.empleador', [
            'vacantesActivas' => $vacantesActivas,
            'postulantesActivos' => $postulantesActivos,
            'contratadosActivos' => $contratadosActivos,
            'vacantes' => $vacantes,
            'misPostulacionesIds' => $misPostulacionesIds,
        ]);
    }

    /**
     * Se llama apenas se abre el modal de "Postularme" en una vacante que
     * pide CV y/o Solicitud -- crea (o reutiliza) una postulación en estado
     * "borrador", solo para que el CV tenga a qué pegarse. Todavía no es
     * una postulación de verdad hasta que se llame a postularse().
     */
    public function iniciarBorrador($vacanteId)
    {
        $vacante = Vacante::findOrFail($vacanteId);

        if ($vacante->empleador_id === Auth::id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes postularte a tu propia vacante.'], 422);
        }

        $yaExiste = Postulacion::where('vacante_id', $vacanteId)
            ->where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->exists();

        if ($yaExiste) {
            return response()->json(['ok' => false, 'error' => 'Ya tienes una postulación activa para esta vacante.'], 422);
        }

        $postulacion = Postulacion::firstOrCreate([
            'vacante_id' => $vacanteId,
            'postulante_id' => Auth::id(),
            'estado' => 'borrador',
        ]);

        return response()->json(['ok' => true, 'postulacionId' => $postulacion->id]);
    }

    public function postularse(Request $request, $vacanteId)
    {
        $vacante = Vacante::findOrFail($vacanteId);

        if ($vacante->empleador_id === Auth::id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes postularte a tu propia vacante.'], 422);
        }

        $yaExiste = Postulacion::where('vacante_id', $vacanteId)
            ->where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->exists();

        if ($yaExiste) {
            return response()->json(['ok' => false, 'error' => 'Ya tienes una postulación activa para esta vacante.'], 422);
        }

        $request->validate([
            'mensaje' => 'nullable|string|max:1000',
        ]);

        // Si ya existía un borrador (porque el modal ya había creado uno
        // para poder subir CV/Solicitud), lo terminamos de completar aquí.
        // Si no existía (la vacante no pedía documentos), se crea directo.
        $postulacion = Postulacion::firstOrNew([
            'vacante_id' => $vacanteId,
            'postulante_id' => Auth::id(),
            'estado' => 'borrador',
        ]);
        $postulacion->vacante_id = $vacanteId;
        $postulacion->postulante_id = Auth::id();
        $postulacion->estado = 'pendiente';
        $postulacion->mensaje = $request->input('mensaje');
        $postulacion->save();

        return response()->json(['ok' => true, 'postulacionId' => $postulacion->id]);
    }

    /**
     * Subir la Solicitud de Empleo para una postulación específica (todavía
     * en borrador o ya confirmada).
     */
    public function subirSolicitudPostulacion(Request $request, $postulacionId)
    {
        $postulacion = Postulacion::where('postulante_id', Auth::id())->findOrFail($postulacionId);

        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:4096',
        ]);

        $postulacion->solicitud_empleo = '/storage/' . $request->file('archivo')->store('solicitudes-empleo', 'public');
        $postulacion->save();

        return response()->json(['ok' => true]);
    }

    public function buscarTalento(Request $request)
    {
        $q = $request->input('q');
        $ubicacion = $request->input('ubicacion');
        $experiencia = $request->input('experiencia');
        $contrato = $request->input('contrato');
        $tipoPago = $request->input('tipo_pago');

        $vacantes = Vacante::with('empleador')
            ->withCount('postulaciones')
            ->where('estado', 'activa')
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%")
                    ->orWhere('ubicacion', 'like', "%{$q}%");
            }))
            ->when($ubicacion, fn($query) => $query->where('ubicacion', 'like', "%{$ubicacion}%"))
            ->when($experiencia, fn($query) => $query->where('experiencia', $experiencia))
            ->when($contrato, fn($query) => $query->where('contrato', $contrato))
            ->when($tipoPago, fn($query) => $query->where('tipo_pago', $tipoPago))
            ->latest()
            ->get();

        $misPostulacionesIds = Postulacion::where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->pluck('vacante_id')
            ->toArray();

        return view('usuario.buscar-talento', [
            'vacantes' => $vacantes,
            'misPostulacionesIds' => $misPostulacionesIds,
            'q' => $q,
            'ubicacion' => $ubicacion,
            'experiencia' => $experiencia,
            'contrato' => $contrato,
            'tipoPago' => $tipoPago,
        ]);
    }
}
