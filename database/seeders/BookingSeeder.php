<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bookings')->truncate();

        $clients    = Client::all()->keyBy('salon_id')->groupBy('salon_id');
        $allClients = Client::all();
        $salons     = Salon::all();
        $services   = Service::all();
        $specialists = User::where('role', User::ROLE_SPECIALIST)->get()->groupBy('salon_id');

        if ($allClients->isEmpty() || $salons->isEmpty() || $services->isEmpty() || $specialists->isEmpty()) {
            $this->command->warn('Пропуск BookingSeeder: нет необходимых данных.');
            return;
        }

        $start = Carbon::create(2026, 1, 1);
        $end   = Carbon::create(2026, 6, 2);

        // Количество записей в день — с сезонным ростом
        // Январь: 5–7, Февраль: 7–10, Март: 12–16, Апрель: 14–18, Май: 12–15, Июнь: 8–10
        $monthIntensity = [
            1 => ['min' => 5,  'max' => 8],
            2 => ['min' => 7,  'max' => 11],
            3 => ['min' => 12, 'max' => 16],
            4 => ['min' => 14, 'max' => 18],
            5 => ['min' => 12, 'max' => 15],
            6 => ['min' => 6,  'max' => 9],
        ];

        $current = $start->copy();

        while ($current->lte($end)) {
            $dayOfWeek = $current->dayOfWeek; // 0=Sun, 6=Sat
            $isWeekend = in_array($dayOfWeek, [0, 6]);
            $month     = $current->month;

            $intensity = $monthIntensity[$month];
            $perDay = rand($intensity['min'], $intensity['max']);

            // По выходным +30% активность
            if ($isWeekend) {
                $perDay = (int) ceil($perDay * 1.3);
            }

            // Воскресенье — меньше
            if ($dayOfWeek === 0) {
                $perDay = max(2, (int) ceil($perDay * 0.5));
            }

            for ($i = 0; $i < $perDay; $i++) {
                // Выбираем салон
                $salon = $salons->random();
                $salonId = $salon->id;

                // Выбираем специалиста из этого салона
                $salonSpecialists = $specialists->get($salonId);
                if (!$salonSpecialists || $salonSpecialists->isEmpty()) {
                    continue;
                }
                $specialist = $salonSpecialists->random();

                // Выбираем клиента (приоритет — клиент того же салона)
                $salonClients = $allClients->where('salon_id', $salonId);
                $client = ($salonClients->isNotEmpty() && rand(0, 10) > 2)
                    ? $salonClients->random()
                    : $allClients->random();

                // Выбираем услугу
                $service = $services->random();

                // Время записи: с 9:00 до 19:00
                $hour   = rand(9, 19);
                $minute = [0, 15, 30, 45][rand(0, 3)];

                $startTime = $current->copy()->setTime($hour, $minute, 0);
                $endTime   = $startTime->copy()->addMinutes($service->duration_minutes);

                // Статус на основе даты
                $now = Carbon::create(2026, 6, 2, 23, 59);
                if ($startTime->isAfter($now)) {
                    // Будущее
                    $status = rand(0, 10) > 3 ? 'confirmed' : 'pending';
                } elseif ($startTime->isAfter(Carbon::create(2026, 6, 1))) {
                    // Сегодня/вчера
                    $r = rand(0, 10);
                    $status = $r > 5 ? 'completed' : ($r > 2 ? 'confirmed' : 'cancelled');
                } else {
                    // Прошлое
                    $r = rand(0, 10);
                    $status = $r > 1 ? 'completed' : 'cancelled';
                }

                // ~5% отмен даже в прошлом
                if ($status === 'completed' && rand(0, 100) < 5) {
                    $status = 'cancelled';
                }

                Booking::create([
                    'client_id'     => $client->id,
                    'salon_id'      => $salonId,
                    'service_id'    => $service->id,
                    'specialist_id' => $specialist->id,
                    'start_time'    => $startTime,
                    'end_time'      => $endTime,
                    'status'        => $status,
                    'notes'         => rand(0, 10) > 7 ? 'Постоянный клиент' : null,
                ]);
            }

            $current->addDay();
        }

        $count = Booking::count();
        $this->command->info("BookingSeeder: создано {$count} записей (01.01.2026 – 02.06.2026).");
    }
}
