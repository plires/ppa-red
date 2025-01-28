<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = Region::all();

        foreach ($regions as $region) {
            Zone::factory()->create([
                'region_id' => $region->id,
            ]);
        }

        Zone::factory(15)->create();
    }
}
