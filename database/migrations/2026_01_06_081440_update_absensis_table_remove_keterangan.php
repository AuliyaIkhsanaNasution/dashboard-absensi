<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan perubahan.
     */
    public function up(): void
{
    // 1. Ubah struktur kolom status terlebih dahulu agar mengizinkan nilai baru
    // Kita tambahkan 'Tepat Waktu' ke dalam list tanpa menghapus yang lama dulu agar tidak error
    Schema::table('absensis', function (Blueprint $table) {
        $table->enum('status', ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa', 'Tepat Waktu'])
              ->default('Tepat Waktu')
              ->change();
    });

    // 2. Sekarang baru aman untuk update data lama (Hadir -> Tepat Waktu)
    DB::table('absensis')
        ->whereNotIn('status', ['Terlambat'])
        ->update(['status' => 'Tepat Waktu']);

    // 3. Terakhir, ciptakan struktur final (hanya Tepat Waktu & Terlambat) dan hapus keterangan
    Schema::table('absensis', function (Blueprint $table) {
        $table->enum('status', ['Tepat Waktu', 'Terlambat'])
              ->default('Tepat Waktu')
              ->change();

        $table->dropColumn('keterangan');
    });
}
    /**
     * Batalkan perubahan (Rollback).
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Kembalikan kolom keterangan
            $table->string('keterangan')->nullable();
            
            // Kembalikan enum ke semula
            $table->enum('status', ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa'])
                  ->default('Hadir')
                  ->change();
        });
    }
};