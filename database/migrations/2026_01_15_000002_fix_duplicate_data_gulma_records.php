<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * FIX DUPLICATE RECORDS: Keep only latest import_log_id for each wilayah_id + id_feature combo
     * This cleanup removes duplicates created by the old updateOrCreate logic
     * that incorrectly included import_log_id in the unique key.
     */
    public function up(): void
    {
        // Find all wilayah_id + id_feature combinations with duplicates
        $duplicates = DB::table('data_gulma')
            ->select('wilayah_id', 'id_feature')
            ->groupBy('wilayah_id', 'id_feature')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        echo "\n🔍 Found " . $duplicates->count() . " feature combinations with duplicates\n";

        foreach ($duplicates as $dup) {
            // For this combination, find the ID of record with LATEST import_log_id
            $latestRecord = DB::table('data_gulma')
                ->where('wilayah_id', $dup->wilayah_id)
                ->where('id_feature', $dup->id_feature)
                ->orderBy('import_log_id', 'desc')
                ->first();

            if ($latestRecord) {
                // Delete all OTHER records with this wilayah_id + id_feature combination
                DB::table('data_gulma')
                    ->where('wilayah_id', $dup->wilayah_id)
                    ->where('id_feature', $dup->id_feature)
                    ->where('id', '<>', $latestRecord->id)  // Keep the latest
                    ->delete();

                echo "✅ Feature {$dup->id_feature} at Wilayah {$dup->wilayah_id}: Kept latest (import_log_id={$latestRecord->import_log_id})\n";
            }
        }

        echo "\n✅ Cleanup complete!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be safely reversed as we've deleted data
        echo "⚠️  This migration cannot be reversed - data cleanup was performed\n";
    }
};
