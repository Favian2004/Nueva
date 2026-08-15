<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratacion extends Model
{
    use HasFactory;

    protected $table = 'contrataciones';

    protected $fillable = [
        'servicio_id',
        'contratante_id',
        'trabajador_id',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function contratante()
    {
        return $this->belongsTo(Usuario::class, 'contratante_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Usuario::class, 'trabajador_id');
    }
}
