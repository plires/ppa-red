<?php

namespace App\Mail;

use App\Models\FormResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormResponseMailToPartner extends Mailable
{
    use Queueable, SerializesModels;

    public $formResponse;
    public $formSubmission;

    /**
     * Create a new message instance.
     */
    public function __construct(FormResponse $formResponse, $formSubmission)
    {
        $this->formResponse = $formResponse;
        $this->formSubmission = $formSubmission;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tenés una nueva consulta de la plataforma PPA RED.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.form_response_to_partner',
            with: [
                'formResponse' => $this->formResponse,
                'formSubmission' => $this->formSubmission,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
