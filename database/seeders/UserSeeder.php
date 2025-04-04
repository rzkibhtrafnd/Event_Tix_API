<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // User 1
        User::create([
            'name' => 'Maliq Akbar',
            'email' => 'maliq@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // User 2
        User::create([
            'name' => 'Aisyah Ramadhani',
            'email' => 'aisyah@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // User 3
        User::create([
            'name' => 'Fahri Hidayat',
            'email' => 'fahri@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // User 4
        User::create([
            'name' => 'Salsabila Dewi',
            'email' => 'salsa@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // User 5
        User::create([
            'name' => 'Rizky Pratama',
            'email' => 'rizky@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
