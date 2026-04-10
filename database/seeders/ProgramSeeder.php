<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        DB::table('program')->insert([
            ['nama_program' => 'wajib', 'kode' => 'wajib'],
            ['nama_program' => 'wajib_ekskul', 'kode' => 'wajib_ekskul'],
            ['nama_program' => 'reguler', 'kode' => 'reguler'],
        ]);
    }
}
