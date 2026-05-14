<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update superadmin user
        $user = User::updateOrCreate(
            ['email' => 'superadmin@findmyroommate.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('SuperAdmin2026!'),
                'is_admin' => true,
                'is_superadmin' => true,
                'is_active' => true,
                'is_approved' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ Superadmin created successfully!');
        $this->command->info('');
        $this->command->info('Email: superadmin@findmyroommate.com');
        $this->command->info('Password: SuperAdmin2026!');
        $this->command->info('');
        $this->command->info('Login at: /superadmin/login');
    }
}
