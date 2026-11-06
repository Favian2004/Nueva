<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Base de todos los avisos de la campana.
 *
 * Cada aviso se guarda en la tabla "notifications" (lo que se ve en la campana)
 * y, si $correo es true, también se manda por correo.
 *
 * Los avisos NO van a cola: se envían en el momento (no hace falta correr
 * "queue:work"). Quien los manda es App\Services\Avisos, que además evita
 * que un fallo del correo rompa la acción del usuario.
 */
abstract class AvisoSimple extends Notification
{
    /** true = además de la campana, se manda por correo. */
    protected bool $correo = false;

    /**
     * Datos del aviso:
     *  tipo     clave corta (nueva_postulacion, contratado...)
     *  modo     'trabajador' o 'empleador' (etiqueta "Como ..." en la campana)
     *  titulo   título corto
     *  texto    una frase
     *  url      a dónde lleva al tocarlo (ruta relativa, ej. /usuario/postulantes)
     *  boton    (opcional) texto del botón del correo
     *  detalle  (opcional) línea extra que solo se ve en el correo
     */
    abstract protected function datos(): array;

    /** Identifica el hecho que originó el aviso (para no avisar dos veces lo mismo). */
    abstract public function referencia(): string;

    public function via(object $notifiable): array
    {
        return $this->correo && !empty($notifiable->email) ? ['database', 'mail'] : ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return $this->datos() + ['ref' => $this->referencia()];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $d = $this->datos();

        return (new MailMessage)
            ->subject($d['titulo'] . ' · ¡SINTECZATE!')
            ->view('emails.aviso', [
                'titulo' => $d['titulo'],
                'texto' => $d['texto'],
                'detalle' => $d['detalle'] ?? null,
                'boton' => $d['boton'] ?? 'Ver en ¡SINTECZATE!',
                'url' => url($d['url']),
            ]);
    }
}
