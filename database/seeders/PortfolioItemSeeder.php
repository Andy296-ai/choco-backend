<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortfolioItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialists = [
            'Елена Кузнецова' => [
                [
                    'title' => 'Сложное окрашивание балаяж',
                    'image_path' => 'https://images.unsplash.com/photo-1560869713-7d0a29430803?w=800',
                ],
                [
                    'title' => 'Стрижка каскад',
                    'image_path' => 'https://images.unsplash.com/photo-1562322140-8baeececf3df?w=800',
                ],
            ],
            'Ольга Смирнова' => [
                [
                    'title' => 'Омбре на длинные волосы',
                    'image_path' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=800',
                ],
                [
                    'title' => 'Короткая стрижка пикси',
                    'image_path' => 'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?w=800',
                ],
            ],
        ];

        foreach ($specialists as $name => $items) {
            $user = User::where('name', $name)->first();
            if ($user) {
                foreach ($items as $item) {
                    PortfolioItem::create(array_merge($item, ['user_id' => $user->id]));
                }
            }
        }
    }
}
