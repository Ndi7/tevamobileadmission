<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\KelasController;
use App\Models\KelasMataPelajaran;
use App\Http\Controllers\ProgramJenjangController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Models\Pendaftar;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\NotifikasiController;

/*
|--------------------------------------------------------------------------
| API ROUTES FOR FLUTTER
|--------------------------------------------------------------------------
*/

// ======================
// AUTH (Flutter)
// ======================
Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);

// ======================
// KELAS & MAPEL (Flutter)
// ======================
Route::get('/kelas', [KelasController::class, 'index']);

Route::get('/kelas/{id}/mapel', function ($id) {
    $data = KelasMataPelajaran::with('mapel')
        ->where('kelas_id', $id)
        ->get()
        ->groupBy('tipe');

    return response()->json([
        'wajib' => $data['wajib'] ?? [],
        'reguler' => $data['reguler'] ?? [],
        'ekskul' => $data['ekskul'] ?? [],
    ]);
});

Route::get('/kelas/{id}/mapel', [ProgramJenjangController::class, 'mapelByKelasApi']);

// ======================
// PENDAFTARAN (Flutter)
// ======================
Route::post('/pendaftaran', [PendaftaranController::class, 'store']);
Route::post('/pendaftaran/user', [PendaftaranController::class, 'byUser']);

// ======================
// AUTH SANCTUM (OPTIONAL)
// ======================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/upload-bukti', [PaymentController::class, 'uploadBukti']);

Route::get('/payment-setting', [PaymentSettingController::class, 'api']);

Route::post('/notifikasi/user', [NotifikasiController::class, 'getByUser']);
Route::post('/notifikasi/read', [NotifikasiController::class, 'markAsRead']);