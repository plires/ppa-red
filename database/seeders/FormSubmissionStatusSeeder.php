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
            'status' => 'En Curso',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'Demorado',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'Completo',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'Cerrado',
        ]);

        FormSubmissionStatus::factory()->create([
            'status' => 'Pendiente',
        ]);
    }
}
