<?php

use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use App\Models\Province;
use App\Models\User;
use App\Models\Zone;
use Database\Seeders\FormSubmissionStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Helpers de dominio
|--------------------------------------------------------------------------
|
| Atajos para armar el escenario mínimo de cada test sin repetir el armado de
| la jerarquía geográfica ni de los estados.
|
*/

/**
 * Siembra los seis estados reales del ciclo de vida de una consulta.
 *
 * FormSubmissionStatus::getIdByName() cachea con rememberForever, así que hay
 * que limpiar la cache: la base se resetea entre tests pero un id cacheado de
 * un test anterior apuntaría a una fila que ya no existe.
 */
function seedStatuses(): void
{
    Cache::flush();

    (new FormSubmissionStatusSeeder)->run();
}

/**
 * Id de un estado por nombre. Siembra los estados si hace falta.
 */
function statusId(string $name): int
{
    if (FormSubmissionStatus::count() === 0) {
        seedStatuses();
    }

    return FormSubmissionStatus::where('name', $name)->value('id');
}

/**
 * Administrador autenticable.
 */
function admin(array $attributes = []): User
{
    return User::factory()->admin()->activated()->create($attributes);
}

/**
 * Partner autenticable y activado.
 */
function partner(array $attributes = []): User
{
    return User::factory()->partner()->activated()->create($attributes);
}

/**
 * Jerarquía completa provincia → zona → localidad con partner asignado.
 */
function localityWithPartner(?User $owner = null): Locality
{
    $owner ??= partner();
    $zone = Zone::factory()->forProvince(Province::factory()->create())->create();

    return Locality::factory()->inZone($zone)->forPartner($owner)->create();
}

/**
 * Jerarquía completa con la localidad deliberadamente sin partner asignado.
 */
function localityWithoutPartner(): Locality
{
    $zone = Zone::factory()->forProvince(Province::factory()->create())->create();

    return Locality::factory()->inZone($zone)->withoutPartner()->create();
}

/**
 * Payload válido para el alta pública de una consulta desde la landing.
 */
function publicSubmissionPayload(Locality $locality, array $overrides = []): array
{
    return array_merge([
        'name' => 'Juana Solicitante',
        'email' => 'juana@example.com',
        'phone' => '11-5555-5555',
        'message' => 'Quisiera un presupuesto para mi instalación.',
        'province_id' => $locality->province_id,
        'zone_id' => $locality->zone_id,
        'locality_id' => $locality->id,
    ], $overrides);
}
