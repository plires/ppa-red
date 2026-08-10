<?php

use App\Models\FormResponse;
use App\Models\FormSubmission;
use Inertia\Testing\AssertableInertia as Assert;

/*
|--------------------------------------------------------------------------
| Aislamiento de datos entre partners
|--------------------------------------------------------------------------
|
| Un partner sólo puede ver sus propias consultas. Que otro partner acceda a
| una consulta ajena expone datos personales del solicitante.
|
*/

beforeEach(fn () => seedStatuses());

it('lists only the submissions owned by the partner', function () {
    $mine = partner();
    $theirs = partner();

    FormSubmission::factory()->count(3)->forLocality(localityWithPartner($mine))->create();
    FormSubmission::factory()->count(2)->forLocality(localityWithPartner($theirs))->create();

    $this->actingAs($mine)
        ->get(route('form_submissions.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('FormSubmissions/Index')
            ->has('formSubmissions', 3)
        );
});

it('lists every submission for an administrator', function () {
    FormSubmission::factory()->count(3)->forLocality(localityWithPartner())->create();
    FormSubmission::factory()->count(2)->forLocality(localityWithPartner())->create();

    $this->actingAs(admin())
        ->get(route('form_submissions.index'))
        ->assertInertia(fn (Assert $page) => $page->has('formSubmissions', 5));
});

it('includes unassigned submissions in the administrator list', function () {
    FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    $this->actingAs(admin())
        ->get(route('form_submissions.index'))
        ->assertInertia(fn (Assert $page) => $page->has('formSubmissions', 1));
});

it('lets a partner open their own submission', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs($owner)
        ->get(route('form_submissions.show', $submission))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('FormSubmissions/Show')
            ->where('formSubmission.id', $submission->id)
        );
});

it('does not render another partner submission', function () {
    $owner = partner();
    $intruder = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $response = $this->actingAs($intruder)->get(route('form_submissions.show', $submission));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

it('lets an administrator open any submission', function () {
    $submission = FormSubmission::factory()->forLocality(localityWithPartner())->create();

    $this->actingAs(admin())
        ->get(route('form_submissions.show', $submission))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('formSubmission.id', $submission->id));
});

it('offers the partner list only to the administrator', function () {
    $owner = partner();
    partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs(admin())
        ->get(route('form_submissions.show', $submission))
        ->assertInertia(fn (Assert $page) => $page->has('partners'));

    $this->actingAs($owner)
        ->get(route('form_submissions.show', $submission))
        ->assertInertia(fn (Assert $page) => $page->where('partners', []));
});

it('marks the pending messages of a submission as read when its owner opens it', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $unread = FormResponse::factory()->forSubmission($submission)->create([
        'is_read' => false,
        'is_system' => false,
    ]);

    $this->actingAs($owner)->get(route('form_submissions.show', $submission));

    expect((bool) $unread->fresh()->is_read)->toBeTrue();
});
