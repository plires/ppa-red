<?php

use App\Models\Locality;
use App\Models\Province;
use App\Models\Zone;

/*
|--------------------------------------------------------------------------
| Endpoints geográficos públicos
|--------------------------------------------------------------------------
|
| Son públicos y los consume el formulario de la landing para armar los selects
| encadenados provincia → zona → localidad. Si devuelven de más, el formulario
| ofrece opciones inválidas; si devuelven de menos, el usuario no puede enviar.
|
*/

it('lists every province', function () {
    Province::factory()->count(3)->create();

    $this->getJson('/api/provinces')
        ->assertOk()
        ->assertJsonCount(3);
});

it('hides soft deleted provinces', function () {
    Province::factory()->count(2)->create();
    Province::factory()->create()->delete();

    $this->getJson('/api/provinces')->assertJsonCount(2);
});

it('lists every zone when no province is given', function () {
    Zone::factory()->count(3)->create();

    $this->getJson('/api/zones')->assertOk()->assertJsonCount(3);
});

it('filters zones by province', function () {
    $province = Province::factory()->create();
    Zone::factory()->count(2)->forProvince($province)->create();
    Zone::factory()->create();

    $this->getJson('/api/zones?province_id='.$province->id)
        ->assertOk()
        ->assertJsonCount(2);
});

it('serves the zones of a province through the dedicated endpoint', function () {
    $province = Province::factory()->create();
    Zone::factory()->count(2)->forProvince($province)->create();
    Zone::factory()->create();

    $this->getJson('/api/get-zones/'.$province->id)
        ->assertOk()
        ->assertJsonCount(2);
});

it('returns an empty list for a province with no zones', function () {
    $province = Province::factory()->create();

    $this->getJson('/api/get-zones/'.$province->id)->assertOk()->assertJsonCount(0);
});

it('returns an empty list for a province that does not exist', function () {
    $this->getJson('/api/get-zones/999999')->assertOk()->assertJsonCount(0);
});

it('lists every locality when no filter is given', function () {
    Locality::factory()->count(3)->create();

    $this->getJson('/api/localities')->assertOk()->assertJsonCount(3);
});

it('filters localities by province', function () {
    $province = Province::factory()->create();
    Locality::factory()->count(2)->forProvince($province)->create();
    Locality::factory()->create();

    $this->getJson('/api/localities?province_id='.$province->id)
        ->assertOk()
        ->assertJsonCount(2);
});

it('filters localities by zone', function () {
    $zone = Zone::factory()->create();
    Locality::factory()->count(2)->inZone($zone)->create();
    Locality::factory()->create();

    $this->getJson('/api/localities?zone_id='.$zone->id)
        ->assertOk()
        ->assertJsonCount(2);
});

it('hides soft deleted localities', function () {
    $province = Province::factory()->create();
    Locality::factory()->count(2)->forProvince($province)->create();
    Locality::factory()->forProvince($province)->create()->delete();

    $this->getJson('/api/localities?province_id='.$province->id)->assertJsonCount(2);
});

it('serves the geographic endpoints without authentication', function (string $endpoint) {
    $this->getJson($endpoint)->assertOk();
})->with(['/api/provinces', '/api/zones', '/api/localities']);
