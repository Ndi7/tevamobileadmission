<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftar = Pendaftar::latest()->get();

        return response()->json($pendaftar);
    }

    public function store(Request $request)
    {
        // 🔥 AMBIL USER DARI SESSION / LOGIN
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized (user belum login)'
            ], 401);
        }

        // 🔥 VALIDASI (HAPUS user_id)
        $request->validate([
            'nama'           => 'required',
            'hp_orangtua'    => 'required',
            'hp_siswa'       => 'required',
            'agama'          => 'required',
            'tanggal_lahir'  => 'required',
            'sekolah'        => 'required',
            'kelas'          => 'required',
            'alamat'         => 'required',
            'total_harga'    => 'required',
        ]);

        // 🔥 AMBIL DATA
        $data = $request->except('foto');

        // 🔥 KUNCI FIX: PAKSA user_id DARI LOGIN
        $data['user_id'] = $user->id;

        // MAPEL
        if ($request->mapel_wajib) {
            $data['mapel_wajib'] = $request->mapel_wajib;
        }

        if ($request->mapel_reguler) {
            $data['mapel_reguler'] = $request->mapel_reguler;
        }

        if ($request->mapel_ekskul) {
            $data['mapel_ekskul'] = $request->mapel_ekskul;
        }

        // FOTO
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
            'data'    => $pendaftar
        ]);
    }

    public function byUser(Request $request)
    {
        // 🔥 PRIORITAS 1: kalau user login (WEB)
        if (auth()->check()) {
            $userId = auth()->id();
        } 
        // 🔥 PRIORITAS 2: kalau dari FLUTTER
        else {
            if (!$request->user_id) {
                return response()->json([
                    'message' => 'user_id wajib'
                ], 400);
            }

            $userId = $request->user_id;
        }

        $data = Pendaftar::with('payment')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function storeApi(Request $request)
{
    // 🔥 VALIDASI (pakai user_id dari Flutter)
    $request->validate([
        'user_id'        => 'required',
        'nama'           => 'required',
        'hp_orangtua'    => 'required',
        'hp_siswa'       => 'required',
        'agama'          => 'required',
        'tanggal_lahir'  => 'required',
        'sekolah'        => 'required',
        'kelas'          => 'required',
        'alamat'         => 'required',
        'total_harga'    => 'required',
    ]);

    $data = $request->except('foto');

    // 🔥 user_id dari Flutter
    $data['user_id'] = $request->user_id;

    // MAPEL
    if ($request->mapel_wajib) {
        $data['mapel_wajib'] = $request->mapel_wajib;
    }

    if ($request->mapel_reguler) {
        $data['mapel_reguler'] = $request->mapel_reguler;
    }

    if ($request->mapel_ekskul) {
        $data['mapel_ekskul'] = $request->mapel_ekskul;
    }

    // FOTO
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

    $pendaftar = Pendaftar::create($data);

    return response()->json([
        'success' => true,
        'message' => 'Pendaftaran berhasil',
        'data'    => $pendaftar
    ]);
}
}