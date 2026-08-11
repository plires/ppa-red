<?php

namespace App\Mail;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FormSubmissionUnassignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formSubmission;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(FormSubmission $formSubmission, $data)
    {
        $this->formSubmission = $formSubmission;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consulta sin partner asignado — requiere reasignación manual',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.form_submission_unassigned',
            with: [
                'formSubmission' => $this->formSubmission,
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
