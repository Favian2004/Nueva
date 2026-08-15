<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnuncioImagen extends Model
{
    use HasFactory;

    protected $table = 'anuncio_imagenes';

    const UPDATED_AT = null;

    protected $fillable = [
        'anuncio_id',
        'imagen',
        'orden',
    ];

    public function anuncio()
    {
        return $this->belongsTo(Anuncio::class);
    }
}
