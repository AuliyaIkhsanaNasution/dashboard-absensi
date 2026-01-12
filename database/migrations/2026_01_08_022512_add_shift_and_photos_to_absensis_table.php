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
    Schema::table('absensis', function (Blueprint $table) {
        $table->string('shift')->nullable()->after('tanggal'); // Pagi, Sore, dsb
        $table->string('foto_masuk')->nullable()->after('jam_masuk');
        $table->string('foto_keluar')->nullable()->after('jam_pulang');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            //
        });
    }
};
