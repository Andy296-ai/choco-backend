<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Authenticate via Telegram Widget data
     */
    public function telegramAuth(Request $request)
    {
        try {
            $data = $request->all();
            \Illuminate\Support\Facades\Log::info('Telegram Auth Request:', $data);
            
            // Проверяем наличие обязательных полей
            if (empty($data['id']) || empty($data['first_name']) || empty($data['auth_date']) || empty($data['hash'])) {
                \Illuminate\Support\Facades\Log::error('Telegram Auth: Missing required fields', $data);
                return redirect()->route('profile')->with('error', 'Неполные данные от Telegram');
            }
            
            if (!$this->verifyTelegramHash($data)) {
                \Illuminate\Support\Facades\Log::error('Telegram Hash Verification Failed.', ['data' => $data]);
                return redirect()->route('profile')->with('error', 'Неверная подпись Telegram');
            }
            
            $telegramData = [
                'id' => $data['id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'username' => $data['username'] ?? null,
                'photo_url' => $data['photo_url'] ?? null,
                'auth_date' => $data['auth_date'],
            ];
            
            $client = Client::findOrCreateFromTelegram($telegramData);
            \Illuminate\Support\Facades\Log::info('Client found/created:', ['id' => $client->id, 'name' => $client->name]);
            
            // Входим через guard и регенерируем сессию
            Auth::guard('client')->login($client, true);
            $request->session()->regenerate();
            
            \Illuminate\Support\Facades\Log::info('Client logged in via guard. Is check() true? ' . (Auth::guard('client')->check() ? 'Yes' : 'No'));
            
            return redirect()->route('profile')->with('success', 'Вы успешно вошли через Telegram!');
            
        } catch (\InvalidArgumentException $e) {
            \Illuminate\Support\Facades\Log::error('Telegram Auth Validation Error: ' . $e->getMessage());
            return redirect()->route('profile')->with('error', 'Ошибка валидации данных: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Telegram Auth Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('profile')->with('error', 'Ошибка авторизации. Попробуйте позже или обратитесь в поддержку.');
        }
    }
    
    private function verifyTelegramHash(array $data)
    {
        $botToken = config('services.telegram.bot_token');
        if (empty($botToken)) {
            \Log::error('Telegram bot token is not configured');
            return false;
        }

        // Проверяем наличие hash
        if (empty($data['hash'])) {
            \Log::error('Telegram hash is missing');
            return false;
        }

        $checkHash = $data['hash'];
        $authDate = $data['auth_date'] ?? null;
        
        // Проверяем срок действия данных (не более 24 часов)
        if ($authDate && (time() - (int)$authDate) > 86400) {
            \Log::error('Telegram auth data expired', ['auth_date' => $authDate]);
            return false;
        }
        
        // Создаем копию данных без hash для проверки
        $dataForCheck = $data;
        unset($dataForCheck['hash']);
        
        // Сортируем ключи
        ksort($dataForCheck);
        
        // Формируем строку для проверки
        $dataCheckString = [];
        foreach ($dataForCheck as $key => $value) {
            if ($value !== null && $value !== '') {
                $dataCheckString[] = $key . '=' . $value;
            }
        }
        $dataCheckString = implode("\n", $dataCheckString);
        
        // Вычисляем секретный ключ
        $secretKey = hash('sha256', $botToken, true);
        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);
        
        // Сравниваем хеши безопасным способом
        if (!hash_equals($hash, $checkHash)) {
            \Log::error('Telegram hash mismatch', [
                'expected' => $hash,
                'received' => $checkHash,
                'data_string' => $dataCheckString
            ]);
            return false;
        }
        
        return true;
    }


    /**
     * Show client profile
     */
    public function showProfile()
    {
        if (!Auth::guard('client')->check()) {
            return view('profile');
        }

        $client = Auth::guard('client')->user();
        $bookings = $client->bookings()
            ->with(['service', 'salon', 'specialist'])
            ->orderBy('start_time', 'desc')
            ->get();

        return view('profile', [
            'user' => $client, // Keep variable name 'user' for blade compatibility
            'bookings' => $bookings
        ]);
    }

    /**
     * Update client profile
     */
    public function updateProfile(Request $request)
    {
        $client = Auth::guard('client')->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($validated);
        return redirect()->route('profile')->with('success', 'Профиль обновлен');
    }
}