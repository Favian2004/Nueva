<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios';

    protected $fillable = [
        'nombre',
    ];

    public function localidades()
    {
        return $this->hasMany(Localidad::class);
    }

    public function anuncios()
    {
        return $this->hasMany(Anuncio::class);
    }
}
