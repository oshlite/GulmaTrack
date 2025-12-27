<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_gulma', function (Blueprint $table) {
            $table->id();
            
            // Kolom existing
            $table->unsignedBigInteger('wilayah_id');
            $table->string('id_feature'); // SEKSI dari CSV
            $table->enum('status_gulma', ['Bersih', 'Ringan', 'Sedang', 'Berat'])->nullable();
            $table->integer('persentase')->nullable();
            $table->date('tanggal');
            $table->unsignedBigInteger('import_log_id')->nullable();
            
            // Kolom CSV baru (sesuai AdminController)
            $table->string('pg')->nullable();
            $table->string('fm')->nullable();
            $table->string('seksi')->nullable(); // Duplicate dari id_feature
            $table->decimal('neto', 10, 2)->nullable();
            $table->decimal('hasil', 10, 2)->nullable();
            $table->integer('umur_tanaman')->nullable();
            $table->string('penanggungjawab')->nullable();
            $table->string('kode_aktf')->nullable();
            $table->string('activitas')->nullable();
            $table->string('kategori')->nullable(); // PENTING untuk warna peta
            $table->decimal('tk_ha', 10, 2)->nullable();
            $table->integer('total_tk')->nullable();
            
            $table->timestamps();

            $table->foreign('wilayah_id')->references('id')->on('wilayah')->onDelete('cascade');
            $table->foreign('import_log_id')->references('id')->on('import_logs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_gulma');
    }
};