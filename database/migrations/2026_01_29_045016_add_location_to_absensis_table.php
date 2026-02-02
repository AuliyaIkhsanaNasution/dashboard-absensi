<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->after('status');
            $table->decimal('longitude', 11, 8)->after('latitude');
            $table->integer('jarak')->nullable()->after('longitude'); // meter
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'jarak']);
        });
    }
};