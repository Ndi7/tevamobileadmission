<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    // Tampilkan semua data biaya
    public function index()
    {
        $biaya = Biaya::orderBy('jenjang')->orderBy('tipe')->get();

        return view('fees', [
            'title' => 'Kelola Biaya',
            'biaya' => $biaya,
        ]);
    }

    // Simpan biaya baru
    public function store(Request $request)
    {
        $request->validate([
            'jenjang' => 'required|string',
            'tipe' => 'required|string',
            'biaya' => 'required|numeric|min:0',
        ]);

        Biaya::create([
            'jenjang' => $request->jenjang,
            'tipe' => $request->tipe,
            'biaya' => $request->biaya,
        ]);

        return redirect()->back()->with('success', 'Biaya berhasil ditambahkan.');
    }

    // Update data biaya
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenjang' => 'required|string',
            'tipe' => 'required|string',
            'biaya' => 'required|numeric|min:0',
        ]);

        Biaya::where('id', $id)->update([
            'jenjang' => $request->jenjang,
            'tipe' => $request->tipe,
            'biaya' => $request->biaya,
        ]);

        return redirect()->back()->with('success', 'Biaya berhasil diperbarui.');
    }

    // Hapus data biaya
    public function destroy($id)
    {
        Biaya::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Biaya berhasil dihapus.');
    }
}
