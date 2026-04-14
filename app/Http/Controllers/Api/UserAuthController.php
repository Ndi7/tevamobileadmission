<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // auto hash dari model
            'photo' => 'default.png',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User berhasil dibuat',
            'data' => $user
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => $user
        ]);
    }

    // UPDATE PROFILE (TANPA TOKEN)
    public function updateProfile(Request $request)
{
    $request->validate([
        'id' => 'required|exists:users,id',
        'name' => 'nullable|string|max:100',
        'email' => 'nullable|email|unique:users,email,' . $request->id,
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ✅ TAMBAHAN
    ]);

    $user = User::find($request->id);

    // 🔥 HANDLE UPLOAD FOTO
    if ($request->hasFile('photo')) {

        $file = $request->file('photo');

        $filename = time() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads'), $filename);

        $user->photo = $filename; // simpan ke DB
    }

    $user->update([
        'name' => $request->name ?? $user->name,
        'email' => $request->email ?? $user->email,
        'phone' => $request->phone ?? $user->phone,
        'address' => $request->address ?? $user->address,
    ]);

    $user->save(); // penting biar photo ke-save

    return response()->json([
        'status' => true,
        'message' => 'Profil berhasil diperbarui',
        'data' => $user
    ]);
}
public function getUser($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => $user
    ]);
}
}