<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@oweru.com',
            'password' => Hash::make('admin123'),
            'phone_number' => '+255123456789',
            'nida_id' => 'ADMIN001',
            'role' => 'admin',
        ]);

        // Create a regular user for testing
        User::create([
            'username' => 'testuser',
            'email' => 'user@oweru.com',
            'password' => Hash::make('password'),
            'phone_number' => '+255987654321',
            'nida_id' => 'USER001',
            'role' => 'user',
        ]);

        // Create cassian user for testimonials
        User::create([
            'username' => 'cassian',
            'email' => 'cassian@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '+255111111111',
            'nida_id' => 'CASSIAN001',
            'role' => 'user',
        ]);
    }
}
