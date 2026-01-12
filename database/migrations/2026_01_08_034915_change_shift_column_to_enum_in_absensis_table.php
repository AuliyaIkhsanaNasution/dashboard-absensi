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
    Schema::table('absensis', function (Blueprint $table) {
        // Mengubah VARCHAR menjadi ENUM dengan opsi yang sesuai
        $table->enum('shift', ['Pagi', 'Siang', 'Malam'])->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('absensis', function (Blueprint $table) {
        // Mengembalikan ke VARCHAR jika rollback
        $table->string('shift', 255)->nullable()->change();
    });
}
};
