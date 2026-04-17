<?php

namespace Database\Factories;

use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $province = Province::inRandomOrder()->first() ?? Province::factory()->create();
        $locality = Locality::inRandomOrder()->first() ?? Locality::factory()->create(['province_id' => $province->id]);
        $status = FormSubmissionStatus::inRandomOrder()->first() ?? FormSubmissionStatus::factory()->create();

        return [
            'user_id' => User::where('role', '!=', 'admin')->inRandomOrder()->first()?->id ?? User::factory()->create(['role' => 'partner'])->id,
            'province_id' => $province->id,
            'zone_id' => null,
            'locality_id' => $locality->id,
            'data' => json_encode([
                'name' => $this->faker->firstName(),
                'phone' => $this->faker->phoneNumber(),
                'email' => $this->faker->safeEmail(),
            ]),
            'form_submission_status_id' => $status->id,
            'closure_reason' => null,
        ];
    }
}
