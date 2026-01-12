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
        Schema::table('drones', function (Blueprint $table) {
            $table->decimal('persen_gulma', 5, 2)->nullable()->comment('Persentase gulma');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drones', function (Blueprint $table) {
            $table->dropColumn('persen_gulma');
        });
    }
};
