<?php

namespace App\Models;

use App\Observers\ContratacionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ContratacionObserver::class])]
class Contratacion extends Model
{
    use HasFactory;

    protected $table = 'contrataciones';

    protected $fillable = [
        'servicio_id',
        'contratante_id',
        'trabajador_id',
        'estado',
        'necesidad',
        'fecha_necesaria',
        'presupuesto',
        'contacto_preferido',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Opciones del formulario "Solicitud de servicio" (clave => texto que se muestra).
    public const FECHAS = [
        'lo_antes_posible' => 'Lo antes posible',
        'esta_semana' => 'Esta semana',
        'este_mes' => 'Este mes',
        'fecha' => 'Una fecha específica',
    ];

    public const PRESUPUESTOS = [
        'cotizacion' => 'Solicitar cotización',
        'menos_500' => 'Menos de $500',
        '500_1000' => 'De $500 a $1,000',
        '1000_3000' => 'De $1,000 a $3,000',
        'mas_3000' => 'Más de $3,000',
        'a_convenir' => 'A convenir con el trabajador',
    ];

    public const CONTACTOS = [
        'whatsapp' => 'WhatsApp',
        'llamada' => 'Llamada',
        'correo' => 'Correo electrónico',
    ];

    /** Lo que se vuelve a mostrar en el formulario cuando alguien reenvía una solicitud. */
    public function datosParaFormulario(): ?array
    {
        if (!$this->necesidad) {
            return null; // solicitud vieja, de antes del formulario: se empieza en blanco
        }

        $fecha = $this->fecha_necesaria;
        $especifica = '';
        if ($fecha && !isset(self::FECHAS[$fecha])) {
            // era una fecha puntual; si ya pasó se deja vacía para que elija otra
            $especifica = $fecha >= now()->format('Y-m-d') ? $fecha : '';
            $fecha = 'fecha';
        }

        return [
            'necesidad' => $this->necesidad,
            'fecha' => $fecha ?: 'lo_antes_posible',
            'fechaEspecifica' => $especifica,
            'presupuesto' => $this->presupuesto ?: 'cotizacion',
            'contacto' => $this->contacto_preferido ?: 'whatsapp',
        ];
    }

    public function etiquetaFecha(): ?string
    {
        $f = $this->fecha_necesaria;
        if (!$f) {
            return null;
        }
        if (isset(self::FECHAS[$f])) {
            return self::FECHAS[$f];
        }
        try {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $f)->translatedFormat('d \\d\\e F \\d\\e Y');
        } catch (\Throwable $e) {
            return $f;
        }
    }

    public function etiquetaPresupuesto(): ?string
    {
        return self::PRESUPUESTOS[$this->presupuesto] ?? null;
    }

    public function etiquetaContacto(): ?string
    {
        return self::CONTACTOS[$this->contacto_preferido] ?? null;
    }

    /**
     * El contacto del trabajador (teléfono, WhatsApp, correo) solo se muestra a quien
     * pidió el servicio DESPUÉS de que el trabajador aceptó su solicitud.
     */
    public static function contactoAbierto($servicioId, $contratanteId): bool
    {
        if (!$contratanteId) {
            return false;
        }

        return static::where('servicio_id', $servicioId)
            ->where('contratante_id', $contratanteId)
            ->where('estado', 'aceptado')
            ->exists();
    }

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
