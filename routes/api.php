<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\ProgramJenjangController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Api\UserDashboardController;

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/

// ======================
// AUTH
// ======================
Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);
Route::post('/user/update-profile', [UserAuthController::class, 'updateProfile']);
Route::get('/user/{id}', [UserAuthController::class, 'getUser']);

Route::middleware('auth:sanctum')->post('/update-profile', [UserAuthController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ======================
// KELAS & MAPEL
// ======================
Route::get('/kelas', [KelasController::class, 'index']);

// 🔥 FIX: hanya 1 route
Route::get('/kelas/{id}/mapel', [ProgramJenjangController::class, 'mapelByKelasApi']);

// ======================
// PENDAFTARAN
// ======================
Route::post('/pendaftaran', [PendaftaranController::class, 'storeApi']);
Route::post('/pendaftaran/user', [PendaftaranController::class, 'byUser']);

// ======================
// PAYMENT
// ======================
Route::post('/upload-bukti', [PaymentController::class, 'uploadBukti']);
Route::get('/payment-setting', [PaymentSettingController::class, 'api']);

// ======================
// NOTIFIKASI
// ======================
Route::post('/notifikasi/user', [NotifikasiController::class, 'getByUser']);
Route::post('/notifikasi/read', [NotifikasiController::class, 'markAsRead']);

// ======================
// DASHBOARD
// ======================
Route::get('/user/dashboard', [UserDashboardController::class, 'index']);