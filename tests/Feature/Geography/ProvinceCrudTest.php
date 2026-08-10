<?php

use App\Models\Locality;
use App\Models\Province;
use App\Models\Zone;

/*
|--------------------------------------------------------------------------
| ABM de provincias
|--------------------------------------------------------------------------
*/

beforeEach(fn () => $this->actingAs(admin()));

it('creates a province', function () {
    $this->post(route('provinces.store'), ['name' => 'Córdoba'])
        ->assertRedirect(route('provinces.index'))
        ->assertSessionHas('success');

    expect(Province::where('name', 'Córdoba')->exists())->toBeTrue();
});

it('requires a name to create a province', function () {
    $this->post(route('provinces.store'), [])->assertSessionHasErrors('name');

    expect(Province::count())->toBe(0);
});

it('refuses a duplicated province name', function () {
    Province::factory()->create(['name' => 'Córdoba']);

    $this->post(route('provinces.store'), ['name' => 'Córdoba'])
        ->assertSessionHasErrors('name');

    expect(Province::where('name', 'Córdoba')->count())->toBe(1);
});

it('renames a province', function () {
    $province = Province::factory()->create(['name' => 'Cordoba']);

    $this->put(route('provinces.update', $province), ['name' => 'Córdoba'])
        ->assertRedirect(route('provinces.index'));

    expect($province->fresh()->name)->toBe('Córdoba');
});

it('lets a province keep its own name on update', function () {
    $province = Province::factory()->create(['name' => 'Córdoba']);

    $this->put(route('provinces.update', $province), ['name' => 'Córdoba'])
        ->assertSessionHasNoErrors();
});

it('soft deletes an empty province', function () {
    $province = Province::factory()->create();

    $this->delete(route('provinces.destroy', $province))
        ->assertSessionHas('success');

    expect(Province::find($province->id))->toBeNull()
        ->and(Province::withTrashed()->find($province->id))->not->toBeNull();
});

it('refuses to delete a province that still has zones', function () {
    $province = Province::factory()->create();
    Zone::factory()->forProvince($province)->create();

    $this->delete(route('provinces.destroy', $province))
        ->assertSessionHas('error');

    expect(Province::find($province->id))->not->toBeNull();
});

it('refuses to delete a province that still has localities', function () {
    $province = Province::factory()->create();
    Locality::factory()->forProvince($province)->create();

    $this->delete(route('provinces.destroy', $province))
        ->assertSessionHas('error');

    expect(Province::find($province->id))->not->toBeNull();
});

it('lists the deleted provinces separately', function () {
    $alive = Province::factory()->create();
    $deleted = Province::factory()->create();
    $deleted->delete();

    $this->get(route('provinces.trashed'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Provinces/Trashed')
            ->has('provinces', 1)
            ->where('provinces.0.id', $deleted->id)
        );

    $this->get(route('provinces.index'))
        ->assertInertia(fn ($page) => $page
            ->has('provinces', 1)
            ->where('provinces.0.id', $alive->id)
        );
});

it('restores a deleted province', function () {
    $province = Province::factory()->create();
    $province->delete();

    $this->patch(route('provinces.restore', $province->id))
        ->assertRedirect(route('provinces.trashed'))
        ->assertSessionHas('success');

    expect(Province::find($province->id))->not->toBeNull();
});

it('fails loudly when restoring a province that does not exist', function () {
    $this->patch(route('provinces.restore', 999999))->assertNotFound();
});

it('shows a province with its zones and localities', function () {
    $province = Province::factory()->create();
    $zone = Zone::factory()->forProvince($province)->create();
    Locality::factory()->inZone($zone)->create();

    $this->get(route('provinces.show', $province))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Provinces/Show')
            ->has('province.zones', 1)
            ->has('province.localities', 1)
        );
});
