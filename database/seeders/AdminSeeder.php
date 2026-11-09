<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::unguarded(function () {
            // Admin user
            User::updateOrCreate(
                ['email' => 'admin@furniture.com'],
                [
                    'name' => 'Santi Admin',
                    'password' => Hash::make('password'),
                    'phone' => '+1 (555) 019-2834',
                    'address' => 'Santi HQ, 100 Luxury Avenue, Suite 500, New York, NY',
                    'role' => UserRole::ADMIN->value,
                ]
            );

            // Sample customer 1
            User::updateOrCreate(
                ['email' => 'santi@gmail.com'],
                [
                    'name' => 'Santi',
                    'password' => Hash::make('password'),
                    'phone' => '+1 (555) 234-5678',
                    'address' => '742 Evergreen Terrace, Springfield, OR',
                    'role' => UserRole::USER->value,
                ]
            );

            // Sample customer 2
            User::updateOrCreate(
                ['email' => 'sarah.jenkins@example.com'],
                [
                    'name' => 'Sarah Jenkins',
                    'password' => Hash::make('password'),
                    'phone' => '+1 (555) 876-5432',
                    'address' => '124 Conch Street, Pacific Grove, CA',
                    'role' => UserRole::USER->value,
                ]
            );
        });
    }
}
