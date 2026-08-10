<?php

namespace Database\Factories;

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FormResponse>
 */
class FormResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * El autor se deriva del envío: por defecto una respuesta pertenece al
     * partner dueño de la consulta. Antes se tomaba un envío al azar con
     * inRandomOrder(), lo que hacía imposible afirmar a qué consulta pertenecía
     * la respuesta recién creada.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'form_submission_id' => FormSubmission::factory(),
            'user_id' => fn (array $attributes) => FormSubmission::withTrashed()->find($attributes['form_submission_id'])->user_id,
            'message' => $this->faker->sentence(12),
            'is_system' => false,
            'is_read' => false,
            'read_at' => null,
        ];
    }

    /**
     * Respuesta perteneciente a un envío concreto.
     */
    public function forSubmission(FormSubmission $formSubmission): static
    {
        return $this->state(fn (array $attributes) => [
            'form_submission_id' => $formSubmission->id,
            'user_id' => $formSubmission->user_id,
        ]);
    }

    /**
     * Respuesta escrita por el usuario público (sin autor autenticado).
     */
    public function fromPublicUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'is_system' => false,
        ]);
    }

    /**
     * Respuesta escrita por un usuario autenticado concreto.
     */
    public function fromUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Mensaje generado por el sistema (cambios de estado, cierres automáticos).
     */
    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system' => true,
            'user_id' => null,
        ]);
    }

    /**
     * Respuesta ya leída.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
