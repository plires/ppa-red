<?php

namespace App\Mail;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailFormSubmissionStatusChange extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;

    public $dataUser;

    public $formSubmission;

    public $subject;

    public $body;

    public $responses;

    public $recipientType;

    /**
     * Create a new message instance.
     */
    public function __construct(FormSubmission $formSubmission, $emailTemplate, string $recipientType = 'partner')
    {
        $this->partner = $formSubmission->user;
        $this->dataUser = json_decode($formSubmission->data, true);
        $this->formSubmission = $formSubmission;
        $this->subject = $emailTemplate->subject;
        $this->body = $emailTemplate->body;
        $this->responses = $formSubmission->formResponses;
        $this->recipientType = $recipientType;
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
                'recipientType' => $this->recipientType,
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
