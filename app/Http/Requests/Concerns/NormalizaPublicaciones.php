<?php

namespace App\Http\Requests\Concerns;

use Closure;

/**
 * Piezas que comparten los formularios de "Publicar vacante" y
 * "Publicar empleo": limpiar lo que escribe la persona y revisar que un
 * texto sea de verdad texto (y no solo números o símbolos).
 */
trait NormalizaPublicaciones
{
    // Letras (con acentos y ñ), números, espacios y signos de puntuación
    // comunes. Todo lo demás (< > { } \ | ^ ~ ` emojis, etc.) no se permite.
    protected function regexTexto(): string
    {
        return '/^[\p{L}\p{N}\s.,;:¡!¿?()\[\]\/&#+%$@\'"°ºª*=_\-–—]+$/u';
    }

    // Reglas para un texto corto de una línea (título, nombre, ubicación).
    protected function reglasTexto(string $nombre, int $min, int $max): array
    {
        return [
            'bail',
            'required',
            'string',
            "min:{$min}",
            "max:{$max}",
            function ($atributo, $valor, Closure $fail) use ($nombre) {
                if (!preg_match($this->regexTexto(), $valor)) {
                    $fail("«{$nombre}» tiene caracteres que no se permiten. Usa solo letras, números y signos comunes.");
                } elseif (!preg_match('/\p{L}/u', $valor)) {
                    $fail("«{$nombre}» debe llevar letras, no solo números o símbolos.");
                }
            },
        ];
    }

    // Reglas para un texto largo (descripción): puede tener varias líneas.
    protected function reglasTextoLargo(string $nombre, int $min, int $max): array
    {
        return [
            'bail',
            'required',
            'string',
            "min:{$min}",
            "max:{$max}",
            function ($atributo, $valor, Closure $fail) use ($nombre) {
                if (preg_match('/[<>]/', $valor)) {
                    $fail("«{$nombre}» no puede llevar los signos < ni >.");
                } elseif (!preg_match('/\p{L}/u', $valor)) {
                    $fail("«{$nombre}» debe llevar letras, no solo números o símbolos.");
                }
            },
        ];
    }

    // Texto de una sola línea: sin espacios de más ni saltos de línea.
    protected function textoLinea(mixed $valor): mixed
    {
        return is_string($valor) ? trim(preg_replace('/\s+/u', ' ', $valor)) : $valor;
    }

    // Texto largo: se quitan espacios al inicio y al final, y los renglones
    // vacíos de sobra (más de uno seguido).
    protected function textoLargo(mixed $valor): mixed
    {
        if (!is_string($valor)) {
            return $valor;
        }

        $valor = str_replace(["\r\n", "\r"], "\n", $valor);

        return trim(preg_replace("/\n{3,}/", "\n\n", $valor));
    }

    // Solo los dígitos de un teléfono: "(222) 123-4567" -> "2221234567".
    protected function soloDigitos(mixed $valor): mixed
    {
        return is_string($valor) ? preg_replace('/\D+/', '', $valor) : $valor;
    }
}
