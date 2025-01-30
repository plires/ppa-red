<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 1 admin
        User::factory(1)->create([
            'name' => 'Pablo Admin',
            'email' => 'pablo@admin.com',
            'phone' => '115-049-7501',
            'role' => 'admin', // Definir que es admin
            'password' => Hash::make('123123123'),
        ]);
    }
}
