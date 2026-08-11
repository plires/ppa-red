<?php

namespace App\Jobs;

use App\Mail\FormResponseMailToPartner;
use App\Models\FormResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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
        $partner = $this->formSubmission->user;
        if (! $partner) {
            return;
        }

        // user() es withTrashed() para conservar el historial, así que un
        // partner eliminado llega hasta acá. Escribirle sería mandar la consulta
        // a una casilla que ya nadie mira.
        if ($partner->trashed()) {
            Log::warning('Consulta asignada a un partner eliminado: no se notificó a nadie.', [
                'form_submission_id' => $this->formSubmission->id,
                'partner_id' => $partner->id,
            ]);

            return;
        }

        Mail::to($partner->email)->send(new FormResponseMailToPartner($this->formResponse, $this->formSubmission, $this->data));
    }
}
