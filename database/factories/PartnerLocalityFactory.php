<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Partner;
use App\Models\Locality;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PartnerLocalityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'partner_id' => Partner::inRandomOrder()->first()->id, // Selecciona un partner aleatorio
            'locality_id' => Locality::whereDoesntHave('partners')->inRandomOrder()->first()->id, // Asegura que la localidad no tenga un partner asociado
        ];
    }
}
