<?php

namespace App\Notifications;

// Para quien todavía no es "usuario verificado": lo invita a subir su INE y su selfie. (solo campana)
// La crea y la quita App\Services\PerfilCompleto; no se manda por correo.
class InvitarVerificacion extends AvisoSimple
{
    /**
     * @param array<string,string> $faltantes documentos por subir (clave => nombre para mostrar)
     * @param bool $rechazada la verificación anterior fue rechazada por el administrador
     */
    public function __construct(public array $faltantes, public bool $rechazada = false)
    {
    }

    public function referencia(): string
    {
        return 'verificar:' . now()->format('YmdHis') . ':' . implode(',', array_keys($this->faltantes));
    }

    protected function datos(): array
    {
        $t = self::textos($this->faltantes, $this->rechazada);

        return [
            'tipo' => 'verificar_cuenta',
            'modo' => '',
            'titulo' => $t['titulo'],
            'texto' => $t['texto'],
            'url' => '/usuario/profile?verificar=1',
            'boton' => 'Subir mis documentos',
            'faltan' => array_keys($this->faltantes),
            'rechazada' => $this->rechazada,
        ];
    }

    /** @return array{titulo:string,texto:string} */
    public static function textos(array $faltantes, bool $rechazada): array
    {
        $nombres = array_values($faltantes);
        $ultimo = array_pop($nombres);
        $lista = $nombres ? implode(', ', $nombres) . ' y ' . $ultimo : $ultimo;

        if ($rechazada) {
            return [
                'titulo' => 'Tu verificación no fue aprobada',
                'texto' => "Vuelve a subir {$lista} para que el administrador la revise otra vez y seas un usuario verificado.",
            ];
        }

        return [
            'titulo' => 'Verifica tu cuenta',
            'texto' => "Sube {$lista} para ser un usuario verificado. Tendrás la insignia «Usuario verificado» y la gente confiará más en ti.",
        ];
    }
}
