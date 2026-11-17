<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cierra la sesión de quien lleva demasiado tiempo SIN HACER NADA (SESSION_LIFETIME, en minutos).
 *
 * Laravel por sí solo renueva la sesión con cualquier petición, y la campana de notificaciones
 * le pregunta al servidor cada minuto; con una pestaña abierta la sesión nunca vencería.
 * Aquí se guarda la última actividad REAL de la persona y las consultas en segundo plano
 * (campana, asistente) no la renuevan.
 */
class InactividadSesion
{
    // Consultas automáticas del propio sitio: no cuentan como actividad de la persona.
    private const SEGUNDO_PLANO = ['usuario/notificaciones', 'asistente/datos', 'usuario/sesion/estado'];

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->hasSession()) {
                $request->session()->forget('ultima_actividad');
            }
            return $next($request);
        }

        $sesion = $request->session();
        $ahora = time();
        $limite = max(1, (int) config('session.lifetime')) * 60;

        if (!$sesion->has('ultima_actividad')) {
            $sesion->put('ultima_actividad', $ahora);
        }

        if ($ahora - (int) $sesion->get('ultima_actividad') > $limite) {
            Auth::logout();
            $sesion->invalidate();
            $sesion->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['mensaje' => 'Tu sesión se cerró por inactividad.'], 401);
            }

            return redirect('/acceso?sesion=inactividad');
        }

        if (!$request->is(...self::SEGUNDO_PLANO)) {
            $sesion->put('ultima_actividad', $ahora);
        }

        return $next($request);
    }
}
