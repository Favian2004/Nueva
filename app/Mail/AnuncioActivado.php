<?php

namespace App\Mail;

use App\Models\SolicitudAnuncio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnuncioActivado extends Mailable
{
    use Queueable, SerializesModels;

    public SolicitudAnuncio $solicitud;
    public string $fechaInicio;
    public string $fechaVencimiento;

    public function __construct(SolicitudAnuncio $solicitud, string $fechaInicio, string $fechaVencimiento)
    {
        $this->solicitud = $solicitud;
        $this->fechaInicio = $fechaInicio;
        $this->fechaVencimiento = $fechaVencimiento;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu anuncio ya está publicado en ¡SINTECZATE!!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.anuncio-activado',
        );
    }
}
