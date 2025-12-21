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

        // Optional: Buat admin kedua (uncomment jika ingin)
        // User::firstOrCreate(
        //     ['email' => 'admin2@gmail.com'],
        //     [
        //         'name' => 'Admin Kedua',
        //         'password' => Hash::make('password123'),
        //         'role' => 'admin',
        //         'is_active' => true,
        //     ]
        // );

        // Optional: Buat guest user (uncomment jika ingin)
        // User::firstOrCreate(
        //     ['email' => 'guest@gmail.com'],
        //     [
        //         'name' => 'Guest User',
        //         'password' => Hash::make('password123'),
        //         'role' => 'guest',
        //         'is_active' => true,
        //     ]
        // );
    }
}