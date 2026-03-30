<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserBackupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found to backup.');
            return;
        }

        // Create backup directory if it doesn't exist
        $backupDir = database_path('backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir);
        }

        // Generate backup filename with timestamp
        $backupFile = $backupDir . '/users_backup_' . now()->format('Y_m_d_His') . '.json';
        
        // Prepare backup data
        $backupData = [
            'backup_info' => [
                'generated_at' => now()->toISOString(),
                'total_users' => $users->count(),
                'backup_type' => 'full_user_backup'
            ],
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'age' => $user->age,
                    'bio' => $user->bio,
                    'university' => $user->university,
                    'course' => $user->course,
                    'year_level' => $user->year_level,
                    'department' => $user->department,
                    'budget_min' => $user->budget_min,
                    'budget_max' => $user->budget_max,
                    'hobbies' => $user->hobbies,
                    'lifestyle_tags' => $user->lifestyle_tags,
                    'role' => $user->role,
                    'is_admin' => $user->is_admin,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            })->toArray()
        ];

        // Save backup to JSON file
        File::put($backupFile, json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->command->info("User backup completed successfully!");
        $this->command->info("Total users backed up: " . $users->count());
        $this->command->info("Backup file saved to: " . $backupFile);

        // Display summary
        $this->command->info("\n=== USER SUMMARY ===");
        foreach ($users as $user) {
            $this->command->line("ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}");
        }
    }
}
