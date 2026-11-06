<?php

namespace App\Services;

/**
 * El salario de una vacante se guarda como texto ("$1,500", "A convenir" y,
 * en las vacantes viejas, cosas como "300 a 400 pesos por día"). Esto lo
 * convierte en números para poder filtrar por un rango de salario.
 */
class SalarioVacante
{
    /**
     * [mínimo, máximo] que se lee en el texto, o null si no trae números
     * (por ejemplo "A convenir").
     *
     * @return array{0: float, 1: float}|null
     */
    public static function rango(?string $texto): ?array
    {
        $texto = trim((string) $texto);
        if ($texto === '') {
            return null;
        }

        // Si hay cantidades con "$", son las que cuentan: "$300 por 5 días" => 300.
        $montos = self::numeros($texto, true) ?: self::numeros($texto, false);

        return $montos ? [min($montos), max($montos)] : null;
    }

    /** ¿El salario de la vacante cae dentro del rango que se pidió? */
    public static function encaja(?array $rango, int|float|null $min, int|float|null $max): bool
    {
        if ($rango === null) {
            return false;
        }

        return ($min === null || $rango[1] >= $min) && ($max === null || $rango[0] <= $max);
    }

    /**
     * Número escrito en un filtro de precio: solo vale si es una cantidad
     * (con o sin $ y comas) de 0 en adelante. Si no, se ignora (null).
     */
    public static function numeroDelFiltro(mixed $valor): int|float|null
    {
        if (!is_scalar($valor)) {
            return null;
        }

        $limpio = str_replace([',', '$', ' '], '', (string) $valor);
        if (!preg_match('/^\d{1,7}(\.\d{1,2})?$/', $limpio)) {
            return null;
        }

        return $limpio + 0;
    }

    /** @return float[] */
    private static function numeros(string $texto, bool $soloConSigno): array
    {
        $patron = $soloConSigno
            ? '/\$\s*(\d{1,3}(?:,\d{3})+(?:\.\d+)?|\d+(?:\.\d+)?)/'
            : '/\d{1,3}(?:,\d{3})+(?:\.\d+)?|\d+(?:\.\d+)?/';

        preg_match_all($patron, $texto, $m);
        $encontrados = $soloConSigno ? $m[1] : $m[0];

        return array_map(fn($n) => (float) str_replace(',', '', $n), $encontrados);
    }
}
