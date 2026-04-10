<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            // jika belum ada
            if (!Schema::hasColumn('mata_pelajaran', 'jenjang')) {
                $table->string('jenjang')->nullable()->after('nama_mapel');
            }
            if (!Schema::hasColumn('mata_pelajaran', 'tipe')) {
                $table->string('tipe')->nullable()->after('jenjang'); // 'wajib','reguler','ekskul'
            }
            // // optional: kelas_id jika kamu ingin mengikat ke kelas tertentu
            // if (!Schema::hasColumn('mata_pelajaran', 'kelas_id')) {
            //     $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete()->after('tipe');
            // }
        });
    }

    public function down()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            if (Schema::hasColumn('mata_pelajaran', 'kelas_id')) {
                $table->dropForeign(['kelas_id']);
                $table->dropColumn('kelas_id');
            }
            if (Schema::hasColumn('mata_pelajaran', 'tipe')) $table->dropColumn('tipe');
            if (Schema::hasColumn('mata_pelajaran', 'jenjang')) $table->dropColumn('jenjang');
        });
    }
};
