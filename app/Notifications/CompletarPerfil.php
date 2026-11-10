<?php

namespace App\Notifications;

// Para cualquier usuario con el perfil incompleto: lo invita a completarlo. (solo campana)
// La crea y la quita App\Services\PerfilCompleto; no se manda por correo.
class CompletarPerfil extends AvisoSimple
{
    /** @param array<string,string> $faltantes clave => nombre para mostrar (ej. 'telefono' => 'tu teléfono') */
    public function __construct(public array $faltantes)
    {
    }

    public function referencia(): string
    {
        // Distinta cada vez: el control de "cuándo volver a avisar" lo lleva PerfilCompleto.
        return 'perfil:' . now()->format('YmdHis') . ':' . implode(',', array_keys($this->faltantes));
    }

    protected function datos(): array
    {
        return [
            'tipo' => 'completar_perfil',
            'modo' => '',
            'titulo' => 'Completa tu perfil',
            'texto' => self::texto($this->faltantes),
            'url' => '/usuario/profile?completar=1',
            'boton' => 'Completar mi perfil',
            'faltan' => array_keys($this->faltantes),
        ];
    }

    /** "Te falta agregar tu teléfono, tu foto y tu descripción. ..." */
    public static function texto(array $faltantes): string
    {
        $nombres = array_values($faltantes);
        $ultimo = array_pop($nombres);
        $lista = $nombres ? implode(', ', $nombres) . ' y ' . $ultimo : $ultimo;

        return "Te falta agregar {$lista}. Con tu perfil completo la gente confía más en ti.";
    }
}
