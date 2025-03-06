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
use App\Mail\FormSubmissionDelayedMailToPartner;

class SendFormSubmissionDelayedMailToPartner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $partner;
    protected $dataUser;
    protected $formSubmission;

    /**
     * Create a new job instance.
     */
    public function __construct(User $partner, $dataUser, FormSubmission $formSubmission)
    {
        $this->partner = $partner;
        $this->dataUser = $dataUser;
        $this->formSubmission = $formSubmission;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->partner->email)->send(new FormSubmissionDelayedMailToPartner($this->partner, $this->dataUser, $this->formSubmission));
    }
}
