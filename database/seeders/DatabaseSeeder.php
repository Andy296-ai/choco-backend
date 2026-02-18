<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SalonSeeder::class,
            ServiceSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            ContactSeeder::class,
            ScheduleSeeder::class,
            PortfolioItemSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
