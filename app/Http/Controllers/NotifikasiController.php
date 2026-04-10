<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    // 🔥 AMBIL NOTIF PER USER
    public function getByUser(Request $request)
    {
        $notif = Notifikasi::where('user_id', $request->user_id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $notif
        ]);
    }

    // 🔥 MARK AS READ
    public function markAsRead(Request $request)
    {
        $notif = Notifikasi::findOrFail($request->id);
        $notif->is_read = true;
        $notif->save();

        return response()->json([
            'message' => 'Notifikasi dibaca'
        ]);
    }
}