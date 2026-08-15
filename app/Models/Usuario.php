<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, \Illuminate\Auth\MustVerifyEmail;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'google_id',
        'telefono',
        'whatsapp',
        'foto_perfil',
        'descripcion',
        'localidad_id',
        'rol',
        'modo_activo',
        'estado',
        'motivo_suspension',
        'verificacion_estado',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    // ===== Relaciones =====

    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    public function documentosVerificacion()
    {
        return $this->hasMany(DocumentoVerificacion::class, 'usuario_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'usuario_id');
    }

    public function vacantes()
    {
        return $this->hasMany(Vacante::class, 'empleador_id');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'postulante_id');
    }

    public function contratacionesComoContratante()
    {
        return $this->hasMany(Contratacion::class, 'contratante_id');
    }

    public function contratacionesComoTrabajador()
    {
        return $this->hasMany(Contratacion::class, 'trabajador_id');
    }

    public function reportesHechos()
    {
        return $this->hasMany(Reporte::class, 'usuario_reporta_id');
    }

    public function reportesRecibidos()
    {
        return $this->hasMany(Reporte::class, 'usuario_reportado_id');
    }

    // ===== Helpers de rol =====

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }
}
