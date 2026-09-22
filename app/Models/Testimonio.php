<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonio extends Model
{
    use HasFactory;

    protected $table = 'testimonios';

    protected $fillable = [
        'usuario_id',
        'texto',
        'estrellas',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
