<?php

namespace Database\Seeders;

use App\Models\FormSubmission;
use App\Models\Locality;
use Illuminate\Database\Seeder;

class FormSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $localities = Locality::all();

        foreach ($localities as $locality) {

            // locality_id explícito: sin él la factory crea una localidad nueva
            // y el envío queda con provincia y localidad de jerarquías distintas.
            FormSubmission::factory()->create([
                'locality_id' => $locality->id,
                'user_id' => $locality->user_id,
                'zone_id' => $locality->zone_id,
                'province_id' => $locality->province_id,
            ]);
        }
    }
}
