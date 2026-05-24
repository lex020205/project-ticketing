<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KeluhanController as AdminKeluhanController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\VerifikasiController as AdminVerifikasiController;
use App\Http\Controllers\Spv\DashboardController as SpvDashboardController;
use App\Http\Controllers\Spv\EskalasiController as SpvEskalasiController;
use App\Http\Controllers\Spv\KategoriMasalahController as SpvKategoriMasalahController;
use App\Http\Controllers\Spv\LaporanController as SpvLaporanController;
use App\Http\Controllers\Spv\VerifikasiController as SpvVerifikasiController;
use App\Http\Controllers\Spv\TicketController as SpvTicketController;
use App\Http\Controllers\Spv\UserController as SpvUserController;
use App\Http\Controllers\Teknisi\DashboardController as TeknisiDashboardController;
use App\Http\Controllers\Teknisi\TicketController as TeknisiTicketController;
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

Route::middleware(['auth', 'checkRole:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/keluhan', [AdminKeluhanController::class, 'index'])->name('keluhan.index');
        Route::get('/keluhan/create', [AdminKeluhanController::class, 'create'])->name('keluhan.create');
        Route::post('/keluhan', [AdminKeluhanController::class, 'store'])->name('keluhan.store');
        Route::get('/keluhan/{keluhan}', [AdminKeluhanController::class, 'show'])->name('keluhan.show');
        Route::get('/keluhan/{keluhan}/edit', [AdminKeluhanController::class, 'edit'])->name('keluhan.edit');
        Route::put('/keluhan/{keluhan}', [AdminKeluhanController::class, 'update'])->name('keluhan.update');
        Route::patch('/keluhan/{keluhan}/validasi', [AdminKeluhanController::class, 'validasi'])->name('keluhan.validasi');
        Route::patch('/keluhan/{keluhan}/tidak-valid', [AdminKeluhanController::class, 'tidakValid'])->name('keluhan.tidak_valid');
        Route::get('/keluhan/{keluhan}/buat-ticket', [AdminTicketController::class, 'createFromKeluhan'])->name('keluhan.buat_ticket');
        Route::post('/keluhan/{keluhan}/buat-ticket', [AdminTicketController::class, 'storeFromKeluhan'])->name('keluhan.store_ticket');

        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');

        Route::get('/verifikasi', [AdminVerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{ticket}', [AdminVerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{ticket}', [AdminVerifikasiController::class, 'verifikasi'])->name('verifikasi.store');
    });

// SPV routes
Route::get('/spv/dashboard', [SpvDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:SPV'])
    ->name('spv.dashboard');

Route::middleware(['auth', 'checkRole:SPV'])
    ->prefix('spv')
    ->name('spv.')
    ->group(function () {
        Route::get('/kategori', [SpvKategoriMasalahController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [SpvKategoriMasalahController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [SpvKategoriMasalahController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{kategori}', [SpvKategoriMasalahController::class, 'show'])->name('kategori.show');
        Route::get('/kategori/{kategori}/edit', [SpvKategoriMasalahController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{kategori}', [SpvKategoriMasalahController::class, 'update'])->name('kategori.update');
        Route::patch('/kategori/{kategori}/toggle-status', [SpvKategoriMasalahController::class, 'toggleStatus'])->name('kategori.toggle_status');

        Route::get('/users', [SpvUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [SpvUserController::class, 'create'])->name('users.create');
        Route::post('/users', [SpvUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [SpvUserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [SpvUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [SpvUserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [SpvUserController::class, 'toggleStatus'])->name('users.toggle_status');
        Route::patch('/users/{user}/reset-password', [SpvUserController::class, 'resetPassword'])->name('users.reset_password');

        Route::get('/laporan', [SpvLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/tickets', [SpvTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [SpvTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/prioritas', [SpvTicketController::class, 'updatePrioritas'])->name('tickets.prioritas');
        Route::patch('/tickets/{ticket}/assign-teknisi', [SpvTicketController::class, 'assignTeknisi'])->name('tickets.assign_teknisi');

        Route::get('/eskalasi', [SpvEskalasiController::class, 'index'])->name('eskalasi.index');
        Route::get('/eskalasi/{eskalasi}', [SpvEskalasiController::class, 'show'])->name('eskalasi.show');
        Route::patch('/eskalasi/{eskalasi}/keputusan', [SpvEskalasiController::class, 'keputusan'])->name('eskalasi.keputusan');

        Route::get('/verifikasi', [SpvVerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{ticket}', [SpvVerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{ticket}', [SpvVerifikasiController::class, 'verifikasi'])->name('verifikasi.store');
    });

// Teknisi routes
Route::get('/teknisi/dashboard', [TeknisiDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:Teknisi'])
    ->name('teknisi.dashboard');

Route::middleware(['auth', 'checkRole:Teknisi'])
    ->prefix('teknisi')
    ->name('teknisi.')
    ->group(function () {
        Route::get('/tickets', [TeknisiTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [TeknisiTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/mulai', [TeknisiTicketController::class, 'mulai'])->name('tickets.mulai');
        Route::post('/tickets/{ticket}/progress', [TeknisiTicketController::class, 'storeProgress'])->name('tickets.progress');
        Route::patch('/tickets/{ticket}/selesai', [TeknisiTicketController::class, 'selesai'])->name('tickets.selesai');
        Route::post('/tickets/{ticket}/lampiran', [TeknisiTicketController::class, 'uploadLampiran'])->name('tickets.lampiran');
        Route::post('/tickets/{ticket}/eskalasi', [TeknisiTicketController::class, 'ajukanEskalasi'])->name('tickets.eskalasi');
    });
