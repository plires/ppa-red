<?php

namespace Database\Factories;

use App\Models\Province;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Zone>
 */
class ZoneFactory extends Factory
{
    /**
     * Contador de instancias: zones.name tiene índice único.
     */
    protected static int $sequence = 0;

    public function definition(): array
    {
        return [
            'name' => 'Zona '.(++static::$sequence),
            'province_id' => Province::factory(),
        ];
    }

    /**
     * Zona perteneciente a una provincia concreta.
     */
    public function forProvince(Province $province): static
    {
        return $this->state(fn (array $attributes) => [
            'province_id' => $province->id,
        ]);
    }
}
