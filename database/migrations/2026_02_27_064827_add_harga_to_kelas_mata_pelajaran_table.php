<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaToKelasMataPelajaranTable extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            $table->integer('harga')->default(0)->after('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
}