<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔹 pendaftar 7 hari terakhir
        $newApplicants = DB::table('pendaftars')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // 🔹 siswa aktif (dari pembayaran diterima)
        $activeStudents = DB::table('payments')
            ->where('status', 'diterima')
            ->count();

        // 🔹 pembayaran pending
        $pendingPayments = DB::table('payments')
            ->where('status', 'menunggu')
            ->count();

        // 🔹 pendaftar terbaru
        $recentApplicants = DB::table('pendaftars')
            ->latest()
            ->limit(3)
            ->get();

        // 🔥 CHART: jumlah pendaftar per hari
        $chart = DB::table('pendaftars')
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // 🔥 label (tanggal)
        $chartLabels = $chart->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });

        // 🔥 data jumlah
        $chartData = $chart->pluck('total');

        return view('dashboard', [
            'title' => 'Dashboard',
            'newApplicants' => $newApplicants,
            'activeStudents' => $activeStudents,
            'pendingPayments' => $pendingPayments,
            'recentApplicants' => $recentApplicants,
            'chartLabels' => $chartLabels, // ✅ tambah
            'chartData' => $chartData      // ✅ tambah
        ]);
    }

    public function exportCSV(Request $request)
    {
        $type = $request->type ?? 'all';

        $query = DB::table('pendaftars');

        // 🔥 FILTER
        if ($type == 'active') {
            $query->join('payments', 'pendaftars.id', '=', 'payments.pendaftar_id')
                  ->where('payments.status', 'diterima');
        }

        if ($type == 'pending') {
            $query->join('payments', 'pendaftars.id', '=', 'payments.pendaftar_id')
                  ->where('payments.status', 'pending');
        }

        $data = $query->select(
                'pendaftars.nama',
                'pendaftars.kelas',
                'pendaftars.sekolah',
                'pendaftars.hp_orangtua',
                'pendaftars.status',
                'pendaftars.created_at'
            )
            ->distinct()
            ->get();

        $filename = "data_{$type}.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Nama', 'Kelas', 'Sekolah', 'No HP', 'Status', 'Tanggal']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama,
                    $row->kelas,
                    $row->sekolah,
                    $row->hp_orangtua,
                    $row->status,
                    Carbon::parse($row->created_at)->format('d-m-Y') // ✅ lebih rapi
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}