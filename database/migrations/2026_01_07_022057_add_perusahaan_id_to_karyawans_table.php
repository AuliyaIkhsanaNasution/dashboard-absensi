<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('karyawans', function (Blueprint $table) {
            // Tambahkan kolom perusahaan_id setelah kolom id
            $table->unsignedBigInteger('perusahaan_id')->nullable()->after('id');
            
            // Buat foreign key constraint
            $table->foreign('perusahaan_id')
                  ->references('id')
                  ->on('perusahaans')
                  ->onDelete('set null'); // Jika perusahaan dihapus, set NULL
        });
    }

    public function down()
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropColumn('perusahaan_id');
        });
    }
};