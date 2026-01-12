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
    Schema::create('cutis', function (Blueprint $table) {
        $table->id();
        // Pastikan tipe data 'karyawan_id' sama dengan Primary Key di tabel karyawans Anda (biasanya bigIncrements)
        $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
        
        $table->enum('jenis_cuti', [
            'Cuti Tahunan', 
            'Cuti Melahirkan', 
            'Cuti Menikah', 
            'Cuti Besar', 
            'Cuti Tanpa Gaji'
        ]);
        
        $table->date('tgl_mulai');
        $table->date('tgl_selesai');
        $table->date('tgl_masuk');
        $table->text('keterangan')->nullable();
        $table->string('dokumen')->nullable(); // Untuk menyimpan nama/path file icon view
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
