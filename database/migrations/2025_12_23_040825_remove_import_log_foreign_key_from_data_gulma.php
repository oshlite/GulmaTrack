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
        Schema::table('data_gulma', function (Blueprint $table) {
            $table->dropForeign(['import_log_id']);
            $table->dropColumn('import_log_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            $table->unsignedBigInteger('import_log_id')->nullable();
            $table->foreign('import_log_id')->references('id')->on('import_logs');
        });
    }
};
