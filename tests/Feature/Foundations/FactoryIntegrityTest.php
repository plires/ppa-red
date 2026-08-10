<?php

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use App\Models\Province;
use App\Models\User;
use App\Models\Zone;

/*
|--------------------------------------------------------------------------
| Integridad de las factories
|--------------------------------------------------------------------------
|
| Toda la suite se apoya en estas factories. Si dejan de ser deterministas o
| de respetar la jerarquía geográfica, los tests de arriba fallan de forma
| intermitente y por la razón equivocada. Estos tests son el piso.
|
*/

it('creates provinces and zones with unique names across many instances', function () {
    $provinces = Province::factory()->count(30)->create();
    $zones = Zone::factory()->count(30)->create();

    expect($provinces->pluck('name')->unique())->toHaveCount(30)
        ->and($zones->pluck('name')->unique())->toHaveCount(30);
});

it('leaves a locality without a partner by default', function () {
    $locality = Locality::factory()->create();

    expect($locality->user_id)->toBeNull();
});

it('keeps the province of a locality consistent with its zone', function () {
    $province = Province::factory()->create();
    $zone = Zone::factory()->forProvince($province)->create();
    $locality = Locality::factory()->inZone($zone)->create();

    expect($locality->zone_id)->toBe($zone->id)
        ->and($locality->province_id)->toBe($province->id);
});

it('derives province, zone and partner of a submission from its locality', function () {
    $owner = partner();
    $locality = localityWithPartner($owner);

    $submission = FormSubmission::factory()->forLocality($locality)->create();

    expect($submission->locality_id)->toBe($locality->id)
        ->and($submission->province_id)->toBe($locality->province_id)
        ->and($submission->zone_id)->toBe($locality->zone_id)
        ->and($submission->user_id)->toBe($owner->id);
});

it('derives the hierarchy even when no locality is given explicitly', function () {
    $submission = FormSubmission::factory()->create();
    $locality = Locality::find($submission->locality_id);

    expect($submission->province_id)->toBe($locality->province_id)
        ->and($submission->zone_id)->toBe($locality->zone_id)
        ->and($submission->user_id)->toBe($locality->user_id);
});

it('attaches a response to the submission it was created for', function () {
    $submission = FormSubmission::factory()->create();

    $response = FormResponse::factory()->forSubmission($submission)->create();

    expect($response->form_submission_id)->toBe($submission->id)
        ->and($response->user_id)->toBe($submission->user_id);
});

it('creates a pending status by default instead of a random one', function () {
    $status = FormSubmissionStatus::factory()->create();

    expect($status->name)->toBe(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
});

it('seeds the six real lifecycle statuses', function () {
    seedStatuses();

    expect(FormSubmissionStatus::count())->toBe(6)
        ->and(FormSubmissionStatus::pluck('name')->all())->toContain(
            FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
            FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
            FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
            FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
        );
});

it('is idempotent when seeding statuses twice', function () {
    seedStatuses();
    seedStatuses();

    expect(FormSubmissionStatus::count())->toBe(6);
});

it('builds admins and partners with the expected roles', function () {
    expect(admin()->role)->toBe(User::ADMIN_USER)
        ->and(admin()->isAdmin())->toBeTrue()
        ->and(partner()->role)->toBe(User::PARTNER_USER)
        ->and(partner()->isPartner())->toBeTrue();
});

it('builds localities with and without an assigned partner', function () {
    expect(localityWithPartner()->user_id)->not->toBeNull()
        ->and(localityWithoutPartner()->user_id)->toBeNull();
});
