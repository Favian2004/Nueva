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
        'nombre_encargado',
        'descripcion',
        'direccion',
        'telefono',
        'whatsapp',
        'email',
        'link_externo',
        'eslogan',
        'plan',
        'imagen_negocio',
        'comprobante_pago',
        'estado',
        'notas_admin',
    ];

    public function pagos()
    {
        return $this->hasMany(PagoAnuncio::class, 'solicitud_anuncio_id');
    }
}
