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

    protected $recipent;

    protected $emailTemplate;

    /**
     * Create a new job instance.
     */
    public function __construct(FormSubmission $formSubmission, $recipent, $emailTemplate)
    {
        $this->formSubmission = $formSubmission;
        $this->recipent = $recipent;
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->recipent)->send(new MailFormSubmissionStatusChange($this->formSubmission, $this->emailTemplate));
    }
}
