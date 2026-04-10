<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $kelas = [
            "Kelas 1 SD",
            "Kelas 2 SD",
            "Kelas 3 SD",
            "Kelas 4 SD",
            "Kelas 5 SD",
            "Kelas 6 SD",
            "Kelas 1 SMP",
            "Kelas 2 SMP",
            "Kelas 3 SMP",
            "Kelas 1 SMA",
            "Kelas 2 SMA",
            "Kelas 3 SMA",
            "Kelas 1 SMK",
            "Kelas 2 SMK",
            "Kelas 3 SMK",
            "Umum"
        ];

        foreach ($kelas as $j) {
            DB::table('kelas')->insert([
                'nama_kelas' => $j
            ]);
        }
    }
}
