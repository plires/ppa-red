<?php

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionNotification;
use App\Models\FormSubmissionStatus;

/*
|--------------------------------------------------------------------------
| Estado de lectura de notificaciones y comentarios
|--------------------------------------------------------------------------
|
| Son los contadores que el partner ve en el dashboard. Si no se limpian, el
| partner ve avisos fantasma; si se limpian de más, se pierde trabajo real.
|
*/

beforeEach(fn () => seedStatuses());

function notificationFor(FormSubmission $submission): FormSubmissionNotification
{
    return FormSubmissionNotification::create([
        'form_submission_id' => $submission->id,
        'previous_status_id' => statusId(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER),
        'new_status_id' => statusId(FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER),
        'is_read' => false,
        'notification_details' => 'Cambio automático por inactividad de 48 horas',
    ]);
}

it('marks a notification as read and sends the partner to the submission', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $notification = notificationFor($submission);

    $this->actingAs($owner)
        ->get(route('notification.mark_as_read', [$notification->id, $submission->id]))
        ->assertRedirect(route('form_submissions.show', $submission->id));

    expect((bool) $notification->fresh()->is_read)->toBeTrue()
        ->and($notification->fresh()->read_at)->not->toBeNull();
});

it('marks every notification of the partner as read', function () {
    $owner = partner();
    $mine = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $first = notificationFor($mine);
    $second = notificationFor($mine);

    $this->actingAs($owner)
        ->post(route('notification.mark_as_all_read'))
        ->assertSessionHas('success');

    expect((bool) $first->fresh()->is_read)->toBeTrue()
        ->and((bool) $second->fresh()->is_read)->toBeTrue();
});

it('leaves the notifications of another partner untouched', function () {
    $owner = partner();
    $other = partner();
    $mine = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $theirs = FormSubmission::factory()->forLocality(localityWithPartner($other))->create();

    $myNotification = notificationFor($mine);
    $theirNotification = notificationFor($theirs);

    $this->actingAs($owner)->post(route('notification.mark_as_all_read'));

    expect((bool) $myNotification->fresh()->is_read)->toBeTrue()
        ->and((bool) $theirNotification->fresh()->is_read)->toBeFalse();
});

it('counts only the unread notifications of the partner', function () {
    $owner = partner();
    $other = partner();
    $mine = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $theirs = FormSubmission::factory()->forLocality(localityWithPartner($other))->create();

    notificationFor($mine);
    notificationFor($theirs);

    expect($owner->unreadNotificationsCount())->toBe(1);
});

it('counts only the unread non system comments of the partner', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    FormResponse::factory()->forSubmission($submission)->create(['is_read' => false, 'is_system' => false]);
    FormResponse::factory()->forSubmission($submission)->create(['is_read' => false, 'is_system' => true]);
    FormResponse::factory()->forSubmission($submission)->read()->create(['is_system' => false]);

    expect($owner->unreadCommentsCount())->toBe(1);
});

it('marks the whole conversation as read when the partner opens one message', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $first = FormResponse::factory()->forSubmission($submission)->create(['is_read' => false, 'is_system' => false]);
    $second = FormResponse::factory()->forSubmission($submission)->create(['is_read' => false, 'is_system' => false]);

    $this->actingAs($owner)
        ->get(route('responses.mark_as_read', $first->id))
        ->assertRedirect(route('form_submissions.show', $submission->id));

    expect((bool) $first->fresh()->is_read)->toBeTrue()
        ->and((bool) $second->fresh()->is_read)->toBeTrue();
});

it('marks every comment of the partner as read', function () {
    $owner = partner();
    $other = partner();
    $mine = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $theirs = FormSubmission::factory()->forLocality(localityWithPartner($other))->create();

    $myComment = FormResponse::factory()->forSubmission($mine)->create(['is_read' => false, 'is_system' => false]);
    $theirComment = FormResponse::factory()->forSubmission($theirs)->create(['is_read' => false, 'is_system' => false]);

    $this->actingAs($owner)
        ->post(route('responses.mark_as_all_read', $owner->id))
        ->assertSessionHas('success');

    expect((bool) $myComment->fresh()->is_read)->toBeTrue()
        ->and((bool) $theirComment->fresh()->is_read)->toBeFalse();
});

it('requires authentication to touch notification state', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $notification = notificationFor($submission);

    $this->post(route('notification.mark_as_all_read'))->assertRedirect(route('login'));
    $this->get(route('notification.mark_as_read', [$notification->id, $submission->id]))
        ->assertRedirect(route('login'));

    expect((bool) $notification->fresh()->is_read)->toBeFalse();
});

it('stops a partner from marking a notification that is not theirs', function () {
    $owner = partner();
    $intruder = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $notification = notificationFor($submission);

    $this->actingAs($intruder)
        ->get(route('notification.mark_as_read', [$notification->id, $submission->id]))
        ->assertNotFound();

    expect((bool) $notification->fresh()->is_read)->toBeFalse();
});

it('lets the administrator mark any notification as read', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();
    $notification = notificationFor($submission);

    $this->actingAs(admin())
        ->get(route('notification.mark_as_read', [$notification->id, $submission->id]))
        ->assertRedirect(route('form_submissions.show', $submission->id));

    expect((bool) $notification->fresh()->is_read)->toBeTrue();
});

it('answers not found for a notification that does not exist', function () {
    $owner = partner();
    $submission = FormSubmission::factory()->forLocality(localityWithPartner($owner))->create();

    $this->actingAs($owner)
        ->get(route('notification.mark_as_read', [999999, $submission->id]))
        ->assertNotFound();
});
