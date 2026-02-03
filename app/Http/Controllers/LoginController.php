<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'root' => ['password' => 'r@@t00', 'role' => 'director', 'name' => 'Директор'],
            'root2' => ['password' => 'r@@t002', 'role' => 'admin', 'name' => 'Администратор'],
            'root3' => ['password' => 'r@@t003', 'role' => 'specialist', 'name' => 'Специалист'],
        ];

        $login = $request->input('login');
        $password = $request->input('password');

        if (isset($credentials[$login]) && $credentials[$login]['password'] === $password) {
            $cred = $credentials[$login];
            
            // Find or create a user in the DB for the hardcoded login to work with Auth
            $user = User::firstOrCreate(
                ['email' => $login . '@admin.com'],
                [
                    'name' => $cred['name'],
                    'password' => bcrypt($password),
                    'role' => $cred['role']
                ]
            );

            Auth::login($user);

            return redirect()->route($user->role . '.dashboard');
        }

        return back()->withErrors(['login' => 'Неверный логин или пароль']);
    }

    public function logout()
    {
        Auth::logout();
        Session::forget('user');
        return redirect()->route('login');
    }

    public function clientLogout()
    {
        Auth::logout();
        Session::forget('client');
        return redirect()->route('profile');
    }
}

