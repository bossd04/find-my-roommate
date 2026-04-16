<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== User Debug Information ===\n";

// Get first user
$user = User::first();

if ($user) {
    echo "User ID: " . $user->id . "\n";
    echo "Email: " . $user->email . "\n";
    echo "First Name: " . ($user->first_name ?? 'NULL') . "\n";
    echo "Last Name: " . ($user->last_name ?? 'NULL') . "\n";
    echo "Phone: " . ($user->phone ?? 'NULL') . "\n";
    echo "Gender: " . ($user->gender ?? 'NULL') . "\n";
    echo "Date of Birth: " . ($user->date_of_birth ?? 'NULL') . "\n";
    echo "Location: " . ($user->location ?? 'NULL') . "\n";
    echo "University: " . ($user->university ?? 'NULL') . "\n";
    echo "Department: " . ($user->department ?? 'NULL') . "\n";
    echo "Course: " . ($user->course ?? 'NULL') . "\n";
    echo "Year Level: " . ($user->year_level ?? 'NULL') . "\n";
    echo "Budget Min: " . ($user->budget_min ?? 'NULL') . "\n";
    echo "Budget Max: " . ($user->budget_max ?? 'NULL') . "\n";
    
    echo "\n=== Profile Completion ===\n";
    echo "isProfileComplete(): " . ($user->isProfileComplete() ? 'TRUE' : 'FALSE') . "\n";
    
    echo "\n=== Roommate Profile ===\n";
    $profile = $user->roommateProfile;
    if ($profile) {
        echo "Profile ID: " . $profile->id . "\n";
        echo "Cleanliness Level: " . ($profile->cleanliness_level ?? 'NULL') . "\n";
        echo "Sleep Pattern: " . ($profile->sleep_pattern ?? 'NULL') . "\n";
        echo "Study Habit: " . ($profile->study_habit ?? 'NULL') . "\n";
        echo "Noise Tolerance: " . ($profile->noise_tolerance ?? 'NULL') . "\n";
    } else {
        echo "No roommate profile found\n";
    }
} else {
    echo "No users found in database\n";
}

echo "\n=== Database Table Structure ===\n";
echo "Checking if columns exist in users table:\n";

$columns = ['gender', 'date_of_birth', 'location', 'university', 'department', 'course', 'year_level', 'budget_min', 'budget_max'];

foreach ($columns as $column) {
    $exists = \Illuminate\Support\Facades\Schema::hasColumn('users', $column);
    echo "$column: " . ($exists ? 'EXISTS' : 'MISSING') . "\n";
}
