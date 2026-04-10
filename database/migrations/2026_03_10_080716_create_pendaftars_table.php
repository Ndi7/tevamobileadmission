<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('pendaftars', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('hp_orangtua');
        $table->string('hp_siswa')->nullable();
        $table->string('agama');
        $table->string('tanggal_lahir');
        $table->string('sekolah');
        $table->string('kelas');
        $table->text('alamat');

        $table->json('mapel_wajib')->nullable();
        $table->json('mapel_reguler')->nullable();
        $table->json('mapel_ekskul')->nullable();

        $table->integer('total_harga');
        $table->string('foto')->nullable();

        $table->string('status')->default('Menunggu');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
