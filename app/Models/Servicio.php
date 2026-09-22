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
