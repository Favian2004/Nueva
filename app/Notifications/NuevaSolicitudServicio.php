<?php

namespace App\Notifications;

use App\Models\Contratacion;

// Para el TRABAJADOR: alguien quiere contratar su servicio. (campana + correo)
class NuevaSolicitudServicio extends AvisoSimple
{
    protected bool $correo = true;

    public function __construct(public Contratacion $contratacion)
    {
    }

    public function referencia(): string
    {
        return 'contratacion:' . $this->contratacion->id;
    }

    protected function datos(): array
    {
        $nombre = $this->contratacion->contratante?->nombre ?: 'Alguien';

        return [
            'tipo' => 'nueva_solicitud',
            'modo' => 'trabajador',
            'titulo' => 'Nueva solicitud de contratación',
            'texto' => "{$nombre} quiere contratar tu servicio «{$this->contratacion->servicio?->titulo}».",
            'boton' => 'Ver solicitud',
            'url' => '/usuario/solicitudes',
        ];
    }
}
