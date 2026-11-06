<?php

namespace App\Notifications;

use App\Models\Postulacion;

// Para el EMPLEADOR: alguien se postuló a su vacante. (campana + correo)
class NuevaPostulacion extends AvisoSimple
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
        $nombre = $this->postulacion->postulante?->nombre ?: 'Alguien';
        $mensaje = trim((string) $this->postulacion->mensaje);

        return [
            'tipo' => 'nueva_postulacion',
            'modo' => 'empleador',
            'titulo' => 'Nueva postulación',
            'texto' => "{$nombre} se postuló a tu vacante «{$vacante?->titulo}».",
            'detalle' => $mensaje !== '' ? "Mensaje de {$nombre}: {$mensaje}" : null,
            'boton' => 'Ver postulantes',
            'url' => '/usuario/postulantes' . ($vacante ? '?vacante=' . $vacante->id : ''),
        ];
    }
}
