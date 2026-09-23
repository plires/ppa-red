<?php

namespace App\Mail;

use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReassignUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;

    public $incoming;

    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(FormSubmission $submission, User $incoming, string $userName)
    {
        $this->submission = $submission;
        $this->incoming = $incoming;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // El usuario final ve al partner que queda a cargo, no a la plataforma.
        $sender = SenderIdentity::forPartner($this->incoming);

        return new Envelope(
            from: $sender->from(),
            replyTo: $sender->replyTo(),
            subject: 'Tu consulta fue asignada a un nuevo especialista — PPA RED',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reassign_user',
            with: [
                'submission' => $this->submission,
                'incoming' => $this->incoming,
                'userName' => $this->userName,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
