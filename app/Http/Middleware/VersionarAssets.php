<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Le agrega "?v=<fecha del archivo>" a todos los .css y .js propios que aparecen en el HTML.
 *
 * Por qué: los celulares guardan CSS y JS en caché y, aunque subas uno nuevo, siguen usando el
 * viejo durante días. Con la fecha del archivo en el enlace, cada vez que cambia un archivo el
 * enlace cambia y todos los navegadores (no solo el tuyo al recargar) lo descargan de nuevo.
 *
 * No toca enlaces que ya traen "?", ni archivos de otros sitios, ni nada que no encuentre en disco.
 */
class VersionarAssets
{
    /** @var array<string, string|null> */
    private static array $cache = [];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $tipo = (string) $response->headers->get('Content-Type', '');
        if (! str_contains($tipo, 'text/html') || $response->isRedirection()) {
            return $response;
        }

        $html = $response->getContent();
        if (! is_string($html) || $html === '') {
            return $response;
        }

        $nuevo = preg_replace_callback(
            '#\b(href|src)=(["\'])([^"\'?\#\s]+\.(?:css|js))\2#i',
            function ($m) {
                $v = $this->version($m[3]);
                return $v ? $m[1] . '=' . $m[2] . $m[3] . '?v=' . $v . $m[2] : $m[0];
            },
            $html
        );

        if (is_string($nuevo) && $nuevo !== $html) {
            $response->setContent($nuevo);
        }

        return $response;
    }

    /** Fecha del archivo si es un archivo propio del sitio; si no, null. */
    private function version(string $url): ?string
    {
        if (array_key_exists($url, self::$cache)) {
            return self::$cache[$url];
        }

        $ruta = parse_url($url, PHP_URL_PATH);
        $host = parse_url($url, PHP_URL_HOST);
        if (! $ruta || ($host && $host !== request()->getHost())) {
            return self::$cache[$url] = null;
        }
        $ruta = '/' . ltrim($ruta, '/');
        if (str_contains($ruta, '..')) {
            return self::$cache[$url] = null;
        }

        $raices = array_filter([
            public_path(),
            base_path('../public_html'),
            ! empty($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : null,
        ]);

        foreach (array_unique($raices) as $raiz) {
            $t = @filemtime($raiz . $ruta);
            if ($t) {
                return self::$cache[$url] = (string) $t;
            }
        }

        return self::$cache[$url] = null;
    }
}
