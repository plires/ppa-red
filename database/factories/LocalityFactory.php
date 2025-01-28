<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Province;
use App\Models\Region;
use App\Models\Zone;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Locality>
 */
class LocalityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'province_id' => Province::inRandomOrder()->first()->id,
            'region_id' => Region::inRandomOrder()->first()->id,
            'zone_id' => Zone::inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(['Departamento', 'Localidad', 'Partido']),
        ];
    }
}
