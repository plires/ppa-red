<?php

namespace App\Jobs;

use App\Mail\FormSubmissionConfirmationMail;
use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFormSubmissionConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formSubmission;

    protected $data;

    public function __construct(FormSubmission $formSubmission, array $data)
    {
        $this->formSubmission = $formSubmission;
        $this->data = $data;
    }

    public function handle(): void
    {
        $email = $this->data['email'] ?? null;
        if (! $email) {
            return;
        }

        $this->formSubmission->loadMissing('user');

        Mail::to($email)->send(new FormSubmissionConfirmationMail($this->formSubmission, $this->data));
    }
}
