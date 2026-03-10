<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'salon_id',
        'service_id',
        'specialist_id',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function specialist()
    {
        return $this->belongsTo(User::class, 'specialist_id');
    }

    /**
     * Check if specialist is available.
     * 
     * @param int $specialistId ID специалиста
     * @param \Carbon\Carbon $start Время начала
     * @param \Carbon\Carbon $end Время окончания
     * @param int|null $excludeBookingId ID бронирования для исключения из проверки (при обновлении)
     * @return bool
     */
    public static function isSpecialistAvailable($specialistId, $start, $end, $excludeBookingId = null)
    {
        // Проверяем, что специалист существует
        $specialist = \App\Models\User::where('id', $specialistId)
            ->where('role', \App\Models\User::ROLE_SPECIALIST)
            ->first();

        if (!$specialist) {
            return false;
        }

        // 1. Check if specialist is working at this time (Schedules)
        $dayOfWeek = strtolower($start->format('l'));
        $schedule = \App\Models\Schedule::where('user_id', $specialistId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_working', true)
            ->first();

        // Если расписание не установлено, используем дефолтные рабочие часы (9:00 - 20:00)
        if (!$schedule) {
            $workStart = \Carbon\Carbon::parse($start->format('Y-m-d') . ' 09:00');
            $workEnd = \Carbon\Carbon::parse($start->format('Y-m-d') . ' 20:00');
        } else {
            $workStart = \Carbon\Carbon::parse($start->format('Y-m-d') . ' ' . $schedule->start_time);
            $workEnd = \Carbon\Carbon::parse($start->format('Y-m-d') . ' ' . $schedule->end_time);
        }

        // Проверяем, что запрашиваемое время попадает в рабочие часы
        if ($start->lt($workStart) || $end->gt($workEnd)) {
            return false;
        }

        // 2. Check for conflicting bookings
        $conflictsQuery = self::where('specialist_id', $specialistId)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    // Новое бронирование начинается во время существующего
                    $q->where('start_time', '>=', $start)->where('start_time', '<', $end);
                })->orWhere(function($q) use ($start, $end) {
                    // Новое бронирование заканчивается во время существующего
                    $q->where('end_time', '>', $start)->where('end_time', '<=', $end);
                })->orWhere(function($q) use ($start, $end) {
                    // Новое бронирование полностью перекрывает существующее
                    $q->where('start_time', '<=', $start)->where('end_time', '>=', $end);
                });
            });

        // Исключаем текущее бронирование при обновлении
        if ($excludeBookingId) {
            $conflictsQuery->where('id', '!=', $excludeBookingId);
        }

        $hasConflicts = $conflictsQuery->exists();

        return !$hasConflicts;
    }
}
