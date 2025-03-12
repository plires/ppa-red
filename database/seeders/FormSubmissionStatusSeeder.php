<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormSubmissionStatus;

class FormSubmissionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
        ]);


        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
        ]);
    }
}
