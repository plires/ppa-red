<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormResponse;

class FormResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Crear 10 respuestas aleatorias
        FormResponse::factory(10)->create();

        // Crear 20 respuestas mas, en base a las 10 anteriormente creadas (para que haya mas de 1 consulta respuesta x form_submission)
        FormResponse::factory(20)
            ->state(function () {
                $randomRecord = FormResponse::inRandomOrder()->first();
                return [
                    'form_submission_id' => $randomRecord->form_submission_id,
                    'user_id'            => $randomRecord->user_id,
                ];
            })
            ->create([
                'is_system' => function () {
                    return rand(0, 1);
                },
            ]);
    }
}
