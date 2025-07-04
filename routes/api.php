<?php

use App\Http\Controllers\AuthController;

// Public API routes
Route::post('register', [AuthController::class, 'register']);
Route::middleware(['web'])->post('login', [AuthController::class, 'login']);
Route::middleware(['web'])->post('logout', [AuthController::class, 'logout'])->name('logout');
