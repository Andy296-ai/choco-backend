<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingApiController extends Controller
{
    /**
     * Get list of salons.
     */
    public function getSalons()
    {
        return response()->json(Salon::all());
    }

    /**
     * Get list of services.
     */
    public function getServices()
    {
        return response()->json(Service::all());
    }

    /**
     * Get list of specialists.
     * Can be filtered by salon_id.
     */
    public function getSpecialists(Request $request)
    {
        $query = User::where('role', User::ROLE_SPECIALIST);

        if ($request->has('salon_id')) {
            $query->where('salon_id', $request->salon_id);
        }

        return response()->json($query->get());
    }

    /**
     * Get available time slots for a specialist on a specific date.
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'specialist_id' => 'required', // Can be 'any'
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $possibleSlots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        $availableSlots = [];

        // Assume default service duration 60m for slot checking if not provided
        $duration = 60; 

        foreach ($possibleSlots as $slot) {
            $start = Carbon::parse($date . ' ' . $slot);
            $end = (clone $start)->addMinutes($duration);

            if ($request->specialist_id === 'any') {
                // If any specialist is available in the salon
                // For this we'd need salon_id, let's skip for now or use a default salon from request
                // In full implementation, "any" would be handled at submission
                $availableSlots[] = $slot; 
            } else {
                if (Booking::isSpecialistAvailable($request->specialist_id, $start, $end)) {
                    $availableSlots[] = $slot;
                }
            }
        }
        
        return response()->json($availableSlots);
    }
}
