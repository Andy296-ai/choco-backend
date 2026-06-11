<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    private $botToken;
    private $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    public function sendMessage($telegramId, $message)
    {
        if (empty($telegramId) || empty($this->botToken)) {
            Log::warning('Telegram notification skipped: missing telegram_id or bot_token');
            return false;
        }

        \App\Jobs\SendTelegramNotification::dispatch($telegramId, $message);
        return true;
    }

    public function getProxyOptions(): array
    {
        $proxy = config('services.telegram.proxy');
        if (empty($proxy)) {
            return [];
        }
        return ['proxy' => $proxy];
    }

    /**
     * Уведомление о создании записи
     */
    public function notifyBookingCreated($booking)
    {
        $client = $booking->client;
        if (!$client || !$client->telegram_id) {
            return false;
        }

        $message = "✅ <b>Запись создана!</b>\n\n";
        $message .= "Услуга: " . ($booking->service->name ?? 'Услуга') . "\n";
        $message .= "Дата и время: " . $booking->start_time->translatedFormat('j F Y, H:i') . "\n";
        $message .= "Мастер: " . ($booking->specialist->name ?? 'Не назначен') . "\n";
        $message .= "Салон: " . ($booking->salon->name ?? 'Салон') . "\n";
        $message .= "Статус: В ожидании";

        return $this->sendMessage($client->telegram_id, $message);
    }

    /**
     * Уведомление об изменении статуса записи
     */
    public function notifyBookingStatusChanged($booking)
    {
        $client = $booking->client;
        if (!$client || !$client->telegram_id) {
            return false;
        }

        $statusMessages = [
            'confirmed' => "✅ <b>Запись подтверждена!</b>",
            'completed' => "✨ <b>Запись завершена!</b>",
            'cancelled' => "❌ <b>Запись отменена</b>"
        ];

        $statusText = $statusMessages[$booking->status] ?? "📋 <b>Статус записи изменен</b>";

        $message = "{$statusText}\n\n";
        $message .= "Услуга: " . ($booking->service->name ?? 'Услуга') . "\n";
        $message .= "Дата и время: " . $booking->start_time->translatedFormat('j F Y, H:i') . "\n";
        $message .= "Мастер: " . ($booking->specialist->name ?? 'Не назначен');

        return $this->sendMessage($client->telegram_id, $message);
    }

    /**
     * Напоминание о записи (за день или за час)
     */
    public function notifyBookingReminder($booking, $hoursBefore = 24)
    {
        $client = $booking->client;
        if (!$client || !$client->telegram_id) {
            return false;
        }

        $hoursText = $hoursBefore >= 5 ? 'часов' : ($hoursBefore == 1 ? 'час' : 'часа');
        
        $message = "🔔 <b>Напоминание о записи</b>\n\n";
        $message .= "Через {$hoursBefore} {$hoursText} у вас запись:\n";
        $message .= "Услуга: " . ($booking->service->name ?? 'Услуга') . "\n";
        $message .= "Время: " . $booking->start_time->translatedFormat('j F Y, H:i') . "\n";
        $message .= "Мастер: " . ($booking->specialist->name ?? 'Не назначен') . "\n";
        $message .= "Салон: " . ($booking->salon->name ?? 'Салон');

        return $this->sendMessage($client->telegram_id, $message);
    }

    /**
     * Уведомление об отмене записи
     */
    public function notifyBookingCancelled($booking)
    {
        $client = $booking->client;
        if (!$client || !$client->telegram_id) {
            return false;
        }

        $message = "❌ <b>Запись отменена</b>\n\n";
        $message .= "Ваша запись была отменена:\n";
        $message .= "Услуга: " . ($booking->service->name ?? 'Услуга') . "\n";
        $message .= "Дата и время: " . $booking->start_time->translatedFormat('j F Y, H:i') . "\n";
        $message .= "Мастер: " . ($booking->specialist->name ?? 'Не назначен');

        return $this->sendMessage($client->telegram_id, $message);
    }
}

