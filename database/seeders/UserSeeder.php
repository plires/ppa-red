<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'pablo@admin.com'],
            [
                'name' => 'Pablo Admin',
                'phone' => '115-049-7501',
                'role' => 'admin',
                'password' => Hash::make('123123123'),
            ]
        );
    }
}
