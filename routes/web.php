<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::view('/register', 'auth.register')->middleware('guest')->name('register');
Route::view('/login', 'auth.login')->middleware('guest')->name('login');
