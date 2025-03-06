<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\FormSubmission;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class MailToPartnerFormSubmissionStatusChange extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;
    public $dataUser;
    public $formSubmission;
    public $subject;
    public $msg;

    /**
     * Create a new message instance.
     */
    public function __construct($partner, $dataUser, FormSubmission $formSubmission, $subject, $msg)
    {
        $this->partner = $partner;
        $this->dataUser = $dataUser;
        $this->formSubmission = $formSubmission;
        $this->subject = $subject;
        $this->msg = $msg;
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
            view: 'emails.to_partner.email_to_partner_form_submission_status_change',
            with: [
                'partner' => $this->partner,
                'dataUser' => $this->dataUser,
                'formSubmission' => $this->formSubmission,
                'subject' => $this->subject,
                'msg' => $this->msg,
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
