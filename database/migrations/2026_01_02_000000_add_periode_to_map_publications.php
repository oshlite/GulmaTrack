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
            // Add tahun, bulan, minggu columns for per-periode publication tracking
            if (!Schema::hasColumn('map_publications', 'tahun')) {
                $table->integer('tahun')->nullable()->after('import_log_id');
                $table->integer('bulan')->nullable()->after('tahun');
                $table->integer('minggu')->nullable()->after('bulan');
                
                // Create unique constraint for per-periode publication
                // Hanya 1 published file per periode (tahun/bulan/minggu)
                $table->unique(['tahun', 'bulan', 'minggu'], 'unique_period_publication');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_publications', function (Blueprint $table) {
            if (Schema::hasColumn('map_publications', 'tahun')) {
                $table->dropUnique('unique_period_publication');
                $table->dropColumn(['tahun', 'bulan', 'minggu']);
            }
        });
    }
};
