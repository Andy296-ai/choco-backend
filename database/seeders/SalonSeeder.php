<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salon;
use Illuminate\Support\Facades\DB;

class SalonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('salons')->truncate();

        Salon::create([
            'name'        => 'Шоколад — Хотьково',
            'address'     => 'г. Хотьково, ул. Михеенко, д. 25',
            'phone'       => '+7 (496) 543-00-00',
            'description' => 'Первый салон сети «Шоколад» в Хотьково. Полный спектр услуг по уходу.',
            'latitude'    => 56.2570000,
            'longitude'   => 37.9730000,
        ]);

        Salon::create([
            'name'        => 'Шоколад — Посад (Центр)',
            'address'     => 'г. Сергиев Посад, проспект Красной Армии, д. 140',
            'phone'       => '+7 (496) 540-00-00',
            'description' => 'Флагманский салон в центре Сергиева Посада. Парикмахерский зал, ногти, косметология.',
            'latitude'    => 56.3115000,
            'longitude'   => 38.1345000,
        ]);

        Salon::create([
            'name'        => 'Шоколад — Посад (Запад)',
            'address'     => 'г. Сергиев Посад, Новоугличское шоссе, д. 67',
            'phone'       => '+7 (496) 549-00-00',
            'description' => 'Уютный салон в западном районе. Специализация: сложные окрашивания и уход за волосами.',
            'latitude'    => 56.3075000,
            'longitude'   => 38.1250000,
        ]);
    }
}
