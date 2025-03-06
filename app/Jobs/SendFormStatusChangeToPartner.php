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
use App\Mail\MailToPartnerFormSubmissionStatusChange;

class SendFormStatusChangeToPartner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $partner;
    protected $dataUser;
    protected $formSubmission;
    protected $subject;
    protected $msg;

    /**
     * Create a new job instance.
     */
    public function __construct(User $partner, $dataUser, FormSubmission $formSubmission, $subject, $msg)
    {
        $this->partner = $partner;
        $this->dataUser = $dataUser;
        $this->formSubmission = $formSubmission;
        $this->subject = $subject;
        $this->msg = $msg;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->partner->email)->send(new MailToPartnerFormSubmissionStatusChange($this->partner, $this->dataUser, $this->formSubmission, $this->subject, $this->msg));
    }
}
