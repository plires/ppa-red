<?php

namespace Database\Factories;

use App\Models\Locality;
use App\Models\Province;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Locality>
 */
class LocalityFactory extends Factory
{
    /**
     * Contador de instancias: localities.name no es único en base, pero los
     * tests se apoyan en nombres distinguibles para las aserciones.
     */
    protected static int $sequence = 0;

    /**
     * Define the model's default state.
     *
     * Por defecto la localidad queda SIN partner asignado (user_id null), que
     * es un estado válido del dominio: el admin puede dejar localidades sin
     * asignar y las consultas que llegan ahí se derivan al administrador.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Localidad '.(++static::$sequence),
            'province_id' => Province::factory(),
            'zone_id' => null,
            'user_id' => null,
        ];
    }

    /**
     * Localidad perteneciente a una provincia concreta.
     */
    public function forProvince(Province $province): static
    {
        return $this->state(fn (array $attributes) => [
            'province_id' => $province->id,
        ]);
    }

    /**
     * Localidad dentro de una zona. Fuerza la provincia de la zona para que la
     * jerarquía provincia → zona → localidad quede consistente.
     */
    public function inZone(Zone $zone): static
    {
        return $this->state(fn (array $attributes) => [
            'zone_id' => $zone->id,
            'province_id' => $zone->province_id,
        ]);
    }

    /**
     * Localidad con partner asignado.
     */
    public function forPartner(User $partner): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $partner->id,
        ]);
    }

    /**
     * Localidad explícitamente sin partner asignado.
     */
    public function withoutPartner(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }
}
