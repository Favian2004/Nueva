<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    use HasFactory;

    protected $table = 'vacantes';

    protected $fillable = [
        'empleador_id',
        'titulo',
        'publicante',
        'ubicacion',
        'trabajadores_requeridos',
        'tipo_pago',
        'salario',
        'experiencia',
        'contrato',
        'beneficios',
        'descripcion',
        'fecha_trabajo',
        'duracion',
        'fecha_limite',
        'telefono',
        'whatsapp',
        'imagen',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_limite' => 'date',
            'beneficios' => 'array',
        ];
    }

    public function empleador()
    {
        return $this->belongsTo(Usuario::class, 'empleador_id');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }
}
