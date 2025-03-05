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
                    FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
                    FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
                    FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
                    FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                    FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                    FormSubmissionStatus::STATUS_CERRADO_SIN_MAS_ACTIVIDAD,
                    FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER
                ]
            ),
        ];
    }
}
