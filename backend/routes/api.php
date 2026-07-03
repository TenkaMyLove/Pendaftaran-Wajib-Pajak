<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegistrationController;

Route::get('/registrations', [RegistrationController::class, 'index']);
Route::post('/registrations', [RegistrationController::class, 'store']);
Route::get('/registrations/{registration}', [RegistrationController::class, 'show']);
