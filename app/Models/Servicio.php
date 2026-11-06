<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'subcategoria_id',
        'titulo',
        'descripcion',
        'ubicacion',
        'telefono',
        'whatsapp',
        'precio',
        'imagen',
        'solicitud_empleo',
        'estado',
    ];

    // ------------------------------------------------------------------
    // Imagen: si quien publica no sube ninguna, se muestra la de por defecto
    // (imagen «Trabajadores · Servicios»). En la base de datos la columna sigue quedando vacía,
    // así que si algún día se cambia la imagen por defecto se cambia en todos lados.
    // ------------------------------------------------------------------

    public const IMAGEN_POR_DEFECTO = '/assets/usuario/img/servicio-sin-imagen.webp';

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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function curriculum()
    {
        return $this->hasOne(Curriculum::class, 'servicio_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    public function contrataciones()
    {
        return $this->hasMany(Contratacion::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }
}
