<?php

namespace App\Jobs;

use App\Mail\ReassignIncomingPartnerMail;
use App\Mail\ReassignOutgoingPartnerMail;
use App\Mail\ReassignUserMail;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPartnerReassignmentEmails implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public FormSubmission $formSubmission,
        public ?User $outgoingPartner,
        public User $incomingPartner,
        public array $data,
    ) {}

    public function handle(): void
    {
        $submission = $this->formSubmission;
        $outgoing = $this->outgoingPartner;
        $incoming = $this->incomingPartner;
        $data = $this->data;

        // Partner saliente (puede no existir si la consulta nunca tuvo partner asignado)
        if ($outgoing) {
            Mail::to($outgoing->email)->send(new ReassignOutgoingPartnerMail($submission, $outgoing, $incoming, $data));
        }

        // Partner entrante
        Mail::to($incoming->email)->send(new ReassignIncomingPartnerMail($submission, $incoming, $data));

        // Usuario final: solo si había un partner previo (reasignación real).
        // Si la consulta nunca tuvo partner asignado, es la primera asignación y no una
        // "reasignación" desde la perspectiva del usuario — no corresponde avisarle.
        $userEmail = $data['email'] ?? null;
        $userName = $data['name'] ?? 'Solicitante';
        if ($outgoing && $userEmail) {
            Mail::to($userEmail)->send(new ReassignUserMail($submission, $incoming, $userName));
        }
    }
}
