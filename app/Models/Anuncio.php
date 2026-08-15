<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    use HasFactory;

    protected $table = 'anuncios';

    protected $fillable = [
        'creado_por',
        'municipio_id',
        'posicion',
        'orden',
        'estado',
    ];

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function imagenes()
    {
        return $this->hasMany(AnuncioImagen::class)->orderBy('orden');
    }
}
