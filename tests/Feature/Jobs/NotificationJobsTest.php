<?php

use App\Jobs\SendFormResponseEmailToPartner;
use App\Jobs\SendFormResponseEmailToUser;
use App\Jobs\SendFormResponseUnassignedEmailToAdmin;
use App\Jobs\SendFormSubmissionConfirmationEmail;
use App\Jobs\SendFormSubmissionUnassignedEmailToAdmin;
use App\Jobs\SendPartnerWelcomeEmail;
use App\Mail\FormResponseMailToPartner;
use App\Mail\FormResponseMailToUser;
use App\Mail\FormResponseUnassignedMail;
use App\Mail\FormSubmissionConfirmationMail;
use App\Mail\FormSubmissionUnassignedMail;
use App\Mail\PartnerWelcomeMail;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Jobs de notificación
|--------------------------------------------------------------------------
|
| Se ejecuta handle() directamente: acá no importa que el job se despache (eso
| ya se verifica en los tests de los controladores), sino que el mail salga y
| que salga al destinatario correcto. Un mail al destinatario equivocado filtra
| datos del solicitante a un partner que no corresponde.
|
*/

beforeEach(function () {
    seedStatuses();
    Mail::fake();
});

function requesterData(): array
{
    return [
        'name' => 'Juana Solicitante',
        'email' => 'juana@example.com',
        'phone' => '11-5555-5555',
        'message' => 'Consulta original.',
    ];
}

it('sends the new message notification to the assigned partner', function () {
    $owner = partner(['email' => 'partner@example.com']);
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseEmailToPartner($response, $submission, requesterData()))->handle();

    Mail::assertSent(FormResponseMailToPartner::class, fn ($mail) => $mail->hasTo('partner@example.com'));
});

it('does not send anything when the submission has no partner', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseEmailToPartner($response, $submission, requesterData()))->handle();

    Mail::assertNothingSent();
});

it('sends the partner reply notification to the requester', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseEmailToUser($response, requesterData()))->handle();

    Mail::assertSent(FormResponseMailToUser::class, fn ($mail) => $mail->hasTo('juana@example.com'));
});

it('routes an unassigned new message to the administrator mailbox', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();
    $response = FormResponse::factory()->forSubmission($submission)->create();

    (new SendFormResponseUnassignedEmailToAdmin($response, $submission, requesterData()))->handle();

    Mail::assertSent(
        FormResponseUnassignedMail::class,
        fn ($mail) => $mail->hasTo(config('mail.from.address'))
    );
});

it('routes an unassigned new submission to the administrator mailbox', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    (new SendFormSubmissionUnassignedEmailToAdmin($submission, requesterData()))->handle();

    Mail::assertSent(
        FormSubmissionUnassignedMail::class,
        fn ($mail) => $mail->hasTo(config('mail.from.address'))
    );
});

it('confirms the submission to the requester', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithPartner())->create();

    (new SendFormSubmissionConfirmationEmail($submission, requesterData()))->handle();

    Mail::assertSent(FormSubmissionConfirmationMail::class, fn ($mail) => $mail->hasTo('juana@example.com'));
});

it('skips the confirmation when the requester left no email', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithPartner())->create();

    (new SendFormSubmissionConfirmationEmail($submission, ['name' => 'Sin correo']))->handle();

    Mail::assertNothingSent();
});

it('welcomes a new partner with a link to set their password', function () {
    $newPartner = partner(['email' => 'nuevo@example.com']);

    (new SendPartnerWelcomeEmail($newPartner))->handle();

    Mail::assertSent(PartnerWelcomeMail::class, fn ($mail) => $mail->hasTo('nuevo@example.com'));
});
