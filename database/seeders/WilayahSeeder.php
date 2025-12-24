<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wilayahData = [
            ['id' => 16, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('wilayah')->insert($wilayahData);
    }
}
