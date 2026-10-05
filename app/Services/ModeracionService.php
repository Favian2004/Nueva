<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModeracionService
{
    private const ENDPOINT = 'https://api.openai.com/v1/moderations';
    private const MODELO = 'omni-moderation-latest';

    // OpenAI normalmente solo marca "flagged" cuando el puntaje de una
    // categoría pasa de ~0.5-0.7 (varía por categoría). Con este umbral más
    // bajo, bloqueamos también contenido "límite" que OpenAI por su cuenta
    // dejaría pasar. Si ves que empieza a bloquear cosas que sí son
    // aceptables, sube este número (más cerca de 1.0 = más permisivo);
    // si se te sigue colando contenido que no quieres, bájalo.
    private const UMBRAL_IA_ESTRICTO = 0.3;

    private const VOCALES = ['a', 'e', 'i', 'o', 'u', 'á', 'é', 'í', 'ó', 'ú', 'ü', 'y'];

    // Caracteres permitidos en un texto "normal": letras, números, espacios
    // y signos de puntuación comunes. Cualquier otra cosa en exceso es señal
    // de texto basura / spam de símbolos.
    private const PATRON_SIMBOLOS_RAROS = '/[^\p{L}\p{N}\s.,;:!?¡¿\'"\-\/()%$&@#+]/u';

    // Sustituciones tipo "leetspeak" -> letra real. Se aplican DESPUÉS de
    // quitar acentos y pasar a minúsculas, para detectar palabras disfrazadas
    // como "p3ndejo", "pvt0", "c4bron", "h4ck34r", "s3xu@l", etc.
    // El "1" casi siempre se usa como "i", así que esa es la versión principal.
    private const SUSTITUCIONES_LEET = [
        '0' => 'o',
        '1' => 'i',
        '3' => 'e',
        '4' => 'a',
        '5' => 's',
        '7' => 't',
        '8' => 'b',
        'v' => 'u',
        '@' => 'a',
        '$' => 's',
    ];

    // Variante alterna: a veces "1" se usa como "l" en vez de "i"
    // (ej. "sexu@1" = "sexual", no "sexuai"). Probamos ambas.
    private const SUSTITUCIONES_LEET_ALT = [
        '0' => 'o',
        '1' => 'l',
        '3' => 'e',
        '4' => 'a',
        '5' => 's',
        '7' => 't',
        '8' => 'b',
        'v' => 'u',
        '@' => 'a',
        '$' => 's',
    ];

    private const CATEGORIAS_ES = [
        'sexual' => 'contenido sexual',
        'sexual/minors' => 'contenido sexual involucrando menores',
        'harassment' => 'acoso',
        'harassment/threatening' => 'acoso con amenazas',
        'hate' => 'discurso de odio',
        'hate/threatening' => 'discurso de odio con amenazas',
        'illicit' => 'contenido ilícito',
        'illicit/violent' => 'contenido ilícito violento',
        'self-harm' => 'autolesión',
        'self-harm/intent' => 'intención de autolesión',
        'self-harm/instructions' => 'instrucciones de autolesión',
        'violence' => 'violencia',
        'violence/graphic' => 'violencia gráfica',
    ];

    public function revisar(?string $texto, ?UploadedFile $imagen = null): array
    {
        $texto = trim((string) $texto);

        // 1) Lista local de palabras/frases prohibidas (instantáneo, sin costo).
        $palabra = $this->palabraProhibidaEncontrada($texto);
        if ($palabra !== null) {
            return $this->resultadoBloqueado(
                "Tu publicación no se pudo guardar porque el texto contiene la palabra o frase no permitida: \"{$palabra}\". Corrígelo e inténtalo de nuevo."
            );
        }

        // 2) Detector de texto sin sentido / basura de símbolos (instantáneo).
        $mensajeBasura = $this->mensajeSiTextoSinSentido($texto);
        if ($mensajeBasura !== null) {
            return $this->resultadoBloqueado($mensajeBasura);
        }

        // 3) Moderación con IA (OpenAI) para todo lo demás: texto e imagen.
        if (! config('services.openai.moderacion_activa', true)) {
            return $this->resultadoLimpio();
        }

        $apiKey = config('services.openai.key');
        if (empty($apiKey)) {
            Log::warning('ModeracionService: OPENAI_API_KEY no configurada, se omite la revisión de IA.');
            return $this->resultadoLimpio();
        }

        $input = [];
        if ($texto !== '') {
            $input[] = ['type' => 'text', 'text' => $texto];
        }
        if ($imagen !== null) {
            $dataUri = $this->imagenABase64($imagen);
            if ($dataUri !== null) {
                $input[] = ['type' => 'image_url', 'image_url' => ['url' => $dataUri]];
            }
        }

        if (empty($input)) {
            return $this->resultadoLimpio();
        }

        try {
            $respuesta = Http::withToken($apiKey)
                ->timeout(20)
                // Reintenta 1 vez si hay un corte de conexión o timeout
                // (cURL 28, cURL 35, etc.) antes de rendirse y dejar pasar
                // el contenido sin revisar.
                ->retry(1, 1000)
                ->post(self::ENDPOINT, [
                    'model' => self::MODELO,
                    'input' => $input,
                ]);

            if (! $respuesta->successful()) {
                Log::warning('ModeracionService: respuesta no exitosa de OpenAI.', [
                    'status' => $respuesta->status(),
                    'body' => $respuesta->body(),
                ]);
                return $this->resultadoLimpio();
            }

            $resultado = $respuesta->json('results.0');

            if (! is_array($resultado)) {
                return $this->resultadoLimpio();
            }

            // OpenAI marca "flagged" con SU propio criterio (bastante
            // permisivo: apunta a contenido claramente explícito). Para
            // este sitio preferimos ser más estrictos, así que ADEMÁS
            // revisamos los puntajes (0 a 1) de cada categoría con un
            // umbral más bajo, para atrapar casos "límite" que OpenAI
            // por sí solo dejaría pasar (igual que pasó con la palabra
            // "sexy" en el texto).
            $categorias = array_keys(array_filter($resultado['categories'] ?? []));
            foreach (($resultado['category_scores'] ?? []) as $categoria => $puntaje) {
                if ($puntaje >= self::UMBRAL_IA_ESTRICTO && ! in_array($categoria, $categorias, true)) {
                    $categorias[] = $categoria;
                }
            }

            if (empty($categorias)) {
                return $this->resultadoLimpio();
            }

            $categoriasEs = array_map(
                fn ($cat) => self::CATEGORIAS_ES[$cat] ?? $cat,
                $categorias
            );
            $listaCategorias = $categoriasEs !== [] ? implode(', ', $categoriasEs) : 'contenido no permitido';

            return $this->resultadoBloqueado(
                "Tu publicación no se pudo guardar porque el texto o la imagen contienen contenido no permitido ({$listaCategorias}). Corrígelo e inténtalo de nuevo."
            );
        } catch (\Throwable $e) {
            // Fail-open: si OpenAI falla (red, timeout, etc.) no bloqueamos
            // toda la publicación de la página por una falla externa.
            Log::error('ModeracionService: excepción al llamar a OpenAI.', [
                'mensaje' => $e->getMessage(),
            ]);
            return $this->resultadoLimpio();
        }
    }

    /**
     * Busca si el texto contiene alguna palabra/frase de config('moderacion.palabras_prohibidas'),
     * incluyendo variantes disfrazadas con números (p3ndejo), letras sueltas
     * separadas por espacios/guiones/puntos/cualquier símbolo (p.o.r.n.o),
     * o combinación de ambas. Devuelve la palabra "limpia" tal como aparece
     * en la lista (para mostrársela al usuario), o null si no encontró nada.
     */
    private function palabraProhibidaEncontrada(string $texto): ?string
    {
        if ($texto === '') {
            return null;
        }

        $normal = $this->normalizar($texto);
        $leet = $this->normalizarLeet($texto);
        $leetAlt = $this->normalizarLeetAlt($texto);

        // Además de las versiones "normal", "leet" y "leetAlt", generamos las
        // mismas con las letras sueltas ya juntadas, de dos formas:
        // 1) colapsarPorPalabra: DENTRO de una misma palabra (sin espacios),
        //    quita cualquier símbolo raro que no sea letra/número, sin
        //    importar el patrón. Esto cubre "p-o-r-no", "p.o.r-n_o", etc,
        //    incluso mezclas raras, porque nunca cruza un espacio real
        //    (nunca junta dos palabras distintas).
        // 2) colapsarLetrasSueltas: cuando las letras están separadas por
        //    ESPACIOS reales ("p o r n o" como varias palabras sueltas).
        $vistas = array_unique([
            $normal,
            $leet,
            $leetAlt,
            $this->colapsarPorPalabra($normal),
            $this->colapsarPorPalabra($leet),
            $this->colapsarPorPalabra($leetAlt),
            $this->colapsarLetrasSueltas($normal),
            $this->colapsarLetrasSueltas($leet),
            $this->colapsarLetrasSueltas($leetAlt),
        ]);

        foreach (config('moderacion.palabras_prohibidas', []) as $palabra) {
            $formaNormal = $this->normalizar($palabra);
            $formaLeet = $this->normalizarLeet($palabra);
            $formaLeetAlt = $this->normalizarLeetAlt($palabra);

            foreach (array_unique([$formaNormal, $formaLeet, $formaLeetAlt]) as $forma) {
                if ($forma === '') {
                    continue;
                }

                // (e?s)? admite el plural regular: gordo/gordos, puta/putas.
                $patron = '/\b' . preg_quote($forma, '/') . '(e?s)?\b/u';

                foreach ($vistas as $vista) {
                    if (@preg_match($patron, $vista) === 1) {
                        return $palabra;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Minúsculas + quita acentos (á->a, é->e, í->i, ó->o, ú->u, ü->u).
     * OJO: la ñ NO se cambia por n a propósito, porque "año" no debe
     * convertirse en "ano".
     */
    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');

        return strtr($texto, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
        ]);
    }

    /**
     * A partir del texto ya normalizado (normalizar()), cambia dígitos y
     * la letra "v" por su equivalente "leetspeak" más común, para detectar
     * palabras disfrazadas como p3ndejo, c4br0n, pvt0, h4ck34r, etc.
     */
    private function normalizarLeet(string $texto): string
    {
        $texto = $this->normalizar($texto);

        return strtr($texto, self::SUSTITUCIONES_LEET);
    }

    /**
     * Igual que normalizarLeet() pero usando "1" -> "l" en vez de "1" -> "i",
     * porque "1" es ambiguo (p1ndejo = pendejo con "i", pero sexu@1 = sexual
     * con "l"). Probamos las dos interpretaciones.
     */
    private function normalizarLeetAlt(string $texto): string
    {
        $texto = $this->normalizar($texto);

        return strtr($texto, self::SUSTITUCIONES_LEET_ALT);
    }

    /**
     * Junta letras que están separadas una por una por cualquier símbolo
     * que no sea letra ni número (espacio, guion, punto, guion bajo, etc.),
     * siempre que haya al menos 3 letras sueltas seguidas de ese patrón.
     * Así "p o r n o", "p-o-r-n-o", "p.o.r.n.o" y "p_o_r_n_o" se vuelven
     * todas "porno", sin tocar el resto del texto ni palabras normales
     * (que no tienen separadores entre cada una de sus letras).
     */
    /**
     * Dentro de cada "palabra" (grupo de caracteres sin espacios), quita
     * cualquier símbolo que no sea letra o número: guiones, puntos, guion
     * bajo, comillas, o cualquier otro carácter raro que alguien use para
     * separar las letras. Como nunca cruza un espacio real, es imposible
     * que junte dos palabras distintas (a diferencia de colapsarLetrasSueltas,
     * que sí puede ver espacios como separador de letras sueltas).
     * Esto cubre variantes mixtas como "p-o-r-no" o "p.o.r-n_o".
     */
    private function colapsarPorPalabra(string $texto): string
    {
        return preg_replace_callback(
            '/\S+/u',
            static function (array $coincidencia): string {
                return preg_replace('/[^\p{L}\p{N}]/u', '', $coincidencia[0]);
            },
            $texto
        );
    }

    private function colapsarLetrasSueltas(string $texto): string
    {
        // (?<![\p{L}\p{N}]) y (?!\p{L}) evitan que el "colapso" empiece o
        // termine a mitad de otra palabra: sin esto, en "Vendo p-o-r-n-o"
        // se comía la última letra de "Vendo" (o la primera de la palabra
        // siguiente) y el resultado quedaba pegado a la palabra vecina,
        // rompiendo el límite de palabra (\b) que usa palabraProhibidaEncontrada.
        return preg_replace_callback(
            '/(?<![\p{L}\p{N}])\p{L}(?:[^\p{L}\p{N}]\p{L}){2,}(?!\p{L})/u',
            static function (array $coincidencia): string {
                return preg_replace('/[^\p{L}\p{N}]/u', '', $coincidencia[0]);
            },
            $texto
        );
    }

    private function mensajeSiTextoSinSentido(string $texto): ?string
    {
        if ($texto === '') {
            return null;
        }

        if (preg_match(self::PATRON_SIMBOLOS_RAROS, $texto)) {
            return 'Tu publicación no se pudo guardar porque el texto contiene demasiados símbolos o caracteres no permitidos. Corrígelo e inténtalo de nuevo.';
        }

        $palabraRara = $this->palabraConDemasiadasConsonantes($texto);
        if ($palabraRara !== null) {
            return "Tu publicación no se pudo guardar porque el texto contiene una palabra sin sentido: \"{$palabraRara}\". Corrígelo e inténtalo de nuevo.";
        }

        $tokenBasura = $this->tokenBasuraAleatoria($texto);
        if ($tokenBasura !== null) {
            return "Tu publicación no se pudo guardar porque el texto contiene texto sin sentido: \"{$tokenBasura}\". Corrígelo e inténtalo de nuevo.";
        }

        return null;
    }

    /**
     * Detecta palabras con demasiadas consonantes seguidas (sin ninguna
     * vocal entre ellas), lo cual casi nunca pasa en español real y es
     * típico de texto tecleado al azar (ej. "xjkqwrt").
     */
    private function palabraConDemasiadasConsonantes(string $texto): ?string
    {
        $palabras = preg_split('/[^\p{L}]+/u', mb_strtolower($texto, 'UTF-8'), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($palabras as $palabra) {
            if (mb_strlen($palabra, 'UTF-8') < 4) {
                continue;
            }

            $letras = mb_str_split($palabra, 1, 'UTF-8');
            $racha = 0;
            $rachaMax = 0;

            foreach ($letras as $letra) {
                if (in_array($letra, self::VOCALES, true)) {
                    $racha = 0;
                } else {
                    $racha++;
                    $rachaMax = max($rachaMax, $racha);
                }
            }

            if ($rachaMax >= 5) {
                return $palabra;
            }
        }

        return null;
    }

    /**
     * Detecta tokens (separados por espacios) que alternan mucho entre
     * letras y números, típico de texto tecleado al azar en el teclado
     * (ej. "3RF4HHF490I43'O5YI9581RI0O'753U8T249I0O540").
     */
    private function tokenBasuraAleatoria(string $texto): ?string
    {
        $tokens = preg_split('/\s+/u', $texto, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($tokens as $token) {
            $caracteres = mb_str_split($token, 1, 'UTF-8');
            $alternancias = 0;
            $tipoAnterior = null;

            foreach ($caracteres as $caracter) {
                if (ctype_digit($caracter)) {
                    $tipoActual = 'digito';
                } elseif (preg_match('/\p{L}/u', $caracter)) {
                    $tipoActual = 'letra';
                } else {
                    $tipoActual = null;
                }

                if ($tipoActual === null) {
                    $tipoAnterior = null;
                    continue;
                }

                if ($tipoAnterior !== null && $tipoActual !== $tipoAnterior) {
                    $alternancias++;
                }

                $tipoAnterior = $tipoActual;
            }

            if ($alternancias >= 4) {
                return $token;
            }
        }

        return null;
    }

    private function imagenABase64(UploadedFile $imagen): ?string
    {
        try {
            $ruta = $imagen->getRealPath();
            $mimeOriginal = $imagen->getMimeType() ?: 'image/jpeg';

            // Las fotos de celular pueden pesar varios MB. Mandar eso tal
            // cual a OpenAI puede tardar más de lo que dura el timeout y
            // hacer que la llamada falle (y como el diseño es "fail-open",
            // eso deja pasar la imagen SIN revisarla). Reducimos la imagen
            // antes de mandarla: para detectar contenido explícito no hace
            // falta la resolución original.
            $reducida = $this->reducirImagenSiEsNecesario($ruta);
            if ($reducida !== null) {
                [$contenido, $mime] = $reducida;

                return "data:{$mime};base64,{$contenido}";
            }

            // No se pudo reducir (GD no disponible, formato no soportado,
            // ya es pequeña, etc.): mandamos el archivo tal cual, como antes.
            $contenido = base64_encode(file_get_contents($ruta));

            return "data:{$mimeOriginal};base64,{$contenido}";
        } catch (\Throwable $e) {
            Log::warning('ModeracionService: no se pudo leer la imagen para moderación.', [
                'mensaje' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Si la imagen mide más de 1280px de lado o pesa más de 800KB, la
     * reduce y la vuelve a comprimir como JPEG calidad 75 usando GD (viene
     * incluido en PHP/XAMPP normalmente, no requiere instalar nada). Esto
     * baja el peso de varios MB a unos cientos de KB, para que la llamada a
     * OpenAI sea rápida y no truene por timeout.
     *
     * Devuelve [contenidoBase64, mime] o null si no se pudo/necesito reducir
     * (en cuyo caso imagenABase64() manda el archivo original tal cual).
     */
    private function reducirImagenSiEsNecesario(string $ruta): ?array
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $info = @getimagesize($ruta);
        if ($info === false) {
            return null;
        }

        [$ancho, $alto, $tipo] = $info;
        $pesoOriginal = @filesize($ruta) ?: 0;

        $limiteLado = 1280;
        $limitePeso = 800 * 1024; // 800 KB

        if ($ancho <= $limiteLado && $alto <= $limiteLado && $pesoOriginal <= $limitePeso) {
            // Ya es lo bastante pequeña, no hace falta tocarla.
            return null;
        }

        $origen = match ($tipo) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($ruta),
            IMAGETYPE_PNG => @imagecreatefrompng($ruta),
            IMAGETYPE_GIF => @imagecreatefromgif($ruta),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($ruta) : false,
            IMAGETYPE_BMP => function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($ruta) : false,
            default => false,
        };

        if (! $origen) {
            return null;
        }

        $escala = min(1, $limiteLado / max($ancho, $alto));
        $anchoNuevo = max(1, (int) round($ancho * $escala));
        $altoNuevo = max(1, (int) round($alto * $escala));

        $destino = imagecreatetruecolor($anchoNuevo, $altoNuevo);

        // Fondo blanco por si la imagen original tiene transparencia
        // (PNG/GIF), para que no salga negro al convertir a JPEG.
        $blanco = imagecolorallocate($destino, 255, 255, 255);
        imagefill($destino, 0, 0, $blanco);
        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $anchoNuevo, $altoNuevo, $ancho, $alto);

        ob_start();
        imagejpeg($destino, null, 75);
        $contenido = ob_get_clean();

        imagedestroy($origen);
        imagedestroy($destino);

        if (empty($contenido)) {
            return null;
        }

        return [base64_encode($contenido), 'image/jpeg'];
    }

    private function resultadoLimpio(): array
    {
        return [
            'flagged' => false,
            'mensaje' => null,
        ];
    }

    private function resultadoBloqueado(string $mensaje): array
    {
        return [
            'flagged' => true,
            'mensaje' => $mensaje,
        ];
    }
}
