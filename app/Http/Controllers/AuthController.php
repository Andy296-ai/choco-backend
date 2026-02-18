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
            
            if (!$this->verifyTelegramHash($data)) {
                \Illuminate\Support\Facades\Log::error('Telegram Hash Verification Failed.');
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
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Telegram Auth Exception: ' . $e->getMessage());
            return redirect()->route('profile')->with('error', 'Ошибка авторизации: ' . $e->getMessage());
        }
    }
    
    private function verifyTelegramHash(array $data)
    {
        $botToken = config('services.telegram.bot_token');
        if (empty($botToken)) return false;

        $checkHash = $data['hash'];
        unset($data['hash']);
        
        ksort($data);
        
        $dataCheckString = [];
        foreach ($data as $key => $value) {
            $dataCheckString[] = $key . '=' . $value;
        }
        $dataCheckString = implode("\n", $dataCheckString);
        
        $secretKey = hash('sha256', $botToken, true);
        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);
        
        if (strcmp($hash, $checkHash) !== 0) {
            return false;
        }
        
        if ((time() - $data['auth_date']) > 86400) {
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