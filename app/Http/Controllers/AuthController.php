<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function telegramAuth(Request $request)
    {
        try {
            $data = $request->all();
            
            if (!$this->verifyTelegramHash($data)) {
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
            
            $user = User::findOrCreateFromTelegram($telegramData);
            
            Auth::login($user, true);
            
            return redirect()->route('profile')->with('success', 'Вы успешно вошли через Telegram!');
            
        } catch (\Exception $e) {
            return redirect()->route('profile')->with('error', 'Ошибка авторизации: ' . $e->getMessage());
        }
    }
    
    private function verifyTelegramHash(array $data)
    {
        $botToken = config('services.telegram.bot_token');
        
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
}