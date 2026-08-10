<?php

namespace Database\Factories;

use App\Models\FormSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FormSubmissionStatus>
 */
class FormSubmissionStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Los estados son un conjunto cerrado (enum en base). Elegirlos al azar
     * hacía que las aserciones sobre el ciclo de vida fueran impredecibles, así
     * que el default es el estado inicial real de una consulta nueva.
     *
     * En los tests conviene usar el helper seedStatuses(), que carga los seis
     * estados reales vía FormSubmissionStatusSeeder.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
            'description' => null,
        ];
    }

    /**
     * Estado con un nombre concreto del conjunto cerrado.
     */
    public function named(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }
}
