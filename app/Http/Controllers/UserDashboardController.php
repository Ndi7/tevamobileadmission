<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftar;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $pendaftar = Pendaftar::with('payment')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.dashboard', [
            'user'        => $user,
            'pendaftar'   => $pendaftar,
            'notifikasi'  => [],
            'jumlahNotif' => 0,
            'showProfile' => true,
            'title'       => 'Dashboard'
        ]);
    }
}