<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulaciones';

    protected $fillable = [
        'vacante_id',
        'postulante_id',
        'estado',
        'mensaje',
    ];

    public function vacante()
    {
        return $this->belongsTo(Vacante::class);
    }

    public function postulante()
    {
        return $this->belongsTo(Usuario::class, 'postulante_id');
    }
}
