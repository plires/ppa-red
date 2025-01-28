<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Zone;
use App\Models\Locality;

class ProvinceZoneLocalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ruta del archivo JSON
        $jsonPath = database_path('seeders/data/provinces_zones_localities.json');
        $data = json_decode(file_get_contents($jsonPath), true);

        // Iterar sobre las provincias
        foreach ($data as $provinceName => $zonesOrLocalities) {
            // Crear la provincia
            $province = Province::create(['name' => $provinceName]);

            // Si la provincia tiene zonas
            if (is_array($zonesOrLocalities)) {
                foreach ($zonesOrLocalities as $zoneName => $localities) {
                    if (is_array($localities)) {
                        // Crear la zona asociada a la provincia
                        $zone = Zone::create([
                            'name' => $zoneName,
                            'province_id' => $province->id,
                        ]);

                        // Crear las localidades asociadas a la zona
                        foreach ($localities as $localityName) {
                            Locality::create([
                                'name' => $localityName,
                                'zone_id' => $zone->id,
                                'province_id' => $province->id,
                            ]);
                        }
                    } else {
                        // Si no hay zonas, crear localidades directamente asociadas a la provincia
                        Locality::create([
                            'name' => $localities,
                            'province_id' => $province->id,
                        ]);
                    }
                }
            }
        }
    }
}
