<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kelas_mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->enum('tipe', ['wajib','reguler','ekskul']);
            $table->timestamps();

            $table->unique(['kelas_id','mata_pelajaran_id','tipe'], 'kelas_mapel_tipe_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas_mata_pelajaran');
    }
};
