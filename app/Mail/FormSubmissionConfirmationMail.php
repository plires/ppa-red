<?php

namespace App\Mail;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FormSubmissionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formSubmission;

    public $data;

    public function __construct(FormSubmission $formSubmission, array $data)
    {
        $this->formSubmission = $formSubmission;
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibimos tu consulta — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.form_submission_confirmation',
            with: [
                'formSubmission' => $this->formSubmission,
                'partner'        => $this->formSubmission->user,
                'data'           => $this->data,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
