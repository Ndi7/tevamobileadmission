<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    /**
     * =========================
     * HALAMAN PENGATURAN PEMBAYARAN
     * =========================
     */
    public function index()
    {
        // Mengambil satu data pengaturan pembayaran
        $setting = PaymentSetting::first();

        // Menampilkan halaman pengaturan pembayaran
        return view('admin.pengaturan-pembayaran', compact('setting'));
    }

    /**
     * =========================
     * UPDATE DATA BANK
     * =========================
     */
    public function store(Request $request)
    {
        /**
         * Validasi data rekening bank
         */
        $request->validate([
            'bank'     => 'required',
            'rekening' => 'required',
            'pemilik'  => 'required',
        ]);

        /**
         * Menyimpan atau memperbarui data bank
         * Menggunakan id = 1 agar hanya ada satu data pengaturan
         */
        PaymentSetting::updateOrCreate(
            ['id' => 1],
            [
                'bank'     => $request->bank,
                'rekening' => $request->rekening,
                'pemilik'  => $request->pemilik,
            ]
        );

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Data bank berhasil disimpan');
    }

    /**
     * =========================
     * UPDATE QRIS
     * =========================
     */
    public function updateQris(Request $request)
    {
        /**
         * Validasi file QRIS
         * Hanya menerima file gambar
         */
        $request->validate([
            'qris' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        // Mengambil data pengaturan pembayaran
        $setting = PaymentSetting::first();

        /**
         * Menghapus file QRIS lama jika ada
         */
        if ($setting && $setting->qris) {
            $old = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $setting->qris;
            if (file_exists($old)) {
                unlink($old);
            }
        }

        /**
         * Menentukan folder penyimpanan QRIS
         */
        $folder = $_SERVER['DOCUMENT_ROOT'] . '/storage/qris';

        // Membuat folder jika belum tersedia
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        /**
         * Proses upload file QRIS
         */
        $file = $request->file('qris');
        $namaFile = 'qris_' . time() . '.' . $file->getClientOriginalExtension();

        // Memindahkan file ke folder tujuan
        move_uploaded_file($file->getPathname(), $folder . '/' . $namaFile);

        /**
         * Menyimpan path QRIS ke database
         */
        PaymentSetting::updateOrCreate(
            ['id' => 1],
            ['qris' => 'qris/' . $namaFile]
        );

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'QRIS berhasil diupdate');
    }

    /**
     * =========================
     * HAPUS DATA QRIS
     * =========================
     */
    public function deleteQris()
    {
        // Mengambil data pengaturan pembayaran
        $setting = PaymentSetting::first();

        if ($setting && $setting->qris) {

            // Menentukan path file QRIS
            $path = public_path('storage/' . $setting->qris);

            // Menghapus file QRIS jika ada
            if (file_exists($path)) {
                unlink($path);
            }

            // Mengosongkan data QRIS di database
            $setting->qris = null;
            $setting->save();
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'QRIS berhasil dihapus');
    }

    /**
     * =========================
     * API DATA PEMBAYARAN
     * =========================
     */
    public function api()
    {
        // Mengambil data pengaturan pembayaran
        $setting = PaymentSetting::first();

        /**
         * Mengembalikan data pembayaran dalam format JSON
         * Digunakan oleh aplikasi mobile atau frontend
         */
        return response()->json([
            'bank'     => $setting->bank ?? null,
            'rekening' => $setting->rekening ?? null,
            'pemilik'  => $setting->pemilik ?? null,
            'qris'     => $setting && $setting->qris
                ? 'https://tevacentre.site/storage/' . $setting->qris
                : null,
        ]);
    }
}