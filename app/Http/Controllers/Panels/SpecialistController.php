<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\PortfolioItem;

class SpecialistController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $bookings = Booking::where('specialist_id', $user->id)
            ->whereDate('start_time', now()->toDateString())
            ->with(['user', 'service'])
            ->orderBy('start_time')
            ->get();

        // Basic earnings for the specialist
        $earnings = Booking::where('specialist_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('start_time', now()->month)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        return view('panels.specialist.dashboard', compact('bookings', 'earnings'));
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

    public function materials()
    {
        return view('panels.specialist.materials');
    }
}
