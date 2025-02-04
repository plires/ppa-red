<?php

namespace Database\Factories;

use App\Models\FormSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormSubmissionStatus>
 */
class FormSubmissionStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(
                [
                    FormSubmissionStatus::STATUS_EN_CURSO,
                    FormSubmissionStatus::STATUS_DEMORADO,
                    FormSubmissionStatus::STATUS_COMPLETO,
                    FormSubmissionStatus::STATUS_CERRADO,
                    FormSubmissionStatus::STATUS_PENDIENTE
                ]
            ),
        ];
    }
}
