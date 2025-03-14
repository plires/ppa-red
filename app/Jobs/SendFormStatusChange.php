<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\MailFormSubmissionStatusChange;

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
