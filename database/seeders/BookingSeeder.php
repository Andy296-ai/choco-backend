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
        // Helper to get client by name (from clients table)
        $getClientId = fn($name) => Client::where('name', $name)->first()?->id;
        // Helper to get specialist by name (from users table)
        $getMasterId = fn($name) => User::where('name', $name)->first()?->id;
        // Helper to get salon by name
        $getSalonId = fn($name) => Salon::where('name', 'like', "%$name%")->first()?->id;
        // Helper to get service by name
        $getServiceId = fn($name) => Service::where('name', 'like', "%$name%")->first()?->id;

        $bookings = [
            // Past bookings (completed)
            [
                'client_name' => 'Иван Петров',
                'salon_keyword' => 'Хотьково',
                'service_keyword' => 'Мужская',
                'master_name' => 'Елена Кузнецова',
                'start_time' => Carbon::now()->subDays(10)->setTime(10, 0),
                'end_time' => Carbon::now()->subDays(10)->setTime(10, 40),
                'status' => 'completed',
                'notes' => 'Клиент доволен',
            ],
            [
                'client_name' => 'Мария Иванова',
                'salon_keyword' => 'Центр',
                'service_keyword' => 'Маникюр + гель',
                'master_name' => 'Ольга Смирнова',
                'start_time' => Carbon::now()->subDays(7)->setTime(14, 0),
                'end_time' => Carbon::now()->subDays(7)->setTime(15, 30),
                'status' => 'completed',
                'notes' => null,
            ],

            // Upcoming bookings
            [
                'client_name' => 'Екатерина Смирнова',
                'salon_keyword' => 'Центр',
                'service_keyword' => 'Женская стрижка',
                'master_name' => 'Елена Кузнецова',
                'start_time' => Carbon::now()->addDays(2)->setTime(10, 0),
                'end_time' => Carbon::now()->addDays(2)->setTime(11, 0),
                'status' => 'confirmed',
                'notes' => null,
            ],
        ];

        foreach ($bookings as $b) {
            $clientId = $getClientId($b['client_name']);
            $salonId = $getSalonId($b['salon_keyword']);
            $serviceId = $getServiceId($b['service_keyword']);
            $masterId = $getMasterId($b['master_name']);

            if ($clientId && $salonId && $serviceId && $masterId) {
                Booking::create([
                    'client_id' => $clientId,
                    'salon_id' => $salonId,
                    'service_id' => $serviceId,
                    'specialist_id' => $masterId,
                    'start_time' => $b['start_time'],
                    'end_time' => $b['end_time'],
                    'status' => $b['status'],
                    'notes' => $b['notes'],
                ]);
            }
        }
    }
}
