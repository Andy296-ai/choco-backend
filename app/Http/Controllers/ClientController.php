<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Show client dashboard
     */
    public function dashboard()
    {
        $client = Auth::guard('client')->user();
        $bookings = Booking::where('client_id', $client->id)
            ->with(['service', 'specialist', 'salon'])
            ->orderBy('start_time', 'desc')
            ->paginate(10);
            
        return view('client.dashboard', compact('bookings'));
    }

    /**
     * Show client bookings
     */
    public function bookings()
    {
        $client = Auth::guard('client')->user();
        $bookings = Booking::where('client_id', $client->id)
            ->with(['service', 'specialist', 'salon'])
            ->orderBy('start_time', 'desc')
            ->paginate(10);
            
        return view('client.bookings', compact('bookings'));
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, Booking $booking)
    {
        $client = Auth::guard('client')->user();
        
        // Проверяем что бронирование принадлежит клиенту
        if ($booking->client_id !== $client->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Проверяем что можно отменить
        if ($booking->status === 'completed') {
            return response()->json(['error' => 'Cannot cancel completed booking'], 400);
        }
        
        $booking->status = 'cancelled';
        $booking->save();
        
        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}
