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
            'name' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
            'description' => 'El usuario envió una consulta y esta a la espera de la contestación del partner.',
        ]);

        FormSubmissionStatus::factory()->create([
            'name' => FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
            'description' => 'El Partner contestó la última consulta del usuario.',
        ]);

        FormSubmissionStatus::factory()->create([
            'name' => FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
            'description' => 'La contestación del partner a la última consulta del usuario tiene un retraso de 48 Hs.',
        ]);


        FormSubmissionStatus::factory()->create([
            'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
            'description' => 'Debido a la falta de actividad del partner en un plazo de 7 días, la consulta ha sido cerrada automáticamente.',
        ]);

        FormSubmissionStatus::factory()->create([
            'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
            'description' => 'Debido a la falta de actividad del usuario en un plazo de 7 días, la consulta ha sido cerrada automáticamente.',
        ]);

        FormSubmissionStatus::factory()->create([
            'name' => FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
            'description' => 'Cierre manual de la consulta por desción del partner.',
        ]);
    }
}
