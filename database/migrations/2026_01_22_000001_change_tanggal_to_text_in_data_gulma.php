<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change tanggal column from DATE to TEXT to preserve raw CSV string
        // (e.g., "2-Nov" instead of auto-converting to "2026-01-21")
        Schema::table('data_gulma', function (Blueprint $table) {
            $table->text('tanggal')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->change();
        });
    }
};
