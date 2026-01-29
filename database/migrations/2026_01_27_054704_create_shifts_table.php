<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_shift');        // Pagi, Siang, Malam
            $table->time('jam_masuk');           // 08:00
            $table->time('jam_pulang');          // 17:00
            $table->unsignedInteger('toleransi_menit')
                  ->default(0);                  // toleransi keterlambatan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};