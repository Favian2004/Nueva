<?php

namespace App\Services;

use App\Models\Usuario;
use App\Notifications\AvisoSimple;
use Illuminate\Support\Facades\Log;

/**
 * Manda un aviso (campana + correo si aplica) sin poder romper lo que el
 * usuario estaba haciendo: si falla el correo, o todavía no existe la tabla
 * "notifications", solo se anota en storage/logs/laravel.log.
 *
 * Tampoco repite el mismo aviso (mismo tipo y mismo hecho) a la misma persona.
 */
class Avisos
{
    public static function enviar(?Usuario $destino, AvisoSimple $aviso): void
    {
        if (!$destino) {
            return;
        }

        try {
            $yaAvisado = $destino->notifications()
                ->where('type', get_class($aviso))
                ->where('data->ref', $aviso->referencia())
                ->exists();

            if ($yaAvisado) {
                return;
            }

            $destino->notify($aviso);
        } catch (\Throwable $e) {
            Log::warning('No se pudo mandar el aviso ' . class_basename($aviso) . ': ' . $e->getMessage());
        }
    }
}
