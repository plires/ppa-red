<?php

use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;

/*
|--------------------------------------------------------------------------
| Cierre manual de una consulta por el partner
|--------------------------------------------------------------------------
*/

beforeEach(fn () => seedStatuses());

it('closes the submission with the reason given by the partner', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)
        ->create();

    $this->actingAs($owner)
        ->put(route('form_submissions.update', $submission), [
            'closure_reason' => 'El cliente ya contrató el servicio.',
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertSessionHasNoErrors();

    $submission = $submission->fresh();

    expect($submission->status->name)->toBe(FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER)
        ->and($submission->closure_reason)->toBe('El cliente ya contrató el servicio.');
});

it('rejects a closure reason shorter than ten characters', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)
        ->create();

    $this->actingAs($owner)
        ->put(route('form_submissions.update', $submission), [
            'closure_reason' => 'corto',
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertSessionHasErrors('closure_reason');

    expect($submission->fresh()->status->name)
        ->toBe(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
});

it('requires a closure reason', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)
        ->create();

    $this->actingAs($owner)
        ->put(route('form_submissions.update', $submission), [
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertSessionHasErrors('closure_reason');

    expect($submission->fresh()->closure_reason)->toBeNull();
});

it('stops a partner from closing a submission that is not theirs', function () {
    $owner = partner();
    $intruder = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)
        ->create();

    $this->actingAs($intruder)
        ->put(route('form_submissions.update', $submission), [
            'closure_reason' => 'Cierre no autorizado por un tercero.',
            'form_submission_id' => $submission->id,
            'user_id' => $owner->id,
        ])->assertSessionHasErrors('form_submission_id');

    expect($submission->fresh()->status->name)
        ->toBe(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
});

it('requires authentication to close a submission', function () {
    $owner = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)
        ->create();

    $this->put(route('form_submissions.update', $submission), [
        'closure_reason' => 'Cierre sin sesión iniciada.',
        'form_submission_id' => $submission->id,
        'user_id' => $owner->id,
    ])->assertRedirect(route('login'));

    expect($submission->fresh()->closure_reason)->toBeNull();
});
