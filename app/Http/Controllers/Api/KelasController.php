<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas
     * Digunakan untuk kebutuhan API ( mobile )
     */
    public function index()
    {
        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            // Status response (true = berhasil)
            'status' => true,

            // Data kelas yang diambil dari database
            'data' => Kelas::select(
                    'id',          // ID kelas
                    'nama_kelas',  // Nama kelas (contoh: Kelas 1, Kelas 2, dst)
                    'jenjang'      // Jenjang pendidikan (SD, SMP, SMA, SMK)
                )

                /**
                 * Pengurutan pertama berdasarkan jenjang pendidikan
                 * Urutan manual menggunakan FIELD agar tidak alfabetis
                 * Urutan yang diinginkan: SD → SMP → SMA → SMK
                 */
                ->orderByRaw(
                    "FIELD(jenjang, 'SD', 'SMP', 'SMA', 'SMK')"
                )

                /**
                 * Pengurutan kedua berdasarkan angka pada nama_kelas
                 * Contoh:
                 * - Kelas 1
                 * - Kelas 2
                 * - Kelas 10
                 *
                 * REGEXP_REPLACE:
                 * Menghapus semua karakter selain angka
                 *
                 * CAST AS UNSIGNED:
                 * Mengubah hasil string angka menjadi bilangan numerik
                 * agar pengurutan menjadi benar (1, 2, 10)
                 */
                ->orderByRaw("
                    CAST(
                        REGEXP_REPLACE(nama_kelas, '[^0-9]', '')
                    AS UNSIGNED)
                ")

                // Eksekusi query dan ambil semua data
                ->get()
        ]);
    }
}