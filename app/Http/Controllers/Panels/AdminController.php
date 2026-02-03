<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $clients = User::where('role', User::ROLE_CLIENT)->get();
        $services = Service::all();
        $masters = $salon ? $salon->users()->where('role', User::ROLE_SPECIALIST)->get() : collect();

        return view('panels.admin.dashboard', compact('salon', 'bookings', 'clients', 'services', 'masters'));
    }

    public function clients()
    {
        return view('panels.admin.clients');
    }

    public function masters()
    {
        $salon = Auth::user()->salon;
        $masters = $salon ? $salon->users()->where('role', User::ROLE_SPECIALIST)->get() : collect();
        return view('panels.admin.masters', compact('masters'));
    }

    public function services()
    {
        return view('panels.admin.services');
    }

    public function warehouse()
    {
        return view('panels.admin.warehouse');
    }
}
