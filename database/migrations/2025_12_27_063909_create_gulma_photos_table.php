<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('gulma_photos', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['bersih', 'ringan', 'sedang', 'berat'])->index();
            $table->string('foto_path');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('gulma_photos');
    }
};