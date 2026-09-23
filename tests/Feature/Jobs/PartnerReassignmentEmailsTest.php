<?php

use App\Jobs\SendPartnerReassignmentEmails;
use App\Mail\ReassignIncomingPartnerMail;
use App\Mail\ReassignOutgoingPartnerMail;
use App\Mail\ReassignUserMail;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Correos de reasignación de partner
|--------------------------------------------------------------------------
|
| El job usa Mailables (ReassignOutgoingPartnerMail, ReassignIncomingPartnerMail,
| ReassignUserMail), así que Mail::fake() los captura sin enviar nada de verdad.
|
*/

beforeEach(function () {
    seedStatuses();
    Mail::fake();
});

$requesterData = [
    'name' => 'Juana Solicitante',
    'email' => 'juana@example.com',
];

it('notifies the outgoing partner, the incoming partner and the requester', function () use ($requesterData) {
    $outgoing = partner(['email' => 'saliente@example.com']);
    $incoming = partner(['email' => 'entrante@example.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    (new SendPartnerReassignmentEmails($submission, $outgoing, $incoming, $requesterData))->handle();

    Mail::assertSent(ReassignOutgoingPartnerMail::class, fn ($mail) => $mail->hasTo('saliente@example.com'));
    Mail::assertSent(ReassignIncomingPartnerMail::class, fn ($mail) => $mail->hasTo('entrante@example.com'));
    Mail::assertSent(ReassignUserMail::class, fn ($mail) => $mail->hasTo('juana@example.com'));
});

it('only notifies the incoming partner on a first assignment', function () use ($requesterData) {
    $incoming = partner(['email' => 'entrante@example.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendPartnerReassignmentEmails($submission, null, $incoming, $requesterData))->handle();

    Mail::assertSent(ReassignIncomingPartnerMail::class, fn ($mail) => $mail->hasTo('entrante@example.com'));
    Mail::assertNotSent(ReassignOutgoingPartnerMail::class);
    Mail::assertNotSent(ReassignUserMail::class);
});

it('does not warn the requester when there was no previous partner', function () use ($requesterData) {
    $incoming = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendPartnerReassignmentEmails($submission, null, $incoming, $requesterData))->handle();

    Mail::assertNotSent(ReassignUserMail::class);
});

it('still notifies both partners when the requester left no email', function () {
    $outgoing = partner(['email' => 'saliente@example.com']);
    $incoming = partner(['email' => 'entrante@example.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    (new SendPartnerReassignmentEmails($submission, $outgoing, $incoming, ['name' => 'Sin correo']))->handle();

    Mail::assertSent(ReassignOutgoingPartnerMail::class, fn ($mail) => $mail->hasTo('saliente@example.com'));
    Mail::assertSent(ReassignIncomingPartnerMail::class, fn ($mail) => $mail->hasTo('entrante@example.com'));
    Mail::assertNotSent(ReassignUserMail::class);
});
