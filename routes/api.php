<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;

// Public API routes
Route::post('register', [AuthController::class, 'register']);
Route::middleware(['web'])->post('login', [AuthController::class, 'login']);
Route::middleware(['web'])->post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('jobs', [JobController::class, 'index']);
Route::get('jobs/{job}', [JobController::class, 'show']);

// Candidate API routes
Route::middleware(['web', 'role:candidate'])->group(function () {
    Route::post('applications', [ApplicationController::class, 'store']);
});

 // Employer API routes
Route::middleware(['web', 'role:employer'])->group(function () {
    Route::put('employer/applications/{application}/status', [ApplicationController::class, 'updateStatus']);
});

 // Admin API routes
Route::middleware(['web', 'role:admin'])->group(function () {
    Route::get('admin/metrics', [AdminController::class, 'metrics']);
});

