<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Booking;
use App\Services\TelegramNotificationService;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Планировщик задач для напоминаний о записях
Schedule::call(function () {
    $telegramService = app(TelegramNotificationService::class);
    
    // Напоминание за 24 часа
    $bookings24h = Booking::where('status', 'confirmed')
        ->whereBetween('start_time', [
            Carbon::now()->addHours(24)->subMinutes(30),
            Carbon::now()->addHours(24)->addMinutes(30)
        ])
        ->with(['client', 'service', 'specialist', 'salon'])
        ->get();
    
    foreach ($bookings24h as $booking) {
        if ($booking->client && $booking->client->telegram_id) {
            $telegramService->notifyBookingReminder($booking, 24);
        }
    }
    
    // Напоминание за 1 час
    $bookings1h = Booking::where('status', 'confirmed')
        ->whereBetween('start_time', [
            Carbon::now()->addHour()->subMinutes(15),
            Carbon::now()->addHour()->addMinutes(15)
        ])
        ->with(['client', 'service', 'specialist', 'salon'])
        ->get();
    
    foreach ($bookings1h as $booking) {
        if ($booking->client && $booking->client->telegram_id) {
            $telegramService->notifyBookingReminder($booking, 1);
        }
    }
})->hourly();
