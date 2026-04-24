<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;

class PendaftaranController extends Controller
{

    // API AMBIL DATA PENDAFTAR
    public function index()
{
    $pendaftar = Pendaftar::latest()->get();

    return response()->json($pendaftar);
}


    public function store(Request $request)
    {

        // VALIDASI DATA
        $request->validate([
            'user_id' => 'required',
            'nama' => 'required',
            'hp_orangtua' => 'required',
            'hp_siswa' => 'required',
            'agama' => 'required',
            'tanggal_lahir' => 'required',
            'sekolah' => 'required',
            'kelas' => 'required',
            'alamat' => 'required',
            'total_harga' => 'required',
        ]);

        $data = $request->except('foto');

        // PASTIKAN USER_ID MASUK
        $data['user_id'] = $request->user_id;

        // HANDLE MAPEL
        if ($request->mapel_wajib) {
            $data['mapel_wajib'] = $request->mapel_wajib;
        }

        if ($request->mapel_reguler) {
            $data['mapel_reguler'] = $request->mapel_reguler;
        }

        if ($request->mapel_ekskul) {
            $data['mapel_ekskul'] = $request->mapel_ekskul;
        }

        // UPLOAD FOTO
        if ($request->hasFile('foto')) {
        
            $file = $request->file('foto');
        
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
            $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
        
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
        
            $file->move($destinationPath, $filename);
        
            $data['foto'] = $filename;
        }
        // SIMPAN
        $pendaftar = Pendaftar::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil',
            'data' => $pendaftar
        ]);
    }

    public function byUser(Request $request)
{
    $data = Pendaftar::with('payment')
        ->where('user_id', $request->user_id)
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}
}