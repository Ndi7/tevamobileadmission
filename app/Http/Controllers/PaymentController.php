<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // =========================
    // TAMPILKAN DATA PEMBAYARAN
    // =========================
    public function index()
    {
        $payments = Payment::with('pendaftar')->latest()->get();

        return view('pembayaran', compact('payments'));
    }

    // =========================
    // UPLOAD BUKTI (API)
    // =========================
    public function uploadBukti(Request $request)
    {
        try {
            $request->validate([
                'bukti' => 'required|image',
                'pendaftar_id' => 'required|exists:pendaftars,id',
                'jumlah' => 'required'
            ]);

            $path = $request->file('bukti')->store('bukti', 'public');

            Payment::create([
                'pendaftar_id' => $request->pendaftar_id,
                'bukti' => $path,
                'jumlah' => $request->jumlah,
                'status' => 'menunggu'
            ]);

            // 🔔 TAMBAHAN NOTIF
            $pendaftar = \App\Models\Pendaftar::find($request->pendaftar_id);

            Notifikasi::create([
                'user_id' => $pendaftar->user_id,
                'judul' => 'Pembayaran', // 🔥 TAMBAHKAN INI
                'pesan' => 'Pembayaran sedang diverifikasi',
                'is_read' => 0
            ]);
            return response()->json([
                'message' => 'Bukti berhasil dikirim'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // KONFIRMASI PEMBAYARAN
    // =========================
    public function confirm(Request $request)
    {
        $payment = Payment::findOrFail($request->id);

        $payment->status = 'diterima';
        $payment->save();

        // 🔥 TAMBAHAN NOTIF
    Notifikasi::create([
        'user_id' => $payment->pendaftar->user_id,
        'judul' => 'Pembayaran berhasil',
        'pesan' => 'Pembayaran kamu sudah dikonfirmasi',
        'is_read' => 0
    ]);

        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi');
    }

    // =========================
    // TOLAK PEMBAYARAN
    // =========================
    public function reject(Request $request)
    {
        $payment = Payment::findOrFail($request->id);

        $payment->status = 'ditolak';
        $payment->save();

        // 🔥 TAMBAHAN NOTIF
    Notifikasi::create([
        'user_id' => $payment->pendaftar->user_id,
        'judul' => 'Pembayaran ditolak',
        'pesan' => 'Silakan upload ulang bukti pembayaran',
        'is_read' => 0
    ]);

        return redirect()->back()->with('success', 'Pembayaran ditolak');
    }
}