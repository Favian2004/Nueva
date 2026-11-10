<?php

namespace App\Models;

use App\Observers\PostulacionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PostulacionObserver::class])]
class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulaciones';

    protected $fillable = [
        'vacante_id',
        'postulante_id',
        'estado',
        'mensaje',
        'solicitud_empleo',
        'disponibilidad',
        'experiencia',
        'contacto_preferido',
    ];

    // Estados en los que el empleador ya aceptó hablar con la persona:
    // desde aquí los dos pueden ver el contacto del otro.
    public const ESTADOS_CONTACTO_ABIERTO = ['entrevista', 'contratado'];

    // Opciones del formulario de postulación (clave => texto que se muestra).
    public const DISPONIBILIDADES = [
        'inmediata' => 'Inmediata',
        'una_semana' => 'En una semana',
        'quince_dias' => 'En 15 días',
        'un_mes' => 'En un mes',
    ];

    public const EXPERIENCIAS = [
        'sin_experiencia' => 'Sin experiencia',
        'menos_1' => 'Menos de 1 año',
        '1_3' => 'De 1 a 3 años',
        'mas_3' => 'Más de 3 años',
    ];

    public const CONTACTOS = [
        'whatsapp' => 'WhatsApp',
        'llamada' => 'Llamada',
        'correo' => 'Correo',
    ];

    public function etiquetaDisponibilidad(): ?string
    {
        return self::DISPONIBILIDADES[$this->disponibilidad] ?? null;
    }

    public function etiquetaExperiencia(): ?string
    {
        return self::EXPERIENCIAS[$this->experiencia] ?? null;
    }

    public function etiquetaContacto(): ?string
    {
        return self::CONTACTOS[$this->contacto_preferido] ?? null;
    }

    /** ¿El empleador ya aceptó a esta persona (entrevista o contratación)? */
    public function contactoAbierto(): bool
    {
        return in_array($this->estado, self::ESTADOS_CONTACTO_ABIERTO, true);
    }

    /**
     * ¿Esta persona ya puede ver el contacto del empleador de esa vacante?
     * Solo si el empleador la aceptó para entrevista o la contrató.
     */
    public static function contactoAbiertoVacante($vacanteId, $postulanteId): bool
    {
        if (!$postulanteId) {
            return false;
        }

        return static::where('vacante_id', $vacanteId)
            ->where('postulante_id', $postulanteId)
            ->whereIn('estado', self::ESTADOS_CONTACTO_ABIERTO)
            ->exists();
    }

    /** Datos de una postulación para rellenar de nuevo el formulario (o null). */
    public function datosParaFormulario(): ?array
    {
        if ($this->mensaje === null && $this->disponibilidad === null) {
            return null;
        }

        return [
            'mensaje' => (string) $this->mensaje,
            'disponibilidad' => (string) $this->disponibilidad,
            'experiencia' => (string) $this->experiencia,
            'contacto_preferido' => (string) $this->contacto_preferido,
        ];
    }

    public function vacante()
    {
        return $this->belongsTo(Vacante::class);
    }

    public function postulante()
    {
        return $this->belongsTo(Usuario::class, 'postulante_id');
    }

    public function curriculum()
    {
        return $this->hasOne(Curriculum::class, 'postulacion_id');
    }
}
