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

class ReassignOutgoingPartnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;

    public $outgoing;

    public $incoming;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(FormSubmission $submission, User $outgoing, User $incoming, array $data)
    {
        $this->submission = $submission;
        $this->outgoing = $outgoing;
        $this->incoming = $incoming;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu consulta asignada fue reasignada — PPA RED',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reassign_outgoing_partner',
            with: [
                'submission' => $this->submission,
                'outgoing' => $this->outgoing,
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
