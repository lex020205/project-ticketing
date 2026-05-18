<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Spv\DashboardController as SpvDashboardController;
use App\Http\Controllers\Teknisi\DashboardController as TeknisiDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('guest')
    ->name('register.submit');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// General dashboard redirect based on role
Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware('auth')
    ->name('dashboard');

// Admin routes
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:Admin'])
    ->name('admin.dashboard');

// SPV routes
Route::get('/spv/dashboard', [SpvDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:SPV'])
    ->name('spv.dashboard');

// Teknisi routes
Route::get('/teknisi/dashboard', [TeknisiDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:Teknisi'])
    ->name('teknisi.dashboard');
