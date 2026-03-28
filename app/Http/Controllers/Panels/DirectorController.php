<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use App\Models\Client;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Schedule;

class DirectorController extends Controller
{
    public function dashboard(Request $request)
    {
        $salonsCount = Salon::count();
        $adminsCount = User::where('role', User::ROLE_ADMIN)->count();
        $mastersCount = User::where('role', User::ROLE_SPECIALIST)->count();
        $clientsCount = Client::count();

        // Calculate real revenue for current month
        $completedBookings = Booking::where('bookings.status', 'completed')
            ->whereMonth('bookings.start_time', now()->month)
            ->whereYear('bookings.start_time', now()->year)
            ->join('services', 'bookings.service_id', '=', 'services.id');
        
        $revenue = $completedBookings->sum('services.price');
        $bookingsCount = $completedBookings->count();
        $avgCheck = $bookingsCount > 0 ? $revenue / $bookingsCount : 0;

        // Calculate real occupancy
        $totalWorkingMinutes = 0;
        $specialists = User::where('role', User::ROLE_SPECIALIST)->with('schedules')->get();
        foreach ($specialists as $specialist) {
            $schedules = $specialist->schedules->where('is_working', true);
            if ($schedules->isEmpty()) {
                // Default 11 hours (9-20) if no schedule
                $totalWorkingMinutes += 11 * 60 * 30; // 30 days
            } else {
                foreach ($schedules as $s) {
                    $start = Carbon::parse($s->start_time);
                    $end = Carbon::parse($s->end_time);
                    $totalWorkingMinutes += $start->diffInMinutes($end) * 4; // Approx 4 weeks in month
                }
            }
        }

        $totalBookedMinutes = Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereMonth('start_time', now()->month)
            ->whereYear('start_time', now()->year)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.duration_minutes');

        $occupancyRate = $totalWorkingMinutes > 0 ? ($totalBookedMinutes / $totalWorkingMinutes) * 100 : 0;

        $stats = [
            'revenue_month' => number_format($revenue, 0, '.', ' ') . ' ₽',
            'new_clients' => $clientsCount,
            'avg_check' => number_format($avgCheck, 0, '.', ' ') . ' ₽',
            'occupancy' => round(min($occupancyRate, 100)) . '%',
        ];

        // Monthly revenue for dashboard chart
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $rev = Booking::where('bookings.status', 'completed')
                ->whereMonth('bookings.start_time', $date->month)
                ->whereYear('bookings.start_time', $date->year)
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->sum('services.price');
            
            $monthlyRevenue[] = [
                'month' => $date->translatedFormat('F'),
                'revenue' => $rev
            ];
        }

        $query = Booking::with(['client', 'service', 'specialist', 'salon']);
        
        // Поиск
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('service', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('specialist', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('salon', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        // Фильтр по статусу
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Фильтр по салону
        if ($request->has('salon_id') && $request->salon_id) {
            $query->where('salon_id', $request->salon_id);
        }
        
        $recentBookings = $query->orderBy('start_time', 'desc')->paginate(10);

        return view('panels.director.dashboard', compact('stats', 'salonsCount', 'adminsCount', 'mastersCount', 'recentBookings', 'monthlyRevenue'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users,login',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'role' => 'required|in:admin,specialist',
            'salon_id' => 'required|exists:salons,id',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        if ($user->role === User::ROLE_SPECIALIST) {
            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            foreach ($days as $day) {
                Schedule::create([
                    'user_id' => $user->id,
                    'salon_id' => $user->salon_id,
                    'day_of_week' => $day,
                    'is_working' => false,
                    'start_time' => '09:00',
                    'end_time' => '18:00',
                ]);
            }
        }

        return response()->json(['message' => 'Сотрудник успешно добавлен', 'user' => $user]);
    }

    public function updateEmployee(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users,login,' . $employee->id,
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string',
            'role' => 'required|in:admin,specialist',
            'salon_id' => 'required|exists:salons,id',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return response()->json(['message' => 'Данные сотрудника обновлены']);
    }

    public function deleteEmployee(User $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Сотрудник удален']);
    }

    public function clients(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && Schema::hasColumn('clients', 'status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->orderBy('name')->paginate(10);

        return view('panels.admin.clients', compact('clients'));
    }

    public function services(Request $request)
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('duration_min')) {
            $query->where('duration_minutes', '>=', $request->duration_min);
        }
        if ($request->filled('duration_max')) {
            $query->where('duration_minutes', '<=', $request->duration_max);
        }

        $services = $query->paginate(9);

        return view('panels.admin.services', compact('services'));
    }

    public function salons()
    {
        $salons = Salon::with('contacts')->paginate(10);
        return view('panels.director.settings', compact('salons')); // Using settings as salon management for now
    }

    public function employees()
    {
        $admins = User::where('role', User::ROLE_ADMIN)->paginate(10, ['*'], 'admins_page');
        $masters = User::where('role', User::ROLE_SPECIALIST)->paginate(10, ['*'], 'masters_page');
        $salons = Salon::all();
        return view('panels.director.employees', compact('admins', 'masters', 'salons'));
    }


    public function finance(Request $request)
    {
        // Parse date range from query params, default to current month
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay() 
            : now()->startOfMonth();
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->endOfDay() 
            : now()->endOfDay();

        // 1. Summary Stats — filtered by selected period
        $totalRevenue = Booking::where('bookings.status', 'completed')
            ->whereBetween('bookings.start_time', [$startDate, $endDate])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        $monthRevenue = $totalRevenue; // same as total for the selected period

        $expenses = $totalRevenue * 0.35;
        $profit = $totalRevenue - $expenses;

        // 2. Revenue by Month (Last 6 months for the chart — always shown)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Booking::where('status', 'completed')
                ->whereMonth('start_time', $date->month)
                ->whereYear('start_time', $date->year)
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->sum('services.price');
            
            $monthlyRevenue[] = [
                'month' => $date->translatedFormat('F'),
                'revenue' => $revenue
            ];
        }

        // 3. Revenue by Salon — filtered by selected period
        $salonRevenue = Salon::get()
            ->map(function($salon) use ($startDate, $endDate) {
                $revenue = Booking::where('salon_id', $salon->id)
                    ->where('status', 'completed')
                    ->whereBetween('start_time', [$startDate, $endDate])
                    ->join('services', 'bookings.service_id', '=', 'services.id')
                    ->sum('services.price');
                
                return [
                    'name' => $salon->name,
                    'revenue' => $revenue
                ];
            });

        // 4. Top Services — filtered by selected period
        $topServices = Service::get()
            ->map(function($service) use ($startDate, $endDate) {
                $completedBookings = $service->bookings()
                    ->where('status', 'completed')
                    ->whereBetween('start_time', [$startDate, $endDate])
                    ->count();
                $revenue = $completedBookings * $service->price;
                return [
                    'name' => $service->name,
                    'revenue' => $revenue,
                    'count' => $completedBookings
                ];
            })
            ->sortByDesc('revenue')
            ->take(5);

        return view('panels.director.finance', compact(
            'totalRevenue', 
            'monthRevenue', 
            'expenses', 
            'profit', 
            'monthlyRevenue', 
            'salonRevenue', 
            'topServices',
            'startDate',
            'endDate'
        ));
    }

    public function storeSalon(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $salon = Salon::create($validated);
        
        return response()->json([
            'message' => 'Салон успешно добавлен',
            'salon' => $salon
        ]);
    }

    public function updateSalon(Request $request, Salon $salon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $salon->update($validated);
        
        return response()->json([
            'message' => 'Салон обновлён',
            'salon' => $salon
        ]);
    }

    public function deleteSalon(Salon $salon)
    {
        // Check if salon has any employees or bookings
        if ($salon->users()->count() > 0) {
            return response()->json([
                'error' => 'Невозможно удалить салон с сотрудниками. Сначала переместите или удалите сотрудников.'
            ], 422);
        }

        if ($salon->bookings()->count() > 0) {
            return response()->json([
                'error' => 'Невозможно удалить салон с записями. Сначала удалите или перенесите записи.'
            ], 422);
        }

        $salon->delete();
        
        return response()->json([
            'message' => 'Салон удалён'
        ]);
    }
}
