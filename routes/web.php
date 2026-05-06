<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\ProgramJenjangController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\SiswaAktifController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Api\PendaftaranController;


/*
|--------------------------------------------------------------------------
| HALAMAN USER (PUBLIC)
|--------------------------------------------------------------------------
*/

// Homepage (user)
Route::get('/', function () {
    return view('welcome');
});

// Register user
Route::get('/register', function () {
    return view('user.register');
})->name('user.register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('user.register.process');

Route::get('/user/login', [UserAuthController::class, 'showLogin'])
    ->name('user.login');

Route::post('/user/login', [UserAuthController::class, 'login'])
    ->name('user.login.process');

Route::get('/login', function () {
    return redirect()->route('user.login');
})->name('login');

Route::post('/user/logout', [UserAuthController::class, 'logout'])
    ->name('user.logout');

Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'nocache'])
    ->name('user.dashboard');

Route::get('/user/info', [UserInfoController::class, 'index'])
    ->name('user.info');

Route::middleware(['auth', 'nocache'])->group(function () {

    Route::get('/user/profile', [UserProfileController::class, 'index'])
        ->name('user.profile');

    Route::get('/user/profile/edit', [UserProfileController::class, 'edit'])
        ->name('user.edit.profile');

    Route::post('/user/profile/update', [UserProfileController::class, 'update'])
        ->name('user.update.profile');

});   

Route::get('/user/ketentuan', function () {
    return view('user.ketentuan');
});

Route::get('/user/pendaftaran', function () {
    return view('user.pendaftaran');
});

Route::get('/success', function () {
    return view('user.success');
});

Route::middleware(['auth', 'nocache'])->group(function () {
    Route::post('/pendaftaran', [PendaftaranController::class, 'store']);

    Route::get('/pembayaran/{uuid}', [PaymentController::class, 'create'])
    ->name('user.pembayaran');

    Route::post('/pembayaran', [PaymentController::class, 'store'])
    ->name('user.pembayaran.store');
});

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login.process');

Route::get('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout');


/*
|--------------------------------------------------------------------------
| HALAMAN ADMIN (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['admin.auth', 'nocache'])->prefix('/admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/export-csv', [DashboardController::class, 'exportCSV']);

    // PENDAFTARAN
    Route::get('/pendaftaran', [PendaftarController::class, 'index'])
        ->name('admin.pendaftaran');

    Route::post('/pendaftaran/accept', [PendaftarController::class, 'accept'])
        ->name('admin.pendaftaran.accept');

    Route::post('/pendaftaran/reject', [PendaftarController::class, 'reject']);

    // SISWA
    Route::get('/siswa-aktif', [SiswaAktifController::class, 'index']);

    // PEMBAYARAN
    Route::get('/pembayaran', [PaymentController::class, 'index']);
    Route::post('/pembayaran/confirm', [PaymentController::class, 'confirm']);
    Route::post('/pembayaran/reject', [PaymentController::class, 'reject']);

    // PENGATURAN PEMBAYARAN
    Route::get('/pengaturan-pembayaran', [PaymentSettingController::class, 'index'])
        ->name('admin.pengaturanPembayaran');

    Route::post('/pengaturan-pembayaran', [PaymentSettingController::class,'store']);
    Route::post('/update-qris', [PaymentSettingController::class, 'updateQris']);
    Route::post('/hapus-qris', [PaymentSettingController::class, 'deleteQris']);

    // KELAS
    Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas');
    Route::post('/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('admin.kelas.edit');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');

    // PROGRAM
    Route::get('/programs', [ProgramJenjangController::class, 'index'])->name('admin.programs');
    Route::post('/programs', [ProgramJenjangController::class, 'store'])->name('admin.programs.store');
    Route::delete('/programs/{id}', [ProgramJenjangController::class, 'destroy'])->name('admin.programs.destroy');

    // MATA PELAJARAN
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

    // PENGATURAN AKUN
    Route::get('/pengaturan', [AuthController::class, 'showSettings'])
        ->name('pengaturan');

    Route::post('/pengaturan', [AuthController::class, 'updateSettings'])
        ->name('pengaturan.update');
}); 