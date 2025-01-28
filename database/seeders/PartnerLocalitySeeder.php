<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partner;
use App\Models\Locality;

class PartnerLocalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Aseguramos que cada localidad tenga un único partner
        $localities = Locality::all();

        foreach ($localities as $locality) {
            Partner::inRandomOrder()
                ->limit(1)
                ->get()
                ->each(function ($partner) use ($locality) {
                    $locality->partners()->attach($partner->id); // Relación pivot
                });
        }

        // Adicional: Si quieres relaciones múltiples para partners
        // Partner::all()->each(function ($partner) use ($localities) {
        //     $localities->random(rand(1, 5))->each(function ($locality) use ($partner) {
        //         $partner->localities()->syncWithoutDetaching($locality->id);
        //     });
        // });
    }
}
