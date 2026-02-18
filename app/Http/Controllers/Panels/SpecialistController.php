<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\PortfolioItem;

class SpecialistController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        $query = Booking::where('specialist_id', $user->id)
            ->with(['client', 'service']);
        
        // Фильтр по дате (по умолчанию сегодня)
        $viewType = $request->get('view', 'today'); // today, week, month, calendar
        
        if ($viewType === 'today') {
            $query->whereDate('start_time', now()->toDateString());
        } elseif ($viewType === 'week') {
            $query->whereBetween('start_time', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($viewType === 'month') {
            $query->whereMonth('start_time', now()->month)
                  ->whereYear('start_time', now()->year);
        }
        
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
                });
            });
        }
        
        // Фильтр по статусу
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $bookings = $query->orderBy('start_time')->get();

        // Basic earnings for the specialist
        $earnings = Booking::where('specialist_id', $user->id)
            ->where('bookings.status', 'completed')
            ->whereMonth('bookings.start_time', now()->month)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        return view('panels.specialist.dashboard', compact('bookings', 'earnings', 'viewType'));
    }

    public function clients()
    {
        return view('panels.specialist.clients');
    }

    public function portfolio()
    {
        $items = Auth::user()->portfolioItems;
        return view('panels.specialist.portfolio', compact('items'));
    }

    public function storePortfolio(Request $request)
    {
        $validated = $request->validate([
            'image_path' => 'required|url', // For now, we use URLs for simplicity, but could be file upload
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $item = Auth::user()->portfolioItems()->create($validated);

        return response()->json(['message' => 'Работа добавлена в портфолио', 'item' => $item]);
    }

    public function deletePortfolio(PortfolioItem $item)
    {
        if ($item->user_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        $item->delete();
        return response()->json(['message' => 'Работа удалена']);
    }


    public function schedule()
    {
        $schedules = Auth::user()->schedules()->orderBy('id')->get();
        return view('panels.specialist.schedule', compact('schedules'));
    }

    public function updateSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.is_working' => 'boolean',
            'schedules.*.start_time' => 'required|string',
            'schedules.*.end_time' => 'required|string',
        ]);

        foreach ($validated['schedules'] as $id => $data) {
            $schedule = Auth::user()->schedules()->find($id);
            if ($schedule) {
                $schedule->update([
                    'is_working' => $data['is_working'] ?? false,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Расписание обновлено');
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        if ($booking->specialist_id !== Auth::id()) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        // Отправка уведомления об изменении статуса
        try {
            $telegramService = app(TelegramNotificationService::class);
            $telegramService->notifyBookingStatusChanged($booking->load(['client', 'service', 'specialist', 'salon']));
        } catch (\Exception $e) {
            \Log::error('Failed to send Telegram notification', ['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Статус обновлен', 'booking' => $booking]);
    }
}
