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
            // Drop foreign key constraint for wilayah_id
            $table->dropForeign(['wilayah_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            // Re-add foreign key constraint
            $table->foreign('wilayah_id')->references('id')->on('wilayah');
        });
    }
};
