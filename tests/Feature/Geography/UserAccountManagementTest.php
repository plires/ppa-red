<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Administración de usuarios no partner
|--------------------------------------------------------------------------
|
| /dashboard/users gestiona sólo cuentas administrativas. Los partners tienen
| su propia sección y no pueden tocarse desde acá — si se filtraran, un partner
| podría terminar con rol admin.
|
*/

beforeEach(fn () => $this->actingAs(admin()));

function adminPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Nueva Administradora',
        'email' => 'nueva@example.com',
        'phone' => '11-3333-3333',
        'password' => 'contrasena-segura',
        'password_confirmation' => 'contrasena-segura',
        'role' => User::ADMIN_USER,
    ], $overrides);
}

it('creates an administrator', function () {
    $this->post(route('users.store'), adminPayload())
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success');

    $created = User::where('email', 'nueva@example.com')->sole();

    expect($created->role)->toBe(User::ADMIN_USER)
        ->and(Hash::check('contrasena-segura', $created->password))->toBeTrue();
});

it('requires a password when creating an administrator', function () {
    $payload = adminPayload();
    unset($payload['password'], $payload['password_confirmation']);

    $this->post(route('users.store'), $payload)->assertSessionHasErrors('password');
});

it('refuses to create a user with the partner role', function () {
    $this->post(route('users.store'), adminPayload(['role' => User::PARTNER_USER]))
        ->assertSessionHasErrors('role');

    expect(User::where('email', 'nueva@example.com')->exists())->toBeFalse();
});

it('refuses an arbitrary role', function () {
    $this->post(route('users.store'), adminPayload(['role' => 'superadmin']))
        ->assertSessionHasErrors('role');
});

it('updates an administrator without changing the password', function () {
    $target = admin(['name' => 'Nombre Viejo']);
    $originalPassword = $target->password;

    $this->put(route('users.update', $target), [
        'name' => 'Nombre Nuevo',
        'email' => $target->email,
        'phone' => $target->phone,
        'role' => User::ADMIN_USER,
    ])->assertSessionHas('success');

    $target = $target->fresh();

    expect($target->name)->toBe('Nombre Nuevo')
        ->and($target->password)->toBe($originalPassword);
});

it('lists administrators only, never partners', function () {
    admin();
    partner();

    $this->get(route('users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Users/Index')->has('users', 2));
});

it('hides partners from every user account route', function (string $routeName, string $method) {
    $target = partner();

    $this->{$method}(route($routeName, $target))->assertNotFound();
})->with([
    'edit' => ['users.edit', 'get'],
    'destroy' => ['users.destroy', 'delete'],
]);

it('stops an administrator from deleting their own account', function () {
    $me = admin();

    $this->actingAs($me)
        ->delete(route('users.destroy', $me))
        ->assertSessionHas('error');

    expect(User::find($me->id))->not->toBeNull();
});

it('soft deletes another administrator', function () {
    $target = admin();

    $this->delete(route('users.destroy', $target))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success');

    expect(User::find($target->id))->toBeNull()
        ->and(User::withTrashed()->find($target->id))->not->toBeNull();
});

it('lists and restores deleted administrators', function () {
    $target = admin();
    $target->delete();

    $this->get(route('users.trashed'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Users/Trashed')->has('users', 1));

    $this->patch(route('users.restore', $target->id))->assertRedirect(route('users.trashed'));

    expect(User::find($target->id))->not->toBeNull();
});

it('refuses to restore a partner through the user account routes', function () {
    $target = partner();
    $target->delete();

    $this->patch(route('users.restore', $target->id))->assertNotFound();

    expect(User::find($target->id))->toBeNull();
});
