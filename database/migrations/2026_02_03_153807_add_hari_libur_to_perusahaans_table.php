<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            // Tambah kolom JSON setelah kolom alamat atau sesuai kebutuhan
            $table->json('hari_libur')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->dropColumn('hari_libur');
        });
    }
};
