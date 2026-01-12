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
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke karyawan
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            
            // Jenis Cuti dengan Enum
            $table->enum('jenis_cuti', [
                'Cuti Tahunan',
                'Cuti Melahirkan',
                'Cuti Menikah',
                'Cuti Besar',
                'Cuti Tanpa Gaji'
            ]);

            // Tanggal
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_masuk_kerja');
            
            // Keterangan dan Dokumen
            $table->text('keterangan');
            $table->string('dokumen')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};