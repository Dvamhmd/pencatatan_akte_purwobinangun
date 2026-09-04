<?php

use App\Http\Controllers\Admin\AdminArchiveController;
use App\Http\Controllers\Admin\AdminBirthController;
use App\Http\Controllers\Admin\AdminCitizenController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDeathController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Auth\WargaAuthController;
use App\Http\Controllers\BirthCertificateController;
use App\Http\Controllers\DeathCertificateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Warga\WargaProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes - Layanan Warga Kalurahan Purwobinangun
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/panduan-persyaratan', [HomeController::class, 'guidelines'])->name('guidelines');

// Autentikasi Khusus Warga (NIK & Password)
Route::prefix('warga')->name('warga.')->group(function () {
    Route::get('/login', [WargaAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WargaAuthController::class, 'login'])->name('login.submit');
    Route::get('/daftar', [WargaAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/daftar', [WargaAuthController::class, 'register'])->name('register.submit');
    Route::post('/logout', [WargaAuthController::class, 'logout'])->name('logout');
});

// Shortcut Login / Register Warga
Route::get('/login', [WargaAuthController::class, 'showLoginForm'])->name('login');
Route::get('/daftar', [WargaAuthController::class, 'showRegisterForm'])->name('register');

// Lacak Permohonan Akta (Publik & Detail Berbasis Hak Akses KK)
Route::prefix('lacak')->name('tracking.')->group(function () {
    Route::get('/', [TrackingController::class, 'index'])->name('index');
    Route::get('/{type}/{registrationNo}', [TrackingController::class, 'show'])->name('show');
    Route::get('/{type}/{registrationNo}/cetak-resi', [TrackingController::class, 'printReceipt'])->name('print_receipt');
});

/*
|--------------------------------------------------------------------------
| Warga Protected Routes - Memerlukan Login Akun Warga Aktif
|--------------------------------------------------------------------------
*/
Route::middleware('auth.warga')->group(function () {
    
    // Profil & Data Warga
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [WargaProfileController::class, 'index'])->name('index');
        Route::put('/', [WargaProfileController::class, 'updateProfile'])->name('update');
        Route::put('/password', [WargaProfileController::class, 'updatePassword'])->name('password');
    });

    // Layanan Akte Kelahiran
    Route::prefix('akte-kelahiran')->name('birth.')->group(function () {
        Route::get('/buat', [BirthCertificateController::class, 'create'])->name('create');
        Route::post('/buat', [BirthCertificateController::class, 'store'])->name('store');
        Route::get('/sukses', [BirthCertificateController::class, 'success'])->name('success');
        Route::get('/daftar-pengajuan', function () {
            return redirect()->route('submissions.index', request()->query());
        })->name('list');
    });

    // Layanan Akte Kematian
    Route::prefix('akte-kematian')->name('death.')->group(function () {
        Route::get('/buat', [DeathCertificateController::class, 'create'])->name('create');
        Route::post('/buat', [DeathCertificateController::class, 'store'])->name('store');
        Route::get('/sukses', [DeathCertificateController::class, 'success'])->name('success');
        Route::get('/daftar-pengajuan', function () {
            return redirect()->route('submissions.index', array_merge(['type' => 'death'], request()->query()));
        })->name('list');
    });

    // Daftar Pengajuan Utama Warga (Akte Kelahiran & Akte Kematian)
    Route::get('/daftar-pengajuan', [BirthCertificateController::class, 'list'])->name('submissions.index');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Panel Petugas Kalurahan
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function () {
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

        // Kelola & Verifikasi Akun Warga
        Route::prefix('warga')->name('citizens.')->group(function () {
            Route::get('/', [AdminCitizenController::class, 'index'])->name('index');
            Route::get('/{citizen}', [AdminCitizenController::class, 'show'])->name('show');
            Route::get('/{citizen}/edit', [AdminCitizenController::class, 'edit'])->name('edit');
            Route::put('/{citizen}', [AdminCitizenController::class, 'update'])->name('update');
            Route::post('/{citizen}/verify', [AdminCitizenController::class, 'verify'])->name('verify');
        });

        // Arsip Pengajuan & Akun Ditolak / Dinonaktifkan
        Route::prefix('arsip')->name('archive.')->group(function () {
            Route::get('/', [AdminArchiveController::class, 'index'])->name('index');
            Route::post('/warga/{citizen}/archive', [AdminArchiveController::class, 'archiveCitizen'])->name('citizen.archive');
            Route::post('/warga/{citizen}/restore', [AdminArchiveController::class, 'restoreCitizen'])->name('citizen.restore');
            Route::post('/akte-kelahiran/{birth}/archive', [AdminArchiveController::class, 'archiveBirth'])->name('birth.archive');
            Route::post('/akte-kelahiran/{birth}/restore', [AdminArchiveController::class, 'restoreBirth'])->name('birth.restore');
            Route::post('/akte-kematian/{death}/archive', [AdminArchiveController::class, 'archiveDeath'])->name('death.archive');
            Route::post('/akte-kematian/{death}/restore', [AdminArchiveController::class, 'restoreDeath'])->name('death.restore');
        });

        // Pengaturan Profil Admin & Email Notifikasi
        Route::prefix('profil')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'index'])->name('index');
            Route::put('/', [AdminProfileController::class, 'updateProfile'])->name('update');
            Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password');
        });
    });
});
