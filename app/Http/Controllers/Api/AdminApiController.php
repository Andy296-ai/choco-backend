<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Schedule;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminApiController extends Controller
{
    public function storeBooking(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'specialist_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'status' => 'required|string|in:pending,confirmed,completed,cancelled',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = (clone $startTime)->addMinutes($service->duration_minutes);

        $specialist = User::where('id', $validated['specialist_id'])
            ->where('role', User::ROLE_SPECIALIST)
            ->where('salon_id', $user->salon_id)
            ->first();

        if (!$specialist) {
            return response()->json(['error' => 'Специалист не найден или не работает в вашем салоне'], 422);
        }

        if ($validated['status'] !== 'cancelled') {
            if (!Booking::isSpecialistAvailable($validated['specialist_id'], $startTime, $endTime)) {
                return response()->json(['error' => 'Выбранный мастер занят на это время'], 422);
            }
        }

        try {
            \DB::beginTransaction();

            $booking = Booking::create([
                'client_id' => $validated['client_id'],
                'salon_id' => $user->salon_id,
                'service_id' => $validated['service_id'],
                'specialist_id' => $validated['specialist_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $validated['status'],
            ]);

            \DB::commit();

            try {
                $telegramService = app(TelegramNotificationService::class);
                $telegramService->notifyBookingCreated($booking->load(['client', 'service', 'specialist', 'salon']));
            } catch (\Exception $e) {
                \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
            }

            return response()->json(['message' => 'Запись создана', 'booking' => $booking]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Ошибка при создании записи'], 500);
        }
    }

    public function updateBooking(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if ($user->salon_id && $booking->salon_id !== $user->salon_id) {
            return response()->json(['error' => 'Нет доступа к этой записи'], 403);
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'specialist_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'status' => 'required|string|in:pending,confirmed,completed,cancelled',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = (clone $startTime)->addMinutes($service->duration_minutes);

        if ($validated['status'] !== 'cancelled' && $startTime->isPast()) {
            return response()->json(['error' => 'Нельзя установить прошедшее время для активной записи'], 422);
        }

        if ($validated['status'] !== 'cancelled') {
            if (!Booking::isSpecialistAvailable($validated['specialist_id'], $startTime, $endTime, $booking->id)) {
                return response()->json(['error' => 'Выбранный мастер занят на это время'], 422);
            }
        }

        $oldStatus = $booking->status;
        
        try {
            \DB::beginTransaction();

            $booking->update([
                'service_id' => $validated['service_id'],
                'specialist_id' => $validated['specialist_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $validated['status'],
            ]);

            \DB::commit();

            if ($oldStatus !== $validated['status']) {
                try {
                    $telegramService = app(TelegramNotificationService::class);
                    $telegramService->notifyBookingStatusChanged($booking->load(['client', 'service', 'specialist', 'salon']));
                } catch (\Exception $e) {
                    \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
                }
            }

            return response()->json(['message' => 'Запись обновлена', 'booking' => $booking]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Ошибка при обновлении записи'], 500);
        }
    }

    public function deleteBooking(Booking $booking)
    {
        $user = Auth::user();
        if ($user->salon_id && $booking->salon_id !== $user->salon_id) {
            return response()->json(['error' => 'Нет доступа к этой записи'], 403);
        }

        $booking->delete();
        return response()->json(['message' => 'Запись удалена']);
    }

    public function storeClient(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:255',
            'email'             => 'nullable|email|max:255',
            'telegram_username' => 'nullable|string|max:255',
        ]);

        // Определяем email: приоритет — введённый вручную, затем из telegram, затем авто
        if (!empty($validated['email'])) {
            $email = $validated['email'];
            if (Client::where('email', $email)->exists()) {
                return response()->json(['message' => 'Клиент с таким email уже существует'], 422);
            }
        } else {
            $baseEmail = $validated['telegram_username']
                ? $validated['telegram_username'] . '@client.local'
                : 'client_' . time() . '@client.local';
            $email = $baseEmail;
            $counter = 1;
            while (Client::where('email', $email)->exists()) {
                $email = str_replace('@client.local', '_' . $counter . '@client.local', $baseEmail);
                $counter++;
            }
        }

        // Привязываем клиента к салону текущего пользователя
        $salonId = auth()->user()?->salon_id ?? null;

        $client = Client::create([
            'name'              => $validated['name'],
            'email'             => $email,
            'phone'             => $validated['phone'] ?? null,
            'password'          => bcrypt(\Illuminate\Support\Str::random(16)),
            'telegram_username' => $validated['telegram_username'] ?? null,
            'salon_id'          => $salonId,
        ]);

        return response()->json(['message' => 'Клиент успешно добавлен', 'client' => $client]);
    }

    public function showClient(Client $client)
    {
        $client->load(['bookings.service', 'bookings.specialist', 'bookings.salon']);
        $bookings = $client->bookings()->orderBy('start_time', 'desc')->get();
        return response()->json([
            'client' => $client,
            'bookings' => $bookings
        ]);
    }

    public function getSchedule(User $master)
    {
        if ($master->role !== User::ROLE_SPECIALIST) {
            return response()->json(['error' => 'Пользователь не является специалистом'], 422);
        }

        $schedules = Schedule::where('user_id', $master->id)->get();

        $daysMap = [
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
        ];

        $formattedSchedules = $schedules->map(function ($schedule) use ($daysMap) {
            $schedule->day_of_week = $daysMap[strtolower($schedule->day_of_week)] ?? null;
            return $schedule;
        });

        return response()->json(['schedules' => $formattedSchedules]);
    }

    public function updateSchedule(Request $request, User $master)
    {
        if ($master->role !== User::ROLE_SPECIALIST) {
            return response()->json(['error' => 'Пользователь не является специалистом'], 422);
        }

        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|min:0|max:6',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i',
            'schedules.*.is_working' => 'required|boolean',
        ]);

        \DB::beginTransaction();
        try {
            $master->schedules()->delete();
            
            $daysArray = [
                0 => 'sunday',
                1 => 'monday',
                2 => 'tuesday',
                3 => 'wednesday',
                4 => 'thursday',
                5 => 'friday',
                6 => 'saturday',
            ];

            foreach ($validated['schedules'] as $index => $data) {
                $dayString = $daysArray[(int)$data['day_of_week']] ?? null;
                if (!$dayString) continue;

                Schedule::updateOrCreate(
                    [
                        'user_id' => $master->id,
                        'day_of_week' => $dayString,
                    ],
                    [
                        'salon_id' => $master->salon_id,
                        'is_working' => $data['is_working'] ?? false,
                        'start_time' => $data['start_time'] ?? null,
                        'end_time' => $data['end_time'] ?? null,
                    ]
                );
            }
            \DB::commit();
            return response()->json(['message' => 'График успешно обновлен']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Ошибка при сохранении графика'], 500);
        }
    }

    public function storeAbsence(Request $request, User $master)
    {
        if ($master->role !== User::ROLE_SPECIALIST) {
            return response()->json(['error' => 'Пользователь не является специалистом'], 422);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        \App\Models\Absence::create([
            'user_id' => $master->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
        ]);

        return response()->json(['message' => 'Отсутствие специалиста успешно добавлено']);
    }

    public function destroyAbsence(\App\Models\Absence $absence)
    {
        $user = Auth::user();

        // Проверяем что мастер принадлежит салону администратора
        if ($user->salon_id && $absence->user->salon_id !== $user->salon_id) {
            return response()->json(['error' => 'Нет доступа'], 403);
        }

        $absence->delete();
        return response()->json(['message' => 'Отсутствие удалено']);
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $service = Service::create($validated);

        return response()->json(['message' => 'Услуга добавлена', 'service' => $service]);
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $service->update($validated);

        return response()->json(['message' => 'Услуга обновлена', 'service' => $service]);
    }

    public function deleteService(Service $service)
    {
        if ($service->bookings()->exists()) {
            return response()->json(['message' => 'Нельзя удалить услугу, на которую есть записи'], 422);
        }

        $service->delete();
        return response()->json(['message' => 'Услуга удалена']);
    }

    /**
     * Обновить скидку и заметки клиента
     */
    public function updateClientDiscount(Request $request, Client $client)
    {
        $validated = $request->validate([
            'discount' => 'required|integer|min:0|max:100',
            'notes'    => 'nullable|string|max:1000',
        ]);

        $client->update($validated);

        return response()->json([
            'message' => 'Скидка клиента обновлена',
            'client'  => $client,
        ]);
    }
}
