<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    public function index()
    {
        $pendaftar = Pendaftar::latest()->get();

        return view('pendaftaran', [
            'title' => 'Daftar Pendaftar',
            'adminName' => session('adminName'),
            'pendaftar' => $pendaftar
        ]);
    }

    public function accept(Request $request)
    {
        $pendaftar = Pendaftar::findOrFail($request->id);

        $pendaftar->status = "DITERIMA";
        $pendaftar->save();

         // 🔥 TAMBAHAN NOTIF
    Notifikasi::create([
        'user_id' => $pendaftar->user_id,
        'judul' => 'Pendaftaran diterima',
        'pesan' => 'Silakan lanjut pembayaran',
        'is_read' => 0
    ]);

        return redirect()->back()->with('success', 'Pendaftar diterima');
    }

    public function reject(Request $request)
    {
        $pendaftar = Pendaftar::findOrFail($request->id);

        $pendaftar->status = "DITOLAK";
        $pendaftar->save();

        // 🔥 TAMBAHAN NOTIF
    Notifikasi::create([
        'user_id' => $pendaftar->user_id,
        'judul' => 'Pendaftaran ditolak',
        'pesan' => 'Silakan hubungi admin',
        'is_read' => 0
    ]);

        return redirect()->back()->with('success', 'Pendaftar ditolak');
    }
}