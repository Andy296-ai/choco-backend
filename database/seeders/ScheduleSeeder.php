<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('schedules')->truncate();

        $specialists = User::where('role', User::ROLE_SPECIALIST)->get();

        // Расписания разные для разных мастеров (реалистичность)
        $scheduleProfiles = [
            // Пн-Пт 9-18, сб 10-16, вс выходной
            'weekdays' => [
                'monday'    => ['09:00', '18:00', true],
                'tuesday'   => ['09:00', '18:00', true],
                'wednesday' => ['09:00', '18:00', true],
                'thursday'  => ['09:00', '18:00', true],
                'friday'    => ['09:00', '18:00', true],
                'saturday'  => ['10:00', '16:00', true],
                'sunday'    => [null,    null,    false],
            ],
            // Вт-Сб 10-19, пн/вс выходной
            'offset' => [
                'monday'    => [null,    null,    false],
                'tuesday'   => ['10:00', '19:00', true],
                'wednesday' => ['10:00', '19:00', true],
                'thursday'  => ['10:00', '19:00', true],
                'friday'    => ['10:00', '19:00', true],
                'saturday'  => ['10:00', '19:00', true],
                'sunday'    => [null,    null,    false],
            ],
            // Пн-Сб 10-18, вс выходной (стандарт)
            'standard' => [
                'monday'    => ['10:00', '18:00', true],
                'tuesday'   => ['10:00', '18:00', true],
                'wednesday' => ['10:00', '18:00', true],
                'thursday'  => ['10:00', '18:00', true],
                'friday'    => ['10:00', '18:00', true],
                'saturday'  => ['10:00', '18:00', true],
                'sunday'    => [null,    null,    false],
            ],
        ];

        $profiles = array_keys($scheduleProfiles);

        foreach ($specialists as $idx => $specialist) {
            $profile = $scheduleProfiles[$profiles[$idx % count($profiles)]];
            $salonId = $specialist->salon_id;

            foreach ($profile as $day => [$start, $end, $isWorking]) {
                Schedule::create([
                    'user_id'    => $specialist->id,
                    'salon_id'   => $salonId,
                    'day_of_week'=> $day,
                    'start_time' => $start ? $start . ':00' : null,
                    'end_time'   => $end   ? $end   . ':00' : null,
                    'is_working' => $isWorking,
                ]);
            }
        }
    }
}
