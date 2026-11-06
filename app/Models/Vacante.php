<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    use HasFactory;

    protected $table = 'vacantes';

    protected $fillable = [
        'empleador_id',
        'categoria_id',
        'subcategoria_id',
        'titulo',
        'publicante',
        'ubicacion',
        'trabajadores_requeridos',
        'tipo_pago',
        'salario',
        'experiencia',
        'contrato',
        'beneficios',
        'descripcion',
        'requiere_cv',
        'requiere_solicitud_empleo',
        'fecha_trabajo',
        'duracion',
        'fecha_limite',
        'telefono',
        'whatsapp',
        'imagen',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_limite' => 'date',
            'beneficios' => 'array',
        ];
    }

    // ------------------------------------------------------------------
    // Imagen: si quien publica no sube ninguna, se muestra la de por defecto
    // (imagen «Empleadores · Vacantes»). En la base de datos la columna sigue quedando vacía,
    // así que si algún día se cambia la imagen por defecto se cambia en todos lados.
    // ------------------------------------------------------------------

    public const IMAGEN_POR_DEFECTO = '/assets/usuario/img/vacante-sin-imagen.webp';

    public function getImagenAttribute($valor)
    {
        return $valor ?: self::IMAGEN_POR_DEFECTO;
    }

    public function setImagenAttribute($valor): void
    {
        // Si llega la imagen por defecto (por ejemplo al editar sin cambiar la imagen), no se guarda
        $this->attributes['imagen'] = ($valor === self::IMAGEN_POR_DEFECTO || $valor === '') ? null : $valor;
    }

    // ¿Subió su propia imagen? (para mostrar «Imagen actual» solo cuando de verdad es suya)
    public function getTieneImagenPropiaAttribute(): bool
    {
        return !empty($this->attributes['imagen']);
    }

    // ------------------------------------------------------------------
    // ¿Sigue abierta? Una vacante recibe postulaciones mientras esté
    // "activa" y todavía no haya pasado su límite para postular. El límite
    // cuenta completo: se puede postular HASTA ese día (incluido) y desde
    // el día siguiente la vacante deja de verse y de aceptar postulaciones.
    // Las vacantes sin límite (las viejas) siguen abiertas como antes.
    // ------------------------------------------------------------------

    public function scopeAbiertas($query)
    {
        return $query->where('estado', 'activa')
            ->where(function ($q) {
                $q->whereNull('fecha_limite')
                    ->orWhereDate('fecha_limite', '>=', today()->toDateString());
            });
    }

    public function scopeVencidas($query)
    {
        return $query->where(function ($q) {
            $q->where('estado', 'vencida')
                ->orWhere(function ($q2) {
                    $q2->where('estado', 'activa')
                        ->whereDate('fecha_limite', '<', today()->toDateString());
                });
        });
    }

    public function getEstaVencidaAttribute(): bool
    {
        if ($this->estado === 'vencida') {
            return true;
        }

        return $this->estado === 'activa'
            && $this->fecha_limite !== null
            && $this->fecha_limite->lt(today());
    }

    public function getEstaAbiertaAttribute(): bool
    {
        return $this->estado === 'activa' && !$this->esta_vencida;
    }

    // 'activa' | 'cerrada' | 'vencida' (la que se muestra en pantalla)
    public function getEstadoEfectivoAttribute(): string
    {
        return $this->esta_vencida ? 'vencida' : (string) $this->estado;
    }

    // "Día del trabajo" se guarda como texto dd/mm/aaaa. Esto lo devuelve
    // como aaaa-mm-dd (lo que usa el calendario del formulario), o null si
    // es una vacante vieja con texto libre ("15 oct - 30 nov", etc.).
    public function getFechaTrabajoIsoAttribute(): ?string
    {
        $texto = trim((string) $this->fecha_trabajo);

        if (!preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $texto, $m)) {
            return null;
        }

        return checkdate((int) $m[2], (int) $m[1], (int) $m[3]) ? "{$m[3]}-{$m[2]}-{$m[1]}" : null;
    }

    public function empleador()
    {
        return $this->belongsTo(Usuario::class, 'empleador_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }
}
