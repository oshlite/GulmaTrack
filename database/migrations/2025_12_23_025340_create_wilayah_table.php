<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id();
            $table->integer('wilayah_id')->unique(); // 16-23
            $table->string('nama_wilayah'); // "Wilayah 16"
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
        
        // Seed data wilayah
        for ($i = 16; $i <= 23; $i++) {
            DB::table('wilayah')->insert([
                'wilayah_id' => $i,
                'nama_wilayah' => "Wilayah $i",
                'deskripsi' => "Area produksi wilayah $i",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};