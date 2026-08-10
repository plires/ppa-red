<?php

use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;

/*
|--------------------------------------------------------------------------
| Reportes
|--------------------------------------------------------------------------
|
| El reporte por partner ya rompió en producción con un 500 cuando una consulta
| quedó con user_id null (columna nullable con onDelete('set null')). Ese caso
| tiene test propio para que no vuelva.
|
*/

beforeEach(fn () => seedStatuses());

it('counts submissions grouped by partner', function () {
    $first = partner(['name' => 'Partner Uno']);
    $second = partner(['name' => 'Partner Dos']);

    FormSubmission::factory()->count(3)->forPartner($first)->create();
    FormSubmission::factory()->count(2)->forPartner($second)->create();

    $response = $this->actingAs(admin())
        ->getJson(route('reports.form_submissions_by_partner'))
        ->assertOk();

    expect($response->json('labels'))->toEqualCanonicalizing(['Partner Uno', 'Partner Dos'])
        ->and(array_sum($response->json('data')))->toBe(5);
});

it('does not break when a submission lost its partner', function () {
    $owner = partner(['name' => 'Partner Uno']);
    FormSubmission::factory()->count(2)->forPartner($owner)->create();
    FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    $response = $this->actingAs(admin())
        ->getJson(route('reports.form_submissions_by_partner'))
        ->assertOk();

    expect($response->json('labels'))->toBe(['Partner Uno'])
        ->and($response->json('data'))->toBe([2]);
});

it('does not break when the partner of a submission was deleted', function () {
    $owner = partner(['name' => 'Partner Borrado']);
    FormSubmission::factory()->count(2)->forPartner($owner)->create();

    // La FK es onDelete('set null'), pero el partner usa soft deletes: la
    // consulta conserva el user_id y la relación lo trae withTrashed().
    $owner->delete();

    $this->actingAs(admin())
        ->getJson(route('reports.form_submissions_by_partner'))
        ->assertOk();
});

it('filters the partner report by date range', function () {
    $owner = partner();
    FormSubmission::factory()->forPartner($owner)->create(['created_at' => now()->subMonths(2)]);
    FormSubmission::factory()->count(2)->forPartner($owner)->create(['created_at' => now()]);

    $response = $this->actingAs(admin())->getJson(route('reports.form_submissions_by_partner', [
        'start_date' => now()->subDays(7)->toDateTimeString(),
        'end_date' => now()->addDay()->toDateTimeString(),
    ]))->assertOk();

    expect($response->json('data'))->toBe([2]);
});

it('filters the partner report by partner', function () {
    $first = partner(['name' => 'Partner Uno']);
    $second = partner(['name' => 'Partner Dos']);
    FormSubmission::factory()->count(3)->forPartner($first)->create();
    FormSubmission::factory()->count(2)->forPartner($second)->create();

    $response = $this->actingAs(admin())
        ->getJson(route('reports.form_submissions_by_partner', ['partner_id' => $second->id]))
        ->assertOk();

    expect($response->json('labels'))->toBe(['Partner Dos'])
        ->and($response->json('data'))->toBe([2]);
});

it('returns an empty report when there is nothing to count', function () {
    $response = $this->actingAs(admin())
        ->getJson(route('reports.form_submissions_by_partner'))
        ->assertOk();

    expect($response->json('labels'))->toBe([])
        ->and($response->json('data'))->toBe([]);
});

it('lists the submissions of a partner in a date range', function () {
    $owner = partner();
    FormSubmission::factory()->forPartner($owner)->create([
        'data' => json_encode(['name' => 'Juana Solicitante']),
    ]);

    $response = $this->actingAs(admin())->getJson(route('reportes.form_submissionsDetail', [
        'user_id' => $owner->id,
        'start' => now()->subDay()->toDateString(),
        'end' => now()->addDay()->toDateString(),
    ]))->assertOk();

    expect($response->json())->toHaveCount(1)
        ->and($response->json('0.end_user_name'))->toBe('Juana Solicitante');
});

it('counts submissions grouped by status', function () {
    $owner = partner();
    FormSubmission::factory()->count(2)->forPartner($owner)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();
    FormSubmission::factory()->forPartner($owner)
        ->withStatus(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER)->create();

    $response = $this->actingAs(admin())
        ->getJson(route('reports.form_status_chart'))
        ->assertOk();

    expect($response->json('labels'))->toEqualCanonicalizing([
        FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
        FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
    ])->and(array_sum($response->json('data')))->toBe(3);
});

it('lists the submissions behind a status slice', function () {
    $owner = partner();
    $statusId = statusId(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
    FormSubmission::factory()->forPartner($owner)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();

    $response = $this->actingAs(admin())->getJson(route('reports.form_status_chart_detail', [
        'user_id' => $owner->id,
        'status_id' => $statusId,
        'start' => now()->subDay()->toDateString(),
        'end' => now()->addDay()->toDateString(),
    ]))->assertOk();

    expect($response->json())->toHaveCount(1);
});

it('accepts the literal null partner to slice a status across every partner', function () {
    $first = partner();
    $second = partner();
    $statusId = statusId(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);

    FormSubmission::factory()->forPartner($first)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();
    FormSubmission::factory()->forPartner($second)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();

    $response = $this->actingAs(admin())->getJson(route('reports.form_status_chart_detail', [
        'user_id' => 'null',
        'status_id' => $statusId,
        'start' => now()->subDay()->toDateString(),
        'end' => now()->addDay()->toDateString(),
    ]))->assertOk();

    expect($response->json())->toHaveCount(2);
});

/*
|--------------------------------------------------------------------------
| Aislamiento de los reportes compartidos con el partner
|--------------------------------------------------------------------------
|
| status_chart, form_status_chart y form_status_chart_detail están fuera del
| grupo AdminMiddleware porque el partner también los usa. Eso obliga a filtrar
| por el partner autenticado dentro del controlador: sin ese filtro, un partner
| leía el detalle de las consultas de otro, con nombre del cliente final.
|
*/

it('gives a partner only their own status totals', function () {
    $owner = partner();
    $other = partner();

    FormSubmission::factory()->forPartner($owner)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();
    FormSubmission::factory()->count(4)->forPartner($other)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();

    $response = $this->actingAs($owner)
        ->getJson(route('reports.form_status_chart'))
        ->assertOk();

    expect(array_sum($response->json('data')))->toBe(1);
});

it('keeps the global status totals for the administrator', function () {
    $owner = partner();
    $other = partner();

    FormSubmission::factory()->forPartner($owner)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();
    FormSubmission::factory()->count(4)->forPartner($other)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();

    $response = $this->actingAs(admin())
        ->getJson(route('reports.form_status_chart'))
        ->assertOk();

    expect(array_sum($response->json('data')))->toBe(5);
});

it('refuses to hand a partner the submissions of another partner', function () {
    $owner = partner();
    $other = partner();
    $statusId = statusId(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);

    FormSubmission::factory()->forPartner($other)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)
        ->create(['data' => json_encode(['name' => 'Cliente del otro partner'])]);

    $response = $this->actingAs($owner)->getJson(route('reports.form_status_chart_detail', [
        'user_id' => $other->id,
        'status_id' => $statusId,
        'start' => now()->subDay()->toDateString(),
        'end' => now()->addDay()->toDateString(),
    ]))->assertOk();

    expect($response->json())->toBe([]);
});

it('ignores the every partner slice when a partner asks for it', function () {
    $owner = partner();
    $other = partner();
    $statusId = statusId(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);

    FormSubmission::factory()->forPartner($owner)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();
    FormSubmission::factory()->count(3)->forPartner($other)
        ->withStatus(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER)->create();

    $response = $this->actingAs($owner)->getJson(route('reports.form_status_chart_detail', [
        'user_id' => 'null',
        'status_id' => $statusId,
        'start' => now()->subDay()->toDateString(),
        'end' => now()->addDay()->toDateString(),
    ]))->assertOk();

    expect($response->json())->toHaveCount(1);
});

it('does not hand the partner directory to a partner', function () {
    partner();
    partner();

    $this->actingAs(partner())
        ->get(route('reports.status_chart'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Reports/StatusChart')
            ->where('partners', [])
        );
});

it('gives the administrator the partner directory for the filters', function () {
    partner();
    partner();

    $this->actingAs(admin())
        ->get(route('reports.status_chart'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('partners', 2));
});
