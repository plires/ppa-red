<?php

namespace Database\Factories;

use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zone>
 */
class ZoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'province_id' => Province::inRandomOrder()->first()?->id ?? Province::factory()->create()->id,
        ];
    }
}
