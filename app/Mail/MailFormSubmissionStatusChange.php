<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\FormSubmission;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class MailFormSubmissionStatusChange extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;
    public $dataUser;
    public $formSubmission;
    public $subject;
    public $body;
    public $responses;

    /**
     * Create a new message instance.
     */
    public function __construct(FormSubmission $formSubmission, $emailTemplate)
    {
        $this->partner = $formSubmission->user;
        $this->dataUser = json_decode($formSubmission->data, true); // Convierte JSON en array;
        $this->formSubmission = $formSubmission;
        $this->subject = $emailTemplate->subject;
        $this->body = $emailTemplate->body;
        $this->responses = $formSubmission->formResponses;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.email_form_submission_status_change',
            with: [
                'partner' => $this->partner,
                'dataUser' => $this->dataUser,
                'formSubmission' => $this->formSubmission,
                'subject' => $this->subject,
                'body' => $this->body,
                'responses' => $this->responses,
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
