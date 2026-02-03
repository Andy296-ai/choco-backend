<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    return view('contacts');
})->name('contacts');

Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');

Route::get('/booking', function () {
    return view('booking');
})->name('booking');

Route::post('/client/logout', [LoginController::class, 'clientLogout'])->name('client.logout');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Panel Routes
Route::middleware(['auth', 'role:director'])->prefix('director')->name('director.')->group(function () {
    Route::get('/', [App\Http\Controllers\Panels\DirectorController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [App\Http\Controllers\Panels\DirectorController::class, 'reports'])->name('reports');
    Route::get('/employees', [App\Http\Controllers\Panels\DirectorController::class, 'employees'])->name('employees');
    
    // Employee CRUD
    Route::post('/employees', [App\Http\Controllers\Panels\DirectorController::class, 'storeEmployee'])->name('employees.store');
    Route::patch('/employees/{employee}', [App\Http\Controllers\Panels\DirectorController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/employees/{employee}', [App\Http\Controllers\Panels\DirectorController::class, 'deleteEmployee'])->name('employees.delete');
    Route::get('/finance', [App\Http\Controllers\Panels\DirectorController::class, 'finance'])->name('finance');
    Route::get('/settings', [App\Http\Controllers\Panels\DirectorController::class, 'salons'])->name('settings');
    
    // Salon CRUD
    Route::post('/salons', [App\Http\Controllers\Panels\DirectorController::class, 'storeSalon'])->name('salons.store');
    Route::patch('/salons/{salon}', [App\Http\Controllers\Panels\DirectorController::class, 'updateSalon'])->name('salons.update');
    Route::delete('/salons/{salon}', [App\Http\Controllers\Panels\DirectorController::class, 'deleteSalon'])->name('salons.delete');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Panels\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Booking CRUD
    Route::post('/bookings', [App\Http\Controllers\Panels\AdminController::class, 'storeBooking'])->name('bookings.store');
    Route::patch('/bookings/{booking}', [App\Http\Controllers\Panels\AdminController::class, 'updateBooking'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [App\Http\Controllers\Panels\AdminController::class, 'deleteBooking'])->name('bookings.delete');

    Route::get('/clients', [App\Http\Controllers\Panels\AdminController::class, 'clients'])->name('clients');
    Route::get('/masters', [App\Http\Controllers\Panels\AdminController::class, 'masters'])->name('masters');
    Route::get('/warehouse', [App\Http\Controllers\Panels\AdminController::class, 'warehouse'])->name('warehouse');
    Route::get('/services', [App\Http\Controllers\Panels\AdminController::class, 'services'])->name('services');
});

Route::middleware(['auth', 'role:specialist'])->prefix('specialist')->name('specialist.')->group(function () {
    Route::get('/', [App\Http\Controllers\Panels\SpecialistController::class, 'dashboard'])->name('dashboard');
    Route::get('/clients', [App\Http\Controllers\Panels\SpecialistController::class, 'clients'])->name('clients');
    Route::get('/portfolio', [App\Http\Controllers\Panels\SpecialistController::class, 'portfolio'])->name('portfolio');
    
    // Portfolio CRUD
    Route::post('/portfolio', [App\Http\Controllers\Panels\SpecialistController::class, 'storePortfolio'])->name('portfolio.store');
    Route::delete('/portfolio/{item}', [App\Http\Controllers\Panels\SpecialistController::class, 'deletePortfolio'])->name('portfolio.delete');

    Route::get('/materials', [App\Http\Controllers\Panels\SpecialistController::class, 'materials'])->name('materials');
});

// Auth
Route::post('/auth/telegram', [AuthController::class, 'telegramAuth'])->name('auth.telegram');

Route::get('/debug/become-director', function () {
    if ($user = auth()->user()) {
        $user->update(['role' => 'director']);
        return "Success! You are now a Director. <a href='".route('director.dashboard')."'>Go to Dashboard</a>";
    }
    return "Please login first.";
})->middleware('auth');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');


