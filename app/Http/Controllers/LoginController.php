<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show staff/admin login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle staff/admin login
     */
    public function login(Request $request)
    {
        // Add CSRF check implicitly via request validation or middleware (default in Laravel)
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            return redirect()->route($user->role . '.dashboard');
        }

        return back()->withErrors([
            'login' => 'Неверный логин или пароль сотрудников.',
        ])->onlyInput('login');
    }

    /**
     * Staff logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Client logout
     */
    public function clientLogout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('profile');
    }
}
