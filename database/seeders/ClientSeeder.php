<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('clients')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $clients = [
            // Салон 1 — Центр
            ['name' => 'Анастасия Белова',    'email' => 'a.belova@mail.ru',     'phone' => '+7 (906) 100-01-01', 'salon_id' => 1, 'discount' => 10, 'telegram_username' => 'nastyabelova'],
            ['name' => 'Виктория Орлова',     'email' => 'v.orlova@mail.ru',     'phone' => '+7 (906) 100-01-02', 'salon_id' => 1, 'discount' => 5],
            ['name' => 'Дарья Захарова',      'email' => 'd.zaharova@mail.ru',   'phone' => '+7 (906) 100-01-03', 'salon_id' => 1, 'discount' => 0,  'telegram_username' => 'dasha_style'],
            ['name' => 'Кристина Лазарева',   'email' => 'k.lazareva@mail.ru',   'phone' => '+7 (906) 100-01-04', 'salon_id' => 1, 'discount' => 15],
            ['name' => 'Полина Семенова',     'email' => 'p.semenova@mail.ru',   'phone' => '+7 (906) 100-01-05', 'salon_id' => 1, 'discount' => 0],
            ['name' => 'Юлия Тихонова',      'email' => 'y.tihonova@mail.ru',   'phone' => '+7 (906) 100-01-06', 'salon_id' => 1, 'discount' => 0],
            ['name' => 'Надежда Колесникова','email' => 'n.kolesnikova@mail.ru', 'phone' => '+7 (906) 100-01-07', 'salon_id' => 1, 'discount' => 20, 'telegram_username' => 'nadya_k'],
            ['name' => 'Светлана Климова',   'email' => 's.klimova@mail.ru',    'phone' => '+7 (906) 100-01-08', 'salon_id' => 1, 'discount' => 0],
            ['name' => 'Галина Воронова',    'email' => 'g.voronova@mail.ru',   'phone' => '+7 (906) 100-01-09', 'salon_id' => 1, 'discount' => 5],
            ['name' => 'Валентина Фролова',  'email' => 'v.frolova@mail.ru',    'phone' => '+7 (906) 100-01-10', 'salon_id' => 1, 'discount' => 0],
            ['name' => 'Андрей Кириллов',    'email' => 'a.kirillov@mail.ru',   'phone' => '+7 (906) 100-01-11', 'salon_id' => 1, 'discount' => 0],
            ['name' => 'Михаил Рябов',       'email' => 'm.ryabov@mail.ru',     'phone' => '+7 (906) 100-01-12', 'salon_id' => 1, 'discount' => 0],
            ['name' => 'Татьяна Михайлова',  'email' => 't.mihaylova@mail.ru',  'phone' => '+7 (906) 100-01-13', 'salon_id' => 1, 'discount' => 10],

            // Салон 2 — Загорск
            ['name' => 'Екатерина Гусева',   'email' => 'e.guseva@mail.ru',     'phone' => '+7 (906) 100-02-01', 'salon_id' => 2, 'discount' => 0,  'telegram_username' => 'katya_guseva'],
            ['name' => 'Людмила Пономарева', 'email' => 'l.ponomareva@mail.ru', 'phone' => '+7 (906) 100-02-02', 'salon_id' => 2, 'discount' => 5],
            ['name' => 'Ольга Щербакова',    'email' => 'o.sherbakova@mail.ru', 'phone' => '+7 (906) 100-02-03', 'salon_id' => 2, 'discount' => 0],
            ['name' => 'Марина Суворова',    'email' => 'm.suvorova@mail.ru',   'phone' => '+7 (906) 100-02-04', 'salon_id' => 2, 'discount' => 10],
            ['name' => 'Тамара Абрамова',    'email' => 't.abramova@mail.ru',   'phone' => '+7 (906) 100-02-05', 'salon_id' => 2, 'discount' => 0,  'telegram_username' => 'tamara_a'],
            ['name' => 'Наталья Жукова',     'email' => 'n.zhukova@mail.ru',    'phone' => '+7 (906) 100-02-06', 'salon_id' => 2, 'discount' => 0],
            ['name' => 'Ирина Горбунова',    'email' => 'i.gorbunova@mail.ru',  'phone' => '+7 (906) 100-02-07', 'salon_id' => 2, 'discount' => 15],
            ['name' => 'Алла Медведева',     'email' => 'a.medvedeva@mail.ru',  'phone' => '+7 (906) 100-02-08', 'salon_id' => 2, 'discount' => 0],
            ['name' => 'Сергей Дементьев',   'email' => 's.dementyev@mail.ru',  'phone' => '+7 (906) 100-02-09', 'salon_id' => 2, 'discount' => 0],
            ['name' => 'Олег Горшков',       'email' => 'o.gorshkov@mail.ru',   'phone' => '+7 (906) 100-02-10', 'salon_id' => 2, 'discount' => 0],
            ['name' => 'Елена Новикова',     'email' => 'e.novikova@mail.ru',   'phone' => '+7 (906) 100-02-11', 'salon_id' => 2, 'discount' => 5,  'telegram_username' => 'elena_nov'],
            ['name' => 'Людмила Егорова',    'email' => 'l.egorova@mail.ru',    'phone' => '+7 (906) 100-02-12', 'salon_id' => 2, 'discount' => 0],

            // Салон 3 — Восток
            ['name' => 'Мария Коновалова',   'email' => 'm.konovalova@mail.ru', 'phone' => '+7 (906) 100-03-01', 'salon_id' => 3, 'discount' => 0],
            ['name' => 'Ксения Панова',      'email' => 'k.panova@mail.ru',     'phone' => '+7 (906) 100-03-02', 'salon_id' => 3, 'discount' => 0,  'telegram_username' => 'kseniya_p'],
            ['name' => 'Вера Маслова',       'email' => 'v.maslova@mail.ru',    'phone' => '+7 (906) 100-03-03', 'salon_id' => 3, 'discount' => 10],
            ['name' => 'Зоя Романова',       'email' => 'z.romanova@mail.ru',   'phone' => '+7 (906) 100-03-04', 'salon_id' => 3, 'discount' => 0],
            ['name' => 'Лидия Осипова',      'email' => 'l.osipova@mail.ru',    'phone' => '+7 (906) 100-03-05', 'salon_id' => 3, 'discount' => 5],
            ['name' => 'Нина Калинина',      'email' => 'n.kalinina@mail.ru',   'phone' => '+7 (906) 100-03-06', 'salon_id' => 3, 'discount' => 0],
            ['name' => 'Раиса Голубева',     'email' => 'r.golubeva@mail.ru',   'phone' => '+7 (906) 100-03-07', 'salon_id' => 3, 'discount' => 0,  'telegram_username' => 'raisa_g'],
            ['name' => 'Инна Воробьева',     'email' => 'i.vorobyeva@mail.ru',  'phone' => '+7 (906) 100-03-08', 'salon_id' => 3, 'discount' => 20],
            ['name' => 'Элла Крылова',       'email' => 'e.krylova@mail.ru',    'phone' => '+7 (906) 100-03-09', 'salon_id' => 3, 'discount' => 0],
            ['name' => 'Тимур Захаров',      'email' => 't.zaharov@mail.ru',    'phone' => '+7 (906) 100-03-10', 'salon_id' => 3, 'discount' => 0],
            ['name' => 'Роман Ефимов',       'email' => 'r.efimov@mail.ru',     'phone' => '+7 (906) 100-03-11', 'salon_id' => 3, 'discount' => 0],
            ['name' => 'Алина Бирюкова',     'email' => 'a.biryukova@mail.ru',  'phone' => '+7 (906) 100-03-12', 'salon_id' => 3, 'discount' => 10, 'telegram_username' => 'alina_b'],
        ];

        $pass = Hash::make('client123');
        foreach ($clients as $row) {
            Client::create(array_merge(['password' => $pass], $row));
        }
    }
}
