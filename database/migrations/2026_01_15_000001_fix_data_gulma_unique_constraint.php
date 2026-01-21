<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 🔥 FIX: id_feature tidak boleh UNIQUE standalone
     * 
     * Masalah: Upload kedua GAGAL karena id_feature (SEKSI) sudah ada
     * Solusi: Unique constraint harus composite (wilayah_id, id_feature, import_log_id)
     *         atau (wilayah_id, id_feature, tanggal)
     * 
     * Dari log:
     * - Upload #3 (1 Nov): 108 records ✅ BERHASIL
     * - Upload #4 (1 Nov): 112 records ❌ GAGAL (id_feature sama)
     * - Upload #5-#10 (2 Nov): 112 records ❌ GAGAL (id_feature sama)
     */
    public function up(): void
    {
        if (Schema::hasTable('data_gulma')) {
            Schema::table('data_gulma', function (Blueprint $table) {
                // Drop the problematic unique constraint on id_feature alone
                // id_feature bisa duplicate, tapi unique dalam konteks (wilayah + import)
                try {
                    // MySQL
                    $table->dropUnique(['id_feature']);
                } catch (\Exception $e) {
                    try {
                        // PostgreSQL
                        DB::statement('ALTER TABLE data_gulma DROP CONSTRAINT IF EXISTS data_gulma_id_feature_unique');
                    } catch (\Exception $e2) {
                        // Constraint sudah tidak ada, skip
                    }
                }
            });
            
            // Add composite unique constraint instead
            // Memungkinkan id_feature yang sama untuk different wilayah/import_log
            Schema::table('data_gulma', function (Blueprint $table) {
                $table->unique(['wilayah_id', 'id_feature', 'import_log_id'], 'unique_wil_feature_import');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('data_gulma')) {
            Schema::table('data_gulma', function (Blueprint $table) {
                // Drop the composite constraint
                try {
                    $table->dropUnique('unique_wil_feature_import');
                } catch (\Exception $e) {
                    try {
                        DB::statement('ALTER TABLE data_gulma DROP CONSTRAINT IF EXISTS unique_wil_feature_import');
                    } catch (\Exception $e2) {
                        // Constraint tidak ada
                    }
                }
            });
            
            // Restore old constraint if needed
            Schema::table('data_gulma', function (Blueprint $table) {
                $table->unique(['id_feature']);
            });
        }
    }
};
