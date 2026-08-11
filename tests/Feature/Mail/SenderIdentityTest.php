<?php

use App\Jobs\SendFormResponseEmailToPartner;
use App\Jobs\SendFormResponseEmailToUser;
use App\Jobs\SendFormResponseUnassignedEmailToAdmin;
use App\Jobs\SendFormStatusChange;
use App\Jobs\SendFormSubmissionConfirmationEmail;
use App\Jobs\SendFormSubmissionUnassignedEmailToAdmin;
use App\Jobs\SendPartnerReassignmentEmails;
use App\Jobs\SendPartnerWelcomeEmail;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\TransactionalEmail;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Identidad visible del remitente (From / Reply-To)
|--------------------------------------------------------------------------
|
| El proveedor (Brevo) es siempre el que entrega, pero el destinatario tiene
| que ver de quién es el mensaje: el solicitante ve a su partner asignado y el
| partner ve al solicitante. La casilla del sistema queda sólo para lo que no
| tiene contraparte humana todavía.
|
| Se usa el transporte `array` real en vez de Mail::fake() por dos motivos:
| MailFake no registra los Mail::send() con vista del job de reasignación, y
| sobre todo porque acá interesa la cabecera final que sale del mailer, no lo
| que devuelve envelope().
|
*/

beforeEach(function () {
    seedStatuses();

    config([
        'mail.from.address' => 'info@ppared.com.ar',
        'mail.from.name' => 'PPA RED',
        'mail.force_from_address' => null,
    ]);
});

/**
 * Cabeceras del último mensaje entregado al transporte.
 *
 * @return array{from_address: string, from_name: ?string, reply_to: array<int, string>}
 */
function lastHeaders(): array
{
    $messages = collect(Mail::mailer()->getSymfonyTransport()->messages());

    expect($messages)->not->toBeEmpty();

    $email = $messages->last()->getOriginalMessage();
    $from = $email->getFrom()[0];

    return [
        'from_address' => $from->getAddress(),
        'from_name' => $from->getName(),
        'reply_to' => collect($email->getReplyTo())->map->getAddress()->all(),
    ];
}

/**
 * Cabeceras del mensaje dirigido a un destinatario puntual.
 *
 * @return array{from_address: string, from_name: ?string, reply_to: array<int, string>}
 */
function headersSentTo(string $recipient): array
{
    $match = collect(Mail::mailer()->getSymfonyTransport()->messages())
        ->map->getOriginalMessage()
        ->first(fn ($email) => collect($email->getTo())->contains(fn ($a) => $a->getAddress() === $recipient));

    expect($match)->not->toBeNull("No se envió ningún mensaje a {$recipient}");

    $from = $match->getFrom()[0];

    return [
        'from_address' => $from->getAddress(),
        'from_name' => $from->getName(),
        'reply_to' => collect($match->getReplyTo())->map->getAddress()->all(),
    ];
}

function requesterPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Juana Solicitante',
        'email' => 'juana@example.com',
        'phone' => '11-5555-5555',
        'message' => 'Consulta original.',
    ], $overrides);
}

function statusChangeTemplate(): TransactionalEmail
{
    return new TransactionalEmail([
        'title' => 'test',
        'type' => 'cambio de estado',
        'recipient_type' => 'user',
        'subject' => 'Cambio de estado de tu consulta',
        'body' => 'El estado de tu consulta cambió.',
    ]);
}

/*
|--------------------------------------------------------------------------
| Correos hacia el usuario final
|--------------------------------------------------------------------------
*/

it('sends the submission confirmation with the assigned partner identity', function () {
    $owner = partner(['name' => 'Instalaciones Sur', 'email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    (new SendFormSubmissionConfirmationEmail($submission, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'sur@ppared.com.ar',
        'from_name' => 'Instalaciones Sur',
        'reply_to' => ['sur@ppared.com.ar'],
    ]);
});

it('falls back to the system identity when the submission has no partner yet', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendFormSubmissionConfirmationEmail($submission, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'info@ppared.com.ar',
        'from_name' => 'PPA RED',
        'reply_to' => ['info@ppared.com.ar'],
    ]);
});

it('sends the partner reply to the requester with the assigned partner identity', function () {
    $owner = partner(['name' => 'Instalaciones Sur', 'email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseEmailToUser($response, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'sur@ppared.com.ar',
        'from_name' => 'Instalaciones Sur',
        'reply_to' => ['sur@ppared.com.ar'],
    ]);
});

it('sends a status change to the requester with the assigned partner identity', function () {
    $owner = partner(['name' => 'Instalaciones Sur', 'email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    (new SendFormStatusChange($submission, 'juana@example.com', statusChangeTemplate(), 'user'))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'sur@ppared.com.ar',
        'from_name' => 'Instalaciones Sur',
        'reply_to' => ['sur@ppared.com.ar'],
    ]);
});

it('sends a status change to the requester from the system when no partner is assigned', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendFormStatusChange($submission, 'juana@example.com', statusChangeTemplate(), 'user'))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'info@ppared.com.ar',
        'from_name' => 'PPA RED',
    ]);
});

it('warns the requester about a reassignment with the incoming partner identity', function () {
    $outgoing = partner(['name' => 'Partner Saliente', 'email' => 'saliente@ppared.com.ar']);
    $incoming = partner(['name' => 'Partner Entrante', 'email' => 'entrante@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    (new SendPartnerReassignmentEmails($submission, $outgoing, $incoming, requesterPayload()))->handle();

    expect(headersSentTo('juana@example.com'))->toMatchArray([
        'from_address' => 'entrante@ppared.com.ar',
        'from_name' => 'Partner Entrante',
        'reply_to' => ['entrante@ppared.com.ar'],
    ]);
});

/*
|--------------------------------------------------------------------------
| Correos hacia el partner
|--------------------------------------------------------------------------
*/

it('sends the new message notification to the partner with the requester identity', function () {
    $owner = partner(['email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseEmailToPartner($response, $submission, requesterPayload()))->handle();

    // El nombre y el Reply-To son del solicitante, pero la dirección queda en el
    // dominio propio: firmar como example.com deja que el DMARC ajeno decida.
    expect(lastHeaders())->toMatchArray([
        'from_address' => 'info@ppared.com.ar',
        'from_name' => 'Juana Solicitante',
        'reply_to' => ['juana@example.com'],
    ]);
});

it('falls back to the system identity when the requester left no email', function () {
    $owner = partner(['email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseEmailToPartner($response, $submission, ['name' => 'Sin correo']))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'info@ppared.com.ar',
        'from_name' => 'PPA RED',
    ]);
});

it('keeps the system identity on partner-facing status changes', function () {
    $owner = partner(['name' => 'Instalaciones Sur', 'email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    (new SendFormStatusChange($submission, 'sur@ppared.com.ar', statusChangeTemplate(), 'partner'))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'info@ppared.com.ar',
        'from_name' => 'PPA RED',
    ]);
});

it('keeps the system identity on reassignment notices to partners', function () {
    $outgoing = partner(['email' => 'saliente@ppared.com.ar']);
    $incoming = partner(['email' => 'entrante@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    (new SendPartnerReassignmentEmails($submission, $outgoing, $incoming, requesterPayload()))->handle();

    expect(headersSentTo('saliente@ppared.com.ar'))->toMatchArray(['from_address' => 'info@ppared.com.ar']);
    expect(headersSentTo('entrante@ppared.com.ar'))->toMatchArray(['from_address' => 'info@ppared.com.ar']);
});

it('keeps the system identity on the partner welcome email', function () {
    (new SendPartnerWelcomeEmail(partner(['email' => 'nuevo@ppared.com.ar'])))->handle();

    expect(lastHeaders())->toMatchArray(['from_address' => 'info@ppared.com.ar']);
});

/*
|--------------------------------------------------------------------------
| Correos hacia el administrador (consultas sin partner)
|--------------------------------------------------------------------------
*/

it('keeps the system identity on unassigned submission notices', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendFormSubmissionUnassignedEmailToAdmin($submission, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray(['from_address' => 'info@ppared.com.ar']);
});

it('keeps the system identity on unassigned message notices', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseUnassignedEmailToAdmin($response, $submission, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray(['from_address' => 'info@ppared.com.ar']);
});

/*
|--------------------------------------------------------------------------
| Alineación con el dominio de envío
|--------------------------------------------------------------------------
|
| El From es lo que SPF y DKIM verifican. Firmar como una dirección de un
| dominio ajeno deja la entrega en manos del DMARC de ese dominio, y los
| proveedores grandes publican p=reject: el correo no cae en spam, rebota.
|
*/

it('does not send as a partner created outside the sending domain', function () {
    $owner = partner(['name' => 'Partner Externo', 'email' => 'externo@gmail.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    (new SendFormSubmissionConfirmationEmail($submission, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'info@ppared.com.ar',
        'from_name' => 'Partner Externo',
        'reply_to' => ['externo@gmail.com'],
    ]);
});

it('sends as the requester when they happen to be on the sending domain', function () {
    $owner = partner(['email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseEmailToPartner($response, $submission, requesterPayload([
        'email' => 'juana@PPARED.com.ar',
    ])))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'juana@PPARED.com.ar',
        'from_name' => 'Juana Solicitante',
    ]);
});

it('never sends as an address on a domain that merely ends with the sending domain', function () {
    $owner = partner(['name' => 'Partner Falso', 'email' => 'alguien@notppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    (new SendFormSubmissionConfirmationEmail($submission, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray(['from_address' => 'info@ppared.com.ar']);
});

/*
|--------------------------------------------------------------------------
| Escape hatch del proveedor
|--------------------------------------------------------------------------
*/

it('pins the From address when the provider only accepts one authenticated mailbox', function () {
    config(['mail.force_from_address' => 'info@ppared.com.ar']);

    $owner = partner(['name' => 'Instalaciones Sur', 'email' => 'sur@ppared.com.ar']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    (new SendFormSubmissionConfirmationEmail($submission, requesterPayload()))->handle();

    expect(lastHeaders())->toMatchArray([
        'from_address' => 'info@ppared.com.ar',
        'from_name' => 'Instalaciones Sur',
        'reply_to' => ['sur@ppared.com.ar'],
    ]);
});
