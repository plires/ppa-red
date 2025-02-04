<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(class: UserSeeder::class);
        $this->call(class: PartnerSeeder::class);
        $this->call(class: ProvinceZoneLocalitySeeder::class);
        $this->call(class: PartnerLocalitySeeder::class);
        $this->call(class: FormSubmissionStatusSeeder::class);
        $this->call(class: FormSubmissionSeeder::class);
        $this->call(class: FormResponseSeeder::class);
    }
}
