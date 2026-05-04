<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\Notifikasi;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 🔥 AMBIL USER DARI LOGIN (SESSION / TOKEN)
        $user = auth()->user();

        // 🔥 CEK LOGIN
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized (user belum login)'
            ], 401);
        }

        // 🔥 AMBIL DATA PENDAFTAR BERDASARKAN USER LOGIN
        $pendaftar = Pendaftar::with('payment')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // 🔥 AMBIL NOTIFIKASI USER
        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pendaftar,
            'notifikasi' => $notifikasi,
            'jumlah_notif' => $notifikasi->where('is_read', 0)->count()
        ]);
    }
}