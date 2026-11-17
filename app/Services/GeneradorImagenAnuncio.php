<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Crea la imagen de un anuncio con IA (OpenAI Images) a partir de lo que el
 * negocio describe. Antes de pedirla se modera lo que escribió la persona, y
 * antes de mostrarla se modera la imagen resultante. La imagen se guarda en
 * "anuncios-ia/" (carpeta temporal del disco público) y el controlador solo
 * la hace definitiva si la solicitud se envía.
 */
class GeneradorImagenAnuncio
{
    private const ENDPOINT = 'https://api.openai.com/v1/images/generations';
    private const ENDPOINT_EDICION = 'https://api.openai.com/v1/images/edits';

    /** Estilos que puede elegir la persona (clave => descripción para la IA). */
    public const ESTILOS = [
        'fotografia' => 'fotografía publicitaria realista, luz natural, colores vivos',
        'ilustracion' => 'ilustración digital colorida y amigable, estilo moderno',
        'minimalista' => 'diseño limpio y minimalista, pocos elementos, fondo liso',
        'artesanal' => 'estilo artesanal mexicano, colores cálidos, aire tradicional',
    ];

    public function __construct(private ModeracionService $moderacion)
    {
    }

    /** Proveedor activo: "cloudflare" (gratis, por defecto) u "openai" (de paga). */
    private function proveedor(): string
    {
        return config('services.imagen_proveedor', 'cloudflare') === 'openai' ? 'openai' : 'cloudflare';
    }

    public function disponible(): bool
    {
        return $this->proveedor() === 'openai'
            ? (bool) config('services.openai.key')
            : (config('services.cloudflare.account_id') && config('services.cloudflare.token'));
    }

    /**
     * @return array{ok:bool, ruta?:string, url?:string, error?:string}
     */
    public function generar(string $descripcion, string $estilo = 'fotografia', ?UploadedFile $referencia = null): array
    {
        $key = config('services.openai.key');
        if (! $this->disponible()) {
            return ['ok' => false, 'error' => 'El generador de imágenes no está disponible por ahora. Sube tu propia imagen.'];
        }

        $estiloTxt = self::ESTILOS[$estilo] ?? self::ESTILOS['fotografia'];

        // Lo que escribió la persona también pasa por el moderador (si es ofensivo no se gasta la IA).
        $revisionTexto = $this->moderacion->revisar($descripcion);
        if (! empty($revisionTexto['flagged'])) {
            return ['ok' => false, 'error' => $revisionTexto['mensaje']];
        }

        // Si la persona subió una foto de referencia, también se modera antes de enviarla a la IA.
        if ($referencia !== null) {
            $revisionRef = $this->moderacion->revisar(null, $referencia, false);
            if (! empty($revisionRef['flagged'])) {
                return ['ok' => false, 'error' => 'La foto de referencia no cumple las reglas del sitio. Sube otra foto de tu negocio o producto.'];
            }
        }

        $reglas = "Composición atractiva y centrada, que se entienda aun en tamaño pequeño. "
            . "Sin ningún texto, letras ni números dentro de la imagen, sin logotipos ni marcas registradas, "
            . "sin personas famosas ni rostros identificables, sin contenido para adultos, violento o político.";

        if ($this->proveedor() === 'cloudflare') {
            return $this->generarConCloudflare($descripcion, $estiloTxt, $reglas, $referencia);
        }

        $parametros = [
            'model' => config('services.openai.imagen_modelo', 'gpt-image-2.5-flare'),
            'size' => '1024x1024',
            'quality' => config('services.openai.imagen_calidad', 'low'),
            'output_format' => 'jpeg',
            'output_compression' => 82,
            'n' => 1,
        ];

        try {
            if ($referencia !== null) {
                // Con foto de referencia: la IA la toma como base y la convierte en una imagen publicitaria.
                $parametros['prompt'] = "Usa la foto adjunta como base: conserva el producto, comida o lugar que aparece, "
                    . "y conviértela en una imagen publicitaria cuadrada para un negocio local de un pueblo de México, "
                    . "con mejor luz, fondo y composición. Lo que pide el negocio: " . $descripcion . ". Estilo: " . $estiloTxt . ". " . $reglas;
                $extension = strtolower($referencia->guessExtension() ?: 'jpg');
                $resp = Http::withToken($key)->timeout(150)
                    ->attach('image[]', file_get_contents($referencia->getRealPath()), 'referencia.' . $extension)
                    ->post(self::ENDPOINT_EDICION, $parametros);
            } else {
                $parametros['prompt'] = "Imagen publicitaria cuadrada para el anuncio de un negocio local de un pueblo de México. "
                    . "El negocio es: " . $descripcion . ". Estilo: " . $estiloTxt . ". " . $reglas;
                $resp = Http::withToken($key)->timeout(120)->post(self::ENDPOINT, $parametros);
            }
        } catch (\Throwable $e) {
            Log::warning('Imagen IA: no se pudo conectar con OpenAI: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'No pudimos conectar con el generador. Inténtalo de nuevo en un momento.'];
        }

        if (! $resp->successful()) {
            Log::warning('Imagen IA: OpenAI respondió ' . $resp->status() . ': ' . mb_substr((string) $resp->body(), 0, 300));
            $bloqueada = $resp->status() === 400 && str_contains((string) $resp->body(), 'safety');
            return ['ok' => false, 'error' => $bloqueada
                ? 'Esa descripción no se pudo convertir en imagen. Cámbiala por algo sobre tu negocio, producto o servicio.'
                : 'El generador de imágenes no está disponible ahora. Sube tu propia imagen o inténtalo más tarde.'];
        }

        return $this->finalizar(base64_decode((string) $resp->json('data.0.b64_json'), true) ?: null);
    }

    /** Genera con Cloudflare Workers AI (FLUX schnell): capa gratuita, sin tarjeta. */
    private function generarConCloudflare(string $descripcion, string $estiloTxt, string $reglas, ?UploadedFile $referencia): array
    {
        $cuenta = config('services.cloudflare.account_id');
        $token = config('services.cloudflare.token');
        $base = "https://api.cloudflare.com/client/v4/accounts/{$cuenta}/ai/run/";

        // FLUX no recibe fotos: si hay referencia, un modelo de visión la describe y esa descripción se suma al texto.
        $detalleFoto = $referencia ? $this->describirFoto($base, $token, $referencia) : null;

        $prompt = "Advertising image, square, for a local small business in a small town in Mexico. "
            . "The business: " . $descripcion . ". "
            . ($detalleFoto ? "Base the image on this real photo of the business: " . $detalleFoto . ". " : '')
            . "Style: " . $estiloTxt . ". "
            . "No text, letters or numbers in the image, no logos or brands, no famous people or identifiable faces, nothing violent, political or adult. "
            . "Attractive, centered composition that reads well at small size.";

        try {
            $resp = Http::withToken($token)->timeout(120)->post($base . config('services.cloudflare.imagen_modelo', '@cf/black-forest-labs/flux-1-schnell'), [
                'prompt' => mb_substr($prompt, 0, 2000),
                'steps' => 6,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Imagen IA: no se pudo conectar con Cloudflare: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'No pudimos conectar con el generador. Inténtalo de nuevo en un momento.'];
        }

        if (! $resp->successful()) {
            Log::warning('Imagen IA: Cloudflare respondió ' . $resp->status() . ': ' . mb_substr((string) $resp->body(), 0, 300));
            $bloqueada = $resp->status() === 400 && stripos((string) $resp->body(), 'nsfw') !== false;
            return ['ok' => false, 'error' => $bloqueada
                ? 'Esa descripción no se pudo convertir en imagen. Cámbiala por algo sobre tu negocio, producto o servicio.'
                : 'El generador de imágenes no está disponible ahora. Sube tu propia imagen o inténtalo más tarde.'];
        }

        return $this->finalizar(base64_decode((string) $resp->json('result.image'), true) ?: null);
    }

    /** Descripción breve (en inglés) de la foto de referencia; si algo falla, se sigue sin ella. */
    private function describirFoto(string $base, string $token, UploadedFile $foto): ?string
    {
        try {
            $img = @imagecreatefromstring((string) file_get_contents($foto->getRealPath()));
            if (! $img) {
                return null;
            }
            $img = imagescale($img, 384) ?: $img;
            ob_start();
            imagejpeg($img, null, 75);
            $bytes = ob_get_clean();
            $resp = Http::withToken($token)->timeout(60)->post($base . '@cf/llava-hf/llava-1.5-7b-hf', [
                'image' => array_values(unpack('C*', $bytes)),
                'prompt' => 'Describe in one short sentence the product, food or place shown, with its main colors.',
                'max_tokens' => 80,
            ]);
            $texto = trim((string) $resp->json('result.description'));
            return $resp->successful() && $texto !== '' ? mb_substr($texto, 0, 300) : null;
        } catch (\Throwable $e) {
            Log::notice('Imagen IA: no se pudo describir la foto de referencia: ' . $e->getMessage());
            return null;
        }
    }

    /** Valida la imagen recibida, la pasa por el moderador y la guarda como JPG temporal. */
    private function finalizar(?string $binario): array
    {
        $info = $binario ? @getimagesizefromstring($binario) : false;
        if (! $binario || ! $info) {
            return ['ok' => false, 'error' => 'La imagen generada no se pudo leer. Inténtalo de nuevo.'];
        }

        if (($info[2] ?? null) !== IMAGETYPE_JPEG && ($gd = @imagecreatefromstring($binario))) {
            ob_start();
            imagejpeg($gd, null, 85);
            $binario = ob_get_clean();
        }

        // La imagen generada también se modera antes de enseñarla.
        $tmp = tempnam(sys_get_temp_dir(), 'iaanuncio');
        file_put_contents($tmp, $binario);
        $archivo = new UploadedFile($tmp, 'anuncio-ia.jpg', 'image/jpeg', null, true);
        $revisionImg = $this->moderacion->revisar(null, $archivo, false);
        @unlink($tmp);
        if (! empty($revisionImg['flagged'])) {
            return ['ok' => false, 'error' => 'La imagen generada no cumple las reglas del sitio. Prueba con otra descripción.'];
        }

        $ruta = 'anuncios-ia/' . Str::uuid() . '.jpg';
        Storage::disk('public')->put($ruta, $binario);
        $this->limpiarTemporales();

        return ['ok' => true, 'ruta' => $ruta, 'url' => '/storage/' . $ruta];
    }

    /** Borra las imágenes de IA que nadie usó en más de 24 horas (la carpeta es solo temporal). */
    private function limpiarTemporales(): void
    {
        try {
            $disco = Storage::disk('public');
            foreach ($disco->files('anuncios-ia') as $archivo) {
                if ($disco->lastModified($archivo) < now()->subDay()->getTimestamp()) {
                    $disco->delete($archivo);
                }
            }
        } catch (\Throwable $e) {
            Log::notice('Imagen IA: no se pudo limpiar la carpeta temporal: ' . $e->getMessage());
        }
    }
}
