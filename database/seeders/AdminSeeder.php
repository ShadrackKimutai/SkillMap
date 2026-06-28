<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@skillmap.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'verification_status' => 'verified',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Test Tasker $i",
                'email' => "tasker$i@skillmap.test",
                'password' => Hash::make('password'),
                'role' => 'tasker',
                'email_verified_at' => now(),
                'phone' => '555' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'phone_verified_at' => now(),
                'verification_status' => 'verified',
                'latitude' => 40.7128 + (rand(-100, 100) / 1000),
                'longitude' => -74.0060 + (rand(-100, 100) / 1000),
            ]);

            \App\Models\TaskerProfile::create([
                'user_id' => $user->id,
                'trade_id' => rand(1, 8),
                'hourly_rate' => rand(25, 150),
                'fixed_rate' => rand(200, 1000),
                'price_negotiable' => rand(0, 1),
                'bio' => "Professional tasker with $i years of experience",
                'average_rating' => rand(3, 5),
            ]);

            \App\Models\TaskerAvailability::create([
                'user_id' => $user->id,
                'status' => 'available',
            ]);

            $user->languages()->sync([rand(1, 3), rand(4, 8)]);

            $specs = array_unique([rand(1, 12), rand(1, 12)]);
            $user->specializations()->sync($specs);
        }

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Test User $i",
                'email' => "user$i@skillmap.test",
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'latitude' => 40.7128 + (rand(-100, 100) / 1000),
                'longitude' => -74.0060 + (rand(-100, 100) / 1000),
            ]);
        }
    }
}
