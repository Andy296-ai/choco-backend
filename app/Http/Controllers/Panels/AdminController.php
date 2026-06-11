<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $salon = $user->salon;

        // Если admin не привязан к салону — вернуть пустой дашборд
        if (!$salon) {
            $bookings = Booking::whereRaw('0 = 1')->paginate(20);
            $clients  = collect();
            $services = collect();
            $masters  = collect();
            return view('panels.admin.dashboard', compact('salon', 'bookings', 'clients', 'services', 'masters'));
        }

        $query = Booking::where('salon_id', $salon->id)
            ->with(['client', 'service', 'specialist']);
        
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
                });
            });
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('start_time', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('start_time', '<=', $request->date_to);
        }
        
        if ($request->has('specialist_id') && $request->specialist_id) {
            $query->where('specialist_id', $request->specialist_id);
        }
        
        $bookings = $query->orderBy('start_time', 'desc')->paginate(20);
        
        $clients = Client::orderBy('name')->get();
        $services = Service::all();
        $masters = $salon 
            ? $salon->users()->where('role', User::ROLE_SPECIALIST)->get() 
            : collect();

        return view('panels.admin.dashboard', compact('salon', 'bookings', 'clients', 'services', 'masters'));
    }

    public function clients(Request $request)
    {
        $salon = Auth::user()->salon;

        // Если admin не привязан к салону — вернуть пустой результат безопасно
        if (!$salon) {
            $clients = Client::whereRaw('0 = 1')->paginate(10);
            return view('panels.admin.clients', compact('clients'));
        }

        $query = $salon->clients();

        // Поиск
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

    public function masters(Request $request)
    {
        $salon = Auth::user()->salon;

        // Если admin не привязан к салону — вернуть пустой результат
        if (!$salon) {
            $masters = User::whereRaw('0 = 1')->paginate(10);
            return view('panels.admin.masters', compact('masters'));
        }

        $query = $salon->users()->where('role', User::ROLE_SPECIALIST);

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $masters = $query->with(['absences' => fn($q) => $q->orderBy('start_date')])->paginate(10);
        return view('panels.admin.masters', compact('masters'));
    }

    public function services(Request $request)
    {
        $query = Service::query();
        
        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Фильтр по цене
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Фильтр по длительности
        if ($request->filled('duration_min')) {
            $query->where('duration_minutes', '>=', $request->duration_min);
        }
        if ($request->filled('duration_max')) {
            $query->where('duration_minutes', '<=', $request->duration_max);
        }
        
        $services = $query->paginate(9);
        return view('panels.admin.services', compact('services'));
    }
}
