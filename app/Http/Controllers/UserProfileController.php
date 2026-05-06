<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * =========================
     * HALAMAN PROFIL
     * =========================
     */
    public function index()
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    /**
     * =========================
     * HALAMAN EDIT PROFIL
     * =========================
     */
    public function edit()
    {
        $user = Auth::user();

        return view('user.edit_profile', compact('user'));
    }

    /**
     * =========================
     * UPDATE PROFIL
     * =========================
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // VALIDASI
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /**
         * =========================
         * UPLOAD FOTO (JIKA ADA)
         * =========================
         */
        if ($request->hasFile('photo')) {

            // hapus foto lama kalau ada
            if ($user->photo && file_exists(public_path('uploads/' . $user->photo))) {
                unlink(public_path('uploads/' . $user->photo));
            }

            // simpan foto baru
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads'), $filename);

            $user->photo = $filename;
        }

        /**
         * =========================
         * UPDATE DATA
         * =========================
         */
        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->address = $request->address;

        $user->save();

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}