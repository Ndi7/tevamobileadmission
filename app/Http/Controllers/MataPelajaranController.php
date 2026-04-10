<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
{
    $subjects = MataPelajaran::orderBy('nama_mapel', 'asc')->get();
    return view('subjects', [
        'title' => 'Kelola Mata Pelajaran',
        'adminName' => session('adminName'),
        'subjects' => $subjects
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'nama_mapel' => 'required',
    ]);

    MataPelajaran::create([
    'nama_mapel' => $request->nama_mapel
]);

    return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_mapel' => 'required',
    ]);

    MataPelajaran::where('id', $id)->update([
    'nama_mapel' => $request->nama_mapel
]);

    return back()->with('success', 'Mata pelajaran berhasil diperbarui.');
}

public function destroy($id)
{
    MataPelajaran::where('id', $id)->delete();
    return back()->with('success', 'Mata pelajaran berhasil dihapus.');
}

}
