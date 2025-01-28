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
            'status' => 'en_curso',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'demorado',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'completo',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'rechazado',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'pendiente',
        ]);
    }
}
