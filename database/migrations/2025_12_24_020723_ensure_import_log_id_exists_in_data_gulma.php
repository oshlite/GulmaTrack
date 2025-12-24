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
        // Check if import_log_id column exists, if not add it
        if (!Schema::hasColumn('data_gulma', 'import_log_id')) {
            Schema::table('data_gulma', function (Blueprint $table) {
                $table->unsignedBigInteger('import_log_id')->nullable()->after('tanggal');
                $table->foreign('import_log_id')->references('id')->on('import_logs')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('data_gulma', 'import_log_id')) {
            Schema::table('data_gulma', function (Blueprint $table) {
                $table->dropForeign(['import_log_id']);
                $table->dropColumn('import_log_id');
            });
        }
    }
};
