<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $telegramId;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct($telegramId, $message)
    {
        $this->telegramId = $telegramId;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $botToken = config('services.telegram.bot_token');
        
        if (empty($this->telegramId) || empty($botToken)) {
            Log::warning('Telegram notification skipped: missing telegram_id or bot_token');
            return;
        }

        try {
            $apiUrl = "https://api.telegram.org/bot{$botToken}";

            $client = Http::timeout(15);
            $proxy = config('services.telegram.proxy');
            if (!empty($proxy)) {
                $client = $client->withOptions(['proxy' => $proxy]);
            }

            $response = $client->post("{$apiUrl}/sendMessage", [
                'chat_id' => $this->telegramId,
                'text' => $this->message,
                'parse_mode' => 'HTML'
            ]);

            if (!$response->successful()) {
                Log::error('Telegram API error from Job', [
                    'response' => $response->json(),
                    'telegram_id' => $this->telegramId
                ]);
                
                // If we want to retry on failure:
                if ($response->status() >= 500 || $response->status() == 429) {
                    $this->release(60); // retry after 60 seconds
                }
            }
        } catch (\Exception $e) {
            Log::error('Telegram notification exception from Job', [
                'error' => $e->getMessage(),
                'telegram_id' => $this->telegramId
            ]);
            
            throw $e; // Let Laravel know it failed so it triggers retry
        }
    }
}
