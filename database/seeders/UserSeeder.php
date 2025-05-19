<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin Pariwisata',
            'email' => 'admin@munabarat.go.id',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
            'address' => 'Kabupaten Muna Barat, Sulawesi Tenggara',
            'profile_image' => null,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Wisatawan users (5 sample users)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Wisatawan {$i}",
                'email' => "wisatawan{$i}@example.com",
                'password' => Hash::make('password'),
                'phone_number' => "08" . rand(1000000000, 9999999999),
                'address' => "Alamat Wisatawan {$i}",
                'profile_image' => null,
                'role' => 'wisatawan',
                'email_verified_at' => now(),
            ]);
        }
    }
}
