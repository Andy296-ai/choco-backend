<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
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
        'salon_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public static function findOrCreateFromTelegram(array $telegramData)
    {
        // Проверяем наличие обязательных полей
        if (empty($telegramData['id']) || empty($telegramData['first_name'])) {
            throw new \InvalidArgumentException('Отсутствуют обязательные данные Telegram');
        }

        // Преобразуем telegram_id в строку для консистентности
        $telegramId = (string) $telegramData['id'];
        
        // Ищем существующего клиента по telegram_id
        $client = static::where('telegram_id', $telegramId)->first();

        if ($client) {
            // Обновляем данные существующего клиента
            $updateData = [
                'name' => trim($telegramData['first_name'] . ' ' . ($telegramData['last_name'] ?? '')),
                'telegram_username' => $telegramData['username'] ?? null,
                'telegram_first_name' => $telegramData['first_name'],
                'telegram_last_name' => $telegramData['last_name'] ?? null,
                'telegram_photo_url' => $telegramData['photo_url'] ?? null,
            ];
            
            $client->update($updateData);
            return $client;
        }

        // Создаем нового клиента
        // Генерируем уникальный email
        $baseEmail = $telegramData['username'] 
            ? $telegramData['username'] . '@telegram.com' 
            : 'telegram_' . $telegramId . '@telegram.com';
        
        // Проверяем уникальность email и добавляем суффикс если нужно
        $email = $baseEmail;
        $counter = 1;
        while (static::where('email', $email)->exists()) {
            $email = str_replace('@telegram.com', '_' . $counter . '@telegram.com', $baseEmail);
            $counter++;
        }

        try {
            $client = static::create([
                'name' => trim($telegramData['first_name'] . ' ' . ($telegramData['last_name'] ?? '')),
                'email' => $email,
                'phone' => null,
                'password' => bcrypt(Str::random(20)), 
                'telegram_id' => $telegramId,
                'telegram_username' => $telegramData['username'] ?? null,
                'telegram_first_name' => $telegramData['first_name'],
                'telegram_last_name' => $telegramData['last_name'] ?? null,
                'telegram_photo_url' => $telegramData['photo_url'] ?? null,
            ]);
            
            \Log::info('Client created from Telegram', [
                'client_id' => $client->id,
                'telegram_id' => $telegramId,
                'email' => $email
            ]);
            
            return $client;
        } catch (\Exception $e) {
            \Log::error('Failed to create client from Telegram', [
                'telegram_id' => $telegramId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
