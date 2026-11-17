<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Revisa lo que la persona SUBE como CV o como Solicitud de Empleo, para que
 * no se pueda meter "cualquier cosa" con extension .pdf:
 *
 *  1) que el archivo sea de verdad lo que dice ser (PDF / Word), no otra cosa renombrada;
 *  2) que su texto no tenga contenido indebido (mismo moderador que el resto del sitio);
 *  3) que el texto se parezca a un CV o a una Solicitud de Empleo.
 *
 * Los PDF que son solo imagen (fotos, escaneos, diseños) no tienen texto que comprobar: se rechazan.
 * La unica excepcion es que el servidor no tenga la libreria que lee PDF (smalot/pdfparser):
 * entonces se aceptan con 'verificado' => false y queda un aviso en el log.
 */
class ValidadorDocumento
{
    public const CV = 'cv';
    public const SOLICITUD = 'solicitud';

    // Menos letras que esto = el PDF casi no tiene texto (escaneo / foto).
    private const MINIMO_LETRAS = 40;

    // Lo que se manda al moderador (suficiente para detectar contenido indebido).
    private const MAX_CARACTERES_MODERACION = 4000;

    // CV: hacen falta "experiencia" y "estudios", y ademas habilidades o algun dato propio de un CV.
    // (Palabras genericas como nombre, telefono, empresa o direccion NO cuentan: salen en cualquier recibo o carta.)
    private const CV_EXPERIENCIA = ['experiencia', 'experiencias', 'laboral', 'trabajos anteriores', 'empleos anteriores', 'historial de trabajo'];
    private const CV_ESTUDIOS = ['estudios', 'escolaridad', 'educacion', 'formacion', 'academic', 'licenciatura', 'bachillerato', 'preparatoria', 'secundaria', 'primaria', 'universidad', 'ingenieria', 'certificad', 'diploma'];
    private const CV_EXTRA = ['habilidades', 'aptitudes', 'competencias', 'conocimientos', 'cursos', 'idiomas', 'skills', 'capacidades', 'curriculum', 'curriculo', 'curricular', 'vitae', 'perfil profesional', 'datos personales', 'objetivo', 'referencias', 'fecha de nacimiento'];

    // Solicitud: tiene que decir que es una solicitud de empleo y traer al menos 2 de estos 4 bloques.
    private const SOLICITUD_TITULO = ['solicitud de empleo', 'solicitud de trabajo', 'solicitud empleo', 'solicitud para empleo', 'solicitud de ocupacion'];
    private const GRUPOS_SOLICITUD = [
        'personales' => ['nombre', 'apellido', 'fecha de nacimiento', 'lugar de nacimiento', 'estado civil', 'edad', 'curp', 'rfc', 'nacionalidad'],
        'domicilio' => ['domicilio', 'direccion', 'telefono', 'celular', 'correo', 'colonia', 'localidad'],
        'escolaridad' => ['escolaridad', 'estudios', 'ultimo grado', 'primaria', 'secundaria', 'bachillerato', 'licenciatura', 'preparatoria'],
        'laboral' => ['experiencia', 'empleos anteriores', 'empleo anterior', 'ultimo empleo', 'ultimo trabajo', 'puesto', 'sueldo', 'referencias'],
    ];
    private const SOLICITUD_BLOQUES_MINIMOS = 2;

    public function __construct(private ModeracionService $moderacion)
    {
    }

    /**
     * Lo que usan los controladores: valida el archivo subido ('archivo') y lo revisa.
     * Devuelve null si todo esta bien, o la respuesta JSON 422 con el motivo.
     */
    public function respuestaSiInvalido(Request $request, string $tipo): ?JsonResponse
    {
        $formatos = $tipo === self::CV ? 'pdf,doc,docx' : 'pdf';
        $v = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:' . $formatos . '|max:4096',
        ], [
            'archivo.required' => 'Elige un archivo.',
            'archivo.uploaded' => 'No se pudo subir el archivo. Revisa que pese menos de 4 MB.',
            'archivo.file' => 'No se pudo subir el archivo.',
            'archivo.mimes' => $tipo === self::CV ? 'El archivo debe ser PDF o Word (.doc, .docx).' : 'El archivo debe ser un PDF.',
            'archivo.max' => 'El archivo pesa más de 4 MB.',
        ]);
        if ($v->fails()) {
            return response()->json(['ok' => false, 'error' => $v->errors()->first()], 422);
        }

        $resultado = $this->revisar($request->file('archivo'), $tipo);
        if (! $resultado['ok']) {
            return response()->json(['ok' => false, 'error' => $resultado['error']], 422);
        }

        return null;
    }

    /**
     * @param string $tipo  ValidadorDocumento::CV o ValidadorDocumento::SOLICITUD
     * @return array{ok: bool, error: ?string, verificado: bool}
     */
    public function revisar(UploadedFile $archivo, string $tipo): array
    {
        $ruta = $archivo->getRealPath();
        if (! $ruta || ! is_readable($ruta)) {
            return $this->rechazado('No pudimos leer el archivo. Vuelve a intentarlo.');
        }

        // 1) ¿Es de verdad un PDF / Word? (se mira el inicio del archivo, no el nombre)
        $formato = $this->formatoReal($ruta);
        if ($formato === null) {
            return $this->rechazado($tipo === self::CV
                ? 'El archivo no es un PDF o Word válido. Sube tu CV en PDF, o crea tu CV desde la plataforma.'
                : 'El archivo no es un PDF válido. Sube tu Solicitud de Empleo en PDF.');
        }
        if ($tipo === self::SOLICITUD && $formato !== 'pdf') {
            return $this->rechazado('La Solicitud de Empleo debe ser un archivo PDF.');
        }

        // 2) Sacar el texto (si se puede).
        if ($formato === 'doc') {
            return $this->rechazado('Los archivos de Word antiguos (.doc) no se pueden revisar. Guárdalo como PDF o como .docx y vuelve a subirlo.');
        }

        $texto = $formato === 'pdf' ? $this->textoDePdf($ruta) : $this->textoDeDocx($ruta);

        if ($texto === false) {
            // Falta la libreria que lee PDF en este servidor: no se puede juzgar el contenido.
            return $this->aceptado(false);
        }
        if ($texto === null) {
            return $this->rechazado('No pudimos leer este archivo. Puede estar dañado o protegido. Sube otro PDF con texto, o crea tu CV desde la plataforma.');
        }

        return $this->revisarTexto($texto, $tipo);
    }

    /**
     * Revisa ya el texto sacado del archivo (separado para poder probarlo sin leer un PDF).
     *
     * @return array{ok: bool, error: ?string, verificado: bool}
     */
    public function revisarTexto(string $texto, string $tipo): array
    {
        $normal = $this->normalizar($texto);
        if (preg_match_all('/\p{L}/u', $normal) < self::MINIMO_LETRAS) {
            // Foto, escaneo o diseño: no hay nada que comprobar, asi que no se acepta.
            return $this->rechazado($tipo === self::CV
                ? 'Este archivo no tiene texto que podamos leer (parece una foto, un escaneo o un diseño). Sube un PDF con texto, o crea tu CV desde la plataforma.'
                : 'Este archivo no tiene texto que podamos leer (parece una foto o un escaneo). Sube tu Solicitud de Empleo en un PDF con texto, no en una imagen.');
        }

        // Contenido indebido (palabras prohibidas + IA). Se salta el detector de "texto sin sentido":
        // un CV real lleva vinetas, guiones largos, etc.
        $revision = $this->moderacion->revisar(
            mb_substr(trim(preg_replace('/\s+/u', ' ', $this->sinCamposInocuos($texto))), 0, self::MAX_CARACTERES_MODERACION),
            null,
            false
        );
        if (! empty($revision['flagged'])) {
            return $this->rechazado('No se pudo subir el archivo porque su contenido no está permitido. Sube un documento tuyo, sin contenido ofensivo.');
        }

        // ¿Se parece a lo que debe ser?
        if ($tipo === self::CV) {
            $parece = $this->tieneAlgunaDe($normal, self::CV_EXPERIENCIA)
                && $this->tieneAlgunaDe($normal, self::CV_ESTUDIOS)
                && $this->tieneAlgunaDe($normal, self::CV_EXTRA);
        } else {
            $parece = $this->tieneAlgunaDe($normal, self::SOLICITUD_TITULO)
                && $this->gruposEncontrados($normal, self::GRUPOS_SOLICITUD) >= self::SOLICITUD_BLOQUES_MINIMOS;
        }

        if (! $parece) {
            return $this->rechazado($tipo === self::CV
                ? 'Este archivo no parece un CV: un CV debe incluir al menos tu experiencia, tus estudios y tus habilidades o datos personales. Sube tu currículum, o crea tu CV desde la plataforma.'
                : 'Este archivo no parece una Solicitud de Empleo: debe decir "Solicitud de empleo" y traer tus datos (nombre, domicilio, escolaridad, experiencia). Sube la solicitud llenada, en PDF.');
        }

        return $this->aceptado(true);
    }

    /**
     * Quita campos de formulario normales que chocan con la lista de palabras prohibidas
     * (por ejemplo "Sexo: Masculino" en una Solicitud de Empleo).
     */
    private function sinCamposInocuos(string $texto): string
    {
        return preg_replace('/\bsexo\b\s*(?=[:\-\(]|masculino|femenino|hombre|mujer)/iu', ' ', $texto) ?? $texto;
    }

    // ---------------------------------------------------------------- formato

    private function formatoReal(string $ruta): ?string
    {
        $cabeza = (string) @file_get_contents($ruta, false, null, 0, 1024);

        if (str_contains($cabeza, '%PDF-')) {
            return 'pdf';
        }
        if (str_starts_with($cabeza, "PK\x03\x04")) {
            return $this->esDocx($ruta) ? 'docx' : null;
        }
        if (str_starts_with($cabeza, "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1")) {
            return 'doc';
        }

        return null;
    }

    private function esDocx(string $ruta): bool
    {
        if (! class_exists(\ZipArchive::class)) {
            return true; // sin ZipArchive no se puede comprobar; ya paso la validacion de Laravel
        }
        $zip = new \ZipArchive();
        if ($zip->open($ruta) !== true) {
            return false;
        }
        $hay = $zip->locateName('word/document.xml') !== false;
        $zip->close();

        return $hay;
    }

    // ----------------------------------------------------------- sacar texto

    /** @return string|false|null  texto; false = falta la libreria; null = no se pudo leer */
    private function textoDePdf(string $ruta): string|false|null
    {
        if (! class_exists(\Smalot\PdfParser\Parser::class)) {
            static $avisado = false;
            if (! $avisado) {
                Log::warning('ValidadorDocumento: falta el paquete smalot/pdfparser (composer require smalot/pdfparser). Los PDF se aceptan sin revisar su contenido.');
                $avisado = true;
            }
            return false;
        }

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $texto = $parser->parseFile($ruta)->getText();
            return is_string($texto) ? $texto : null;
        } catch (\Throwable $e) {
            // PDF dañado, protegido o que la libreria no entiende: no se puede comprobar, no se acepta.
            Log::info('ValidadorDocumento: no se pudo leer un PDF.', ['mensaje' => $e->getMessage()]);
            return null;
        }
    }

    /** @return string|false|null  texto; false = el servidor no puede abrir .docx; null = no se pudo leer */
    private function textoDeDocx(string $ruta): string|false|null
    {
        if (! class_exists(\ZipArchive::class)) {
            return false;
        }
        $zip = new \ZipArchive();
        if ($zip->open($ruta) !== true) {
            return null;
        }
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();
        if (! is_string($xml) || $xml === '') {
            return null;
        }

        $xml = preg_replace('/<\/w:p>|<w:br\s*\/?>|<w:tab\s*\/?>/', ' ', $xml);

        return html_entity_decode(strip_tags($xml), ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    // ----------------------------------------------------- parecido a un CV

    private function tieneAlgunaDe(string $normal, array $palabras): bool
    {
        $compacto = preg_replace('/[^a-z0-9ñ]/u', '', $normal);

        foreach ($palabras as $palabra) {
            // Al inicio de una palabra (asi "edad" no cuenta dentro de "universidad").
            if (preg_match('/(?<![a-zñ])' . preg_quote($palabra, '/') . '/u', $normal) === 1) {
                return true;
            }
        }

        // Algunos PDF separan las letras ("E x p e r i e n c i a"): se prueba tambien sin espacios.
        foreach ($palabras as $palabra) {
            $p = preg_replace('/[^a-z0-9ñ]/u', '', $palabra);
            if (strlen($p) >= 8 && str_contains($compacto, $p)) {
                return true;
            }
        }

        return false;
    }

    private function gruposEncontrados(string $normal, array $grupos): int
    {
        $n = 0;
        foreach ($grupos as $palabras) {
            if ($this->tieneAlgunaDe($normal, $palabras)) {
                $n++;
            }
        }

        return $n;
    }

    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = strtr($texto, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u']);

        return trim(preg_replace('/[^a-z0-9ñ\-@.]+/u', ' ', $texto));
    }

    // ------------------------------------------------------------- resultados

    private function aceptado(bool $verificado): array
    {
        return ['ok' => true, 'error' => null, 'verificado' => $verificado];
    }

    private function rechazado(string $mensaje): array
    {
        return ['ok' => false, 'error' => $mensaje, 'verificado' => false];
    }
}
