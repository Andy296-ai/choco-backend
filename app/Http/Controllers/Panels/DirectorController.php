<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorController extends Controller
{
    public function dashboard()
    {
        $salonsCount = Salon::count();
        $adminsCount = User::where('role', User::ROLE_ADMIN)->count();
        $mastersCount = User::where('role', User::ROLE_SPECIALIST)->count();
        $clientsCount = User::where('role', User::ROLE_CLIENT)->count();

        // Basic stats for the dashboard
        $stats = [
            'revenue_month' => '0 ₽', // To be implemented with real bookings
            'new_clients' => $clientsCount,
            'avg_check' => '0 ₽',
            'occupancy' => '0%',
        ];

        return view('panels.director.dashboard', compact('stats', 'salonsCount', 'adminsCount', 'mastersCount'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'role' => 'required|in:admin,specialist',
            'salon_id' => 'required|exists:salons,id',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        return response()->json(['message' => 'Сотрудник успешно добавлен', 'user' => $user]);
    }

    public function updateEmployee(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string',
            'role' => 'required|in:admin,specialist',
            'salon_id' => 'required|exists:salons,id',
        ]);

        $employee->update($validated);

        return response()->json(['message' => 'Данные сотрудника обновлены']);
    }

    public function deleteEmployee(User $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Сотрудник удален']);
    }

    public function salons()
    {
        $salons = Salon::with('contacts')->get();
        return view('panels.director.settings', compact('salons')); // Using settings as salon management for now
    }

    public function employees()
    {
        $admins = User::where('role', User::ROLE_ADMIN)->get();
        $masters = User::where('role', User::ROLE_SPECIALIST)->get();
        $salons = Salon::all();
        return view('panels.director.employees', compact('admins', 'masters', 'salons'));
    }

    public function reports()
    {
        return view('panels.director.reports');
    }

    public function finance()
    {
        return view('panels.director.finance');
    }
}
