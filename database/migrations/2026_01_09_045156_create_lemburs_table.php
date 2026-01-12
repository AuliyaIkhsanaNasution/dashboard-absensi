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
    Schema::create('lemburs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
        $table->date('tgl_lembur');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->enum('kategori', ['Hari Kerja', 'Hari Libur']);
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
        Schema::dropIfExists('lemburs');
    }
};
