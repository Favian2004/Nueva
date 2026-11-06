<?php

namespace App\Observers;

use App\Models\Postulacion;
use App\Notifications\NuevaPostulacion;
use App\Notifications\PostulacionContratada;
use App\Notifications\PostulacionRechazada;
use App\Services\Avisos;

/**
 * Avisa cuando cambia una postulación, sin importar quién la cambió
 * (el empleador, el administrador, etc.).
 */
class PostulacionObserver
{
    public function created(Postulacion $postulacion): void
    {
        if ($postulacion->estado === 'pendiente') {
            $this->avisarAlEmpleador($postulacion);
        }
    }

    public function updated(Postulacion $postulacion): void
    {
        if (!$postulacion->wasChanged('estado')) {
            return;
        }

        $antes = $postulacion->getOriginal('estado');

        if ($postulacion->estado === 'pendiente' && $antes === 'borrador') {
            // El borrador se confirmó: ahora sí es una postulación.
            $this->avisarAlEmpleador($postulacion);
        } elseif ($postulacion->estado === 'contratado') {
            Avisos::enviar($postulacion->postulante, new PostulacionContratada($postulacion));
        } elseif ($postulacion->estado === 'rechazado') {
            Avisos::enviar($postulacion->postulante, new PostulacionRechazada($postulacion));
        }
    }

    private function avisarAlEmpleador(Postulacion $postulacion): void
    {
        Avisos::enviar($postulacion->vacante?->empleador, new NuevaPostulacion($postulacion));
    }
}
