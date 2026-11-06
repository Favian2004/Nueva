<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Que el botón «atrás» no deje volver a una página con sesión después de cerrarla.
 *
 * Sin esto, el navegador guarda las páginas que se vieron con la sesión iniciada y,
 * al cerrar sesión y darle «atrás», las muestra otra vez como si nada. Aquí se le dice
 * que NO las guarde: al darle «atrás» las vuelve a pedir al servidor, y como ya no hay
 * sesión, el servidor manda a iniciar sesión.
 *
 * Solo afecta a las respuestas de quien tiene la sesión iniciada; las páginas públicas
 * para visitantes (sin sesión) no cambian.
 */
class SinCacheConSesion
{
    // Por si el navegador restaura la página desde su memoria (algunos lo hacen aunque se le pida que no):
    // en ese caso se recarga para que el servidor revise la sesión.
    private const RECARGAR = '<script>window.addEventListener("pageshow",function(e){if(e.persisted){location.reload();}});</script>';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Sin sesión (o archivos/descargas): se deja como está
        if (!Auth::check() || $response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        $tipo = (string) $response->headers->get('Content-Type');
        if (stripos($tipo, 'text/html') !== false && !$response->isRedirection()) {
            $contenido = $response->getContent();
            if (is_string($contenido) && stripos($contenido, '</head>') !== false) {
                $response->setContent(preg_replace('#</head>#i', self::RECARGAR . '</head>', $contenido, 1));
            }
        }

        return $response;
    }
}
