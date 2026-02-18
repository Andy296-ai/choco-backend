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
     */
    public static function isSpecialistAvailable($specialistId, $start, $end)
    {
        // 1. Check if specialist is working at this time (Schedules)
        $dayOfWeek = strtolower($start->format('l'));
        $schedule = \App\Models\Schedule::where('user_id', $specialistId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_working', true)
            ->first();

        if (!$schedule) return false;

        $workStart = \Carbon\Carbon::parse($start->format('Y-m-d') . ' ' . $schedule->start_time);
        $workEnd = \Carbon\Carbon::parse($start->format('Y-m-d') . ' ' . $schedule->end_time);

        if ($start->lt($workStart) || $end->gt($workEnd)) return false;

        // 2. Check for conflicting bookings
        $conflicts = self::where('specialist_id', $specialistId)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    $q->where('start_time', '>=', $start)->where('start_time', '<', $end);
                })->orWhere(function($q) use ($start, $end) {
                    $q->where('end_time', '>', $start)->where('end_time', '<=', $end);
                })->orWhere(function($q) use ($start, $end) {
                    $q->where('start_time', '<=', $start)->where('end_time', '>=', $end);
                });
            })
            ->exists();

        return !$conflicts;
    }
}
