<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_mapel_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->enum('tipe', ['wajib', 'reguler', 'ekskul']);
            $table->timestamps();

            $table->unique(['kelas_id', 'mata_pelajaran_id', 'tipe'], 'unik_paket_mapel_per_kelas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_mapel_kelas');
    }
};
