<?php

use App\Jobs\SendPartnerWelcomeEmail;
use App\Models\FormSubmission;
use App\Models\Locality;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;

/*
|--------------------------------------------------------------------------
| Administración de partners
|--------------------------------------------------------------------------
|
| La sección Partners es exclusiva del rol partner: ningún admin puede quedar
| expuesto a edición o borrado desde acá.
|
*/

beforeEach(function () {
    Queue::fake();
    $this->actingAs(admin());
});

it('creates a partner and emails them a welcome link', function () {
    $this->post(route('partners.store'), [
        'name' => 'Nuevo Partner',
        'email' => 'nuevo@example.com',
        'phone' => '11-4444-4444',
    ])->assertRedirect(route('partners.index'))->assertSessionHas('success');

    $partner = User::where('email', 'nuevo@example.com')->sole();

    expect($partner->role)->toBe(User::PARTNER_USER)
        ->and($partner->isActivated())->toBeFalse();

    Queue::assertPushed(SendPartnerWelcomeEmail::class);
});

it('never stores a usable password when the admin creates a partner', function () {
    $this->post(route('partners.store'), [
        'name' => 'Nuevo Partner',
        'email' => 'nuevo@example.com',
        'phone' => '11-4444-4444',
    ]);

    $partner = User::where('email', 'nuevo@example.com')->sole();

    expect(Hash::check('password', $partner->password))->toBeFalse()
        ->and(Hash::check('', $partner->password))->toBeFalse();
});

it('requires name, email and phone to create a partner', function (string $field) {
    $payload = [
        'name' => 'Nuevo Partner',
        'email' => 'nuevo@example.com',
        'phone' => '11-4444-4444',
    ];
    unset($payload[$field]);

    $this->post(route('partners.store'), $payload)->assertSessionHasErrors($field);
})->with(['name', 'email', 'phone']);

it('refuses a duplicated partner email', function () {
    partner(['email' => 'ocupado@example.com']);

    $this->post(route('partners.store'), [
        'name' => 'Otro Partner',
        'email' => 'ocupado@example.com',
        'phone' => '11-4444-4444',
    ])->assertSessionHasErrors('email');
});

it('updates a partner without touching their password', function () {
    $partner = partner(['name' => 'Nombre Viejo']);
    $originalPassword = $partner->password;

    $this->put(route('partners.update', $partner), [
        'name' => 'Nombre Nuevo',
        'email' => $partner->email,
        'phone' => $partner->phone,
    ])->assertSessionHas('success');

    $partner = $partner->fresh();

    expect($partner->name)->toBe('Nombre Nuevo')
        ->and($partner->password)->toBe($originalPassword);
});

it('activates a pending partner when the admin sets their password by hand', function () {
    $partner = User::factory()->partner()->pendingActivation()->create();

    $this->put(route('partners.update', $partner), [
        'name' => $partner->name,
        'email' => $partner->email,
        'phone' => $partner->phone,
        'password' => 'contrasena-nueva',
        'password_confirmation' => 'contrasena-nueva',
    ])->assertSessionHas('success');

    $partner = $partner->fresh();

    expect($partner->isActivated())->toBeTrue()
        ->and(Hash::check('contrasena-nueva', $partner->password))->toBeTrue();
});

it('rejects a password confirmation that does not match', function () {
    $partner = partner();

    $this->put(route('partners.update', $partner), [
        'name' => $partner->name,
        'email' => $partner->email,
        'phone' => $partner->phone,
        'password' => 'contrasena-nueva',
        'password_confirmation' => 'otra-cosa',
    ])->assertSessionHasErrors('password');
});

it('soft deletes a partner with no localities assigned', function () {
    $partner = partner();

    $this->delete(route('partners.destroy', $partner))
        ->assertRedirect(route('partners.index'))
        ->assertSessionHas('success');

    expect(User::find($partner->id))->toBeNull()
        ->and(User::withTrashed()->find($partner->id))->not->toBeNull();
});

it('refuses to delete a partner that still has localities', function () {
    $partner = partner();
    Locality::factory()->forPartner($partner)->create();

    $this->delete(route('partners.destroy', $partner))->assertSessionHas('error');

    expect(User::find($partner->id))->not->toBeNull();
});

it('lists and restores deleted partners', function () {
    $partner = partner();
    $partner->delete();

    $this->get(route('partners.trashed'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Partners/Trashed')->has('partners', 1));

    $this->patch(route('partners.restore', $partner->id))
        ->assertRedirect(route('partners.trashed'));

    expect(User::find($partner->id))->not->toBeNull();
});

it('lists partners only, never administrators', function () {
    partner();
    partner();

    $this->get(route('partners.index'))
        ->assertInertia(fn ($page) => $page->component('Partners/Index')->has('partners', 2));
});

it('resends the welcome email to a partner pending activation', function () {
    $partner = User::factory()->partner()->pendingActivation()->create();

    $this->post(route('partners.resend_welcome', $partner))->assertSessionHas('success');

    Queue::assertPushed(SendPartnerWelcomeEmail::class);
});

it('refuses to resend the welcome email to an already activated partner', function () {
    $partner = partner();

    $this->post(route('partners.resend_welcome', $partner))->assertSessionHas('error');

    Queue::assertNotPushed(SendPartnerWelcomeEmail::class);
});

it('hides administrators from every partner route', function (string $routeName, string $method) {
    $administrator = admin();

    $this->{$method}(route($routeName, $administrator))->assertNotFound();
})->with([
    'show' => ['partners.show', 'get'],
    'edit' => ['partners.edit', 'get'],
    'destroy' => ['partners.destroy', 'delete'],
    'resend welcome' => ['partners.resend_welcome', 'post'],
]);

it('shows a partner with their localities and recent submissions', function () {
    $partner = partner();
    localityWithPartner($partner);
    seedStatuses();
    FormSubmission::factory()->count(2)->forPartner($partner)->create();

    $this->get(route('partners.show', $partner))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Partners/Show')
            ->has('partner.localities', 1)
            ->has('recentSubmissions', 2)
        );
});
