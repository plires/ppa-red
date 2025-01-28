<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Partner;
use App\Models\Province;
use App\Models\Region;
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
            'user_id' => User::inRandomOrder()->first()->id,
            'partner_id' => Partner::inRandomOrder()->first()->id,
            'province_id' => Province::inRandomOrder()->first()->id,
            'region_id' => Region::inRandomOrder()->first()->id,
            'locality_id' => Locality::inRandomOrder()->first()->id,
            'data' => json_encode([
                'name' => $this->faker->firstName(),
                'phone' => $this->faker->phoneNumber(),
                'email' => $this->faker->safeEmail(),
                'comments' => $this->faker->sentence(),
            ]),
            'form_submission_status_id' => FormSubmissionStatus::inRandomOrder()->first()->id,
        ];
    }
}
