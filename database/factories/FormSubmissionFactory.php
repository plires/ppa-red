<?php

namespace Database\Factories;

use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FormSubmission>
 */
class FormSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * La localidad es el ancla de la jerarquía: provincia, zona y partner se
     * derivan de ella para que el envío quede geográficamente consistente.
     * Antes se elegían filas al azar con inRandomOrder(), lo que producía
     * envíos con provincia y localidad de jerarquías distintas y aserciones
     * que fallaban de forma intermitente.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locality_id' => Locality::factory(),
            'province_id' => fn (array $attributes) => Locality::withTrashed()->find($attributes['locality_id'])->province_id,
            'zone_id' => fn (array $attributes) => Locality::withTrashed()->find($attributes['locality_id'])->zone_id,
            'user_id' => fn (array $attributes) => Locality::withTrashed()->find($attributes['locality_id'])->user_id,
            'data' => json_encode([
                'name' => $this->faker->firstName(),
                'phone' => $this->faker->phoneNumber(),
                'email' => $this->faker->safeEmail(),
            ]),
            'form_submission_status_id' => FormSubmissionStatus::factory(),
            'closure_reason' => null,
        ];
    }

    /**
     * Envío anclado a una localidad concreta (hereda provincia, zona y partner).
     */
    public function forLocality(Locality $locality): static
    {
        return $this->state(fn (array $attributes) => [
            'locality_id' => $locality->id,
            'province_id' => $locality->province_id,
            'zone_id' => $locality->zone_id,
            'user_id' => $locality->user_id,
        ]);
    }

    /**
     * Envío asignado a un partner concreto.
     */
    public function forPartner(User $partner): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $partner->id,
        ]);
    }

    /**
     * Envío huérfano: la localidad no tiene partner asignado, o el partner fue
     * eliminado (user_id queda en null por el onDelete('set null')).
     */
    public function unassigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Envío en un estado concreto. Requiere que los estados estén sembrados.
     */
    public function withStatus(string $statusName): static
    {
        return $this->state(fn (array $attributes) => [
            'form_submission_status_id' => FormSubmissionStatus::where('name', $statusName)->value('id')
                ?? FormSubmissionStatus::factory()->named($statusName)->create()->id,
        ]);
    }
}
