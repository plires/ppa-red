<?php

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use Inertia\Testing\AssertableInertia as Assert;

/*
|--------------------------------------------------------------------------
| Pantalla pública de seguimiento (acceso por token)
|--------------------------------------------------------------------------
|
| El token es la única credencial del usuario final. Un token inválido no puede
| romper la página ni filtrar información.
|
*/

beforeEach(fn () => seedStatuses());

it('renders the submission for a valid token', function () {
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner())
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)
        ->create();

    $this->get(route('public.form_submission.show', $submission->secure_token))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('PublicForms/Show')
            ->where('formSubmission.id', $submission->id)
            ->where('isClosed', false)
            ->where('closedByPartner', false)
        );
});

it('exposes the requester details decoded for the view', function () {
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner())
        ->create(['data' => json_encode([
            'name' => 'Juana Solicitante',
            'email' => 'juana@example.com',
            'phone' => '11-5555-5555',
            'message' => 'Consulta original.',
        ])]);

    $this->get(route('public.form_submission.show', $submission->secure_token))
        ->assertInertia(fn (Assert $page) => $page
            ->where('formData.name', 'Juana Solicitante')
            ->where('formData.email', 'juana@example.com')
        );
});

it('includes the conversation messages', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithPartner())->create();
    FormResponse::factory()->forSubmission($submission)->create(['message' => 'Primer mensaje']);
    FormResponse::factory()->forSubmission($submission)->create(['message' => 'Segundo mensaje']);

    $this->get(route('public.form_submission.show', $submission->secure_token))
        ->assertInertia(fn (Assert $page) => $page->has('formSubmission.form_responses', 2));
});

it('renders a not found page for an unknown token instead of failing', function () {
    $this->get(route('public.form_submission.show', 'token-que-no-existe'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('PublicForms/NotFound'));
});

it('does not leak another submission when the token does not match', function () {
    $mine = FormSubmission::factory()->forLocality(localityWithPartner())->create();
    FormSubmission::factory()->forLocality(localityWithPartner())->create();

    $this->get(route('public.form_submission.show', $mine->secure_token))
        ->assertInertia(fn (Assert $page) => $page->where('formSubmission.id', $mine->id));
});

it('flags a submission closed by the partner', function () {
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner())
        ->withStatus(FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER)
        ->create();

    $this->get(route('public.form_submission.show', $submission->secure_token))
        ->assertInertia(fn (Assert $page) => $page
            ->where('isClosed', true)
            ->where('closedByPartner', true)
        );
});

it('flags an automatically closed submission as closed but not closed by the partner', function (string $status) {
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner())
        ->withStatus($status)
        ->create();

    $this->get(route('public.form_submission.show', $submission->secure_token))
        ->assertInertia(fn (Assert $page) => $page
            ->where('isClosed', true)
            ->where('closedByPartner', false)
        );
})->with([
    FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
    FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
]);

it('keeps an open submission unflagged', function (string $status) {
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner())
        ->withStatus($status)
        ->create();

    $this->get(route('public.form_submission.show', $submission->secure_token))
        ->assertInertia(fn (Assert $page) => $page->where('isClosed', false));
})->with([
    FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
    FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
    FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
]);

it('renders a submission whose locality has no partner assigned', function () {
    $submission = FormSubmission::factory()
        ->forLocality(localityWithoutPartner())
        ->create();

    $this->get(route('public.form_submission.show', $submission->secure_token))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('PublicForms/Show'));
});
