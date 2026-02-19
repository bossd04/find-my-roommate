<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $logs = [
            [
                'log_name' => 'default',
                'description' => 'System initialized',
                'event' => 'system.start',
                'properties' => json_encode(['init' => true]),
                'causer_id' => null,
                'causer_type' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Console',
                'method' => 'CLI',
                'route' => '/admin/dashboard',
                'created_at' => Carbon::now()->subMinutes(30),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'log_name' => 'default',
                'description' => 'Admin user logged in',
                'event' => 'auth.login',
                'properties' => json_encode(['user_id' => 1, 'role' => 'admin']),
                'causer_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'method' => 'GET',
                'route' => '/admin/login',
                'created_at' => Carbon::now()->subMinutes(25),
                'updated_at' => Carbon::now()->subMinutes(25),
            ],
            [
                'log_name' => 'default',
                'description' => 'User management action: Viewed user list',
                'event' => 'user.view',
                'properties' => json_encode(['count' => 15, 'filter' => 'all']),
                'causer_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'method' => 'GET',
                'route' => '/admin/users',
                'created_at' => Carbon::now()->subMinutes(20),
                'updated_at' => Carbon::now()->subMinutes(20),
            ],
            [
                'log_name' => 'default',
                'description' => 'System configuration updated',
                'event' => 'config.update',
                'properties' => json_encode(['setting' => 'maintenance_mode', 'value' => false]),
                'causer_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'method' => 'PUT',
                'route' => '/admin/settings',
                'created_at' => Carbon::now()->subMinutes(15),
                'updated_at' => Carbon::now()->subMinutes(15),
            ],
            [
                'log_name' => 'default',
                'description' => 'Activity logs cleared by admin',
                'event' => 'logs.clear',
                'properties' => json_encode(['count' => 25, 'reason' => 'manual']),
                'causer_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'method' => 'POST',
                'route' => '/admin/activity-logs/clear',
                'created_at' => Carbon::now()->subMinutes(10),
                'updated_at' => Carbon::now()->subMinutes(10),
            ],
            [
                'log_name' => 'default',
                'description' => 'New user registration',
                'event' => 'user.register',
                'properties' => json_encode(['user_id' => 2, 'email' => 'user2@example.com']),
                'causer_id' => null,
                'causer_type' => null,
                'ip_address' => '203.0.113.45',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
                'method' => 'POST',
                'route' => '/register',
                'created_at' => Carbon::now()->subMinutes(5),
                'updated_at' => Carbon::now()->subMinutes(5),
            ],
        ];

        DB::table('activity_logs')->insert($logs);
    }
}
