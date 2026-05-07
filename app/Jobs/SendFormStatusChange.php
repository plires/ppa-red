<?php

namespace App\Jobs;

use App\Mail\MailFormSubmissionStatusChange;
use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFormStatusChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formSubmission;

    protected $recipient;

    protected $emailTemplate;

    protected $recipientType;

    /**
     * Create a new job instance.
     */
    public function __construct(FormSubmission $formSubmission, $recipient, $emailTemplate, string $recipientType = 'partner')
    {
        $this->formSubmission = $formSubmission;
        $this->recipient = $recipient;
        $this->emailTemplate = $emailTemplate;
        $this->recipientType = $recipientType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->recipient)->send(new MailFormSubmissionStatusChange($this->formSubmission, $this->emailTemplate, $this->recipientType));
    }
}
