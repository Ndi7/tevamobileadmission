<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\ProgramJenjangController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\SiswaAktifController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AnnouncementController;
// use App\Http\Controllers\FeesController; // ✅ ganti dari BiayaController ke FeesController

use App\Models\Kelas;

/*
|--------------------------------------------------------------------------
| LOGIN & LOGOUT
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman pertama → ke Login
Route::get('/', fn() => redirect()->route('login'));


/*
|--------------------------------------------------------------------------
| HALAMAN ADMIN (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['admin.auth', 'nocache'])->prefix('/admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/export-csv', [DashboardController::class, 'exportCSV']);

    Route::get('/pendaftaran', [PendaftarController::class, 'index'])
    ->name('admin.pendaftaran');
    
    Route::post('/pendaftaran/accept', [PendaftarController::class, 'accept'])
    ->name('admin.pendaftaran.accept');

    Route::post('/pendaftaran/reject', [PendaftarController::class, 'reject']);

    Route::get('/siswa-aktif', [SiswaAktifController::class, 'index']);

    Route::get('/pembayaran', [PaymentController::class, 'index']);
    Route::post('/pembayaran/confirm', [PaymentController::class, 'confirm']);
    Route::post('/pembayaran/reject', [PaymentController::class, 'reject']);

    Route::get('/pengaturan-pembayaran', fn () => view('pengaturan-pembayaran', [
    'title' => 'Pengaturan Pembayaran',
    'adminName' => session('adminName')
]))->name('admin.pengaturanPembayaran');

    Route::get('/pengaturan-pembayaran', [PaymentSettingController::class, 'index']);
    Route::post('/pengaturan-pembayaran',[PaymentSettingController::class,'store']);
    Route::post('/update-qris', [PaymentSettingController::class, 'updateQris']);
    Route::post('/hapus-qris', [PaymentSettingController::class, 'deleteQris']);

    // KELAS
    Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas');
    Route::post('/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('admin.kelas.edit');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');

    // PROGRAM & JENJANG
    Route::get('/programs', [ProgramJenjangController::class, 'index'])->name('admin.programs');
    Route::post('/programs', [ProgramJenjangController::class, 'store'])->name('admin.programs.store');
    Route::delete('/programs/{id}', [ProgramJenjangController::class, 'destroy'])->name('admin.programs.destroy');

    // MATA PELAJARAN (CRUD)
    Route::get('/subjects', [MataPelajaranController::class, 'index'])->name('admin.subjects');
    Route::post('/subjects', [MataPelajaranController::class, 'store'])->name('admin.subjects.store');
    Route::put('/subjects/{id}', [MataPelajaranController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/subjects/{id}', [MataPelajaranController::class, 'destroy'])->name('admin.subjects.destroy');

    // PENGUMUMAN
    Route::get('/pengumuman', [AnnouncementController::class, 'index']);
    Route::post('/pengumuman', [AnnouncementController::class, 'store']);
    
    Route::delete('/pengumuman/{id}', [AnnouncementController::class, 'destroy']);
    Route::put('/pengumuman/{id}', [AnnouncementController::class, 'update']);

    // LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('admin.laporan');

    // PENGATURAN
    Route::get('/pengaturan', [AuthController::class, 'showSettings'])->name('pengaturan');
    Route::post('/pengaturan', [AuthController::class, 'updateSettings'])->name('pengaturan.update');

});
