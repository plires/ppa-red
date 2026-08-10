<?php

use App\Jobs\SendPartnerReassignmentEmails;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Queue;

/*
|--------------------------------------------------------------------------
| Reasignación de partner (sólo administrador)
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    seedStatuses();
    Queue::fake();
});

it('lets the administrator move a submission to another partner', function () {
    $outgoing = partner();
    $incoming = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    $this->actingAs(admin())
        ->patch(route('form_submissions.reassign', $submission), ['partner_id' => $incoming->id])
        ->assertSessionHas('success');

    expect($submission->fresh()->user_id)->toBe($incoming->id);
});

it('notifies both partners about the reassignment', function () {
    $outgoing = partner();
    $incoming = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($outgoing))->create();

    $this->actingAs(admin())
        ->patch(route('form_submissions.reassign', $submission), ['partner_id' => $incoming->id]);

    Queue::assertPushed(SendPartnerReassignmentEmails::class);
});

it('assigns an orphan submission to a partner', function () {
    $incoming = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithoutPartner())->create();

    expect($submission->user_id)->toBeNull();

    $this->actingAs(admin())
        ->patch(route('form_submissions.reassign', $submission), ['partner_id' => $incoming->id])
        ->assertSessionHas('success');

    expect($submission->fresh()->user_id)->toBe($incoming->id);
});

it('refuses to reassign to the partner already assigned', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs(admin())
        ->patch(route('form_submissions.reassign', $submission), ['partner_id' => $owner->id])
        ->assertSessionHas('error');

    Queue::assertNotPushed(SendPartnerReassignmentEmails::class);
});

it('refuses to reassign to a user that is not a partner', function () {
    $owner = partner();
    $anotherAdmin = admin();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs(admin())
        ->patch(route('form_submissions.reassign', $submission), ['partner_id' => $anotherAdmin->id])
        ->assertSessionHasErrors('partner_id');

    expect($submission->fresh()->user_id)->toBe($owner->id);
});

it('refuses to reassign to a user that does not exist', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs(admin())
        ->patch(route('form_submissions.reassign', $submission), ['partner_id' => 999999])
        ->assertSessionHasErrors('partner_id');

    expect($submission->fresh()->user_id)->toBe($owner->id);
});

it('requires a partner to be chosen', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs(admin())
        ->patch(route('form_submissions.reassign', $submission), [])
        ->assertSessionHasErrors('partner_id');

    expect($submission->fresh()->user_id)->toBe($owner->id);
});
