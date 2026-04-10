<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginSuccessMail;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        if (Session::has('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('login', [
            'title' => 'Login Admin'
        ]);
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {

            // Simpan data admin ke session
            Session::put('admin', $admin);
            Session::put('adminName', $admin->name);

            // ===============================
            // KIRIM EMAIL LOGIN BERHASIL
            // ===============================
            Mail::to($admin->email)->send(
                new LoginSuccessMail($admin, $request->ip())
            );

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Logout
    public function logout()
    {
        Session::forget('admin');
        Session::forget('adminName');

        return redirect('/login');
    }

    // Halaman pengaturan akun
    public function showSettings()
    {
        $admin = Session::get('admin');

        return view('pengaturan', [
            'title' => 'Pengaturan Akun',
            'admin' => $admin,
            'adminName' => $admin->name ?? 'Admin',
        ]);
    }

    // Update pengaturan akun
    public function updateSettings(Request $request)
    {
        $sessionAdmin = Session::get('admin');
        $admin = Admin::findOrFail($sessionAdmin->id);

        $request->validate([
            'name' => 'required|string|max:100',
            'password' => 'nullable|min:6',
        ]);

        $admin->name = $request->name;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        // Update session
        Session::put('admin', $admin);
        Session::put('adminName', $admin->name);

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}