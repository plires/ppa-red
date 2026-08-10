<?php

use App\Models\Locality;
use App\Models\Province;
use App\Models\Zone;

/*
|--------------------------------------------------------------------------
| ABM de localidades
|--------------------------------------------------------------------------
|
| La localidad es donde se decide a qué partner le llega cada consulta, así que
| el caso "sin partner asignado" es tan válido como el caso con partner.
|
*/

beforeEach(fn () => $this->actingAs(admin()));

it('creates a locality assigned to a partner', function () {
    $province = Province::factory()->create();
    $zone = Zone::factory()->forProvince($province)->create();
    $owner = partner();

    $this->post(route('localities.store'), [
        'name' => 'Villa Carlos Paz',
        'province_id' => $province->id,
        'zone_id' => $zone->id,
        'user_id' => $owner->id,
    ])->assertSessionHas('success');

    expect(Locality::sole())
        ->name->toBe('Villa Carlos Paz')
        ->user_id->toBe($owner->id)
        ->zone_id->toBe($zone->id);
});

it('creates a locality with no partner assigned', function () {
    $province = Province::factory()->create();
    $zone = Zone::factory()->forProvince($province)->create();

    $this->post(route('localities.store'), [
        'name' => 'Localidad sin partner',
        'province_id' => $province->id,
        'zone_id' => $zone->id,
        'user_id' => null,
    ])->assertSessionHasNoErrors();

    expect(Locality::sole()->user_id)->toBeNull();
});

it('requires a zone when the province has zones', function () {
    $province = Province::factory()->create();
    Zone::factory()->forProvince($province)->create();

    $this->post(route('localities.store'), [
        'name' => 'Sin zona',
        'province_id' => $province->id,
        'zone_id' => null,
    ])->assertSessionHasErrors('zone_id');

    expect(Locality::count())->toBe(0);
});

it('accepts a locality with no zone when the province has none', function () {
    $province = Province::factory()->create();

    $this->post(route('localities.store'), [
        'name' => 'Provincia sin zonas',
        'province_id' => $province->id,
        'zone_id' => null,
    ])->assertSessionHasNoErrors();

    expect(Locality::sole()->zone_id)->toBeNull();
});

it('requires a name and a province', function (array $payload, string $field) {
    $this->post(route('localities.store'), $payload)->assertSessionHasErrors($field);

    expect(Locality::count())->toBe(0);
})->with([
    'missing name' => [['province_id' => 1], 'name'],
    'missing province' => [['name' => 'Sin provincia'], 'province_id'],
]);

it('refuses a province that does not exist', function () {
    $this->post(route('localities.store'), [
        'name' => 'Provincia inexistente',
        'province_id' => 999999,
    ])->assertSessionHasErrors('province_id');
});

it('reassigns a locality to another partner', function () {
    $locality = localityWithPartner();
    $newOwner = partner();

    $this->put(route('localities.update', $locality), [
        'name' => $locality->name,
        'province_id' => $locality->province_id,
        'zone_id' => $locality->zone_id,
        'user_id' => $newOwner->id,
    ])->assertSessionHas('success');

    expect($locality->fresh()->user_id)->toBe($newOwner->id);
});

it('removes the partner from a locality', function () {
    $locality = localityWithPartner();

    $this->put(route('localities.update', $locality), [
        'name' => $locality->name,
        'province_id' => $locality->province_id,
        'zone_id' => $locality->zone_id,
        'user_id' => null,
    ])->assertSessionHasNoErrors();

    expect($locality->fresh()->user_id)->toBeNull();
});

it('soft deletes a locality', function () {
    $locality = localityWithPartner();

    $this->delete(route('localities.destroy', $locality))->assertSessionHas('success');

    expect(Locality::find($locality->id))->toBeNull()
        ->and(Locality::withTrashed()->find($locality->id))->not->toBeNull();
});

it('lists and restores deleted localities', function () {
    $locality = localityWithPartner();
    $locality->delete();

    $this->get(route('localities.trashed'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Localities/Trashed')
            ->has('localities', 1)
        );

    $this->patch(route('localities.restore', $locality->id))->assertSessionHas('success');

    expect(Locality::find($locality->id))->not->toBeNull();
});

it('keeps deleted localities out of the main list', function () {
    $alive = localityWithPartner();
    $deleted = localityWithPartner();
    $deleted->delete();

    $this->get(route('localities.index'))
        ->assertInertia(fn ($page) => $page
            ->has('localities', 1)
            ->where('localities.0.id', $alive->id)
        );
});

it('offers only partners as assignable owners', function () {
    partner();
    admin();

    $this->get(route('localities.create'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Localities/Create')
            ->has('partners', 1)
        );
});
