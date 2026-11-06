<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Búsqueda que perdona faltas de ortografía.
 *
 * Quien publica un servicio o una vacante a veces escribe mal ("Plomerro",
 * "Electrisista", "Limpiesa", "Jardineria"), y quien busca escribe bien
 * ("plomero"). Con un LIKE normal no se encuentran. Aquí se compara palabra
 * por palabra y se acepta lo que:
 *   - es igual sin acentos ni mayúsculas ("albanil" = "Albañil");
 *   - es de la misma familia ("plomero" = "plomería", "jardinero" = "jardinería");
 *   - suena igual en español ("vaca" = "baca", "cosina" = "cocina", "ase" = "hace");
 *   - se parece con una o dos letras de diferencia (según el largo de la palabra).
 *
 * Todas las palabras que se buscan deben aparecer (como en la búsqueda anterior).
 */
class BusquedaTolerante
{
    // Palabras que no ayudan a encontrar nada.
    private const SIN_VALOR = [
        'de', 'del', 'la', 'las', 'el', 'los', 'un', 'una', 'unos', 'unas', 'para', 'con', 'en', 'y', 'o', 'a',
        'por', 'que', 'se', 'su', 'al', 'mi', 'me', 'es', 'ser', 'hay', 'muy',
    ];

    // Terminaciones que se quitan para llegar a la "raíz" del oficio (plomero, plomería => plom).
    private const TERMINACIONES = ['eria', 'ero', 'era', 'ista', 'ador', 'adora', 'ico', 'ica', 'ante', 'cion', 'ura', 'ia', 'or', 'ora', 'o', 'a'];

    /** ¿Alguna palabra de los textos coincide con TODAS las palabras buscadas? */
    public static function coincide(string $busqueda, ?string ...$textos): bool
    {
        $buscadas = self::palabras($busqueda);
        if (!$buscadas) {
            return true;
        }

        $delTexto = [];
        foreach ($textos as $texto) {
            foreach (self::palabras((string) $texto, false) as $palabra) {
                $delTexto[$palabra] = true;
            }
        }
        $delTexto = array_keys($delTexto);

        foreach ($buscadas as $buscada) {
            $encontrada = false;
            foreach ($delTexto as $palabra) {
                if (self::palabraCoincide($buscada, $palabra)) {
                    $encontrada = true;
                    break;
                }
            }
            if (!$encontrada) {
                return false;
            }
        }

        return true;
    }

    /**
     * Ids de los registros (servicios, vacantes...) que coinciden con la búsqueda.
     *
     * @param  iterable  $registros  modelos con "id" y los campos a revisar
     * @param  string[]  $campos     por ejemplo ['titulo', 'descripcion']
     * @return int[]
     */
    public static function ids(iterable $registros, string $busqueda, array $campos): array
    {
        $ids = [];
        foreach ($registros as $registro) {
            $textos = [];
            foreach ($campos as $campo) {
                $textos[] = (string) ($registro->{$campo} ?? '');
            }
            if (self::coincide($busqueda, ...$textos)) {
                $ids[] = (int) $registro->id;
            }
        }

        return $ids;
    }

    // ------------------------------------------------------------------

    /** Texto => palabras en minúsculas, sin acentos ni signos. */
    public static function palabras(string $texto, bool $quitarSinValor = true): array
    {
        $limpio = trim((string) preg_replace('/[^a-z0-9]+/', ' ', Str::ascii(mb_strtolower($texto))));
        if ($limpio === '') {
            return [];
        }

        $palabras = array_values(array_unique(explode(' ', $limpio)));
        if ($quitarSinValor) {
            $palabras = array_values(array_filter(
                $palabras,
                fn($p) => !in_array($p, self::SIN_VALOR, true) && (strlen($p) >= 2 || ctype_digit($p))
            ));
        }

        return $palabras;
    }

    private static function palabraCoincide(string $buscada, string $palabra): bool
    {
        if ($buscada === $palabra) {
            return true;
        }

        $lb = strlen($buscada);
        $lp = strlen($palabra);

        // Números y palabras muy cortas: solo si son iguales o una empieza con la otra ("5" no es "50").
        if ($lb < 3) {
            return $lb >= 2 && $lp >= $lb && str_starts_with($palabra, $buscada);
        }

        // Una contiene a la otra (plomero / plomeros, lava / lavadora).
        if (str_contains($palabra, $buscada)) {
            return true;
        }
        if ($lp >= 5 && str_contains($buscada, $palabra)) {
            return true;
        }

        // Misma familia: plomero y plomería comparten la raíz "plom"; electricista y electricidad empiezan igual.
        $raizB = self::raiz($buscada);
        $raizP = self::raiz($palabra);
        if (strlen($raizB) >= 4 && $raizB === $raizP) {
            return true;
        }
        if (strlen($raizB) >= 6 && str_starts_with($palabra, $raizB)) {
            return true;
        }
        if (strlen($raizP) >= 6 && str_starts_with($buscada, $raizP)) {
            return true;
        }

        // Suenan igual ("cosina" = "cocina", "bentana" = "ventana").
        $claveB = self::clave($buscada);
        $claveP = self::clave($palabra);
        if (strlen($claveB) >= 4 && $claveB === $claveP) {
            return true;
        }
        // Misma familia y mal escrita ("electrisidad" para "electricista"): empieza igual en sonido.
        $inicio = substr($claveB, 0, strlen($raizB));
        if (strlen($inicio) >= 5 && str_starts_with($claveP, $inicio)) {
            return true;
        }

        // Se parecen con pocas letras de diferencia (según el largo de la palabra buscada).
        $permitidas = $lb >= 9 ? 2 : ($lb >= 5 ? 1 : 0);
        if ($permitidas > 0) {
            if (abs($lb - $lp) <= $permitidas && self::distancia($buscada, $palabra) <= $permitidas) {
                return true;
            }
            if (abs(strlen($claveB) - strlen($claveP)) <= $permitidas && self::distancia($claveB, $claveP) <= $permitidas) {
                return true;
            }

            // La raíz con una falta ("electrisista" y "electricista": electris / electric)
            $rcB = self::clave($raizB);
            $rcP = self::clave($raizP);
            if (strlen($rcB) >= 5 && strlen($rcP) >= 5 && self::distancia($rcB, $rcP) <= 1) {
                return true;
            }
            // La palabra del texto puede venir cortada o con la terminación mal ("plomer", "plomeroo")
            if ($lp >= 4 && str_starts_with($claveP, substr($claveB, 0, max(4, strlen($claveB) - 2)))) {
                return true;
            }
        }

        return false;
    }

    /** plomero, plomería => plom ; electricista => electric ; cajero => cajero (si queda muy corto, no se corta). */
    private static function raiz(string $palabra): string
    {
        foreach (self::TERMINACIONES as $fin) {
            $largo = strlen($fin);
            if (strlen($palabra) - $largo >= 4 && substr($palabra, -$largo) === $fin) {
                return substr($palabra, 0, -$largo);
            }
        }

        return $palabra;
    }

    /** "Cómo suena" en español: b/v, c/s/z, qu/k, g/j, h muda, ll/y, letras repetidas. */
    private static function clave(string $p): string
    {
        $p = str_replace('ll', 'y', $p);
        $p = str_replace('ch', 'X', $p);
        $p = str_replace('h', '', $p);
        $p = preg_replace('/qu(?=[ei])/', 'k', $p);
        $p = str_replace(['q'], 'k', $p);
        $p = preg_replace('/gu(?=[ei])/', 'G', $p);
        $p = preg_replace('/g(?=[ei])/', 'j', $p);
        $p = str_replace('G', 'g', $p);
        $p = preg_replace('/c(?=[ei])/', 's', $p);
        $p = str_replace(['z'], 's', $p);
        $p = str_replace(['c'], 'k', $p);
        $p = str_replace(['v', 'w'], ['b', 'u'], $p);
        $p = str_replace('x', 'ks', $p);

        return (string) preg_replace('/(.)\1+/', '$1', $p);
    }

    /** Distancia de edición (cambiar, quitar, poner o intercambiar dos letras seguidas cuenta 1). */
    private static function distancia(string $a, string $b): int
    {
        $la = strlen($a);
        $lb = strlen($b);
        if ($la === 0) {
            return $lb;
        }
        if ($lb === 0) {
            return $la;
        }

        $d = [];
        for ($i = 0; $i <= $la; $i++) {
            $d[$i][0] = $i;
        }
        for ($j = 0; $j <= $lb; $j++) {
            $d[0][$j] = $j;
        }

        for ($i = 1; $i <= $la; $i++) {
            for ($j = 1; $j <= $lb; $j++) {
                $costo = $a[$i - 1] === $b[$j - 1] ? 0 : 1;
                $d[$i][$j] = min($d[$i - 1][$j] + 1, $d[$i][$j - 1] + 1, $d[$i - 1][$j - 1] + $costo);
                if ($i > 1 && $j > 1 && $a[$i - 1] === $b[$j - 2] && $a[$i - 2] === $b[$j - 1]) {
                    $d[$i][$j] = min($d[$i][$j], $d[$i - 2][$j - 2] + 1);
                }
            }
        }

        return $d[$la][$lb];
    }
}
