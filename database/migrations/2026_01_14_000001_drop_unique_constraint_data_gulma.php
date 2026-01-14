<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop unique constraint on (wilayah_id, id_feature)
        // This allows same location to be imported in different periods/import_logs
        Schema::table('data_gulma', function (Blueprint $table) {
            // Drop existing unique index if it exists
            try {
                $table->dropUnique('data_gulma_wilayah_id_feature_unique');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
        });
    }

    public function down(): void
    {
        // Restore unique constraint
        Schema::table('data_gulma', function (Blueprint $table) {
            $table->unique(['wilayah_id', 'id_feature']);
        });
    }
};
