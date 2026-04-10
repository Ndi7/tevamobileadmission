<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Tampilkan daftar kelas
    public function index()
    {
        return view('kelas', [
            'title' => 'Kelola Kelas',
            'adminName' => session('adminName'),
            'kelas' => Kelas::orderBy('id', 'asc')->get() // urut sesuai data masuk
        ]);
    }


    // Simpan kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50'
        ]);

        Kelas::create([
    'nama_kelas' => $request->nama_kelas,
    'jenjang' => $request->jenjang,
]);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    // Update kelas
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50'
        ]);

        Kelas::where('id', $id)->update([
    'nama_kelas' => $request->nama_kelas,
    'jenjang' => $request->jenjang,
]);

        return redirect()->back()->with('success', 'Kelas berhasil diperbarui!');
    }

    // Hapus kelas
    public function destroy($id)
    {
        Kelas::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}
