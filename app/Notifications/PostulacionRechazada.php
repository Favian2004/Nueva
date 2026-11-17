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
        $motivo = trim((string) $this->postulacion->motivo_rechazo);

        // Si el empleador explicó por qué, se le muestra a la persona; si no, el mensaje de siempre.
        $texto = $motivo !== ''
            ? "Tu postulación a «{$vacante?->titulo}» no fue seleccionada. Motivo del empleador: «{$motivo}»"
            : "Tu postulación a «{$vacante?->titulo}» no fue seleccionada esta vez. ¡Sigue intentando!";

        return [
            'tipo' => 'rechazado',
            'modo' => 'trabajador',
            'titulo' => 'Postulación no seleccionada',
            'texto' => $texto,
            'url' => '/usuario/postulantes?tab=enviadas',
        ];
    }
}
