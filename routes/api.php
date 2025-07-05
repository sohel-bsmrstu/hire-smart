<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;

// Public API routes
Route::post('register', [AuthController::class, 'register']);
Route::middleware(['web'])->post('login', [AuthController::class, 'login']);
Route::middleware(['web'])->post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('jobs', [JobController::class, 'index']);
Route::get('jobs/{job}', [JobController::class, 'show']);
