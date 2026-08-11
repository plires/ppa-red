<?php

namespace App\Jobs;

use App\Mail\SenderIdentity;
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
        $outgoing   = $this->outgoingPartner;
        $incoming   = $this->incomingPartner;
        $data       = $this->data;

        // Partner saliente (puede no existir si la consulta nunca tuvo partner asignado)
        if ($outgoing) {
            Mail::send(
                'emails.reassign_outgoing_partner',
                compact('submission', 'outgoing', 'incoming', 'data'),
                fn ($m) => $m->to($outgoing->email)->subject('Tu consulta asignada fue reasignada — PPA RED')
            );
        }

        // Partner entrante
        Mail::send(
            'emails.reassign_incoming_partner',
            compact('submission', 'outgoing', 'incoming', 'data'),
            fn ($m) => $m->to($incoming->email)->subject('Se te asignó una nueva consulta — PPA RED')
        );

        // Usuario final: solo si había un partner previo (reasignación real).
        // Si la consulta nunca tuvo partner asignado, es la primera asignación y no una
        // "reasignación" desde la perspectiva del usuario — no corresponde avisarle.
        $userEmail = $data['email'] ?? null;
        $userName  = $data['name']  ?? 'Solicitante';
        if ($outgoing && $userEmail) {
            // El usuario final ve al partner que queda a cargo, no a la plataforma.
            $sender = SenderIdentity::forPartner($incoming);
            $from = $sender->from();

            Mail::send(
                'emails.reassign_user',
                compact('submission', 'incoming', 'data', 'userName'),
                fn ($m) => $m->to($userEmail)
                    ->from($from->address, $from->name)
                    ->replyTo($sender->address, $sender->name)
                    ->subject('Tu consulta fue asignada a un nuevo especialista — PPA RED')
            );
        }
    }
}
