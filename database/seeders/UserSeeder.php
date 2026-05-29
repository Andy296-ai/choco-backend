<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        // ─── ДИРЕКТОРА ───────────────────────────────────────────────────────
        User::create([
            'login'    => 'root',
            'name'     => 'Главный Директор',
            'email'    => 'dzona925@gmail.com',
            'phone'    => '+79104968213',
            'password' => Hash::make('r@@t00'),
            'role'     => User::ROLE_DIRECTOR,
            'salon_id' => null,
        ]);

        User::create([
            'login'    => 'Shodruz',
            'name'     => 'Шодруз Рахимов',
            'email'    => 'shodruz@choco.ru',
            'phone'    => '+79104968213',
            'password' => Hash::make('Joni.000'),
            'role'     => User::ROLE_DIRECTOR,
            'salon_id' => null,
        ]);

        // ─── АДМИНИСТРАТОРЫ ──────────────────────────────────────────────────
        User::create([
            'login'    => 'Shodruz2',
            'name'     => 'Администратор Центр',
            'email'    => 'shodruz2@choco.ru',
            'phone'    => '+7 (496) 540-10-21',
            'password' => Hash::make('Joni.000'),
            'role'     => User::ROLE_ADMIN,
            'salon_id' => 1,
        ]);

        User::create([
            'login'    => 'Shodruz3',
            'name'     => 'Администратор Загорск',
            'email'    => 'shodruz3@choco.ru',
            'phone'    => '+7 (496) 540-10-22',
            'password' => Hash::make('Joni.000'),
            'role'     => User::ROLE_ADMIN,
            'salon_id' => 2,
        ]);

        User::create([
            'login'    => 'elena_master',
            'name'     => 'Елена Нечаева',
            'email'    => 'elena.admin@choco.ru',
            'phone'    => '+7 (496) 540-10-23',
            'password' => Hash::make('password'),
            'role'     => User::ROLE_ADMIN,
            'salon_id' => 3,
        ]);

        // ─── СПЕЦИАЛИСТЫ ─────────────────────────────────────────────────────
        // Салон 1 — Центр
        User::create([
            'login'    => 'elena_k',
            'name'     => 'Елена Кузнецова',
            'email'    => 'elena.k@choco.ru',
            'phone'    => '+7 (926) 607-07-02',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 1,
        ]);

        User::create([
            'login'    => 'olga_s',
            'name'     => 'Ольга Смирнова',
            'email'    => 'olga.s@choco.ru',
            'phone'    => '+7 (926) 607-07-03',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 1,
        ]);

        User::create([
            'login'    => 'anna_k',
            'name'     => 'Анна Козлова',
            'email'    => 'anna.k@choco.ru',
            'phone'    => '+7 (926) 607-07-04',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 1,
        ]);

        // Салон 2 — Загорск
        User::create([
            'login'    => 'dmitry_v',
            'name'     => 'Дмитрий Волков',
            'email'    => 'dmitry.v@choco.ru',
            'phone'    => '+7 (926) 607-07-05',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 2,
        ]);

        User::create([
            'login'    => 'marina_s',
            'name'     => 'Марина Соколова',
            'email'    => 'marina.s@choco.ru',
            'phone'    => '+7 (926) 607-07-06',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 2,
        ]);

        User::create([
            'login'    => 'polina_v',
            'name'     => 'Полина Васильева',
            'email'    => 'polina.v@choco.ru',
            'phone'    => '+7 (926) 607-07-07',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 2,
        ]);

        // Салон 3 — Восток
        User::create([
            'login'    => 'natalia_b',
            'name'     => 'Наталья Борисова',
            'email'    => 'natalia.b@choco.ru',
            'phone'    => '+7 (926) 607-07-08',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 3,
        ]);

        User::create([
            'login'    => 'igor_p',
            'name'     => 'Игорь Петров',
            'email'    => 'igor.p@choco.ru',
            'phone'    => '+7 (926) 607-07-09',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 3,
        ]);

        User::create([
            'login'    => 'ksenia_m',
            'name'     => 'Ксения Миронова',
            'email'    => 'ksenia.m@choco.ru',
            'phone'    => '+7 (926) 607-07-10',
            'password' => Hash::make('master123'),
            'role'     => User::ROLE_SPECIALIST,
            'salon_id' => 3,
        ]);
    }
}

