<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'email_verified_at' => null,
            'remember_token' => User::generateVerificationToken(),
            'password' => Hash::make('123123123'),
        ]);

        // Crear 10 socios
        User::factory(10)->create([
            'role' => 'partner', // Definir que son socios
        ]);
    }
}
