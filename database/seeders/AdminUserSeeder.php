<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@clubhouse.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Create a test student user
        User::firstOrCreate(
            ['email' => 'student@clubhouse.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password123'),
                'role' => 'student',
            ]
        );

        // Create a test club president user
        User::firstOrCreate(
            ['email' => 'president@clubhouse.com'],
            [
                'name' => 'Club President',
                'password' => Hash::make('password123'),
                'role' => 'club_president',
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@clubhouse.com / password123');
        $this->command->info('Student: student@clubhouse.com / password123');
        $this->command->info('President: president@clubhouse.com / password123');
    }
}
