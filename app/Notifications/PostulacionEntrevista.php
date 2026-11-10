<?php

namespace App\Notifications;

use App\Models\Postulacion;

// Para el TRABAJADOR: el empleador lo aceptó para entrevista y ya puede ver su contacto. (campana + correo)
class PostulacionEntrevista extends AvisoSimple
{
    protected bool $correo = true;

    public function __construct(public Postulacion $postulacion)
    {
    }

    public function referencia(): string
    {
        return 'postulacion:' . $this->postulacion->id;
    }

    protected function datos(): array
    {
        $vacante = $this->postulacion->vacante;
        $quien = $vacante?->publicante ?: ($vacante?->empleador?->nombre ?: 'El empleador');

        return [
            'tipo' => 'entrevista',
            'modo' => 'trabajador',
            'titulo' => '¡Te aceptaron para entrevista!',
            'texto' => "{$quien} quiere conocerte para «{$vacante?->titulo}».",
            'detalle' => 'Ya puedes ver su teléfono y WhatsApp en tu postulación. Por tu seguridad, nunca pagues por un empleo.',
            'boton' => 'Ver contacto',
            'url' => '/usuario/postulantes?tab=enviadas',
        ];
    }
}
