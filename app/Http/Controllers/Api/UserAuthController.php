<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    /**
     * =========================
     * REGISTER USER BARU
     * =========================
     */
    public function register(Request $request)
    {
        /**
         * Validasi input pendaftaran user
         */
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        /**
         * Membuat data user baru
         * Password akan di-hash otomatis oleh model User
         */
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // auto hash dari model
            'photo'    => 'default.png',
        ]);

        // Response berhasil
        return response()->json([
            'status'  => true,
            'message' => 'User berhasil dibuat',
            'data'    => $user
        ]);
    }

    /**
     * =========================
     * LOGIN USER
     * =========================
     */
    public function login(Request $request)
    {
        /**
         * Validasi data login
         */
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Mencari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        /**
         * Jika user tidak ditemukan atau password tidak sesuai
         */
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Login berhasil
        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => $user
        ]);
    }

    /**
     * =========================
     * UPDATE PROFIL USER
     * (Tanpa token / autentikasi)
     * =========================
     */
    public function updateProfile(Request $request)
    {
        /**
         * Validasi data update profil
         */
        $request->validate([
            'id'      => 'required|exists:users,id',
            'name'    => 'nullable|string|max:100',
            'email'   => 'nullable|email|unique:users,email,' . $request->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Mengambil data user berdasarkan ID
        $user = User::find($request->id);

        /**
         * Proses upload foto profil
         */
        if ($request->hasFile('photo')) {

            // Mengambil file foto
            $file = $request->file('photo');

            // Membuat nama file berdasarkan waktu upload
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Menentukan folder tujuan upload
            $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads';

            // Membuat folder jika belum tersedia
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Memindahkan file ke folder tujuan
            $file->move($destinationPath, $filename);

            // Menyimpan nama file ke database
            $user->photo = $filename;
        }

        /**
         * Update data profil user
         * Jika field tidak dikirim, maka data lama tetap digunakan
         */
        $user->update([
            'name'    => $request->name ?? $user->name,
            'email'   => $request->email ?? $user->email,
            'phone'   => $request->phone ?? $user->phone,
            'address' => $request->address ?? $user->address,
        ]);

        // Simpan perubahan ke database
        $user->save();

        // Response berhasil
        return response()->json([
            'status'  => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user
        ]);
    }

    /**
     * =========================
     * AMBIL DATA USER BERDASARKAN ID
     * =========================
     */
    public function getUser($id)
    {
        // Mencari user berdasarkan ID
        $user = User::find($id);

        // Jika user tidak ditemukan
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Response data user
        return response()->json([
            'status' => true,
            'data'   => $user
        ]);
    }
}