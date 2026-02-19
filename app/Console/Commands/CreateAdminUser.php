<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@findmyroommate.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@findmyroommate.com');
        $this->info('Password: admin123');
    }
}
