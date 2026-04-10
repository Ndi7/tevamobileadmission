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
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->string('nama_ayah')->nullable();
        $table->string('nama_ibu')->nullable();
    });
}

public function down()
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->dropColumn(['nama_ayah','nama_ibu']);
    });
}
};
