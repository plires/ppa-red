<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\FormSubmission;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class FormSubmissionDelayedMailToPartner extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;
    public $dataUser;
    public $formSubmission;

    /**
     * Create a new message instance.
     */
    public function __construct($partner, $dataUser, FormSubmission $formSubmission)
    {
        $this->partner = $partner;
        $this->dataUser = $dataUser;
        $this->formSubmission = $formSubmission;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tenés un formulario pendiente de respuesta hace 48 Hs'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.to_partner.form_submission_delayed_mail_to_partner',
            with: [
                'partner' => $this->partner,
                'dataUser' => $this->dataUser,
                'formSubmission' => $this->formSubmission
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
