<?php

use App\Jobs\SendFormResponseEmailToPartner;
use App\Jobs\SendFormResponseUnassignedEmailToAdmin;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Queue;

/*
|--------------------------------------------------------------------------
| Respuestas del usuario final desde la pantalla pública
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    seedStatuses();
    Queue::fake();
});

it('lets the anonymous requester reply to their own submission', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)
        ->create();

    $this->post(route('public.form_responses.store'), [
        'message' => 'Gracias, quedo a la espera.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ])->assertSessionHasNoErrors();

    $reply = FormResponse::sole();

    expect($reply->message)->toBe('Gracias, quedo a la espera.')
        ->and($reply->form_submission_id)->toBe($submission->id)
        ->and((bool) $reply->is_system)->toBeFalse();
});

it('moves the submission back to pending a partner reply', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)
        ->create();

    $this->post(route('public.form_responses.store'), [
        'message' => 'Sigo esperando.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ]);

    expect($submission->fresh()->status->name)
        ->toBe(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
});

it('notifies the assigned partner of the new message', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->post(route('public.form_responses.store'), [
        'message' => 'Nuevo mensaje.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ]);

    Queue::assertPushed(SendFormResponseEmailToPartner::class);
    Queue::assertNotPushed(SendFormResponseUnassignedEmailToAdmin::class);
});

it('notifies the administrator when the submission has no partner', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    $this->post(route('public.form_responses.store'), [
        'message' => 'Nuevo mensaje.',
        'form_submission_id' => $submission->id,
        'user_id' => null,
    ])->assertSessionHasNoErrors();

    Queue::assertPushed(SendFormResponseUnassignedEmailToAdmin::class);
    Queue::assertNotPushed(SendFormResponseEmailToPartner::class);
});

it('forbids an authenticated user from posting through the public endpoint', function (callable $userBuilder) {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs($userBuilder())
        ->post(route('public.form_responses.store'), [
            'message' => 'Intento desde el dashboard.',
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertForbidden();

    expect(FormResponse::count())->toBe(0);
})->with([
    'admin' => [fn () => admin()],
    'partner' => [fn () => partner()],
]);

it('rejects a reply aimed at a submission owned by another partner', function () {
    $owner = partner();
    $someoneElse = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->post(route('public.form_responses.store'), [
        'message' => 'Mensaje cruzado.',
        'form_submission_id' => $submission->id,
        'user_id' => $someoneElse->id,
    ])->assertSessionHasErrors('user_id');

    expect(FormResponse::count())->toBe(0);
});

it('rejects a reply without a message', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->post(route('public.form_responses.store'), [
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ])->assertSessionHasErrors('message');

    expect(FormResponse::count())->toBe(0);
});

it('rejects a reply for a submission that does not exist', function () {
    $this->post(route('public.form_responses.store'), [
        'message' => 'Mensaje huérfano.',
        'form_submission_id' => 999999,
        'user_id' => null,
    ])->assertSessionHasErrors('form_submission_id');

    expect(FormResponse::count())->toBe(0);
});
