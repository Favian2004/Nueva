<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';

    protected $fillable = [
        'usuario_id',
        'tipo_objeto',
        'objeto_id',
        'contenido',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
