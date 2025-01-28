<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Locality;

class PartnerLocalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Filtrar usuarios válidos que no sean admin
        $partners = User::where('role', '!=', 'admin')->get();

        // Crear socios si no hay disponibles
        if ($partners->isEmpty()) {
            $partners = User::factory(10)->create(['role' => 'partner']);
        }

        // Asignar localidades a socios
        Locality::all()->each(function ($locality) use ($partners) {
            $locality->update([
                'user_id' => $partners->random()->id,
            ]);
        });
    }
}
