<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoAnuncio extends Model
{
    use HasFactory;

    protected $table = 'pagos_anuncio';

    protected $fillable = [
        'solicitud_anuncio_id',
        'plan',
        'monto',
        'moneda',
        'estado',
        'mp_payment_id',
        'mp_preference_id',
        'fecha_pago',
        'fecha_inicio_anuncio',
        'fecha_vencimiento_anuncio',
    ];

    protected function casts(): array
    {
        return [
            'fecha_pago' => 'datetime',
            'fecha_inicio_anuncio' => 'date',
            'fecha_vencimiento_anuncio' => 'date',
        ];
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudAnuncio::class, 'solicitud_anuncio_id');
    }
}
