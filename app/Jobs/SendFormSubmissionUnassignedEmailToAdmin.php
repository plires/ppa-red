<?php

namespace App\Jobs;

use App\Mail\FormSubmissionUnassignedMail;
use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFormSubmissionUnassignedEmailToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formSubmission;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(FormSubmission $formSubmission, $data)
    {
        $this->formSubmission = $formSubmission;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(config('mail.from.address'))
            ->send(new FormSubmissionUnassignedMail($this->formSubmission, $this->data));
    }
}
