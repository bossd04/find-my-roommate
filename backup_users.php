<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== USER BACKUP REPORT ===" . PHP_EOL;
echo "Generated at: " . now() . PHP_EOL;
echo "================================" . PHP_EOL . PHP_EOL;

$users = User::all();

if ($users->isEmpty()) {
    echo "No users found in the database." . PHP_EOL;
    exit;
}

echo "Total Users: " . $users->count() . PHP_EOL . PHP_EOL;

echo "=== USER DETAILS ===" . PHP_EOL;
foreach ($users as $user) {
    echo "ID: " . $user->id . PHP_EOL;
    echo "Name: " . $user->name . PHP_EOL;
    echo "Email: " . $user->email . PHP_EOL;
    echo "First Name: " . ($user->first_name ?? 'N/A') . PHP_EOL;
    echo "Last Name: " . ($user->last_name ?? 'N/A') . PHP_EOL;
    echo "Phone: " . ($user->phone ?? 'N/A') . PHP_EOL;
    echo "Gender: " . ($user->gender ?? 'N/A') . PHP_EOL;
    echo "University: " . ($user->university ?? 'N/A') . PHP_EOL;
    echo "Course: " . ($user->course ?? 'N/A') . PHP_EOL;
    echo "Role: " . ($user->role ?? 'user') . PHP_EOL;
    echo "Is Admin: " . ($user->is_admin ? 'Yes' : 'No') . PHP_EOL;
    echo "Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . PHP_EOL;
    echo "Created At: " . $user->created_at . PHP_EOL;
    echo "Updated At: " . $user->updated_at . PHP_EOL;
    echo "----------------------------------------" . PHP_EOL;
}

echo PHP_EOL . "=== BACKUP COMPLETED ===" . PHP_EOL;
