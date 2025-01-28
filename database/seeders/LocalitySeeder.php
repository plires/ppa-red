<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Region;
use App\Models\Zone;
use App\Models\Locality;

class LocalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = Region::all();
        $zones = Zone::all();

        foreach ($regions as $region) {
            foreach ($zones as $zone) {
                Locality::factory()->create([
                    'province_id' => Province::inRandomOrder()->first()->id,
                    'region_id' => $region->id,
                    'zone_id' => $zone->id,
                ]);
            }
        }

        Locality::factory(15)->create();
    }
}
