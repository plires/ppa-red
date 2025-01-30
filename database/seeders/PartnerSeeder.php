<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear 1 partner
        User::factory(1)->create([
            'name' => 'Pablo Partner',
            'email' => 'pablo@partner.com',
            'phone' => '115-049-7501',
            'role' => 'partner', // Definir que es admin
            'email_verified_at' => NULL,
            'remember_token' => User::generateVerificationToken(),
            'password' => Hash::make('123123123'),
        ]);

        // Crear 10 socios
        User::factory(10)->create([
            'role' => 'partner', // Definir que son socios
        ]);
    }
}
