<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cegah duplikat dengan firstOrCreate
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'erlambang@gmail.com'],
            [
                'name' => 'Admin Erlambang',
                'password' => Hash::make('erlambang123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'furqon@gmail.com'],
            [
                'name' => 'Admin Furqon',
                'password' => Hash::make('furqon123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}