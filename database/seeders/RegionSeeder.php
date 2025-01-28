<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\Province;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $provinces = Province::all();

        foreach ($provinces as $province) {
            Region::factory()->create([
                'province_id' => $province->id,
            ]);
        }

        Region::factory(5)->create();
    }
}
