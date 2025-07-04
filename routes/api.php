<?php

use App\Http\Controllers\AuthController;

// Public API routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
