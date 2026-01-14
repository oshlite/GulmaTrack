<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old table if it exists
        Schema::dropIfExists('data_gulma');

        // Create new table dengan struktur yang benar sesuai format CSV
        // PG,FM,WIL,SEKSI,NETO,HASIL,uMUR,TNM_STS,ACTIVITAS,KATEGORI,TANGGAL,TK/HA,TOTAL_TK
        Schema::create('data_gulma', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wilayah_id')->nullable();
            $table->string('id_feature')->nullable()->unique();
            $table->string('status_gulma')->nullable();
            $table->integer('persentase')->nullable();

            // Kolom CSV sesuai format
            $table->string('pg')->nullable(); // PG
            $table->string('fm')->nullable(); // FM
            $table->string('seksi')->nullable(); // SEKSI
            $table->decimal('neto', 10, 2)->nullable(); // NETO
            $table->decimal('hasil', 10, 2)->nullable(); // HASIL
            $table->decimal('umur', 10, 2)->nullable(); // uMUR (umur tanaman)
            $table->string('tnm_sts')->nullable(); // TNM_STS (tanaman status)
            $table->string('activitas')->nullable(); // ACTIVITAS
            $table->string('kategori')->nullable(); // KATEGORI
            $table->date('tanggal')->nullable(); // TANGGAL
            $table->decimal('tk_ha', 10, 2)->nullable(); // TK/HA
            $table->decimal('total_tk', 10, 2)->nullable(); // TOTAL_TK

            // Relationship columns
            $table->unsignedBigInteger('import_log_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_gulma');
    }
};
