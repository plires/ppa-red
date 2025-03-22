<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FormSubmission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtener un form_submission existente o crearlo si no hay ninguno
        $formSubmission = FormSubmission::inRandomOrder()->first() ?? FormSubmission::factory()->create();

        return [
            'form_submission_id' => $formSubmission->id,
            'user_id' => $formSubmission->user_id, // Asegurar que el user_id sea el mismo
            'message' => $this->faker->sentence(12),
            'is_system' => false, // Dejamos como false por defecto
            'is_read' => false,
            'read_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
