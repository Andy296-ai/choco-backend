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
Route::prefix('director')->name('director.')->group(function () {
    Route::get('/', function () { return view('panels.director.dashboard'); })->name('dashboard');
    Route::get('/reports', function () { return view('panels.director.reports'); })->name('reports');
    Route::get('/employees', function () { return view('panels.director.employees'); })->name('employees');
    Route::get('/finance', function () { return view('panels.director.finance'); })->name('finance');
    Route::get('/settings', function () { return view('panels.director.settings'); })->name('settings');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () { return view('panels.admin.dashboard'); })->name('dashboard');
    Route::get('/clients', function () { return view('panels.admin.clients'); })->name('clients');
    Route::get('/masters', function () { return view('panels.admin.masters'); })->name('masters');
    Route::get('/warehouse', function () { return view('panels.admin.warehouse'); })->name('warehouse');
    Route::get('/services', function () { return view('panels.admin.services'); })->name('services');
});

Route::prefix('specialist')->name('specialist.')->group(function () {
    Route::get('/', function () { return view('panels.specialist.dashboard'); })->name('dashboard');
    Route::get('/clients', function () { return view('panels.specialist.clients'); })->name('clients');
    Route::get('/portfolio', function () { return view('panels.specialist.portfolio'); })->name('portfolio');
    Route::get('/materials', function () { return view('panels.specialist.materials'); })->name('materials');
});

// Auth
Route::post('/auth/telegram', [AuthController::class, 'telegramAuth'])->name('auth.telegram');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');


