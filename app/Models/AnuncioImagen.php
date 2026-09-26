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
        'eslogan',
        'link_externo',
        'link_ubicacion',
        'solicitud_anuncio_id',
    ];

    public function anuncio()
    {
        return $this->belongsTo(Anuncio::class);
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudAnuncio::class, 'solicitud_anuncio_id');
    }
}
