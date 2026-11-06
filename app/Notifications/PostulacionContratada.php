<?php

namespace App\Notifications;

use App\Models\Postulacion;

// Para el TRABAJADOR: lo contrataron para una vacante. (campana + correo)
class PostulacionContratada extends AvisoSimple
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

        $contacto = collect([
            $vacante?->telefono ? 'Teléfono: ' . $vacante->telefono : null,
            $vacante?->whatsapp ? 'WhatsApp: ' . $vacante->whatsapp : null,
        ])->filter()->implode(' · ');

        return [
            'tipo' => 'contratado',
            'modo' => 'trabajador',
            'titulo' => '¡Te contrataron!',
            'texto' => "{$quien} te contrató para «{$vacante?->titulo}».",
            'detalle' => $contacto !== '' ? "Para ponerte de acuerdo — {$contacto}" : null,
            'boton' => 'Ver mi postulación',
            'url' => '/usuario/postulantes?tab=enviadas',
        ];
    }
}
