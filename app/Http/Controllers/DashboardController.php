<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * =========================
     * HALAMAN DASHBOARD ADMIN
     * =========================
     */
    public function index()
    {
        /**
         * Jumlah pendaftar dalam 7 hari terakhir
         */
        $newApplicants = DB::table('pendaftars')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        /**
         * Jumlah siswa aktif
         * Diambil dari data pembayaran yang sudah diterima
         */
        $activeStudents = DB::table('payments')
            ->where('status', 'diterima')
            ->count();

        /**
         * Jumlah pembayaran yang masih menunggu
         */
        $pendingPayments = DB::table('payments')
            ->where('status', 'menunggu')
            ->count();

        /**
         * Data pendaftar terbaru (3 data terakhir)
         */
        $recentApplicants = DB::table('pendaftars')
            ->latest()
            ->limit(3)
            ->get();

        /**
         * =========================
         * DATA UNTUK CHART
         * =========================
         * Menampilkan jumlah pendaftar per hari
         */
        $chart = DB::table('pendaftars')
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        /**
         * Label chart (tanggal)
         * Format: 01 Jan, 02 Jan, dst
         */
        $chartLabels = $chart->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });

        /**
         * Data chart (jumlah pendaftar)
         */
        $chartData = $chart->pluck('total');

        // Kirim seluruh data ke view dashboard
        return view('admin.dashboard', [
            'title'           => 'Dashboard',
            'newApplicants'   => $newApplicants,
            'activeStudents'  => $activeStudents,
            'pendingPayments' => $pendingPayments,
            'recentApplicants'=> $recentApplicants,
            'chartLabels'     => $chartLabels,
            'chartData'       => $chartData
        ]);
    }

    /**
     * =========================
     * EXPORT DATA PENDAFTAR KE CSV
     * =========================
     */
    public function exportCSV(Request $request)
    {
        /**
         * Menentukan tipe data export
         * Default: all
         */
        $type = $request->type ?? 'all';

        // Query dasar data pendaftar
        $query = DB::table('pendaftars');

        /**
         * =========================
         * FILTER DATA EXPORT
         * =========================
         */
        if ($type == 'active') {
            // Export pendaftar dengan pembayaran diterima
            $query->join('payments', 'pendaftars.id', '=', 'payments.pendaftar_id')
                  ->where('payments.status', 'diterima');
        }

        if ($type == 'pending') {
            // Export pendaftar dengan pembayaran pending
            $query->join('payments', 'pendaftars.id', '=', 'payments.pendaftar_id')
                  ->where('payments.status', 'pending');
        }

        /**
         * Mengambil data yang akan diexport
         */
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

        /**
         * Nama file CSV
         */
        $filename = "data_{$type}.csv";

        /**
         * Header response CSV
         */
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        /**
         * Callback untuk menulis isi file CSV
         */
        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Header kolom CSV
            fputcsv($file, [
                'Nama',
                'Kelas',
                'Sekolah',
                'No HP',
                'Status',
                'Tanggal'
            ]);

            // Isi data CSV
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama,
                    $row->kelas,
                    $row->sekolah,
                    $row->hp_orangtua,
                    $row->status,
                    Carbon::parse($row->created_at)->format('d-m-Y')
                ]);
            }

            fclose($file);
        };

        // Mengirim file CSV sebagai streaming response
        return response()->stream($callback, 200, $headers);
    }
}