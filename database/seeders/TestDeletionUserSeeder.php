<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDeletionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing test user if exists
        User::where('email', 'testdeletion@example.com')->forceDelete();

        // Create test user
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'testdeletion@example.com',
            'password' => Hash::make('password123'),
            'phone' => '09123456789',
            'gender' => 'male',
            'date_of_birth' => '1995-01-01',
            'is_active' => true,
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info("Test user created successfully!");
        $this->command->info("Email: testdeletion@example.com");
        $this->command->info("Password: password123");
        $this->command->info("User ID: {$user->id}");
    }
}
