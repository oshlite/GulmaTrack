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
        Schema::create('data_gulma', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wilayah_id');
            $table->string('id_feature');
            $table->enum('status_gulma', ['Bersih', 'Ringan', 'Sedang', 'Berat']);
            $table->integer('persentase');
            $table->date('tanggal');
            $table->unsignedBigInteger('import_log_id')->nullable();
            $table->timestamps();

            $table->foreign('wilayah_id')->references('id')->on('wilayah');
            $table->foreign('import_log_id')->references('id')->on('import_logs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_gulma');
    }
};
