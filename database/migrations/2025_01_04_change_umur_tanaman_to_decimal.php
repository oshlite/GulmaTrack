<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            // Change umur_tanaman from integer to decimal
            $table->decimal('umur_tanaman', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            // Revert back to integer if needed
            $table->integer('umur_tanaman')->nullable()->change();
        });
    }
};
