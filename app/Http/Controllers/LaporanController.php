<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // 🔥 PENDAFTAR BULANAN
        $pendaftar = DB::table('pendaftars')
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = $pendaftar->map(function ($item) {
            return Carbon::create()->month($item->bulan)->format('M');
        });

        $bulanData = $pendaftar->pluck('total');

        // 🔥 KEUANGAN (dari pembayaran diterima)
        $keuangan = DB::table('payments')
            ->where('status', 'diterima')
            ->selectRaw('MONTH(created_at) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('laporan', [
            'bulanLabels' => $bulanLabels,
            'bulanData' => $bulanData,
            'keuangan' => $keuangan
        ]);
    }
}