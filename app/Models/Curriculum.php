<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curriculums';

    protected $fillable = [
        'usuario_id',
        'servicio_id',
        'postulacion_id',
        'nombre',
        'apellidos',
        'titulo',
        'foto',
        'telefono',
        'correo',
        'resumen',
        'formacion',
        'experiencia',
        'cursos',
        'habilidades',
        'idiomas',
        'intereses',
        'referencias',
        'direccion',
        'fecha_nacimiento',
        'nacionalidad',
        'estado_civil',
        'tipo_documento',
        'detalles_documento',
        'archivo_subido',
    ];

    protected function casts(): array
    {
        return [
            'formacion' => 'array',
            'experiencia' => 'array',
            'cursos' => 'array',
            'habilidades' => 'array',
            'idiomas' => 'array',
            'intereses' => 'array',
            'referencias' => 'array',
            'fecha_nacimiento' => 'date',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class);
    }

    /**
     * Un CV "de verdad" es el que tiene al menos nombre y algo de contenido.
     * Sirve para saber si mostrar "Crear mi CV" o "Editar mi CV" / "Ver mi CV".
     */
    public function getTieneContenidoAttribute(): bool
    {
        return !empty($this->nombre) || !empty($this->archivo_subido);
    }
}
