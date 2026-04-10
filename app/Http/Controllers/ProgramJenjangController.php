<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\KelasMataPelajaran;
use Illuminate\Support\Facades\DB;

class ProgramJenjangController extends Controller
{
    // 🧭 Halaman admin
    public function index()
    {
        $kelasList = Kelas::orderBy('id')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();

        $paket = KelasMataPelajaran::with('mapel')
            ->orderBy('kelas_id')
            ->orderBy('tipe')
            ->get();

        return view('programs', compact('kelasList', 'mapelList', 'paket'));
    }

    // 🟢 Simpan mapel + harga
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tipe' => 'required|in:wajib,reguler,ekskul',
            'harga' => 'required|numeric|min:0',
        ]);

        // cek apakah mapel sudah ada di kelas
        if (
            DB::table('kelas_mata_pelajaran')
                ->where('kelas_id', $request->kelas_id)
                ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                ->exists()
        ) {
            return back()->with('success', 'Mapel ini sudah ada di kelas tersebut.');
        }

        // simpan ke database
        DB::table('kelas_mata_pelajaran')->insert([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tipe' => $request->tipe,
            'harga' => $request->harga, // ⭐ ini yang menyimpan harga
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Paket mapel berhasil disimpan.');
    }

    // 🔴 Hapus mapel dari kelas
    public function destroy($id)
    {
        DB::table('kelas_mata_pelajaran')->where('id', $id)->delete();

        return back()->with('success', 'Paket mapel berhasil dihapus.');
    }

    // 📱 API untuk Flutter
    public function mapelByKelasApi($kelasId)
    {
        $data = KelasMataPelajaran::with('mapel')
            ->where('kelas_id', $kelasId)
            ->get()
            ->groupBy('tipe');

        return response()->json([
            'wajib' => $data->get('wajib', []),
            'reguler' => $data->get('reguler', []),
            'ekskul' => $data->get('ekskul', []),
        ]);
    }
}