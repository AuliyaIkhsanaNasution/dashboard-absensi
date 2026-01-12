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
    Schema::table('izins', function (Blueprint $table) {
        // Menghapus kolom status
        $table->dropColumn('status');
    });
}

public function down(): void
{
    Schema::table('izins', function (Blueprint $table) {
        // Untuk rollback: menambahkan kembali kolom status
        $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
    });
}
};
