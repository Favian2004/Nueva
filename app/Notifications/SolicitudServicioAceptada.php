<?php

namespace App\Notifications;

use App\Models\Contratacion;

// Para quien CONTRATÓ: el trabajador aceptó su solicitud. (campana + correo)
class SolicitudServicioAceptada extends AvisoSimple
{
    protected bool $correo = true;

    public function __construct(public Contratacion $contratacion)
    {
    }

    public function referencia(): string
    {
        return 'contratacion:' . $this->contratacion->id . ':aceptada';
    }

    protected function datos(): array
    {
        $servicio = $this->contratacion->servicio;
        $nombre = $this->contratacion->trabajador?->nombre ?: 'El trabajador';

        $contacto = collect([
            $servicio?->telefono ? 'Teléfono: ' . $servicio->telefono : null,
            $servicio?->whatsapp ? 'WhatsApp: ' . $servicio->whatsapp : null,
        ])->filter()->implode(' · ');

        return [
            'tipo' => 'solicitud_aceptada',
            'modo' => 'trabajador', // /usuario/solicitudes es una pantalla del modo trabajador
            'titulo' => '¡Aceptaron tu solicitud!',
            'texto' => "{$nombre} aceptó tu solicitud para «{$servicio?->titulo}».",
            'detalle' => $contacto !== '' ? "Para ponerte de acuerdo — {$contacto}" : null,
            'boton' => 'Ver mis solicitudes',
            'url' => '/usuario/solicitudes?tab=enviadas',
        ];
    }
}
