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

class ReassignIncomingPartnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;

    public $incoming;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(FormSubmission $submission, User $incoming, array $data)
    {
        $this->submission = $submission;
        $this->incoming = $incoming;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Se te asignó una nueva consulta — PPA RED',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reassign_incoming_partner',
            with: [
                'submission' => $this->submission,
                'incoming' => $this->incoming,
                'data' => $this->data,
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
