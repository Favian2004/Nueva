<?php

namespace App\Models;

use App\Observers\PostulacionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PostulacionObserver::class])]
class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulaciones';

    protected $fillable = [
        'vacante_id',
        'postulante_id',
        'estado',
        'mensaje',
        'solicitud_empleo',
    ];

    public function vacante()
    {
        return $this->belongsTo(Vacante::class);
    }

    public function postulante()
    {
        return $this->belongsTo(Usuario::class, 'postulante_id');
    }

    public function curriculum()
    {
        return $this->hasOne(Curriculum::class, 'postulacion_id');
    }
}
