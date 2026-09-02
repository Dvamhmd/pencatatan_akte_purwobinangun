<?php

use App\Http\Controllers\Admin\AdminBirthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDeathController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\BirthCertificateController;
use App\Http\Controllers\DeathCertificateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes - Layanan Warga Kalurahan Purwobinangun
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/panduan-persyaratan', [HomeController::class, 'guidelines'])->name('guidelines');

// Layanan Akte Kelahiran
Route::prefix('akte-kelahiran')->name('birth.')->group(function () {
    Route::get('/buat', [BirthCertificateController::class, 'create'])->name('create');
    Route::post('/buat', [BirthCertificateController::class, 'store'])->name('store');
    Route::get('/sukses', [BirthCertificateController::class, 'success'])->name('success');
    Route::get('/daftar-pengajuan', [BirthCertificateController::class, 'list'])->name('list');
});

// Shortcut Route Daftar Pengajuan Warga
Route::get('/daftar-pengajuan', [BirthCertificateController::class, 'list'])->name('submissions.index');

// Layanan Akte Kematian
Route::prefix('akte-kematian')->name('death.')->group(function () {
    Route::get('/buat', [DeathCertificateController::class, 'create'])->name('create');
    Route::post('/buat', [DeathCertificateController::class, 'store'])->name('store');
    Route::get('/sukses', [DeathCertificateController::class, 'success'])->name('success');
});

// Lacak Permohonan
Route::prefix('lacak')->name('tracking.')->group(function () {
    Route::get('/', [TrackingController::class, 'index'])->name('index');
    Route::get('/{type}/{registrationNo}', [TrackingController::class, 'show'])->name('show');
    Route::get('/{type}/{registrationNo}/cetak-resi', [TrackingController::class, 'printReceipt'])->name('print_receipt');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Panel Petugas Kalurahan
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kelola Akte Kelahiran
        Route::prefix('akte-kelahiran')->name('birth.')->group(function () {
            Route::get('/', [AdminBirthController::class, 'index'])->name('index');
            Route::get('/{birth}', [AdminBirthController::class, 'show'])->name('show');
            Route::put('/{birth}/status', [AdminBirthController::class, 'updateStatus'])->name('update_status');
            Route::get('/{birth}/cetak-surat', [AdminBirthController::class, 'printLetter'])->name('print_letter');
        });

        // Kelola Akte Kematian
        Route::prefix('akte-kematian')->name('death.')->group(function () {
            Route::get('/', [AdminDeathController::class, 'index'])->name('index');
            Route::get('/{death}', [AdminDeathController::class, 'show'])->name('show');
            Route::put('/{death}/status', [AdminDeathController::class, 'updateStatus'])->name('update_status');
            Route::get('/{death}/cetak-surat', [AdminDeathController::class, 'printLetter'])->name('print_letter');
        });
    });
});
