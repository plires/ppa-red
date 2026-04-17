<?php

namespace App\Jobs;

use App\Mail\FormResponseMailToPartner;
use App\Models\FormResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFormResponseEmailToPartner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formResponse;

    protected $formSubmission;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(FormResponse $formResponse, $formSubmission, $data)
    {
        $this->formResponse = $formResponse;
        $this->formSubmission = $formSubmission;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->formResponse->user->email)->send(new FormResponseMailToPartner($this->formResponse, $this->formSubmission, $this->data));
    }
}
