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
    $salons = \App\Models\Salon::paginate(10);
    return view('contacts', compact('salons'));
})->name('contacts');

Route::get('/gallery', function () {
    $portfolioItems = \App\Models\PortfolioItem::with('user')
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    return view('gallery', compact('portfolioItems'));
})->name('gallery');

Route::get('/booking', function () {
    return view('booking');
})->name('booking');

Route::post('/booking', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
Route::post('/booking/{booking}/cancel', [App\Http\Controllers\BookingController::class, 'cancel'])->name('booking.cancel')->middleware('auth:client');

Route::post('/client/logout', [LoginController::class, 'clientLogout'])->name('client.logout');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Panel Routes
Route::middleware(['auth', 'role:director'])->prefix('director')->name('director.')->group(function () {
    Route::get('/', [App\Http\Controllers\Panels\DirectorController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [App\Http\Controllers\Panels\DirectorController::class, 'employees'])->name('employees');
    // Database management
    Route::get('/db', [App\Http\Controllers\Panels\DirectorDatabaseController::class, 'index'])->name('db.index');
    Route::get('/db/table/{table}', [App\Http\Controllers\Panels\DirectorDatabaseController::class, 'showTable'])->name('db.table');
    Route::get('/db/table/{table}/create', [App\Http\Controllers\Panels\DirectorDatabaseController::class, 'create'])->name('db.create');
    Route::post('/db/table/{table}/store', [App\Http\Controllers\Panels\DirectorDatabaseController::class, 'store'])->name('db.store');
    Route::get('/db/table/{table}/edit/{id}', [App\Http\Controllers\Panels\DirectorDatabaseController::class, 'edit'])->name('db.edit');
    Route::put('/db/table/{table}/update/{id}', [App\Http\Controllers\Panels\DirectorDatabaseController::class, 'update'])->name('db.update');
    Route::delete('/db/table/{table}/delete/{id}', [App\Http\Controllers\Panels\DirectorDatabaseController::class, 'destroy'])->name('db.destroy');

    // Employee CRUD
    Route::post('/employees', [App\Http\Controllers\Panels\DirectorController::class, 'storeEmployee'])->name('employees.store');
    Route::patch('/employees/{employee}', [App\Http\Controllers\Panels\DirectorController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/employees/{employee}', [App\Http\Controllers\Panels\DirectorController::class, 'deleteEmployee'])->name('employees.delete');
    Route::get('/finance', [App\Http\Controllers\Panels\DirectorController::class, 'finance'])->name('finance');
    Route::get('/settings', [App\Http\Controllers\Panels\DirectorController::class, 'salons'])->name('settings');

    Route::get('/clients', [App\Http\Controllers\Panels\DirectorController::class, 'clients'])->name('clients');
    Route::post('/clients', [App\Http\Controllers\Api\AdminApiController::class, 'storeClient'])->name('clients.store');
    Route::get('/clients/{client}', [App\Http\Controllers\Api\AdminApiController::class, 'showClient'])->name('clients.show');
    
    // Salon CRUD
    Route::post('/salons', [App\Http\Controllers\Panels\DirectorController::class, 'storeSalon'])->name('salons.store');
    Route::patch('/salons/{salon}', [App\Http\Controllers\Panels\DirectorController::class, 'updateSalon'])->name('salons.update');
    Route::delete('/salons/{salon}', [App\Http\Controllers\Panels\DirectorController::class, 'deleteSalon'])->name('salons.delete');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Panels\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Booking CRUD
    Route::post('/bookings', [App\Http\Controllers\Api\AdminApiController::class, 'storeBooking'])->name('bookings.store');
    Route::patch('/bookings/{booking}', [App\Http\Controllers\Api\AdminApiController::class, 'updateBooking'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [App\Http\Controllers\Api\AdminApiController::class, 'deleteBooking'])->name('bookings.delete');

    Route::get('/clients', [App\Http\Controllers\Panels\AdminController::class, 'clients'])->name('clients');
    Route::post('/clients', [App\Http\Controllers\Api\AdminApiController::class, 'storeClient'])->name('clients.store');
    Route::get('/clients/{client}', [App\Http\Controllers\Api\AdminApiController::class, 'showClient'])->name('clients.show');
    
    Route::get('/masters', [App\Http\Controllers\Panels\AdminController::class, 'masters'])->name('masters');
    Route::get('/masters/{master}/schedule', [App\Http\Controllers\Api\AdminApiController::class, 'getSchedule'])->name('masters.schedule');
    Route::post('/masters/{master}/schedule', [App\Http\Controllers\Api\AdminApiController::class, 'updateSchedule'])->name('masters.schedule.update');
    Route::post('/masters/{master}/absence', [App\Http\Controllers\Api\AdminApiController::class, 'storeAbsence'])->name('masters.absence.store');
    
    Route::get('/services', [App\Http\Controllers\Panels\AdminController::class, 'services'])->name('services');
    Route::post('/services', [App\Http\Controllers\Api\AdminApiController::class, 'storeService'])->name('services.store');
    Route::patch('/services/{service}', [App\Http\Controllers\Api\AdminApiController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [App\Http\Controllers\Api\AdminApiController::class, 'deleteService'])->name('services.delete');
});

Route::middleware(['auth', 'role:specialist'])->prefix('specialist')->name('specialist.')->group(function () {
    Route::get('/', [App\Http\Controllers\Panels\SpecialistController::class, 'dashboard'])->name('dashboard');
    Route::get('/clients', [App\Http\Controllers\Panels\SpecialistController::class, 'clients'])->name('clients');
    Route::get('/schedule', [App\Http\Controllers\Panels\SpecialistController::class, 'schedule'])->name('schedule');
    Route::post('/schedule', [App\Http\Controllers\Panels\SpecialistController::class, 'updateSchedule'])->name('schedule.update');
    Route::get('/portfolio', [App\Http\Controllers\Panels\SpecialistController::class, 'portfolio'])->name('portfolio');
    Route::patch('/bookings/{booking}/status', [App\Http\Controllers\Panels\SpecialistController::class, 'updateBookingStatus'])->name('bookings.update-status');
    
    // Portfolio CRUD
    Route::post('/portfolio', [App\Http\Controllers\Panels\SpecialistController::class, 'storePortfolio'])->name('portfolio.store');
    Route::delete('/portfolio/{item}', [App\Http\Controllers\Panels\SpecialistController::class, 'deletePortfolio'])->name('portfolio.delete');
});

// Client Routes - отдельные от employee routes
Route::middleware(['web'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('dashboard')->middleware('auth:client');
    Route::get('/bookings', [App\Http\Controllers\ClientController::class, 'bookings'])->name('bookings')->middleware('auth:client');
    Route::patch('/bookings/{booking}/cancel', [App\Http\Controllers\ClientController::class, 'cancelBooking'])->name('bookings.cancel')->middleware('auth:client');
    Route::delete('/bookings/{booking}/delete', [App\Http\Controllers\ClientController::class, 'deleteBooking'])->name('bookings.delete')->middleware('auth:client');
});

// Auth
Route::any('/auth/telegram', [AuthController::class, 'telegramAuth'])->name('auth.telegram');

// API for Dynamic Booking
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/salons', [App\Http\Controllers\Api\BookingApiController::class, 'getSalons'])->name('salons');
    Route::get('/services', [App\Http\Controllers\Api\BookingApiController::class, 'getServices'])->name('services');
    Route::get('/specialists', [App\Http\Controllers\Api\BookingApiController::class, 'getSpecialists'])->name('specialists');
    Route::get('/available-slots', [App\Http\Controllers\Api\BookingApiController::class, 'getAvailableSlots'])->name('slots');
});

// Debug route removed for security reasons

Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update')->middleware('auth:client');


