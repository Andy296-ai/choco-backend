<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Services\TelegramNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Store a new booking.
     */
    public function store(\App\Http\Requests\StoreBookingRequest $request)
    {
        // Проверяем авторизацию клиента — для записи нужен вход через Telegram
        if (!Auth::guard('client')->check()) {
            return response()->json([
                'error' => 'Для онлайн-записи необходимо войти через Telegram.',
                'require_auth' => true,
            ], 401);
        }

        $validated = $request->validated();

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $endTime = (clone $startTime)->addMinutes($service->duration_minutes);

        // Проверяем, что время не в прошлом
        if ($startTime->isPast()) {
            return response()->json(['error' => 'Нельзя записаться на прошедшее время.'], 422);
        }

        $specialistId = $validated['specialist_id'];

        // Logic for "Any specialist"
        if ($specialistId === 'any') {
            $specialistId = $this->findAvailableSpecialist($validated['salon_id'], $startTime, $endTime);
            if (!$specialistId) {
                return response()->json(['error' => 'К сожалению, на выбранное время нет свободных мастеров.'], 422);
            }
        } else {
            // Проверяем, что специалист существует и работает в этом салоне
            $specialist = User::where('id', $specialistId)
                ->where('role', User::ROLE_SPECIALIST)
                ->where('salon_id', $validated['salon_id'])
                ->first();
            
            if (!$specialist) {
                return response()->json(['error' => 'Выбранный мастер не найден или не работает в этом салоне.'], 422);
            }
            
            // Check if specific specialist is available
            if (!Booking::isSpecialistAvailable($specialistId, $startTime, $endTime)) {
                return response()->json(['error' => 'Выбранный мастер занят на это время.'], 422);
            }
        }

        // Используем транзакцию для атомарности операции
        try {
            \DB::beginTransaction();

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

            \DB::commit();

            // Отправка уведомления через Telegram (после успешного создания)
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
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to create booking', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Ошибка при создании записи. Попробуйте позже.'], 500);
        }
    }

    public function cancel(Booking $booking)
    {
        // Проверяем авторизацию
        if (!Auth::guard('client')->check()) {
            return response()->json(['error' => 'Необходима авторизация'], 401);
        }

        // Проверяем права доступа (клиент может отменять только свои записи)
        if ($booking->client_id !== Auth::guard('client')->id()) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }

        // Проверяем, что запись еще не отменена или завершена
        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return response()->json(['error' => 'Нельзя отменить запись со статусом: ' . $booking->status], 422);
        }

        try {
            \DB::beginTransaction();
            
            $booking->update(['status' => 'cancelled']);
            
            \DB::commit();

            // Отправка уведомления об отмене
            try {
                $telegramService = app(TelegramNotificationService::class);
                $telegramService->notifyBookingCancelled($booking->load(['client', 'service', 'specialist', 'salon']));
            } catch (\Exception $e) {
                \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
            }

            return response()->json(['message' => 'Запись отменена']);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to cancel booking', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Ошибка при отмене записи'], 500);
        }
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
