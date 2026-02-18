<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Salon::create([
            'name' => 'Шоколад — Хотьково',
            'address' => 'г. Хотьково, ул. Михеенко, д. 25',
            'phone' => '+7 (496) 543-00-00',
            'description' => 'Первый салон сети «Шоколад» в Хотьково.',
            'latitude' => 56.2570000,
            'longitude' => 37.9730000,
        ]);

        \App\Models\Salon::create([
            'name' => 'Шоколад — Посад (Центр)',
            'address' => 'г. Сергиев Посад, проспект Красной Армии, д. 140',
            'phone' => '+7 (496) 540-00-00',
            'description' => 'Салон в центре Сергиева Посада.',
            'latitude' => 56.3115000,
            'longitude' => 38.1345000,
        ]);

        \App\Models\Salon::create([
            'name' => 'Шоколад — Посад (Запад)',
            'address' => 'г. Сергиев Посад, Новоугличское шоссе, д. 67',
            'phone' => '+7 (496) 549-00-00',
            'description' => 'Уютный салон в западном районе Посада.',
            'latitude' => 56.3075000,
            'longitude' => 38.1250000,
        ]);
    }
}
