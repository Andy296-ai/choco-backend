<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_telegram_auth_creates_new_client()
    {
        Config::set('services.telegram.bot_token', 'test_token');
        
        // Mocking the behavior of verifyTelegramHash is hard without actually calculating it,
        // so we'll test the findOrCreateFromTelegram logic directly or mock the controller method if possible.
        // For feature test, we'll try to simulate a valid request.
        
        $data = [
            'id' => '12345678',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'auth_date' => time(),
        ];
        
        // Calculate a valid hash for the test
        $botToken = 'test_token';
        $dataForCheck = $data;
        ksort($dataForCheck);
        $dataCheckString = [];
        foreach ($dataForCheck as $key => $value) {
            $dataCheckString[] = $key . '=' . $value;
        }
        $dataCheckString = implode("\n", $dataCheckString);
        $secretKey = hash('sha256', $botToken, true);
        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);
        
        $data['hash'] = $hash;

        $response = $this->get(route('auth.telegram', $data));

        $response->assertRedirect(route('profile'));
        $this->assertDatabaseHas('clients', [
            'telegram_id' => '12345678',
            'telegram_username' => 'johndoe',
        ]);
        $this->assertTrue(\Auth::guard('client')->check());
    }

    public function test_telegram_auth_fails_with_invalid_hash()
    {
        Config::set('services.telegram.bot_token', 'test_token');
        
        $data = [
            'id' => '12345678',
            'first_name' => 'John',
            'auth_date' => time(),
            'hash' => 'invalid_hash',
        ];

        $response = $this->get(route('auth.telegram', $data));

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('error', 'Неверная подпись Telegram');
        $this->assertFalse(\Auth::guard('client')->check());
    }
}
