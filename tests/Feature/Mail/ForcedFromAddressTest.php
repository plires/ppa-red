<?php

use App\Mail\FormResponseUnassignedMail;
use App\Mail\FormSubmissionConfirmationMail;
use App\Mail\FormSubmissionUnassignedMail;
use App\Mail\PartnerWelcomeMail;
use App\Mail\ReassignIncomingPartnerMail;
use App\Mail\ReassignOutgoingPartnerMail;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| MAIL_FORCE_FROM_ADDRESS fija el remitente de TODO mensaje
|--------------------------------------------------------------------------
|
| El proveedor sólo relaya remitentes verificados. Si MAIL_FROM_ADDRESS apunta
| a una casilla no verificada en el proveedor, el remitente real tiene que ser
| la dirección forzada. Antes, los Mailables que no pasaban por SenderIdentity
| salían con MAIL_FROM_ADDRESS y el proveedor los rechazaba (bienvenida a
| partners, avisos al admin, reasignaciones).
|
*/

beforeEach(function () {
    seedStatuses();

    config([
        'mail.from.address' => 'omarpaz@ppa.ar',
        'mail.from.name' => 'PPA RED',
        'mail.force_from_address' => 'no-reply@ppared.com.ar',
    ]);
});

/**
 * Remitente del último mensaje entregado al transporte `array`.
 *
 * @return array{address: string, name: string}
 */
function forcedTestLastFrom(): array
{
    $messages = collect(Mail::mailer()->getSymfonyTransport()->messages());

    expect($messages)->not->toBeEmpty();

    $from = $messages->last()->getOriginalMessage()->getFrom()[0];

    return ['address' => $from->getAddress(), 'name' => $from->getName()];
}

dataset('mailables that define no sender of their own', [
    'partner welcome' => fn () => new PartnerWelcomeMail(partner(), 'https://ppa.test/reset-password/token'),

    'unassigned submission notice to the admin' => fn () => new FormSubmissionUnassignedMail(
        FormSubmission::factory()->forLocality(localityWithoutPartner())->create(),
        ['name' => 'Juana', 'email' => 'juana@example.com'],
    ),

    'unassigned response notice to the admin' => function () {
        $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();
        $response = FormResponse::factory()->forSubmission($submission)->fromPublicUser()->create();

        return new FormResponseUnassignedMail($response, $submission, ['name' => 'Juana', 'email' => 'juana@example.com']);
    },

    'reassignment notice to the incoming partner' => fn () => new ReassignIncomingPartnerMail(
        FormSubmission::factory()->forLocality(localityWithoutPartner())->create(),
        partner(),
        ['name' => 'Juana', 'email' => 'juana@example.com'],
    ),

    'reassignment notice to the outgoing partner' => fn () => new ReassignOutgoingPartnerMail(
        FormSubmission::factory()->forLocality(localityWithoutPartner())->create(),
        partner(),
        partner(),
        ['name' => 'Juana', 'email' => 'juana@example.com'],
    ),
]);

it('sends from the forced address a mailable that defines no sender', function (callable $build) {
    Mail::to('destino@example.com')->send($build());

    expect(forcedTestLastFrom())->toBe(['address' => 'no-reply@ppared.com.ar', 'name' => 'PPA RED']);
})->with('mailables that define no sender of their own');

it('sends from the forced address a plain message that is not a mailable', function () {
    Mail::raw('Texto plano', fn ($message) => $message->to('destino@example.com')->subject('Prueba'));

    expect(forcedTestLastFrom()['address'])->toBe('no-reply@ppared.com.ar');
});

it('keeps the display name a mailable already chose when it pins the address', function () {
    $owner = partner(['name' => 'Roberto Instalaciones']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    Mail::to('juana@example.com')->send(new FormSubmissionConfirmationMail($submission, ['name' => 'Juana', 'email' => 'juana@example.com']));

    expect(forcedTestLastFrom())->toBe(['address' => 'no-reply@ppared.com.ar', 'name' => 'Roberto Instalaciones']);
});

it('names the variable when the forced address is not a valid email', function () {
    config(['mail.force_from_address' => 'no-reply']);

    expect(fn () => Mail::to('destino@example.com')->send(new PartnerWelcomeMail(partner(), 'https://ppa.test/reset-password/token')))
        ->toThrow(InvalidArgumentException::class, 'MAIL_FORCE_FROM_ADDRESS');
});

it('falls back to a placeholder when neither the admin nor the sender address is defined', function () {
    $keys = ['MAIL_ADMIN_ADDRESS', 'MAIL_FROM_ADDRESS'];
    $previous = collect($keys)->mapWithKeys(fn ($key) => [$key => [$_ENV[$key] ?? null, $_SERVER[$key] ?? null]]);

    try {
        foreach ($keys as $key) {
            $_ENV[$key] = $_SERVER[$key] = '';
        }

        $config = require config_path('mail.php');
    } finally {
        foreach ($previous as $key => [$env, $server]) {
            $env === null ? Arr::forget($_ENV, $key) : $_ENV[$key] = $env;
            $server === null ? Arr::forget($_SERVER, $key) : $_SERVER[$key] = $server;
        }
    }

    expect($config['admin_address'])->toBe('hello@example.com');
});

it('leaves the default sender untouched when no address is forced', function () {
    config(['mail.force_from_address' => null]);

    Mail::to('destino@example.com')->send(new PartnerWelcomeMail(partner(), 'https://ppa.test/reset-password/token'));

    expect(forcedTestLastFrom()['address'])->toBe('omarpaz@ppa.ar');
});
