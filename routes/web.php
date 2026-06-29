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
use App\Http\Controllers\Teknisi\RiwayatPengerjaanController as TeknisiRiwayatPengerjaanController;
use App\Http\Controllers\Teknisi\StatusSayaController as TeknisiStatusSayaController;
use App\Http\Controllers\Teknisi\TicketController as TeknisiTicketController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Modul 1 - Auth, Role Access, dan Dashboard Awal
// Ringkas: rute login/register/logout serta akses awal berbasis role.
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

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Profil user login
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->name('profile.show');

// Modul 1 - Auth, Role Access, dan Dashboard Awal
// Ringkas: redirect dashboard sesuai role.
// General dashboard redirect based on role
Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware('auth')
    ->name('dashboard');

// Super Admin routes
Route::get('/super-admin/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:Super Admin'])
    ->name('super-admin.dashboard');

Route::middleware(['auth', 'checkRole:Super Admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        Route::get('/tickets', [\App\Http\Controllers\SuperAdmin\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/roles', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'index'])->name('roles.index');
        Route::get('/audit', [\App\Http\Controllers\SuperAdmin\AuditController::class, 'index'])->name('audit.index');
        Route::get('/settings', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/laporan', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'index'])->name('laporan.index');
    });

// Modul 14 - Finalisasi Dashboard Statistik per Role
// Ringkas: dashboard admin dengan ringkasan statistik.
// Admin routes
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:Admin'])
    ->name('admin.dashboard');

Route::middleware(['auth', 'checkRole:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Modul 11 - Laporan Admin dan SPV
        // Ringkas: laporan admin.
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');

        // Modul 2 - Admin Keluhan
        // Ringkas: CRUD dan validasi keluhan.
        Route::get('/keluhan', [AdminKeluhanController::class, 'index'])->name('keluhan.index');
        Route::get('/keluhan/create', [AdminKeluhanController::class, 'create'])->name('keluhan.create');
        Route::post('/keluhan', [AdminKeluhanController::class, 'store'])->name('keluhan.store');
        Route::get('/keluhan/{keluhan}', [AdminKeluhanController::class, 'show'])->name('keluhan.show');
        Route::get('/keluhan/{keluhan}/edit', [AdminKeluhanController::class, 'edit'])->name('keluhan.edit');
        Route::put('/keluhan/{keluhan}', [AdminKeluhanController::class, 'update'])->name('keluhan.update');
        Route::patch('/keluhan/{keluhan}/validasi', [AdminKeluhanController::class, 'validasi'])->name('keluhan.validasi');
        Route::patch('/keluhan/{keluhan}/tidak-valid', [AdminKeluhanController::class, 'tidakValid'])->name('keluhan.tidak_valid');

        // Modul 3 - Admin Buat Ticket dari Keluhan
        // Ringkas: form dan proses pembuatan ticket dari keluhan valid.
        Route::get('/keluhan/{keluhan}/buat-ticket', [AdminTicketController::class, 'createFromKeluhan'])->name('keluhan.buat_ticket');
        Route::post('/keluhan/{keluhan}/buat-ticket', [AdminTicketController::class, 'storeFromKeluhan'])->name('keluhan.store_ticket');

        // Modul 10 - Admin Monitoring Ticket dan Assign Teknisi
        // Ringkas: monitoring ticket pada sisi admin.
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');

        // Modul 8 - Admin/SPV Verifikasi Ticket Selesai
        // Ringkas: verifikasi ticket selesai oleh admin.
        Route::get('/verifikasi', [AdminVerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{ticket}', [AdminVerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{ticket}', [AdminVerifikasiController::class, 'verifikasi'])->name('verifikasi.store');
    });

// Modul 14 - Finalisasi Dashboard Statistik per Role
// Ringkas: dashboard SPV dengan ringkasan statistik.
// SPV routes
Route::get('/spv/dashboard', [SpvDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:SPV'])
    ->name('spv.dashboard');

Route::middleware(['auth', 'checkRole:SPV'])
    ->prefix('spv')
    ->name('spv.')
    ->group(function () {
        // Modul 13 - SPV Kategori Masalah
        // Ringkas: CRUD kategori masalah dan status aktif/nonaktif.
        Route::get('/kategori', [SpvKategoriMasalahController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [SpvKategoriMasalahController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [SpvKategoriMasalahController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{kategori}', [SpvKategoriMasalahController::class, 'show'])->name('kategori.show');
        Route::get('/kategori/{kategori}/edit', [SpvKategoriMasalahController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{kategori}', [SpvKategoriMasalahController::class, 'update'])->name('kategori.update');
        Route::patch('/kategori/{kategori}/toggle-status', [SpvKategoriMasalahController::class, 'toggleStatus'])->name('kategori.toggle_status');

        // Modul 12 - SPV User Management
        // Ringkas: CRUD user, status, dan reset password.
        Route::get('/users', [SpvUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [SpvUserController::class, 'create'])->name('users.create');
        Route::post('/users', [SpvUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [SpvUserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [SpvUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [SpvUserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [SpvUserController::class, 'toggleStatus'])->name('users.toggle_status');
        Route::patch('/users/{user}/reset-password', [SpvUserController::class, 'resetPassword'])->name('users.reset_password');

        // Modul 11 - Laporan Admin dan SPV
        // Ringkas: laporan SPV.
        Route::get('/laporan', [SpvLaporanController::class, 'index'])->name('laporan.index');

        // Modul 9 - SPV Monitoring Semua Ticket
        // Ringkas: monitoring ticket SPV termasuk prioritas dan assign ulang.
        Route::get('/tickets', [SpvTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [SpvTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/prioritas', [SpvTicketController::class, 'updatePrioritas'])->name('tickets.prioritas');
        Route::patch('/tickets/{ticket}/assign-teknisi', [SpvTicketController::class, 'assignTeknisi'])->name('tickets.assign_teknisi');

        // Modul 7 - SPV Keputusan Eskalasi
        // Ringkas: daftar dan keputusan eskalasi.
        Route::get('/eskalasi', [SpvEskalasiController::class, 'index'])->name('eskalasi.index');
        Route::get('/eskalasi/{eskalasi}', [SpvEskalasiController::class, 'show'])->name('eskalasi.show');
        Route::patch('/eskalasi/{eskalasi}/keputusan', [SpvEskalasiController::class, 'keputusan'])->name('eskalasi.keputusan');

        // Modul 8 - Admin/SPV Verifikasi Ticket Selesai
        // Ringkas: verifikasi ticket selesai oleh SPV.
        Route::get('/verifikasi', [SpvVerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{ticket}', [SpvVerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{ticket}', [SpvVerifikasiController::class, 'verifikasi'])->name('verifikasi.store');
    });

// Modul 14 - Finalisasi Dashboard Statistik per Role
// Ringkas: dashboard teknisi dengan ringkasan statistik.
// Teknisi routes
Route::get('/teknisi/dashboard', [TeknisiDashboardController::class, 'index'])
    ->middleware(['auth', 'checkRole:Teknisi'])
    ->name('teknisi.dashboard');

Route::middleware(['auth', 'checkRole:Teknisi'])
    ->prefix('teknisi')
    ->name('teknisi.')
    ->group(function () {
        // Modul 4 - Teknisi Ticket Saya dan Progress Pengerjaan
        // Ringkas: daftar dan detail ticket teknisi.
        Route::get('/tickets', [TeknisiTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [TeknisiTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/mulai', [TeknisiTicketController::class, 'mulai'])->name('tickets.mulai');
        Route::post('/tickets/{ticket}/progress', [TeknisiTicketController::class, 'storeProgress'])->name('tickets.progress');
        Route::patch('/tickets/{ticket}/selesai', [TeknisiTicketController::class, 'selesai'])->name('tickets.selesai');

        // Modul 5 - Teknisi Upload Bukti Pengerjaan
        // Ringkas: upload lampiran hasil pekerjaan.
        Route::post('/tickets/{ticket}/lampiran', [TeknisiTicketController::class, 'uploadLampiran'])->name('tickets.lampiran');

        // Modul 6 - Teknisi Ajukan Eskalasi
        // Ringkas: pengajuan eskalasi ke SPV.
        Route::post('/tickets/{ticket}/eskalasi', [TeknisiTicketController::class, 'ajukanEskalasi'])->name('tickets.eskalasi');

        // Modul 15 - Teknisi Riwayat Pengerjaan
        // Ringkas: riwayat progress teknisi.
        Route::get('/riwayat-pengerjaan', [TeknisiRiwayatPengerjaanController::class, 'index'])->name('riwayat.index');

        // Modul 16 - Teknisi Status Saya
        // Ringkas: ringkasan status ticket teknisi.
        Route::get('/status-saya', [TeknisiStatusSayaController::class, 'index'])->name('status.index');
    });
