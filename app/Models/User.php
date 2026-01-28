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
    ];

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
        ]);
    }


    public function isTelegramUser()
    {
        return !is_null($this->telegram_id);
    }
}