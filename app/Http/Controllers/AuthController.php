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
    /**
     * =========================
     * MENAMPILKAN HALAMAN LOGIN
     * =========================
     */
    public function showLogin()
    {
        /**
         * Jika admin sudah login (session masih ada),
         * maka langsung diarahkan ke dashboard admin
         */
        if (Session::has('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // Menampilkan halaman login admin
        return view('admin.login', [
            'title' => 'Login Admin'
        ]);
    }

    /**
     * =========================
     * PROSES LOGIN ADMIN
     * =========================
     */
    public function login(Request $request)
    {
        /**
         * Validasi data login
         */
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Mencari data admin berdasarkan email
        $admin = Admin::where('email', $request->email)->first();

        /**
         * Mengecek apakah admin ada dan password sesuai
         */
        if ($admin && Hash::check($request->password, $admin->password)) {

            /**
             * Menyimpan data admin ke dalam session
             * Digunakan untuk autentikasi halaman admin
             */
            Session::put('admin', $admin);
            Session::put('adminName', $admin->name);

            /**
             * ===============================
             * MENGIRIM EMAIL LOGIN BERHASIL
             * ===============================
             * Email dikirim ke admin sebagai notifikasi keamanan
             * Berisi informasi login dan alamat IP
             */
            Mail::to($admin->email)->send(
                new LoginSuccessMail($admin, $request->ip())
            );

            // Login berhasil, arahkan ke dashboard
            return redirect()->route('admin.dashboard');
        }

        // Jika login gagal, kembali ke halaman login
        return back()->with('error', 'Email atau password salah!');
    }

    /**
     * =========================
     * LOGOUT ADMIN
     * =========================
     */
    public function logout()
    {
        // Menghapus data admin dari session
        Session::forget('admin');
        Session::forget('adminName');

        // Kembali ke halaman login
        return redirect('/admin/login');
    }

    /**
     * =========================
     * HALAMAN PENGATURAN AKUN ADMIN
     * =========================
     */
    public function showSettings()
    {
        // Mengambil data admin dari session
        $admin = Session::get('admin');

        // Menampilkan halaman pengaturan akun
        return view('admin.pengaturan', [
            'title'     => 'Pengaturan Akun',
            'admin'     => $admin,
            'adminName' => $admin->name ?? 'Admin',
        ]);
    }

    /**
     * =========================
     * UPDATE PENGATURAN AKUN ADMIN
     * =========================
     */
    public function updateSettings(Request $request)
    {
        // Mengambil data admin dari session
        $sessionAdmin = Session::get('admin');

        // Mengambil data admin dari database
        $admin = Admin::findOrFail($sessionAdmin->id);

        /**
         * Validasi data yang akan diperbarui
         */
        $request->validate([
            'name'     => 'required|string|max:100',
            'password' => 'nullable|min:6',
        ]);

        // Update nama admin
        $admin->name = $request->name;

        /**
         * Update password hanya jika diisi
         * Password akan di-hash menggunakan Hash::make()
         */
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        // Simpan perubahan ke database
        $admin->save();

        /**
         * Update data admin di session
         * Agar perubahan langsung terbaca di sistem
         */
        Session::put('admin', $admin);
        Session::put('adminName', $admin->name);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
 * =========================
 * REGISTER USER (PUBLIC)
 * =========================
 */
public function register(Request $request)
{
    $request->validate([
        'username' => 'required',
        'email' => 'required|email|unique:users,email',
        'notelpon' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    \App\Models\User::create([
        'name' => $request->username,
        'email' => $request->email,
        'phone' => $request->notelpon,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('user.login')
        ->with('success', 'Register berhasil, silakan login');
}
}