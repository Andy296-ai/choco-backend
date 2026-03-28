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
            return back()->with('error', 'У вас нет прав для отмены этой записи');
        }
        
        // Проверяем что можно отменить
        if ($booking->status === 'completed') {
            return back()->with('error', 'Нельзя отменить завершенную запись');
        }
        
        $booking->status = 'cancelled';
        $booking->save();
        
        return back()->with('success', 'Запись успешно отменена');
    }

    /**
     * Delete booking completely
     */
    public function deleteBooking(Request $request, Booking $booking)
    {
        $client = Auth::guard('client')->user();
        
        // Проверяем что бронирование принадлежит клиенту
        if ($booking->client_id !== $client->id) {
            return back()->with('error', 'У вас нет прав для удаления этой записи');
        }
        
        // Проверяем что запись отменена или завершена
        if (!in_array($booking->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'Можно удалить только отмененные или завершенные записи');
        }
        
        $booking->delete();
        
        return back()->with('success', 'Запись успешно удалена');
    }
}
