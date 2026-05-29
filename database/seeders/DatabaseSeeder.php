<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

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

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('База данных заполнена данными за январь–июнь 2026.');
    }
}
