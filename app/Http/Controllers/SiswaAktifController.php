<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SiswaAktifController extends Controller
{
    public function index()
    {
        $students = DB::table('payments')
            ->join('pendaftars', 'payments.pendaftar_id', '=', 'pendaftars.id')
            ->where('payments.status', 'diterima')
            ->select(
                'pendaftars.nama',
                'pendaftars.kelas',
                'pendaftars.mapel_wajib',
                'pendaftars.mapel_reguler',
                'pendaftars.mapel_ekskul'
            )
            ->get();

        return view('siswa-aktif', compact('students'));
    }
}