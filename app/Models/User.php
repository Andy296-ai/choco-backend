<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'telegram_id',
        'telegram_username',
        'telegram_first_name',
        'telegram_last_name',
        'telegram_photo_url',
        'role',
        'salon_id',
    ];

    const ROLE_CLIENT = 'client';
    const ROLE_SPECIALIST = 'specialist';
    const ROLE_ADMIN = 'admin';
    const ROLE_DIRECTOR = 'director';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function findOrCreateFromTelegram(array $telegramData)
    {
        $user = static::where('telegram_id', $telegramData['id'])->first();

        if ($user) {
            $user->update([
                'name' => $telegramData['first_name'] . ' ' . ($telegramData['last_name'] ?? ''),
                'telegram_username' => $telegramData['username'] ?? null,
                'telegram_first_name' => $telegramData['first_name'],
                'telegram_last_name' => $telegramData['last_name'] ?? null,
                'telegram_photo_url' => $telegramData['photo_url'] ?? null,
            ]);
            return $user;
        }

        $email = $telegramData['username'] 
            ? $telegramData['username'] . '@telegram.com' 
            : $telegramData['id'] . '@telegram.com';

        return static::create([
            'name' => $telegramData['first_name'] . ' ' . ($telegramData['last_name'] ?? ''),
            'email' => $email,
            'phone' => null,
            'password' => bcrypt(Str::random(20)), 
            'telegram_id' => $telegramData['id'],
            'telegram_username' => $telegramData['username'] ?? null,
            'telegram_first_name' => $telegramData['first_name'],
            'telegram_last_name' => $telegramData['last_name'] ?? null,
            'telegram_photo_url' => $telegramData['photo_url'] ?? null,
            'role' => self::ROLE_CLIENT,
        ]);
    }


    public function isTelegramUser()
    {
        return !is_null($this->telegram_id);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function specialistBookings()
    {
        return $this->hasMany(Booking::class, 'specialist_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function portfolioItems()
    {
        return $this->hasMany(PortfolioItem::class);
    }

    public function isDirector() { return $this->role === self::ROLE_DIRECTOR; }
    public function isAdmin() { return $this->role === self::ROLE_ADMIN; }
    public function isSpecialist() { return $this->role === self::ROLE_SPECIALIST; }
    public function isClient() { return $this->role === self::ROLE_CLIENT; }
}