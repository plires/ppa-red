<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear 1 admin
        User::factory(1)->create([
            'name' => 'Pablo Lires',
            'email' => 'pablolires@gmail.com',
            'phone' => '115-049-7501',
            'role' => 'admin', // Definir que son socios
        ]);

        // Crear 10 socios
        User::factory(10)->create([
            'role' => 'partner', // Definir que son socios
        ]);
    }
}
