<?php

namespace App\Jobs;

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
        public User $outgoingPartner,
        public User $incomingPartner,
        public array $data,
    ) {}

    public function handle(): void
    {
        $submission = $this->formSubmission;
        $outgoing   = $this->outgoingPartner;
        $incoming   = $this->incomingPartner;
        $data       = $this->data;

        // Partner saliente
        Mail::send(
            'emails.reassign_outgoing_partner',
            compact('submission', 'outgoing', 'incoming', 'data'),
            fn ($m) => $m->to($outgoing->email)->subject('Tu consulta asignada fue reasignada — PPA RED')
        );

        // Partner entrante
        Mail::send(
            'emails.reassign_incoming_partner',
            compact('submission', 'outgoing', 'incoming', 'data'),
            fn ($m) => $m->to($incoming->email)->subject('Se te asignó una nueva consulta — PPA RED')
        );

        // Usuario final
        $userEmail = $data['email'] ?? null;
        $userName  = $data['name']  ?? 'Solicitante';
        if ($userEmail) {
            Mail::send(
                'emails.reassign_user',
                compact('submission', 'incoming', 'data', 'userName'),
                fn ($m) => $m->to($userEmail)->subject('Tu consulta fue asignada a un nuevo especialista — PPA RED')
            );
        }
    }
}
