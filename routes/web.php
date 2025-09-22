<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\AkunController;

// === SYMLINK STORAGE ===
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Symlink storage berhasil dibuat!';
});

// === LANDING PAGE ===
Route::get('/', function () {
    return view('welcome'); // File resources/views/welcome.blade.php
})->name('landing');

// === AUTH ===
Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// === SEMUA YANG SUDAH LOGIN ===
Route::middleware('auth')->group(function () {

    // === Redirect sesuai Role setelah login ===
    Route::get('/redirect-dashboard', function () {
        $user = Auth::user();

        if ($user->role_id == 1) {
            return redirect()->route('tu.dashboard');
        } elseif ($user->role_id == 2) {
            return redirect()->route('user.dashboard');
        } else {
            abort(403, 'Role tidak dikenali.');
        }
    })->name('root');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role_id == 1) {
        return redirect()->route('tu.dashboard');
    } elseif ($user->role_id == 2) {
        return redirect()->route('user.dashboard');
    } else {
        abort(403, 'Role tidak dikenali!.');
    }
})->name('dashboard');

// Untuk user
Route::get('/user/pengajuan/refresh', [PengajuanController::class, 'refreshUserPengajuanTable'])->name('user.pengajuan.refresh');

// Untuk admin
Route::get('/tu/daftar/refresh', [PengajuanController::class, 'refreshAdminTable'])->name('tu.daftar.refresh');


    // === DASHBOARD TU (ADMIN / TATA USAHA) ===
    Route::get('/tu/dashboard', [PengajuanController::class, 'dashboardTU'])->name('tu.dashboard');

    // === DASHBOARD USER ===
    Route::get('/user/dashboard', [PengajuanController::class, 'dashboardUser'])->name('user.dashboard');

    // === FITUR TATA USAHA (TU) ===
    Route::prefix('tu')->name('tu.')->group(function () {

        // Daftar & Rekap
        Route::get('/daftar', [PengajuanController::class, 'indexTU'])->name('daftar.index');
        Route::get('/rekap-excel', [PengajuanController::class, 'exportExcelTU'])->name('rekap.excel');
        Route::get('/daftar/daftar-permintaan', [PengajuanController::class, 'liveTable'])->name('daftar.daftar-permintaan');
        Route::get('/pengajuan/pdf', [PengajuanController::class, 'generatePdf'])->name('pengajuan.pdf');

        // === MANAJEMEN AKUN ===
        Route::prefix('akun')->name('akun.')->group(function () {
            Route::get('/', [AkunController::class, 'index'])->name('index');
            Route::get('/create', [AkunController::class, 'create'])->name('create');
            Route::post('/', [AkunController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AkunController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AkunController::class, 'update'])->name('update');
            Route::delete('/{id}', [AkunController::class, 'destroy'])->name('destroy');
        });

        // === PENGAJUAN TU ===
        Route::get('/pengajuan', [PengajuanController::class, 'indexTU'])->name('pengajuan');
        Route::get('/pengajuan/{id}/edit', [PengajuanController::class, 'editTU'])->name('pengajuan.edit');
        Route::put('/pengajuan/{id}', [PengajuanController::class, 'updateTU'])->name('pengajuan.update');
        Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroyTU'])->name('pengajuan.delete');
        Route::patch('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.update-status');

        // === STOK BARANG ===
        Route::prefix('stok')->name('stok.')->group(function () {
            Route::get('/', [StokBarangController::class, 'index'])->name('index');
            Route::get('/create', [StokBarangController::class, 'create'])->name('create');
            Route::post('/store', [StokBarangController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [StokBarangController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [StokBarangController::class, 'update'])->name('update');
            Route::delete('/{id}', [StokBarangController::class, 'destroy'])->name('destroy');
            Route::get('/export', [StokBarangController::class, 'exportExcel'])->name('export');
        });
    });

    // === FITUR USER (PENGAJU) ===
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/pengajuan', [PengajuanController::class, 'indexUser'])->name('pengajuan');
        Route::get('/pengajuan/create', [PengajuanController::class, 'createUser'])->name('pengajuan.create');
        Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.delete');
        Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');
        Route::get('/pengajuan/{id}/pdf', [PengajuanController::class, 'generatePdf'])->name('pengajuan.pdf');
    });

    // === AUTOCOMPLETE NAMA BARANG ===
    Route::get('/stok/autocomplete', [StokBarangController::class, 'autocomplete'])->name('stok.autocomplete');

    // ✅ Update checkbox via AJAX
    Route::post('/pengajuan/update-check', [PengajuanController::class, 'updateCheck'])->name('pengajuan.updateCheck');
});
