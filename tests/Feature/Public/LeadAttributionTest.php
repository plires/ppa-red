<?php

use App\Models\FormSubmission;
use Illuminate\Support\Facades\Queue;

/*
|--------------------------------------------------------------------------
| Origen de marketing de cada lead
|--------------------------------------------------------------------------
|
| La atribución se guarda saneada y nunca puede frenar el alta de un lead.
|
*/

beforeEach(function () {
    seedStatuses();
    Queue::fake();
});

it('persists click ids, utms, landing url and referrer', function () {
    $locality = localityWithPartner();

    $attribution = [
        'gclid' => 'Cj0KCQ-test-gclid',
        'gbraid' => 'gbraid-test',
        'wbraid' => 'wbraid-test',
        'fbclid' => 'IwAR-test-fbclid',
        'utm_source' => 'google',
        'utm_medium' => 'cpc',
        'utm_campaign' => 'portones-bsas',
        'utm_term' => 'porton automatico',
        'utm_content' => 'anuncio-a',
        'landing_url' => 'https://ppared.com.ar/?utm_source=google&gclid=Cj0KCQ-test-gclid',
        'referrer' => 'https://www.google.com/',
    ];

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, $attribution))
        ->assertSessionHasNoErrors();

    expect(FormSubmission::sole()->only(array_keys($attribution)))->toBe($attribution);
});

it('creates the lead with null attribution when none is sent', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality))
        ->assertSessionHasNoErrors();

    $submission = FormSubmission::sole();

    foreach (array_keys(FormSubmission::ATTRIBUTION_FIELDS) as $field) {
        expect($submission->{$field})->toBeNull();
    }
});

it('truncates oversized attribution values instead of rejecting the lead', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'utm_campaign' => str_repeat('a', 300),
        'landing_url' => 'https://ppared.com.ar/?q='.str_repeat('b', 3000),
    ]))->assertSessionHasNoErrors();

    $submission = FormSubmission::sole();

    expect(mb_strlen($submission->utm_campaign))->toBe(255)
        ->and(mb_strlen($submission->landing_url))->toBe(2048);
});

it('ignores non-string attribution values and still creates the lead', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'gclid' => ['malicioso'],
        'utm_source' => '   ',
    ]))->assertSessionHasNoErrors();

    $submission = FormSubmission::sole();

    expect($submission->gclid)->toBeNull()
        ->and($submission->utm_source)->toBeNull();
});
