<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Директор (Requested: root / r@@t00)
        User::create([
            'login' => 'root',
            'name' => 'Главный Директор',
            'email' => 'dzona925@gmail.com',
            'phone' => '+79104968213',
            'password' => Hash::make('r@@t00'),
            'role' => User::ROLE_DIRECTOR,
            'salon_id' => null, // Can manage any salon
        ]);

        // 2. Остальных админов и директоров пользователь просил удалить (или позволить добавить самому)
        // Но для работы системы нам нужен хотя бы один мастер для тестов
        User::create([
            'login' => 'elena_master',
            'name' => 'Елена Кузнецова',
            'email' => 'elena.k@choco.ru',
            'phone' => '+7 (926) 607-07-02',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 1,
        ]);
        
        User::create([
            'login' => 'olga_master',
            'name' => 'Ольга Смирнова',
            'email' => 'olga.s@choco.ru',
            'phone' => '+7 (926) 607-07-03',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 1,
        ]);

        User::create([
            'login' => 'anna_master',
            'name' => 'Анна Козлова',
            'email' => 'anna.k@choco.ru',
            'phone' => '+7 (926) 607-07-04',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 1,
        ]);

        User::create([
            'login' => 'dmitry_master',
            'name' => 'Дмитрий Волков',
            'email' => 'dmitry.v@choco.ru',
            'phone' => '+7 (926) 607-07-05',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 2,
        ]);

        User::create([
            'login' => 'marina_master',
            'name' => 'Марина Соколова',
            'email' => 'marina.s@choco.ru',
            'phone' => '+7 (926) 607-07-06',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 2,
        ]);

        User::create([
            'login' => 'sergey_master',
            'name' => 'Сергей Морозов',
            'email' => 'sergey.m@choco.ru',
            'phone' => '+7 (926) 607-07-07',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 3,
        ]);

        User::create([
            'login' => 'natalia_master',
            'name' => 'Наталья Борисова',
            'email' => 'natalia.b@choco.ru',
            'phone' => '+7 (926) 607-07-08',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 3,
        ]);

        User::create([
            'login' => 'igor_master',
            'name' => 'Игорь Петров',
            'email' => 'igor.p@choco.ru',
            'phone' => '+7 (926) 607-07-09',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SPECIALIST,
            'salon_id' => 3,
        ]);
    }
}
