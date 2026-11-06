<?php

namespace App\Notifications;

use App\Models\Postulacion;

// Para el TRABAJADOR: su postulación no fue seleccionada. (solo campana, sin correo)
class PostulacionRechazada extends AvisoSimple
{
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

        return [
            'tipo' => 'rechazado',
            'modo' => 'trabajador',
            'titulo' => 'Postulación no seleccionada',
            'texto' => "Tu postulación a «{$vacante?->titulo}» no fue seleccionada esta vez. ¡Sigue intentando!",
            'url' => '/usuario/postulantes?tab=enviadas',
        ];
    }
}
