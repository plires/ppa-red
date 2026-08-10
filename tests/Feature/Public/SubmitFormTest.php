<?php

use App\Jobs\SendFormResponseEmailToPartner;
use App\Jobs\SendFormSubmissionConfirmationEmail;
use App\Jobs\SendFormSubmissionUnassignedEmailToAdmin;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use App\Models\Province;
use App\Models\Zone;
use Illuminate\Support\Facades\Queue;

/*
|--------------------------------------------------------------------------
| Alta pública de una consulta desde la landing
|--------------------------------------------------------------------------
|
| Es la puerta de entrada del negocio: si esto se rompe no entra un solo lead.
|
*/

beforeEach(function () {
    seedStatuses();
    Queue::fake();
});

it('creates a submission assigned to the partner of the locality', function () {
    $owner = partner();
    $locality = localityWithPartner($owner);

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));

    $submission = FormSubmission::sole();

    expect($submission->user_id)->toBe($owner->id)
        ->and($submission->locality_id)->toBe($locality->id)
        ->and($submission->province_id)->toBe($locality->province_id)
        ->and($submission->zone_id)->toBe($locality->zone_id);
});

it('stores the requester details as json in the data column', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'name' => 'Juana Solicitante',
        'email' => 'juana@example.com',
        'phone' => '11-5555-5555',
    ]));

    expect(json_decode(FormSubmission::sole()->data, true))
        ->toMatchArray([
            'name' => 'Juana Solicitante',
            'email' => 'juana@example.com',
            'phone' => '11-5555-5555',
        ]);
});

it('opens the submission pending a partner reply', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));

    expect(FormSubmission::sole()->status->name)
        ->toBe(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
});

it('seeds the conversation with the requester message as a non system response', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'message' => 'Necesito un presupuesto.',
    ]));

    $response = FormResponse::sole();

    expect($response->message)->toBe('Necesito un presupuesto.')
        ->and($response->user_id)->toBeNull()
        ->and((bool) $response->is_system)->toBeFalse()
        ->and($response->form_submission_id)->toBe(FormSubmission::sole()->id);
});

it('notifies the assigned partner', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));

    Queue::assertPushed(SendFormResponseEmailToPartner::class);
    Queue::assertNotPushed(SendFormSubmissionUnassignedEmailToAdmin::class);
});

it('notifies the administrator when the locality has no partner assigned', function () {
    $locality = localityWithoutPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));

    Queue::assertPushed(SendFormSubmissionUnassignedEmailToAdmin::class);
    Queue::assertNotPushed(SendFormResponseEmailToPartner::class);

    expect(FormSubmission::sole()->user_id)->toBeNull();
});

it('always confirms receipt to the requester', function (Locality $locality) {
    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));

    Queue::assertPushed(SendFormSubmissionConfirmationEmail::class);
})->with([
    'with partner' => [fn () => localityWithPartner()],
    'without partner' => [fn () => localityWithoutPartner()],
]);

it('redirects to the token page of the new submission', function () {
    $locality = localityWithPartner();

    $response = $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));

    $response->assertRedirect(
        route('public.form_submission.show', FormSubmission::sole()->secure_token)
    );
});

it('generates a unique secure token for every submission', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));
    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality));

    $tokens = FormSubmission::pluck('secure_token');

    expect($tokens)->toHaveCount(2)
        ->and($tokens->unique())->toHaveCount(2)
        ->and(strlen($tokens->first()))->toBe(32);
});

it('never exposes the secure token when the model is serialized', function () {
    $locality = localityWithPartner();
    $submission = FormSubmission::factory()->forLocality($locality)->create();

    expect($submission->toArray())->not->toHaveKey('secure_token');
});

it('accepts a submission whose locality has no zone', function () {
    $province = Province::factory()->create();
    $locality = Locality::factory()->forProvince($province)->forPartner(partner())->create();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality))
        ->assertSessionHasNoErrors();

    expect(FormSubmission::sole()->zone_id)->toBeNull();
});

it('rejects a submission with missing required fields', function (string $field) {
    $locality = localityWithPartner();
    $payload = publicSubmissionPayload($locality);
    unset($payload[$field]);

    $this->post(route('public.form_submission.store'), $payload)
        ->assertSessionHasErrors($field);

    expect(FormSubmission::count())->toBe(0);
})->with(['name', 'email', 'phone', 'message', 'province_id', 'locality_id']);

it('rejects a submission with an invalid email', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'email' => 'no-es-un-email',
    ]))->assertSessionHasErrors('email');

    expect(FormSubmission::count())->toBe(0);
});

it('rejects a submission pointing at a province or locality that does not exist', function (string $field) {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        $field => 999999,
    ]))->assertSessionHasErrors($field);

    expect(FormSubmission::count())->toBe(0);
})->with(['province_id', 'locality_id']);

it('redirects a bare GET on the submission endpoint back to the landing', function () {
    $this->get('/public/form_submission')->assertRedirect('/');
});

/*
|--------------------------------------------------------------------------
| Consistencia geográfica
|--------------------------------------------------------------------------
|
| El formulario de la landing arma los selects encadenados, pero nada impide un
| POST hecho a mano. Sin estas reglas se creaban consultas con provincia y
| localidad de jerarquías distintas, que después rompen los reportes y dejan al
| partner equivocado a cargo.
|
*/

it('rejects a locality that does not belong to the submitted province', function () {
    $locality = localityWithPartner();
    $otherProvince = Province::factory()->create();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'province_id' => $otherProvince->id,
    ]))->assertSessionHasErrors('locality_id');

    expect(FormSubmission::count())->toBe(0);
});

it('rejects a locality that does not belong to the submitted zone', function () {
    $locality = localityWithPartner();
    $otherZone = Zone::factory()->forProvince(Province::find($locality->province_id))->create();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'zone_id' => $otherZone->id,
    ]))->assertSessionHasErrors('zone_id');

    expect(FormSubmission::count())->toBe(0);
});

it('rejects a zone that does not belong to the submitted province', function () {
    $locality = localityWithPartner();
    $foreignZone = Zone::factory()->create();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'zone_id' => $foreignZone->id,
    ]))->assertSessionHasErrors();

    expect(FormSubmission::count())->toBe(0);
});

it('accepts a consistent hierarchy', function () {
    $locality = localityWithPartner();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality))
        ->assertSessionHasNoErrors();

    expect(FormSubmission::count())->toBe(1);
});

it('rejects a zone sent for a locality that has none', function () {
    $province = Province::factory()->create();
    $locality = Locality::factory()->forProvince($province)->forPartner(partner())->create();
    $zone = Zone::factory()->forProvince($province)->create();

    $this->post(route('public.form_submission.store'), publicSubmissionPayload($locality, [
        'zone_id' => $zone->id,
    ]))->assertSessionHasErrors('zone_id');

    expect(FormSubmission::count())->toBe(0);
});
