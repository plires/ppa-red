<?php

namespace Database\Seeders;

use App\Models\FormResponse;
use App\Models\FormSubmission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FormResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $formSubmissions = FormSubmission::all();

        foreach ($formSubmissions as $formSubmission) {
            FormResponse::factory()->create([
                'form_submission_id' => $formSubmission->id,
                'user_id' => $formSubmission->user_id,
                'is_system' => 0, // Dejamos false por defecto para que sea el primer mensaje del usuario de la landing
                'is_read' => 0,
            ]);
        }

        // Crear 60 respuestas mas (para que haya mas de 1 consulta respuesta x form_submission)
        FormResponse::factory(60)
            ->state(function () {
                $randomRecord = FormResponse::inRandomOrder()->first();

                // Generar el valor de is_system
                $isSystem = rand(0, 1);
                // Determinar el valor de is_read basado en is_system
                $isRead = $isSystem === 0 ? 0 : 1;

                return [
                    'form_submission_id' => $randomRecord->form_submission_id,
                    'user_id'            => $randomRecord->user_id,
                    'is_system'          => $isSystem,
                    'is_read'            => $isRead,
                ];
            })
            ->create();
    }
}
