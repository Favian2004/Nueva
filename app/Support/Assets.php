<?php

namespace App\Support;

/**
 * Le pone número de versión al JS del dashboard de usuario para que, cada vez que
 * se sube uno nuevo, los navegadores (celulares incluidos) lo vuelvan a descargar
 * en lugar de seguir usando su copia vieja.
 *
 * main.js carga notificaciones.js copiando su "?v=..." (ver main.js), así que con
 * versionar main.js se versionan los dos.
 */
class Assets
{
    /** Se usa si el servidor no deja leer la fecha de los archivos. Súbelo al cambiar el JS. */
    private const RESPALDO = '20261108';

    private const ARCHIVOS = ['main.js', 'notificaciones.js'];

    public static function usuarioMain(): string
    {
        $ruta = 'assets/usuario/js/main.js';
        return asset($ruta) . '?v=' . self::version();
    }

    private static function version(): string
    {
        $carpetas = array_unique(array_filter([
            public_path('assets/usuario/js'),
            base_path('../public_html/assets/usuario/js'),
            $_SERVER['DOCUMENT_ROOT'] ?? null ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/assets/usuario/js' : null,
        ]));

        foreach ($carpetas as $carpeta) {
            $mayor = 0;
            foreach (self::ARCHIVOS as $archivo) {
                $t = @filemtime($carpeta . DIRECTORY_SEPARATOR . $archivo);
                if ($t) {
                    $mayor = max($mayor, $t);
                }
            }
            if ($mayor) {
                return (string) $mayor;
            }
        }

        return self::RESPALDO;
    }
}
