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
    Schema::create('izins', function (Blueprint $table) {
        $table->id();
        // 1. Relasi ke karyawan
        $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
        
        // 2. Jenis Izin dengan Enum sesuai permintaan
        $table->enum('jenis_izin', [
            'Izin sakit tanpa surat dokter', 
            'Izin sakit dengan surat dokter', 
            'Izin keperluan keluarga', 
            'Izin mengurus dokumen', 
            'Izin pulang cepat'
        ]);

        //  Tanggal dan Keterangan
        $table->date('tanggal_izin');
        $table->text('keterangan');

        // Untuk menyimpan nama file dokumen pendukung (poin 5)
        $table->string('dokumen')->nullable(); 
        
        $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izins');
    }
};
