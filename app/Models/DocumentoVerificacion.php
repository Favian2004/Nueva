<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoVerificacion extends Model
{
    use HasFactory;

    protected $table = 'documentos_verificacion';

    protected $fillable = [
        'usuario_id',
        'tipo_documento',
        'indice',
        'archivo',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
