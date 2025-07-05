<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::view('/register', 'auth.register')->middleware('guest')->name('register');
Route::view('/login', 'auth.login')->middleware('guest')->name('login');

// Public Job Listings (Accessible to all)
Route::get('/', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Employer Specific Routes
Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/jobs', [JobController::class, 'employerJobs'])->name('jobs.index');
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->middleware('employer.job.access')->name('jobs.edit');
    Route::put('/jobs/{job}', [JobController::class, 'update'])->middleware('employer.job.access')->name('jobs.update');
    Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->middleware('employer.job.access')->name('jobs.destroy');
    Route::get('/jobs/{job}/applications', [ApplicationController::class, 'employerApplications'])->middleware('employer.job.access')->name('jobs.applications');
    Route::put('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
});

// Admin Specific Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/metrics', [AdminController::class, 'metrics'])->name('metrics');
});
