<?php

namespace Database\Factories;

use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Province>
 */
class ProvinceFactory extends Factory
{
    /**
     * Contador de instancias. La columna provinces.name tiene índice único, y
     * faker->word() agota su diccionario rápidamente y provoca colisiones
     * intermitentes. Un contador garantiza nombres únicos de forma determinista.
     */
    protected static int $sequence = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Provincia '.(++static::$sequence),
        ];
    }
}
