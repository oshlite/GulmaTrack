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
        Schema::create('gulma_photos', function (Blueprint $table) {
            $table->id();
            $table->string('wilayah_id', 10); // Wilayah 16-23
            $table->string('lokasi'); // Kode lokasi/seksi (A1, B2, C3, dst)
            $table->string('foto_path'); // Path file foto
            $table->enum('status_gulma', ['bersih', 'ringan', 'sedang', 'berat']);
            $table->date('tanggal_foto'); // Tanggal pengambilan foto
            $table->text('deskripsi')->nullable(); // Catatan/deskripsi kondisi
            $table->unsignedBigInteger('uploaded_by'); // User ID yang upload
            $table->string('file_size')->nullable(); // Ukuran file
            $table->string('mime_type')->nullable(); // image/jpeg, image/png
            $table->timestamps();
            $table->softDeletes(); // Soft delete untuk recovery

            // Foreign key
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes untuk optimasi query
            $table->index('wilayah_id');
            $table->index('lokasi');
            $table->index('status_gulma');
            $table->index('tanggal_foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gulma_photos');
    }
};