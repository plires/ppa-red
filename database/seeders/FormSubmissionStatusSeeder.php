<?php

namespace Database\Seeders;

use App\Models\FormSubmissionStatus;
use Illuminate\Database\Seeder;

class FormSubmissionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
                'description' => 'El usuario envió una consulta y esta a la espera de la contestación del partner.',
            ],
            [
                'name' => FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
                'description' => 'El Partner contestó la última consulta del usuario.',
            ],
            [
                'name' => FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
                'description' => 'La contestación del partner a la última consulta del usuario tiene un retraso de 48 Hs.',
            ],
            [
                'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'description' => 'Debido a la falta de actividad del partner en un plazo de 7 días, la consulta ha sido cerrada automáticamente.',
            ],
            [
                'name' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'description' => 'Debido a la falta de actividad del usuario en un plazo de 7 días, la consulta ha sido cerrada automáticamente.',
            ],
            [
                'name' => FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
                'description' => 'Cierre manual de la consulta por desción del partner.',
            ],
        ];

        foreach ($statuses as $status) {
            FormSubmissionStatus::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}
