<?php

namespace App\Services;

use App\Models\Servicio;
use App\Models\Vacante;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Averigua en qué localidad está cada servicio o vacante, para poder filtrar
 * y ordenar por "lugar más cercano".
 *
 * El sistema no guarda coordenadas, así que la cercanía se calcula por
 * localidad. El dato sale, en este orden, de:
 *   1. lo que se escribió en "Ubicación" del servicio (aunque venga con
 *      acentos, mayúsculas o texto extra: "Col. Centro, Xáltipan");
 *   2. una palabra propia de una sola localidad ("Tzinacapan" para
 *      "San Miguel Tzinacapan");
 *   3. el nombre del municipio o de la cabecera ("Zacapoaxtla");
 *   4. la localidad del perfil de quien lo publica (el trabajador o el empleador).
 */
class UbicacionServicios
{
    // Palabras que no sirven para reconocer una localidad por sí solas
    // (nombres de santos, "nuevo", "centro"... se repiten en muchas localidades).
    private const COMUNES = [
        'san', 'santa', 'santo', 'santiago', 'barrio', 'colonia', 'col', 'centro', 'norte', 'sur',
        'oriente', 'poniente', 'del', 'las', 'los', 'una', 'con', 'calle', 'avenida', 'puebla',
        'municipal', 'cabecera', 'localidad', 'comunidad', 'ejido', 'rancho', 'nuevo', 'nueva',
        'grande', 'chico', 'chica', 'viejo', 'vieja', 'lomas', 'cerro', 'miguel', 'pedro', 'andres',
        'francisco', 'antonio', 'lucas', 'mateo', 'marcos', 'pablo', 'felipe', 'nicolas', 'bartolo',
        'cristobal', 'sebastian', 'agustin', 'gabriel', 'rafael', 'martin', 'diego', 'esteban',
        'lorenzo', 'jeronimo', 'maria', 'magdalena', 'guadalupe', 'concepcion', 'isabel', 'trinidad',
        'asuncion', 'natividad', 'candelaria', 'dolores', 'carmen',
    ];

    /** Localidades con nombre propio, de la más larga a la más corta. */
    private array $propias = [];

    /** Palabra propia => id (solo si la palabra pertenece a una única localidad). */
    private array $palabras = [];

    /** Cabecera / municipio: sirven cuando no hay nada más específico. */
    private array $genericas = [];

    public function __construct(Collection $localidades, string $municipio = 'Zacapoaxtla')
    {
        $municipioClave = self::normalizar($municipio);
        $vecesPalabra = [];

        foreach ($localidades as $loc) {
            $clave = self::normalizar(preg_replace('/\(.*?\)/u', ' ', (string) $loc->nombre));
            if ($clave === '') {
                continue;
            }

            $esGenerica = $clave === $municipioClave
                || preg_match('/cabecera/iu', (string) $loc->nombre);

            if ($esGenerica) {
                $this->genericas[] = ['id' => (int) $loc->id, 'clave' => $clave];
                continue;
            }

            $this->propias[] = ['id' => (int) $loc->id, 'clave' => $clave];

            foreach (array_unique(explode(' ', $clave)) as $palabra) {
                if (mb_strlen($palabra) >= 5 && !in_array($palabra, self::COMUNES, true) && $palabra !== $municipioClave) {
                    $vecesPalabra[$palabra][] = (int) $loc->id;
                }
            }
        }

        usort($this->propias, fn($a, $b) => strlen($b['clave']) <=> strlen($a['clave']));

        foreach ($vecesPalabra as $palabra => $ids) {
            if (count($ids) === 1) {
                $this->palabras[$palabra] = $ids[0];
            }
        }
    }

    /** Minúsculas, sin acentos ni signos: "Xáltipan, Pue." => "xaltipan pue". */
    public static function normalizar(?string $texto): string
    {
        $texto = Str::ascii(mb_strtolower((string) $texto));

        return trim(preg_replace('/[^a-z0-9]+/', ' ', $texto));
    }

    /** id de la localidad del servicio o vacante, o null si no se puede saber. */
    public function localidadDe(Servicio|Vacante $publicacion): ?int
    {
        $texto = self::normalizar($publicacion->ubicacion);

        if ($texto !== '') {
            $envuelto = ' ' . $texto . ' ';

            foreach ($this->propias as $loc) {
                if (str_contains($envuelto, ' ' . $loc['clave'] . ' ')) {
                    return $loc['id'];
                }
            }

            foreach (explode(' ', $texto) as $palabra) {
                if (isset($this->palabras[$palabra])) {
                    return $this->palabras[$palabra];
                }
            }

            foreach ($this->genericas as $loc) {
                if (str_contains($envuelto, ' ' . $loc['clave'] . ' ')) {
                    return $loc['id'];
                }
            }
        }

        $quien = $publicacion instanceof Vacante ? $publicacion->empleador : $publicacion->usuario;
        $delPerfil = $quien->localidad_id ?? null;

        return $delPerfil ? (int) $delPerfil : null;
    }

    /**
     * @param  iterable<Servicio|Vacante>  $publicaciones  (con "usuario" o "empleador" cargado)
     * @return array<int, int|null>  id de la publicación => id de la localidad
     */
    public function mapa(iterable $publicaciones): array
    {
        $mapa = [];
        foreach ($publicaciones as $publicacion) {
            $mapa[$publicacion->id] = $this->localidadDe($publicacion);
        }

        return $mapa;
    }
}
