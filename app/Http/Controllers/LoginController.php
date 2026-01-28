<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
            $user = $credentials[$login];
            Session::put('user', [
                'login' => $login,
                'role' => $user['role'],
                'name' => $user['name']
            ]);

            return redirect()->route($user['role'] . '.dashboard');
        }

        return back()->withErrors(['login' => 'Неверный логин или пароль']);
    }

    public function logout()
    {
        Session::forget('user');
        return redirect()->route('login');
    }


    public function clientLogout()
    {
        Session::forget('client');
        return redirect()->route('profile');
    }
}

