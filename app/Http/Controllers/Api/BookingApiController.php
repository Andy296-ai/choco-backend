<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingApiController extends Controller
{
    /**
     * Get list of salons.
     */
    public function getSalons()
    {
        return response()->json(Salon::all());
    }

    /**
     * Get list of services.
     */
    public function getServices()
    {
        return response()->json(Service::all());
    }

    /**
     * Get list of specialists.
     * Can be filtered by salon_id.
     */
    public function getSpecialists(Request $request)
    {
        $query = User::where('role', User::ROLE_SPECIALIST);

        if ($request->has('salon_id')) {
            $query->where('salon_id', $request->salon_id);
        }

        return response()->json($query->get());
    }

    /**
     * Get available time slots for a specialist on a specific date.
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'specialist_id' => 'required', // Can be 'any'
            'date' => 'required|date',
            'salon_id' => 'nullable|exists:salons,id', // обязателен при specialist_id=any, передаётся с фронта
            'service_id' => 'nullable|exists:services,id', // для длительности; если нет — 60 мин
        ]);

        $date = Carbon::parse($request->date);

        // Проверяем, что дата не в прошлом
        if ($date->isPast() && !$date->isToday()) {
            return response()->json(['error' => 'Нельзя выбрать прошедшую дату'], 422);
        }

        // Длительность: из услуги или по умолчанию 60 минут
        $duration = 60;
        if ($request->filled('service_id')) {
            $service = Service::find($request->service_id);
            if ($service) {
                $duration = $service->duration_minutes;
            }
        }

        // Получаем расписание специалиста или салона для определения рабочих часов
        $workStart = 9; // По умолчанию с 9:00
        $workEnd = 20; // По умолчанию до 20:00
        
        $possibleSlots = [];
        for ($hour = $workStart; $hour < $workEnd; $hour++) {
            $possibleSlots[] = sprintf('%02d:00', $hour);
        }

        $availableSlots = [];

        if ($request->specialist_id === 'any') {
            // При "любой специалист" нужен salon_id
            if (!$request->filled('salon_id')) {
                return response()->json(['error' => 'Выберите салон для поиска свободного времени'], 422);
            }

            $specialists = User::where('role', User::ROLE_SPECIALIST)
                ->where('salon_id', $request->salon_id)
                ->get();

            if ($specialists->isEmpty()) {
                return response()->json(['error' => 'В этом салоне нет специалистов'], 422);
            }

            foreach ($possibleSlots as $slot) {
                $start = Carbon::parse($date->format('Y-m-d') . ' ' . $slot);
                $end = (clone $start)->addMinutes($duration);

                // Проверяем, что время не в прошлом (если это сегодня)
                if ($start->isPast()) {
                    continue;
                }

                // Проверяем, есть ли хотя бы один свободный специалист
                $hasAvailableSpecialist = false;
                foreach ($specialists as $specialist) {
                    if (Booking::isSpecialistAvailable($specialist->id, $start, $end)) {
                        $hasAvailableSpecialist = true;
                        break;
                    }
                }

                if ($hasAvailableSpecialist) {
                    $availableSlots[] = $slot;
                }
            }
        } else {
            // Проверяем доступность конкретного специалиста
            $specialist = User::where('id', $request->specialist_id)
                ->where('role', User::ROLE_SPECIALIST)
                ->first();

            if (!$specialist) {
                return response()->json(['error' => 'Специалист не найден'], 404);
            }

            // Получаем расписание специалиста для этого дня недели
            $dayOfWeek = strtolower($date->format('l'));
            $schedule = \App\Models\Schedule::where('user_id', $specialist->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_working', true)
                ->first();

            if ($schedule) {
                $workStartTime = Carbon::parse($schedule->start_time);
                $workEndTime = Carbon::parse($schedule->end_time);
                $workStart = (int) $workStartTime->format('H');
                $workEnd = (int) $workEndTime->format('H');
                
                // Пересчитываем возможные слоты на основе расписания
                $possibleSlots = [];
                for ($hour = $workStart; $hour < $workEnd; $hour++) {
                    $possibleSlots[] = sprintf('%02d:00', $hour);
                }
            }

            foreach ($possibleSlots as $slot) {
                $start = Carbon::parse($date->format('Y-m-d') . ' ' . $slot);
                $end = (clone $start)->addMinutes($duration);

                // Проверяем, что время не в прошлом (если это сегодня)
                if ($start->isPast()) {
                    continue;
                }

                if (Booking::isSpecialistAvailable($request->specialist_id, $start, $end)) {
                    $availableSlots[] = $slot;
                }
            }
        }
        
        return response()->json($availableSlots);
    }
}
