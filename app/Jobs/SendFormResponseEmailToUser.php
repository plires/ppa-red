<?php

namespace App\Jobs;

use App\Models\FormResponse;
use Illuminate\Bus\Queueable;
use App\Mail\FormResponseMailToUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFormResponseEmailToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formResponse;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(FormResponse $formResponse, $data)
    {
        $this->formResponse = $formResponse;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->data['email'])->send(new FormResponseMailToUser($this->formResponse, $this->data));
    }
}
