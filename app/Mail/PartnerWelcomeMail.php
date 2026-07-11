<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
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
        return new Envelope(
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
            ]
        );
    }
}
