<?php

namespace App\Jobs;

use App\Models\FormResponse;
use Illuminate\Bus\Queueable;
use App\Mail\FormResponseMailToPartner;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFormResponseEmailToPartner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formResponse;
    protected $formSubmission;

    /**
     * Create a new job instance.
     */
    public function __construct(FormResponse $formResponse, $formSubmission)
    {
        $this->formResponse = $formResponse;
        $this->formSubmission = $formSubmission;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->formResponse->user->email)->send(new FormResponseMailToPartner($this->formResponse, $this->formSubmission));
    }
}
