<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('mata_pelajaran', function (Blueprint $table) {
        if (Schema::hasColumn('mata_pelajaran', 'jenjang')) {
            $table->dropColumn('jenjang');
        }
        if (Schema::hasColumn('mata_pelajaran', 'tipe')) {
            $table->dropColumn('tipe');
        }
    });
}

public function down()
{
    Schema::table('mata_pelajaran', function (Blueprint $table) {
        $table->string('jenjang')->nullable();
        $table->string('tipe')->nullable();
    });
}

};
