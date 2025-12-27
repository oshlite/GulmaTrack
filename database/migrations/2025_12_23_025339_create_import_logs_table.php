<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('wilayah_id'); // Comma-separated: "16,17,18"
            
            $table->integer('tahun')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('minggu')->nullable();
            
            $table->integer('jumlah_records')->default(0);
            $table->integer('jumlah_berhasil')->default(0);
            $table->integer('jumlah_gagal')->default(0);
            $table->enum('status', ['pending', 'success', 'partial', 'failed'])->default('pending');
            $table->text('error_log')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};