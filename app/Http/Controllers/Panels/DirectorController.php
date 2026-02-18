<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use App\Models\Client;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $stats = [
            'revenue_month' => number_format($revenue, 0, '.', ' ') . ' ₽',
            'new_clients' => $clientsCount,
            'avg_check' => number_format($avgCheck, 0, '.', ' ') . ' ₽',
            'occupancy' => '78%', // Mock occupancy for now
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
        
        $recentBookings = $query->orderBy('start_time', 'desc')->paginate(20);

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


    public function finance()
    {
        // 1. Summary Stats
        $totalRevenue = Booking::where('bookings.status', 'completed')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        $monthRevenue = Booking::where('bookings.status', 'completed')
            ->whereMonth('bookings.start_time', now()->month)
            ->whereYear('bookings.start_time', now()->year)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        $expenses = $totalRevenue * 0.35; // Mock expenses as 35% of revenue
        $profit = $totalRevenue - $expenses;

        // 2. Revenue by Month (Last 6 months)
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

        // 3. Revenue by Salon
        $salonRevenue = Salon::get()
            ->map(function($salon) {
                $revenue = Booking::where('salon_id', $salon->id)
                    ->where('status', 'completed')
                    ->join('services', 'bookings.service_id', '=', 'services.id')
                    ->sum('services.price');
                
                return [
                    'name' => $salon->name,
                    'revenue' => $revenue
                ];
            });

        // 4. Top Services
        $topServices = Service::withCount(['bookings' => function($q) {
                $q->where('status', 'completed');
            }])
            ->get()
            ->map(function($service) {
                $revenue = $service->bookings()->where('status', 'completed')->count() * $service->price;
                return [
                    'name' => $service->name,
                    'revenue' => $revenue,
                    'count' => $service->bookings_count
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
            'topServices'
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
