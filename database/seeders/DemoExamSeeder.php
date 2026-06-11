<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoExamSeeder extends Seeder
{
    // Логины и пароли для демонстрации
    // Администратор: marina_kh  /  Admin@Choco2026
    // Специалист:    svetlana_kh /  Master@Choco2026

    public function run(): void
    {
        $salonId = 1; // Шоколад — Хотьково

        // ─── АДМИНИСТРАТОР ────────────────────────────────────────────────────
        User::firstOrCreate(
            ['login' => 'marina_kh'],
            [
                'name'     => 'Марина Кириллова',
                'email'    => 'marina.kh@choco.ru',
                'phone'    => '+7 (496) 543-00-10',
                'password' => Hash::make('Admin@Choco2026'),
                'role'     => User::ROLE_ADMIN,
                'salon_id' => $salonId,
            ]
        );

        // ─── СПЕЦИАЛИСТ ───────────────────────────────────────────────────────
        $specialist = User::firstOrCreate(
            ['login' => 'svetlana_kh'],
            [
                'name'     => 'Светлана Зайцева',
                'email'    => 'svetlana.kh@choco.ru',
                'phone'    => '+7 (926) 543-00-20',
                'password' => Hash::make('Master@Choco2026'),
                'role'     => User::ROLE_SPECIALIST,
                'salon_id' => $salonId,
            ]
        );

        // ─── РАСПИСАНИЕ СПЕЦИАЛИСТА (Пн–Сб 9:00–19:00) ───────────────────────
        $days = [
            'monday'    => ['09:00', '19:00', true],
            'tuesday'   => ['09:00', '19:00', true],
            'wednesday' => ['09:00', '19:00', true],
            'thursday'  => ['09:00', '19:00', true],
            'friday'    => ['09:00', '19:00', true],
            'saturday'  => ['10:00', '17:00', true],
            'sunday'    => [null,    null,    false],
        ];

        foreach ($days as $day => [$start, $end, $isWorking]) {
            if (!$specialist->schedules()->where('day_of_week', $day)->exists()) {
                Schedule::create([
                    'user_id'     => $specialist->id,
                    'salon_id'    => $salonId,
                    'day_of_week' => $day,
                    'start_time'  => $start,
                    'end_time'    => $end,
                    'is_working'  => $isWorking,
                ]);
            }
        }

        // ─── ЗАПИСИ ───────────────────────────────────────────────────────────
        $clients  = Client::where('salon_id', $salonId)->get();
        $services = Service::all();

        if ($clients->isEmpty() || $services->isEmpty()) {
            $this->command->warn('DemoExamSeeder: нет клиентов или услуг для салона 1.');
            return;
        }

        $start = Carbon::create(2026, 5, 11);
        $end   = Carbon::create(2026, 6, 30);
        $today = Carbon::create(2026, 6, 11);

        $allSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
            '17:00', '17:30', '18:00', '18:30',
        ];

        // Фиксированные комбинации клиент + услуга для разнообразия
        $bookingTemplates = [
            ['Анастасия Белова',   'Окрашивание волос',        'Постоянный клиент, скидка 10%'],
            ['Виктория Орлова',    'Маникюр + гель-лак',       null],
            ['Дарья Захарова',     'Женская стрижка',          'Предпочитает чёлку'],
            ['Кристина Лазарева',  'Сложное окрашивание',      'Балаяж, цвет карамель'],
            ['Полина Семенова',    'Укладка волос',            null],
            ['Юлия Тихонова',     'Маникюр классический',     null],
            ['Надежда Колесникова','Окрашивание волос',        'Постоянный клиент, скидка 20%'],
            ['Светлана Климова',   'Женская стрижка',          null],
            ['Галина Воронова',    'Маникюр + гель-лак',       'Покрытие синего цвета'],
            ['Валентина Фролова',  'Укладка волос',            'К свадьбе дочери'],
            ['Андрей Кириллов',   'Мужская стрижка',          null],
            ['Михаил Рябов',      'Мужская стрижка',          'Под машинку'],
            ['Татьяна Михайлова', 'Окрашивание волос',        'Постоянный клиент, скидка 10%'],
        ];

        $clientsByName = $clients->keyBy('name');

        $current = $start->copy();
        $bookingsCreated = 0;

        while ($current->lte($end)) {
            $dayOfWeek = $current->dayOfWeek; // 0=Sun, 6=Sat

            // Воскресенье — выходной
            if ($dayOfWeek === 0) {
                $current->addDay();
                continue;
            }

            // 3–5 записей в день в будние, 4–6 в субботу
            $perDay = $dayOfWeek === 6 ? rand(4, 6) : rand(3, 5);

            // Перемешиваем слоты
            $slotsForDay = $allSlots;
            shuffle($slotsForDay);
            $slotsForDay = array_slice($slotsForDay, 0, $perDay);

            foreach ($slotsForDay as $slotTime) {
                [$hour, $minute] = explode(':', $slotTime);

                // Выбираем случайный шаблон записи
                $template = $bookingTemplates[array_rand($bookingTemplates)];
                [$clientName, $serviceName, $note] = $template;

                $client  = $clientsByName->get($clientName) ?? $clients->random();
                $service = $services->firstWhere('name', $serviceName) ?? $services->random();

                $startTime = $current->copy()->setTime((int)$hour, (int)$minute, 0);
                $endTime   = $startTime->copy()->addMinutes($service->duration_minutes ?? 60);

                // Определяем статус
                if ($startTime->isBefore($today)) {
                    // Прошедшие — в основном выполненные
                    $r = rand(1, 10);
                    $status = $r <= 8 ? 'completed' : 'cancelled';
                } elseif ($startTime->isToday()) {
                    $status = rand(0, 1) ? 'confirmed' : 'completed';
                } else {
                    // Будущие — подтверждённые и ожидающие
                    $r = rand(1, 10);
                    $status = $r <= 6 ? 'confirmed' : ($r <= 9 ? 'pending' : 'cancelled');
                }

                Booking::create([
                    'client_id'     => $client->id,
                    'salon_id'      => $salonId,
                    'service_id'    => $service->id,
                    'specialist_id' => $specialist->id,
                    'start_time'    => $startTime,
                    'end_time'      => $endTime,
                    'status'        => $status,
                    'notes'         => $note,
                ]);

                $bookingsCreated++;
            }

            $current->addDay();
        }

        $this->command->info("DemoExamSeeder: создано {$bookingsCreated} записей для Светланы Зайцевой (11.05.2026 – 30.06.2026).");
        $this->command->info('');
        $this->command->info('┌─────────────────────────────────────────────┐');
        $this->command->info('│         ДАННЫЕ ДЛЯ ДЕМО-ЭКЗАМЕНА           │');
        $this->command->info('├─────────────────────────────────────────────┤');
        $this->command->info('│ Администратор                               │');
        $this->command->info('│   Логин:  marina_kh                         │');
        $this->command->info('│   Пароль: Admin@Choco2026                   │');
        $this->command->info('│   Имя:    Марина Кириллова                  │');
        $this->command->info('│   Салон:  Шоколад — Хотьково                │');
        $this->command->info('├─────────────────────────────────────────────┤');
        $this->command->info('│ Специалист                                  │');
        $this->command->info('│   Логин:  svetlana_kh                       │');
        $this->command->info('│   Пароль: Master@Choco2026                  │');
        $this->command->info('│   Имя:    Светлана Зайцева                  │');
        $this->command->info('│   Салон:  Шоколад — Хотьково                │');
        $this->command->info('└─────────────────────────────────────────────┘');
    }
}
