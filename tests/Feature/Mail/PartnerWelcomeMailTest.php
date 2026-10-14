<?php

use App\Mail\PartnerWelcomeMail;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Correo de bienvenida al partner
|--------------------------------------------------------------------------
|
| Sale desde una casilla que no recibe respuestas, así que el correo tiene
| que decirlo y mandar las dudas al administrador, que además es su Reply-To.
|
*/

beforeEach(function () {
    config([
        'mail.from.address' => 'info@ppared.com.ar',
        'mail.force_from_address' => 'no-reply@ppared.com.ar',
        'mail.admin_address' => 'admin@ppa.ar',
    ]);
});

function welcomeMailUrl(): string
{
    return 'https://ppa.test/reset-password/abc123?email=nuevo%40ppared.com.ar';
}

function renderedWelcomeMail(): string
{
    return (new PartnerWelcomeMail(partner(['name' => 'Nuevo Partner']), welcomeMailUrl()))->render();
}

it('replies to the administrator instead of the no-reply mailbox', function () {
    Mail::to('nuevo@ppared.com.ar')->send(new PartnerWelcomeMail(partner(), welcomeMailUrl()));

    $email = collect(Mail::mailer()->getSymfonyTransport()->messages())->last()->getOriginalMessage();

    expect(collect($email->getReplyTo())->map->getAddress()->all())->toBe(['admin@ppa.ar']);
});

it('tells the partner not to answer the no-reply mailbox and links the administrator without printing the address', function () {
    $html = renderedWelcomeMail();

    expect($html)
        ->toContain('no-reply@ppared.com.ar')
        ->toContain('href="mailto:admin@ppa.ar"')
        ->and(strip_tags($html))->not->toContain('admin@ppa.ar');
});

it('offers the activation link as plain text in case the button fails', function () {
    $html = renderedWelcomeMail();

    expect(substr_count($html, welcomeMailUrl()))->toBeGreaterThanOrEqual(2)
        ->and($html)->toContain('copiá y pegá');
});

it('lists the steps in order, with the choose-your-own-password step and without the profile one', function () {
    $html = renderedWelcomeMail();

    expect($html)
        ->not->toContain('Completá los datos de tu perfil')
        ->and(strpos($html, 'desde el botón de arriba'))->toBeLessThan(strpos($html, 'Elegí tu propia contraseña'))
        ->and(strpos($html, 'Elegí tu propia contraseña'))->toBeLessThan(strpos($html, 'Iniciá sesión'));
});
