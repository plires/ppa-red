<?php

use App\Models\Locality;
use App\Models\Province;
use App\Models\Zone;

/*
|--------------------------------------------------------------------------
| ABM de zonas
|--------------------------------------------------------------------------
*/

beforeEach(fn () => $this->actingAs(admin()));

it('creates a zone inside a province', function () {
    $province = Province::factory()->create();

    $this->post(route('zones.store'), [
        'name' => 'Zona Norte',
        'province_id' => $province->id,
    ])->assertRedirect(route('zones.index'))->assertSessionHas('success');

    expect(Zone::sole())
        ->name->toBe('Zona Norte')
        ->province_id->toBe($province->id);
});

it('requires a name and a province to create a zone', function (array $payload, string $field) {
    $this->post(route('zones.store'), $payload)->assertSessionHasErrors($field);

    expect(Zone::count())->toBe(0);
})->with([
    'missing name' => [[], 'name'],
    'missing province' => [['name' => 'Zona Norte'], 'province_id'],
]);

it('refuses a duplicated zone name', function () {
    $province = Province::factory()->create();
    Zone::factory()->forProvince($province)->create(['name' => 'Zona Norte']);

    $this->post(route('zones.store'), [
        'name' => 'Zona Norte',
        'province_id' => $province->id,
    ])->assertSessionHasErrors('name');

    expect(Zone::where('name', 'Zona Norte')->count())->toBe(1);
});

it('lets a zone keep its own name on update', function () {
    $zone = Zone::factory()->create(['name' => 'Zona Norte']);

    $this->put(route('zones.update', $zone), [
        'name' => 'Zona Norte',
        'province_id' => $zone->province_id,
    ])->assertSessionHasNoErrors();
});

it('refuses to rename a zone onto the name of another zone', function () {
    Zone::factory()->create(['name' => 'Zona Norte']);
    $zone = Zone::factory()->create(['name' => 'Zona Sur']);

    $this->put(route('zones.update', $zone), [
        'name' => 'Zona Norte',
        'province_id' => $zone->province_id,
    ])->assertSessionHasErrors('name');

    expect($zone->fresh()->name)->toBe('Zona Sur');
});

/*
| El índice único de la base no excluye las zonas borradas, así que un nombre
| en la papelera sigue ocupado. La regla de validación acompaña ese
| comportamiento en vez de dejar que reviente en el INSERT.
*/
it('keeps the name of a deleted zone reserved', function () {
    $province = Province::factory()->create();
    Zone::factory()->forProvince($province)->create(['name' => 'Zona Norte'])->delete();

    $this->post(route('zones.store'), [
        'name' => 'Zona Norte',
        'province_id' => $province->id,
    ])->assertSessionHasErrors('name');
});

it('renames a zone', function () {
    $zone = Zone::factory()->create(['name' => 'Zona Nrte']);

    $this->put(route('zones.update', $zone), [
        'name' => 'Zona Norte',
        'province_id' => $zone->province_id,
    ])->assertSessionHas('success');

    expect($zone->fresh()->name)->toBe('Zona Norte');
});

it('soft deletes a zone', function () {
    $zone = Zone::factory()->create();

    $this->delete(route('zones.destroy', $zone))->assertRedirect(route('zones.index'));

    expect(Zone::find($zone->id))->toBeNull()
        ->and(Zone::withTrashed()->find($zone->id))->not->toBeNull();
});

it('lists and restores deleted zones', function () {
    $zone = Zone::factory()->create();
    $zone->delete();

    $this->get(route('zones.trashed'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Zones/Trashed')->has('zones', 1));

    $this->patch(route('zones.restore', $zone->id))->assertRedirect(route('zones.trashed'));

    expect(Zone::find($zone->id))->not->toBeNull();
});

it('keeps deleted zones out of the main list', function () {
    $alive = Zone::factory()->create();
    $deleted = Zone::factory()->create();
    $deleted->delete();

    $this->get(route('zones.index'))
        ->assertInertia(fn ($page) => $page
            ->has('zones', 1)
            ->where('zones.0.id', $alive->id)
        );
});

it('shows a zone with its province and localities', function () {
    $zone = Zone::factory()->create();
    Locality::factory()->inZone($zone)->create();

    $this->get(route('zones.show', $zone))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Zones/Show')
            ->has('zone.localities', 1)
            ->where('zone.province.id', $zone->province_id)
        );
});
