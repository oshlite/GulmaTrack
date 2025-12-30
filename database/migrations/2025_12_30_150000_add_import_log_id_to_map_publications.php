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
        Schema::table('map_publications', function (Blueprint $table) {
            // Add import_log_id if it doesn't exist
            if (!Schema::hasColumn('map_publications', 'import_log_id')) {
                $table->unsignedBigInteger('import_log_id')->nullable()->after('id');
                $table->foreign('import_log_id')->references('id')->on('import_logs')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_publications', function (Blueprint $table) {
            if (Schema::hasColumn('map_publications', 'import_log_id')) {
                $table->dropForeign(['import_log_id']);
                $table->dropColumn('import_log_id');
            }
        });
    }
};
