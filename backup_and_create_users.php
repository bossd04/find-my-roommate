<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== USER BACKUP AND CREATION ===" . PHP_EOL;
echo "Generated at: " . now() . PHP_EOL;
echo "======================================" . PHP_EOL . PHP_EOL;

// Backup existing users
$existingUsers = User::all();

echo "=== EXISTING USERS BACKUP ===" . PHP_EOL;
if ($existingUsers->isEmpty()) {
    echo "No existing users found." . PHP_EOL;
} else {
    foreach ($existingUsers as $user) {
        echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}" . PHP_EOL;
    }
}

echo PHP_EOL . "=== CREATING TEST USERS ===" . PHP_EOL;

// Create test users for login testing
$testUsers = [
    [
        'name' => 'John Doe',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'role' => 'user',
        'is_admin' => false,
        'phone' => '09123456789',
        'gender' => 'male',
        'university' => 'Pangasinan State University',
        'course' => 'Computer Science',
        'year_level' => '3rd Year',
    ],
    [
        'name' => 'Jane Smith',
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'role' => 'user',
        'is_admin' => false,
        'phone' => '09987654321',
        'gender' => 'female',
        'university' => 'University of Pangasinan',
        'course' => 'Business Administration',
        'year_level' => '2nd Year',
    ],
    [
        'name' => 'Mike Wilson',
        'first_name' => 'Mike',
        'last_name' => 'Wilson',
        'email' => 'mike@example.com',
        'password' => 'password123',
        'role' => 'user',
        'is_admin' => false,
        'phone' => '09112223344',
        'gender' => 'male',
        'university' => 'Dagupan City Colleges',
        'course' => 'Information Technology',
        'year_level' => '1st Year',
    ]
];

$createdUsers = [];
foreach ($testUsers as $userData) {
    try {
        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            array_merge($userData, [
                'password' => Hash::make($userData['password']),
                'email_verified_at' => now(),
            ])
        );
        
        $createdUsers[] = $user;
        echo "✅ Created/Updated: {$user->name} ({$user->email})" . PHP_EOL;
    } catch (Exception $e) {
        echo "❌ Error creating {$userData['email']}: " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . "=== LOGIN CREDENTIALS ===" . PHP_EOL;
echo "You can now login with these credentials:" . PHP_EOL . PHP_EOL;

echo "=== ADMIN ACCOUNT ===" . PHP_EOL;
echo "Email: admin@admin.com" . PHP_EOL;
echo "Password: admin123" . PHP_EOL . PHP_EOL;

echo "=== TEST USER ACCOUNTS ===" . PHP_EOL;
foreach ($testUsers as $userData) {
    echo "Email: {$userData['email']}" . PHP_EOL;
    echo "Password: {$userData['password']}" . PHP_EOL;
    echo "Name: {$userData['name']}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== FINAL USER COUNT ===" . PHP_EOL;
$totalUsers = User::count();
echo "Total users in database: {$totalUsers}" . PHP_EOL;

echo PHP_EOL . "=== BACKUP COMPLETED ===" . PHP_EOL;
