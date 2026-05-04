<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function index()
    {
        return view('user.info', [
            'showProfile' => false, // ❌ sembunyikan profil
            'title' => 'Informasi'  // tampil di header
        ]);
    }
}