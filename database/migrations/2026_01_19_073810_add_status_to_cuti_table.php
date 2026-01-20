<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_status_to_cuti_table.php
public function up()
{
    Schema::table('cutis', function (Blueprint $table) {
        $table->enum('status', ['pending', 'approved', 'rejected'])
              ->default('pending')
              ->after('keterangan');
    });
}

public function down()
{
    Schema::table('cutis', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
