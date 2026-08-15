<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';

    protected $fillable = [
        'usuario_reporta_id',
        'usuario_reportado_id',
        'tipo_objeto',
        'objeto_id',
        'motivo',
        'descripcion',
        'estado',
    ];

    public function usuarioReporta()
    {
        return $this->belongsTo(Usuario::class, 'usuario_reporta_id');
    }

    public function usuarioReportado()
    {
        return $this->belongsTo(Usuario::class, 'usuario_reportado_id');
    }
}
