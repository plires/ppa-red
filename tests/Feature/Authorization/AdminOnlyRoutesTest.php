<?php

use App\Models\FormSubmission;

/*
|--------------------------------------------------------------------------
| Rutas exclusivas del administrador
|--------------------------------------------------------------------------
|
| AdminMiddleware es la única barrera entre un partner y la administración
| completa del sistema. Un partner que entra acá ve datos de todos los demás
| partners: no es un bug de UI, es un incidente de datos.
|
| El dataset enumera las rutas admin-only sin parámetros. Cuando se agregue una
| ruta nueva bajo el grupo AdminMiddleware, sumarla acá.
|
*/

dataset('admin only routes', [
    'provinces.index' => ['provinces.index'],
    'provinces.create' => ['provinces.create'],
    'provinces.trashed' => ['provinces.trashed'],
    'zones.index' => ['zones.index'],
    'zones.create' => ['zones.create'],
    'zones.trashed' => ['zones.trashed'],
    'localities.index' => ['localities.index'],
    'localities.create' => ['localities.create'],
    'localities.trashed' => ['localities.trashed'],
    'partners.index' => ['partners.index'],
    'partners.create' => ['partners.create'],
    'partners.trashed' => ['partners.trashed'],
    'users.index' => ['users.index'],
    'users.create' => ['users.create'],
    'users.trashed' => ['users.trashed'],
    'reports.index' => ['reports.index'],
]);

it('redirects a guest to the login screen', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with('admin only routes');

it('turns a partner away', function (string $routeName) {
    $this->actingAs(partner())
        ->get(route($routeName))
        ->assertRedirect(route('form_submissions.index'));
})->with('admin only routes');

it('lets an administrator through', function (string $routeName) {
    seedStatuses();

    $this->actingAs(admin())
        ->get(route($routeName))
        ->assertOk();
})->with('admin only routes');

it('turns a partner away from reassigning a submission', function () {
    $owner = partner();
    $intruder = partner();
    $submission = FormSubmission::factory()
        ->forLocality(localityWithPartner($owner))
        ->create();

    $this->actingAs($intruder)
        ->patch(route('form_submissions.reassign', $submission), ['partner_id' => $intruder->id])
        ->assertRedirect(route('form_submissions.index'));

    expect($submission->fresh()->user_id)->toBe($owner->id);
});

it('sends a partner landing on the dashboard root to their submissions', function () {
    $this->actingAs(partner())
        ->get(route('dashboard'))
        ->assertRedirect(route('form_submissions.index'));
});

it('shows the dashboard to an administrator', function () {
    $this->actingAs(admin())
        ->get(route('dashboard'))
        ->assertOk();
});

it('requires authentication on the shared dashboard routes', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with([
    'dashboard' => ['dashboard'],
    'form_submissions.index' => ['form_submissions.index'],
    'profile.edit' => ['profile.edit'],
]);

it('lets a partner reach the routes shared with the administrator', function (string $routeName) {
    seedStatuses();

    $this->actingAs(partner())
        ->get(route($routeName))
        ->assertOk();
})->with([
    'form_submissions.index' => ['form_submissions.index'],
    'profile.edit' => ['profile.edit'],
]);
