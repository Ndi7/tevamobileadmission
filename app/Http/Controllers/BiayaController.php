<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    /**
     * =========================
     * MENAMPILKAN SEMUA DATA BIAYA
     * =========================
     */
    public function index()
    {
        /**
         * Mengambil seluruh data biaya
         * Diurutkan berdasarkan jenjang dan tipe biaya
         */
        $biaya = Biaya::orderBy('jenjang')
                      ->orderBy('tipe')
                      ->get();

        // Menampilkan halaman pengelolaan biaya
        return view('admin.fees', [
            'title' => 'Kelola Biaya',
            'biaya' => $biaya,
        ]);
    }

    /**
     * =========================
     * MENYIMPAN DATA BIAYA BARU
     * =========================
     */
    public function store(Request $request)
    {
        /**
         * Validasi input biaya
         */
        $request->validate([
            'jenjang' => 'required|string',
            'tipe'    => 'required|string',
            'biaya'   => 'required|numeric|min:0',
        ]);

        /**
         * Menyimpan data biaya ke database
         */
        Biaya::create([
            'jenjang' => $request->jenjang,
            'tipe'    => $request->tipe,
            'biaya'   => $request->biaya,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Biaya berhasil ditambahkan.');
    }

    /**
     * =========================
     * UPDATE DATA BIAYA
     * =========================
     */
    public function update(Request $request, $id)
    {
        /**
         * Validasi data biaya yang diperbarui
         */
        $request->validate([
            'jenjang' => 'required|string',
            'tipe'    => 'required|string',
            'biaya'   => 'required|numeric|min:0',
        ]);

        /**
         * Update data biaya berdasarkan ID
         */
        Biaya::where('id', $id)->update([
            'jenjang' => $request->jenjang,
            'tipe'    => $request->tipe,
            'biaya'   => $request->biaya,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Biaya berhasil diperbarui.');
    }

    /**
     * =========================
     * HAPUS DATA BIAYA
     * =========================
     */
    public function destroy($id)
    {
        /**
         * Menghapus data biaya berdasarkan ID
         */
        Biaya::where('id', $id)->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Biaya berhasil dihapus.');
    }
}