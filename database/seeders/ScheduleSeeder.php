<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all specialists
        $specialists = User::where('role', User::ROLE_SPECIALIST)->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        foreach ($specialists as $specialist) {
            $salonId = $specialist->salon_id;

            foreach ($days as $day) {
                Schedule::create([
                    'user_id' => $specialist->id,
                    'salon_id' => $salonId,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00',
                    'is_working' => true,
                ]);
            }

            // Sunday - day off
            Schedule::create([
                'user_id' => $specialist->id,
                'salon_id' => $salonId,
                'day_of_week' => 'sunday',
                'start_time' => null,
                'end_time' => null,
                'is_working' => false,
            ]);
        }
    }
}
