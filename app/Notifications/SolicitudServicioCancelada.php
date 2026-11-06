<?php

namespace App\Notifications;

use App\Models\Contratacion;

// Para quien CONTRATÓ: el trabajador no pudo aceptar su solicitud. (solo campana)
class SolicitudServicioCancelada extends AvisoSimple
{
    public function __construct(public Contratacion $contratacion)
    {
    }

    public function referencia(): string
    {
        return 'contratacion:' . $this->contratacion->id . ':cancelada';
    }

    protected function datos(): array
    {
        $nombre = $this->contratacion->trabajador?->nombre ?: 'El trabajador';

        return [
            'tipo' => 'solicitud_cancelada',
            'modo' => 'trabajador', // /usuario/solicitudes es una pantalla del modo trabajador
            'titulo' => 'Solicitud no aceptada',
            'texto' => "{$nombre} no pudo aceptar tu solicitud para «{$this->contratacion->servicio?->titulo}».",
            'url' => '/usuario/solicitudes?tab=enviadas',
        ];
    }
}
