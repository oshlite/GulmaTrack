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
            // Tambah kolom dari format CSV baru
            $table->string('pg')->nullable();
            $table->string('fm')->nullable();
            $table->string('seksi')->nullable();
            $table->decimal('neto', 10, 2)->nullable();
            $table->decimal('hasil', 10, 2)->nullable();
            $table->integer('umur_tanaman')->nullable();
            $table->string('penanggungjawab')->nullable();
            $table->string('kode_aktf')->nullable();
            $table->string('activitas')->nullable();
            $table->string('kategori')->nullable();
            $table->decimal('tk_ha', 10, 2)->nullable();
            $table->integer('total_tk')->nullable();
        });
        
        // Ubah kolom existing jadi nullable dengan raw SQL untuk PostgreSQL
        \DB::statement('ALTER TABLE data_gulma ALTER COLUMN status_gulma DROP NOT NULL');
        \DB::statement('ALTER TABLE data_gulma ALTER COLUMN persentase DROP NOT NULL');
        \DB::statement('ALTER TABLE data_gulma ALTER COLUMN tanggal DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            $table->dropColumn([
                'pg', 'fm', 'seksi', 'neto', 'hasil', 
                'umur_tanaman', 'penanggungjawab', 'kode_aktf',
                'activitas', 'kategori', 'tk_ha', 'total_tk'
            ]);
        });
    }
};
