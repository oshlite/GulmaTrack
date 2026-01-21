<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop unique constraint on (wilayah_id, id_feature)
        // This allows same location to be imported in different periods/import_logs
        if (Schema::hasTable('data_gulma')) {
            // Use raw SQL for PostgreSQL to safely drop constraint if it exists
            try {
                DB::statement('ALTER TABLE data_gulma DROP CONSTRAINT IF EXISTS data_gulma_wilayah_id_feature_unique');
            } catch (\Exception $e) {
                // Constraint doesn't exist or other error, continue
            }
        }
    }

    public function down(): void
    {
        // Restore unique constraint
        Schema::table('data_gulma', function (Blueprint $table) {
            $table->unique(['wilayah_id', 'id_feature']);
        });
    }
};