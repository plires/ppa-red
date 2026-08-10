<?php

use App\Jobs\SendFormResponseEmailToUser;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Queue;

/*
|--------------------------------------------------------------------------
| Respuesta del partner desde el dashboard
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    seedStatuses();
    Queue::fake();
});

it('records the partner reply on their own submission', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)
        ->create();

    $this->actingAs($owner)
        ->post(route('form_responses.store'), [
            'message' => 'Le paso el presupuesto por mail.',
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertSessionHasNoErrors();

    $reply = FormResponse::sole();

    expect($reply->message)->toBe('Le paso el presupuesto por mail.')
        ->and($reply->form_submission_id)->toBe($submission->id)
        ->and($reply->user_id)->toBe($owner->id);
});

it('moves the submission to answered by the partner', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)
        ->create();

    $this->actingAs($owner)->post(route('form_responses.store'), [
        'message' => 'Respuesta del partner.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ]);

    expect($submission->fresh()->status->name)
        ->toBe(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
});

it('emails the requester when the partner replies', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs($owner)->post(route('form_responses.store'), [
        'message' => 'Respuesta del partner.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ]);

    Queue::assertPushed(SendFormResponseEmailToUser::class);
});

it('rescues a delayed submission back to answered', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER)
        ->create();

    $this->actingAs($owner)->post(route('form_responses.store'), [
        'message' => 'Perdón la demora.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ]);

    expect($submission->fresh()->status->name)
        ->toBe(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
});

it('stops a partner from replying to a submission that is not theirs', function () {
    $owner = partner();
    $intruder = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs($intruder)
        ->post(route('form_responses.store'), [
            'message' => 'Mensaje intruso.',
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertSessionHasErrors('form_submission_id');

    expect(FormResponse::count())->toBe(0);
});

it('stops the administrator from replying through the partner endpoint', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs(admin())
        ->post(route('form_responses.store'), [
            'message' => 'Mensaje del admin.',
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertForbidden();

    expect(FormResponse::count())->toBe(0);
});

it('rejects an empty reply', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs($owner)
        ->post(route('form_responses.store'), [
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertSessionHasErrors('message');

    expect(FormResponse::count())->toBe(0);
});

it('requires authentication to reply from the dashboard', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->post(route('form_responses.store'), [
        'message' => 'Sin sesión.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ])->assertRedirect(route('login'));

    expect(FormResponse::count())->toBe(0);
});
