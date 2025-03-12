<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Province;
use App\Models\Zone;
use App\Models\Locality;
use App\Models\FormSubmissionStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormSubmission>
 */
class FormSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', '!=', 'admin')->inRandomOrder()->first()?->id ?? User::factory()->create(['role' => 'partner'])->id,
            'province_id' => Province::inRandomOrder()->first()->id,
            'zone_id' => Zone::inRandomOrder()->first()->id,
            'locality_id' => Locality::inRandomOrder()->first()->id,
            'data' => json_encode([
                'name' => $this->faker->firstName(),
                'phone' => $this->faker->phoneNumber(),
                'email' => $this->faker->safeEmail()
            ]),
            'form_submission_status_id' => $statusId = FormSubmissionStatus::inRandomOrder()->first()->id,
            'closure_reason' => match ($statusId) {
                4 => 'Esta consulta estuvo inactiva durante 7 días, sin ninguna iteracción por parte del partner.',
                5 => 'Esta consulta estuvo inactiva durante 7 días, sin ninguna iteracción por parte del usuario.',
                6 => 'Esta consulta fue cerrada por el partner.',
                default => null, // Puedes colocar otro valor por defecto si es necesario
            },
        ];
    }
}
