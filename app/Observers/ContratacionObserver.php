<?php

namespace App\Observers;

use App\Models\Contratacion;
use App\Notifications\NuevaSolicitudServicio;
use App\Notifications\SolicitudServicioAceptada;
use App\Notifications\SolicitudServicioCancelada;
use App\Services\Avisos;

/**
 * Avisos de las solicitudes de contratación de servicios.
 */
class ContratacionObserver
{
    public function created(Contratacion $contratacion): void
    {
        if ($contratacion->estado === 'pendiente') {
            Avisos::enviar($contratacion->trabajador, new NuevaSolicitudServicio($contratacion));
        }
    }

    public function updated(Contratacion $contratacion): void
    {
        if (!$contratacion->wasChanged('estado')) {
            return;
        }

        if ($contratacion->estado === 'aceptado') {
            Avisos::enviar($contratacion->contratante, new SolicitudServicioAceptada($contratacion));
        } elseif ($contratacion->estado === 'cancelado') {
            Avisos::enviar($contratacion->contratante, new SolicitudServicioCancelada($contratacion));
        }
    }
}
