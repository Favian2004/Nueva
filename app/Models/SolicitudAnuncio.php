<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAnuncio extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_anuncio';

    protected $fillable = [
        'nombre_negocio',
        'descripcion',
        'telefono',
        'whatsapp',
        'email',
        'link_externo',
        'plan',
        'imagen_negocio',
        'comprobante_pago',
        'estado',
        'notas_admin',
    ];
}
