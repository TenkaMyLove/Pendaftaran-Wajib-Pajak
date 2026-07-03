<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegistrationController;

use App\Http\Controllers\Auth\LoginController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Register Routes (accessed by client/mobile etc.)
Route::get('/register', [RegistrationController::class, 'create'])->name('registrations.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('registrations.store');

// Protected Admin Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::patch('/registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('registrations.updateStatus');
});
