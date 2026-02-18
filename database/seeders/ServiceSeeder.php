<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Парикмахерские услуги
            [
                'name' => 'Женская стрижка',
                'description' => 'Стрижка любой сложности с укладкой',
                'price' => 1500,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Мужская стрижка',
                'description' => 'Классическая или модельная стрижка',
                'price' => 800,
                'duration_minutes' => 40,
            ],
            [
                'name' => 'Окрашивание волос',
                'description' => 'Однотонное окрашивание профессиональными красителями',
                'price' => 3500,
                'duration_minutes' => 120,
            ],
            [
                'name' => 'Сложное окрашивание',
                'description' => 'Балаяж, омбре, шатуш, мелирование',
                'price' => 7000,
                'duration_minutes' => 180,
            ],
            [
                'name' => 'Укладка волос',
                'description' => 'Профессиональная укладка феном',
                'price' => 1200,
                'duration_minutes' => 45,
            ],
            
            // Ногтевой сервис
            [
                'name' => 'Маникюр классический',
                'description' => 'Обработка ногтей и кутикулы',
                'price' => 800,
                'duration_minutes' => 45,
            ],
            [
                'name' => 'Маникюр + гель-лак',
                'description' => 'Маникюр с покрытием гель-лаком',
                'price' => 1800,
                'duration_minutes' => 90,
            ],
            [
                'name' => 'Педикюр классический',
                'description' => 'Обработка стоп и ногтей',
                'price' => 1500,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Педикюр + гель-лак',
                'description' => 'Педикюр с покрытием гель-лаком',
                'price' => 2500,
                'duration_minutes' => 90,
            ],
            [
                'name' => 'Наращивание ногтей',
                'description' => 'Наращивание гелем или акрилом',
                'price' => 3000,
                'duration_minutes' => 120,
            ],
            
            // Косметология
            [
                'name' => 'Чистка лица',
                'description' => 'Механическая или ультразвуковая чистка',
                'price' => 2500,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Пилинг лица',
                'description' => 'Химический пилинг для обновления кожи',
                'price' => 3000,
                'duration_minutes' => 45,
            ],
            [
                'name' => 'Уход за лицом',
                'description' => 'Комплексный уход с массажем и маской',
                'price' => 2000,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Массаж лица',
                'description' => 'Лимфодренажный или скульптурный массаж',
                'price' => 1500,
                'duration_minutes' => 30,
            ],
            [
                'name' => 'Коррекция бровей',
                'description' => 'Оформление формы бровей',
                'price' => 500,
                'duration_minutes' => 20,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
