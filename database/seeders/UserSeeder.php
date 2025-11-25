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
        User::create([
            'name' => 'Owner TokoKita',
            'email' => 'owner@tokokita.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'email_verified_at' => now(),
        ]);

        // Admin
        User::create([
            'name' => 'Admin TokoKita',
            'email' => 'admin@tokokita.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567891',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'email_verified_at' => now(),
        ]);

        // Kasir 1
        User::create([
            'name' => 'Kasir TokoKita',
            'email' => 'kasir@tokokita.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
            'phone' => '081234567892',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'email_verified_at' => now(),
        ]);

        // Kasir tambahan
        User::factory(2)->create([
            'role' => 'kasir'
        ]);

        // Customer (optional)
        // User::factory(5)->create([
        //     'role' => 'customer'
        // ]);
    }
}
