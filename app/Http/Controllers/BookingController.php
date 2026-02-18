<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Services\TelegramNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Store a new booking.
     */
    public function store(\App\Http\Requests\StoreBookingRequest $request)
    {
        $validated = $request->validated();

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $endTime = (clone $startTime)->addMinutes($service->duration_minutes);

        $specialistId = $validated['specialist_id'];

        // Logic for "Any specialist"
        if ($specialistId === 'any') {
            $specialistId = $this->findAvailableSpecialist($validated['salon_id'], $startTime, $endTime);
            if (!$specialistId) {
                return response()->json(['error' => 'К сожалению, на выбранное время нет свободных мастеров.'], 422);
            }
        } else {
            // Check if specific specialist is available
            if (!Booking::isSpecialistAvailable($specialistId, $startTime, $endTime)) {
                return response()->json(['error' => 'Выбранный мастер занят на это время.'], 422);
            }
        }

        // Create the booking
        $booking = Booking::create([
            'client_id' => Auth::guard('client')->id(),
            'salon_id' => $validated['salon_id'],
            'service_id' => $validated['service_id'],
            'specialist_id' => $specialistId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Отправка уведомления через Telegram
        try {
            $telegramService = app(TelegramNotificationService::class);
            $telegramService->notifyBookingCreated($booking->load(['client', 'service', 'specialist', 'salon']));
        } catch (\Exception $e) {
            // Логируем ошибку, но не прерываем создание записи
            \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Запись успешно создана!',
            'booking' => $booking
        ]);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->client_id !== Auth::guard('client')->id()) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }

        $booking->update(['status' => 'cancelled']);

        // Отправка уведомления об отмене
        try {
            $telegramService = app(TelegramNotificationService::class);
            $telegramService->notifyBookingCancelled($booking->load(['client', 'service', 'specialist', 'salon']));
        } catch (\Exception $e) {
            \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Запись отменена']);
    }

    /**
     * Find an available specialist in a salon for a given time range.
     */
    private function findAvailableSpecialist($salonId, $start, $end)
    {
        $specialists = User::where('role', User::ROLE_SPECIALIST)
            ->where('salon_id', $salonId)
            ->get();

        foreach ($specialists as $specialist) {
            if (Booking::isSpecialistAvailable($specialist->id, $start, $end)) {
                return $specialist->id;
            }
        }

        return null;
    }
}
