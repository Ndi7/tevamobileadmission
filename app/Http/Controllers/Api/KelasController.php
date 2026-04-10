<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Kelas::select('id', 'nama_kelas', 'jenjang')
                ->orderByRaw("FIELD(jenjang, 'SD', 'SMP', 'SMA', 'SMK')")
                ->orderByRaw("
                    CAST(
                        REGEXP_REPLACE(nama_kelas, '[^0-9]', '')
                    AS UNSIGNED)
                ")
                ->get()
        ]);
    }
}