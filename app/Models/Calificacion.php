<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'servicio_id',
        'usuario_id',
        'estrellas',
        'comentario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
