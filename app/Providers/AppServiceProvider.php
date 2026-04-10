<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Pendaftar;
use App\Models\Payment;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    View::composer('*', function ($view) {

        // 🔴 Pendaftaran
        $jumlahMenunggu = Pendaftar::where('status', 'MENUNGGU')->count();

        // 🟡 Pembayaran
        $jumlahPembayaranPending = Payment::where('status', 'MENUNGGU')->count();

        $view->with([
            'jumlahMenunggu' => $jumlahMenunggu,
            'jumlahPembayaranPending' => $jumlahPembayaranPending
        ]);
    });
}
}