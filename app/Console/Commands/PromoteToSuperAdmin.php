<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteToSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:promote {email? : The email of the admin to promote}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote an admin user to superadmin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        if (!$email) {
            // Show list of admin users
            $admins = User::where('is_admin', true)->select('id', 'first_name', 'last_name', 'email', 'is_superadmin')->get();

            if ($admins->isEmpty()) {
                $this->error('No admin users found.');
                return 1;
            }

            $this->info('Available Admin Users:');
            $headers = ['ID', 'Name', 'Email', 'Super Admin'];
            $rows = $admins->map(function (User $admin) {
                return [
                    $admin->id,
                    $admin->fullName(),
                    $admin->email,
                    $admin->is_superadmin ? 'Yes' : 'No'
                ];
            })->toArray();

            $this->table($headers, $rows);
            $email = $this->ask('Enter the email of the admin to promote to superadmin');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        if (!$user->is_admin) {
            $this->error("User {$email} is not an admin. Please make them an admin first.");
            return 1;
        }

        if ($user->is_superadmin) {
            $this->warn("User {$email} is already a superadmin.");
            return 0;
        }

        $user->update(['is_superadmin' => true]);

        $this->info("✓ Successfully promoted {$user->fullName()} ({$email}) to superadmin!");
        $this->info("You can now access the Super Admin Dashboard at /admin/superadmin/dashboard");

        return 0;
    }
}
