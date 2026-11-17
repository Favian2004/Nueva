<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SesionController extends Controller
{
    // Cuántos minutos de inactividad aguanta la sesión (lo usa el aviso del dashboard).
    public function estado(): JsonResponse
    {
        return response()->json(['minutos' => max(1, (int) config('session.lifetime'))]);
    }

    // "Seguir conectado": una petición normal ya cuenta como actividad (ver InactividadSesion).
    public function renovar(): Response
    {
        return response()->noContent();
    }
}
