<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Salon;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $salon = $user->salon;
        
        $query = Booking::where('salon_id', $salon->id)
            ->with(['client', 'service', 'specialist']);
        
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
                });
            });
        }
        
        // Фильтр по статусу
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Фильтр по дате
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('start_time', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('start_time', '<=', $request->date_to);
        }
        
        // Фильтр по специалисту
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

    public function storeBooking(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'specialist_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'status' => 'required|string',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = (clone $startTime)->addMinutes($service->duration_minutes);

        $booking = Booking::create([
            'client_id' => $validated['client_id'],
            'salon_id' => $user->salon_id,
            'service_id' => $validated['service_id'],
            'specialist_id' => $validated['specialist_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $validated['status'],
        ]);

        // Отправка уведомления
        try {
            $telegramService = app(TelegramNotificationService::class);
            $telegramService->notifyBookingCreated($booking->load(['client', 'service', 'specialist', 'salon']));
        } catch (\Exception $e) {
            \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Запись создана', 'booking' => $booking]);
    }

    public function updateBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'specialist_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'status' => 'required|string',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = (clone $startTime)->addMinutes($service->duration_minutes);

        $oldStatus = $booking->status;
        $booking->update([
            'service_id' => $validated['service_id'],
            'specialist_id' => $validated['specialist_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $validated['status'],
        ]);

        // Отправка уведомления если статус изменился
        if ($oldStatus !== $validated['status']) {
            try {
                $telegramService = app(TelegramNotificationService::class);
                $telegramService->notifyBookingStatusChanged($booking->load(['client', 'service', 'specialist', 'salon']));
            } catch (\Exception $e) {
                \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['message' => 'Запись обновлена', 'booking' => $booking]);
    }

    public function deleteBooking(Booking $booking)
    {
        $booking->delete();
        return response()->json(['message' => 'Запись удалена']);
    }

    public function clients(Request $request)
    {
        $query = Client::query();
        
        // Поиск
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $clients = $query->orderBy('name')->paginate(20);
        return view('panels.admin.clients', compact('clients'));
    }

    public function masters()
    {
        $salon = Auth::user()->salon;
        $masters = $salon ? $salon->users()->where('role', User::ROLE_SPECIALIST)->get() : collect();
        return view('panels.admin.masters', compact('masters'));
    }

    public function services()
    {
        $services = Service::all();
        return view('panels.admin.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $service = Service::create($validated);

        return response()->json(['message' => 'Услуга добавлена', 'service' => $service]);
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $service->update($validated);

        return response()->json(['message' => 'Услуга обновлена', 'service' => $service]);
    }

    public function deleteService(Service $service)
    {
        if ($service->bookings()->exists()) {
            return response()->json(['message' => 'Нельзя удалить услугу, на которую есть записи'], 422);
        }

        $service->delete();
        return response()->json(['message' => 'Услуга удалена']);
    }

}
