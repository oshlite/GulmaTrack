<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada sebelum ditambahkan
            if (!Schema::hasColumn('data_gulma', 'pg')) {
                $table->string('pg')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'fm')) {
                $table->string('fm')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'seksi')) {
                $table->string('seksi')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'neto')) {
                $table->decimal('neto', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'hasil')) {
                $table->decimal('hasil', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'umur_tanaman')) {
                $table->integer('umur_tanaman')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'penanggungjawab')) {
                $table->string('penanggungjawab')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'kode_aktf')) {
                $table->string('kode_aktf')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'activitas')) {
                $table->string('activitas')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'kategori')) {
                $table->string('kategori')->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'tk_ha')) {
                $table->decimal('tk_ha', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('data_gulma', 'total_tk')) {
                $table->integer('total_tk')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_gulma', function (Blueprint $table) {
            // Safely drop columns if they exist
            $columns = [
                'pg', 'fm', 'seksi', 'neto', 'hasil', 
                'umur_tanaman', 'penanggungjawab', 'kode_aktf',
                'activitas', 'kategori', 'tk_ha', 'total_tk'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('data_gulma', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};