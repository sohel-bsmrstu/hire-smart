<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::view('/register', 'auth.register')->middleware('guest')->name('register');
Route::view('/login', 'auth.login')->middleware('guest')->name('login');

// Employer Specific Routes
Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/jobs', [JobController::class, 'employerJobs'])->name('jobs.index');
});
