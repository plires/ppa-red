<?php

use App\Jobs\SendPartnerReassignmentEmails;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Correos de reasignación de partner
|--------------------------------------------------------------------------
|
| Este job usa Mail::send() con una vista en vez de una clase Mailable, y
| MailFake sólo registra Mailables: Mail::fake() lo dejaría pasar sin capturar
| nada y el test daría un falso verde. Por eso se usa el transporte `array`
| real (configurado en TestCase) y se inspeccionan los mensajes enviados.
|
*/

beforeEach(fn () => seedStatuses());

/**
 * Destinatarios de todos los correos enviados en el request actual.
 *
 * @return array<int, string>
 */
function sentRecipients(): array
{
    return collect(Mail::mailer()->getSymfonyTransport()->messages())
        ->flatMap(fn ($message) => collect($message->getOriginalMessage()->getTo())
            ->map(fn ($address) => $address->getAddress()))
        ->all();
}

$requesterData = [
    'name' => 'Juana Solicitante',
    'email' => 'juana@example.com',
];

it('notifies the outgoing partner, the incoming partner and the requester', function () use ($requesterData) {
    $outgoing = partner(['email' => 'saliente@example.com']);
    $incoming = partner(['email' => 'entrante@example.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    (new SendPartnerReassignmentEmails($submission, $outgoing, $incoming, $requesterData))->handle();

    expect(sentRecipients())->toEqualCanonicalizing([
        'saliente@example.com',
        'entrante@example.com',
        'juana@example.com',
    ]);
});

it('only notifies the incoming partner on a first assignment', function () use ($requesterData) {
    $incoming = partner(['email' => 'entrante@example.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendPartnerReassignmentEmails($submission, null, $incoming, $requesterData))->handle();

    expect(sentRecipients())->toBe(['entrante@example.com']);
});

it('does not warn the requester when there was no previous partner', function () use ($requesterData) {
    $incoming = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendPartnerReassignmentEmails($submission, null, $incoming, $requesterData))->handle();

    expect(sentRecipients())->not->toContain('juana@example.com');
});

it('still notifies both partners when the requester left no email', function () {
    $outgoing = partner(['email' => 'saliente@example.com']);
    $incoming = partner(['email' => 'entrante@example.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    (new SendPartnerReassignmentEmails($submission, $outgoing, $incoming, ['name' => 'Sin correo']))->handle();

    expect(sentRecipients())->toEqualCanonicalizing([
        'saliente@example.com',
        'entrante@example.com',
    ]);
});
