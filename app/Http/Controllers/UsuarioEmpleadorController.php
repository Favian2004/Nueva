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

    public function postularse(Request $request, $vacanteId)
    {
        $vacante = Vacante::findOrFail($vacanteId);

        $yaExiste = Postulacion::where('vacante_id', $vacanteId)
            ->where('postulante_id', Auth::id())
            ->exists();

        if ($yaExiste) {
            return response()->json(['ok' => false, 'error' => 'Ya te postulaste a esta vacante.'], 422);
        }

        $request->validate([
            'mensaje' => 'nullable|string|max:1000',
        ]);

        Postulacion::create([
            'vacante_id' => $vacanteId,
            'postulante_id' => Auth::id(),
            'estado' => 'pendiente',
            'mensaje' => $request->input('mensaje'),
        ]);

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
