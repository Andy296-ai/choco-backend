<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        $salons = Salon::all();
        $services = Service::all();
        $specialists = User::where('role', User::ROLE_SPECIALIST)->get();

        if ($clients->isEmpty() || $salons->isEmpty() || $services->isEmpty() || $specialists->isEmpty()) {
            return;
        }

        // Generate ~150 historical bookings over the last 6 months
        for ($i = 0; $i < 150; $i++) {
            $service = $services->random();
            $specialist = $specialists->random();
            
            // Try to match salon to specialist's salon if possible
            $salon = $specialist->salon_id ? Salon::find($specialist->salon_id) : $salons->random();
            
            // Random date in the last 6 months
            $daysAgo = rand(0, 180);
            $hour = rand(9, 19);
            $minute = rand(0, 5) * 10;
            
            $startTime = Carbon::now()->subDays($daysAgo)->setTime($hour, $minute);
            $endTime = (clone $startTime)->addMinutes($service->duration_minutes);

            // 85% chance of being completed if in the past
            // 15% chance of being cancelled or confirmed (if near future)
            $status = 'completed';
            if ($startTime->isFuture()) {
                $status = rand(0, 10) > 2 ? 'confirmed' : 'cancelled';
            } else {
                $status = rand(0, 10) > 1 ? 'completed' : 'cancelled';
            }

            Booking::create([
                'client_id' => $clients->random()->id,
                'salon_id' => $salon->id,
                'service_id' => $service->id,
                'specialist_id' => $specialist->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $status,
                'notes' => rand(0, 10) > 7 ? 'Сгенерированная запись для отчёта' : null,
            ]);
        }
    }
}
