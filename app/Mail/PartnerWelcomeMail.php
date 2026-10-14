<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;

    public $setPasswordUrl;

    public function __construct(User $partner, string $setPasswordUrl)
    {
        $this->partner = $partner;
        $this->setPasswordUrl = $setPasswordUrl;
    }

    public function envelope(): Envelope
    {
        // El correo sale desde una casilla que no recibe respuestas: las dudas
        // del partner van al administrador.
        return new Envelope(
            replyTo: [new Address((string) config('mail.admin_address'), (string) config('mail.from.name'))],
            subject: '¡Bienvenido a PPA RED! Activá tu cuenta de partner',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner_welcome',
            with: [
                'partner' => $this->partner,
                'setPasswordUrl' => $this->setPasswordUrl,
                'adminAddress' => config('mail.admin_address'),
                'noReplyAddress' => config('mail.force_from_address') ?: config('mail.from.address'),
            ]
        );
    }
}
