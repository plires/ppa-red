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
            'status' => FormSubmissionStatus::STATUS_DEMORADO,
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_PENDIENTE,
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_EN_CURSO,
        ]);


        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_COMPLETO,
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => FormSubmissionStatus::STATUS_CERRADO,
        ]);
    }
}
