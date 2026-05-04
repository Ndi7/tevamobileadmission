<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * =========================
     * MENAMPILKAN DATA PEMBAYARAN
     * =========================
     */
    public function index()
    {
        /**
         * Mengambil seluruh data pembayaran
         * Relasi dengan tabel pendaftar
         * Diurutkan dari data terbaru
         */
        $payments = Payment::with('pendaftar')
            ->latest()
            ->get();

        // Menampilkan halaman pembayaran
        return view('admin.pembayaran', compact('payments'));
    }

    /**
     * =========================
     * UPLOAD BUKTI PEMBAYARAN (API)
     * =========================
     */
    public function uploadBukti(Request $request)
    {
        try {
            /**
             * Validasi data upload bukti pembayaran
             */
            $request->validate([
                'bukti'        => 'required|image',
                'pendaftar_id' => 'required|exists:pendaftars,id',
                'jumlah'       => 'required'
            ]);

            /**
             * Menyimpan file bukti pembayaran
             * Disimpan ke storage public/bukti
             */
            $path = $request->file('bukti')->store('bukti', 'public');

            /**
             * Menyimpan data pembayaran ke database
             * Status awal: menunggu
             */
            Payment::create([
                'pendaftar_id' => $request->pendaftar_id,
                'bukti'        => $path,
                'jumlah'       => $request->jumlah,
                'status'       => 'menunggu'
            ]);

            /**
             * =========================
             * MENAMBAHKAN NOTIFIKASI
             * =========================
             */
            $pendaftar = \App\Models\Pendaftar::find($request->pendaftar_id);

            Notifikasi::create([
                'user_id' => $pendaftar->user_id,
                'judul'   => 'Pembayaran',
                'pesan'   => 'Pembayaran sedang diverifikasi',
                'is_read' => 0
            ]);

            // Response berhasil
            return response()->json([
                'message' => 'Bukti berhasil dikirim'
            ], 200);

        } catch (\Exception $e) {

            // Response jika terjadi error
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========================
     * KONFIRMASI PEMBAYARAN
     * =========================
     */
    public function confirm(Request $request)
    {
        // Mengambil data pembayaran berdasarkan ID
        $payment = Payment::findOrFail($request->id);

        // Mengubah status pembayaran menjadi diterima
        $payment->status = 'diterima';
        $payment->save();

        /**
         * Menambahkan notifikasi pembayaran berhasil
         */
        Notifikasi::create([
            'user_id' => $payment->pendaftar->user_id,
            'judul'   => 'Pembayaran berhasil',
            'pesan'   => 'Pembayaran kamu sudah dikonfirmasi',
            'is_read' => 0
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi');
    }

    /**
     * =========================
     * TOLAK PEMBAYARAN
     * =========================
     */
    public function reject(Request $request)
    {
        // Mengambil data pembayaran berdasarkan ID
        $payment = Payment::findOrFail($request->id);

        // Mengubah status pembayaran menjadi ditolak
        $payment->status = 'ditolak';
        $payment->save();

        /**
         * Menambahkan notifikasi pembayaran ditolak
         */
        Notifikasi::create([
            'user_id' => $payment->pendaftar->user_id,
            'judul'   => 'Pembayaran ditolak',
            'pesan'   => 'Silakan upload ulang bukti pembayaran',
            'is_read' => 0
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pembayaran ditolak');
    }



    /**
     * =========================
     * KHUSUS USER PAYMENT
     * =========================
     */

    public function create($id)
{
    $pendaftar = \App\Models\Pendaftar::findOrFail($id);
    $setting   = \App\Models\PaymentSetting::first();

    // 🔥 FIX DI SINI
    $jumlah = $pendaftar->total_harga;

    return view('user.pembayaran', [
        'pendaftar' => $pendaftar,
        'jumlah'    => $jumlah,
        'setting'   => $setting
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'bukti'        => 'required|image',
            'jumlah'       => 'required|numeric'
        ]);

        $path = $request->file('bukti')->store('bukti', 'public');

        Payment::create([
            'pendaftar_id' => $request->pendaftar_id,
            'bukti'        => $path,
            'jumlah'       => $request->jumlah,
            'status'       => 'menunggu'
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Bukti pembayaran berhasil dikirim');
    }
}