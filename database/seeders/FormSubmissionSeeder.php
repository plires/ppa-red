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

            FormSubmission::factory()->create([
                'user_id' => $locality->user_id,
                'zone_id' => $locality->zone_id,
                'province_id' => $locality->province_id,
            ]);
        }
    }
}
