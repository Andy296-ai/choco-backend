<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortfolioItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('portfolio_items')->truncate();

        $portfolioData = [
            'Елена Кузнецова' => [
                ['title' => 'Балаяж на тёмной основе',   'description' => 'Плавный переход от шоколадного к карамельному. Техника: балаяж + тонирование.', 'image_path' => 'https://images.unsplash.com/photo-1560869713-7d0a29430803?w=800'],
                ['title' => 'Стрижка каскад с укладкой', 'description' => 'Классический каскад, объёмная укладка феном.',                                  'image_path' => 'https://images.unsplash.com/photo-1562322140-8baeececf3df?w=800'],
                ['title' => 'Омбре пепельный',           'description' => 'Холодное омбре: от тёмно-каштанового к светло-пепельному.',                       'image_path' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800'],
            ],
            'Ольга Смирнова' => [
                ['title' => 'Окрашивание шатуш',         'description' => 'Мягкое осветление прядей в технике шатуш. Натуральный солнечный эффект.',         'image_path' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=800'],
                ['title' => 'Стрижка боб',               'description' => 'Чёткий боб с прямой чёлкой. Идеально для тонких волос.',                          'image_path' => 'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?w=800'],
            ],
            'Анна Козлова' => [
                ['title' => 'Маникюр миндальная форма',  'description' => 'Нежно-розовый гель-лак, миндальная форма, минималистичный дизайн.',                'image_path' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800'],
                ['title' => 'Педикюр красный классик',   'description' => 'Классический красный педикюр с SPA-уходом.',                                       'image_path' => 'https://images.unsplash.com/photo-1519415943484-9fa1873496d4?w=800'],
                ['title' => 'Весенний маникюр',          'description' => 'Пастельные оттенки, цветочный принт, объёмный декор.',                             'image_path' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800'],
            ],
            'Дмитрий Волков' => [
                ['title' => 'Мужская стрижка андеркат',  'description' => 'Классический андеркат с выбритыми висками. Укладка помадой.',                      'image_path' => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?w=800'],
                ['title' => 'Бритьё опасной бритвой',   'description' => 'Традиционное бритьё с горячим полотенцем и баттер-кремом.',                         'image_path' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=800'],
            ],
            'Марина Соколова' => [
                ['title' => 'Коррекция бровей',          'description' => 'Архитектура бровей с окрашиванием хной. Эффект густых и чётких бровей.',           'image_path' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800'],
                ['title' => 'Ламинирование ресниц',      'description' => 'Ламинирование + ботокс ресниц. Эффект завивки до 6 недель.',                       'image_path' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800'],
            ],
            'Наталья Борисова' => [
                ['title' => 'Чистка лица',               'description' => 'Комбинированная чистка + карбоновый пилинг. Кожа сияет.',                          'image_path' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=800'],
                ['title' => 'Уход за лицом с массажем',  'description' => 'Французский массаж + гиалуроновая маска. Лифтинг-эффект.',                         'image_path' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800'],
            ],
        ];

        foreach ($portfolioData as $specialistName => $items) {
            $user = User::where('name', $specialistName)->first();
            if (!$user) continue;
            foreach ($items as $item) {
                PortfolioItem::create(array_merge($item, ['user_id' => $user->id]));
            }
        }
    }
}
