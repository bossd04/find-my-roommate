<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Testing Direct Database Update ===\n";

// Get first user
$user = User::find(1);

if ($user) {
    echo "Before update:\n";
    echo "Phone: " . ($user->phone ?? 'NULL') . "\n";
    echo "Gender: " . ($user->gender ?? 'NULL') . "\n";
    echo "Location: " . ($user->location ?? 'NULL') . "\n";
    echo "University: " . ($user->university ?? 'NULL') . "\n";
    echo "Course: " . ($user->course ?? 'NULL') . "\n";
    
    // Update user with test data
    $user->update([
        'phone' => '09123456789',
        'gender' => 'male',
        'location' => 'Dagupan City',
        'university' => 'Pangasinan State University',
        'department' => 'Computer Science',
        'course' => 'Bachelor of Science in Computer Science',
        'year_level' => '3rd Year',
        'date_of_birth' => '2000-01-01',
        'budget_min' => 5000,
        'budget_max' => 15000
    ]);
    
    echo "\nAfter update:\n";
    $user->refresh();
    echo "Phone: " . ($user->phone ?? 'NULL') . "\n";
    echo "Gender: " . ($user->gender ?? 'NULL') . "\n";
    echo "Location: " . ($user->location ?? 'NULL') . "\n";
    echo "University: " . ($user->university ?? 'NULL') . "\n";
    echo "Course: " . ($user->course ?? 'NULL') . "\n";
    echo "Department: " . ($user->department ?? 'NULL') . "\n";
    echo "Year Level: " . ($user->year_level ?? 'NULL') . "\n";
    echo "Date of Birth: " . ($user->date_of_birth ?? 'NULL') . "\n";
    
    // Create roommate profile
    $profile = $user->roommateProfile()->updateOrCreate(
        ['user_id' => $user->id],
        [
            'display_name' => $user->first_name . ' ' . $user->last_name,
            'age' => 24, // Calculate from date_of_birth
            'gender' => $user->gender,
            'cleanliness_level' => 'average',
            'sleep_pattern' => 'flexible',
            'study_habit' => 'moderate',
            'noise_tolerance' => 'moderate',
        ]
    );
    
    echo "\nRoommate Profile created with ID: " . $profile->id . "\n";
    
    // Check profile completion
    echo "\nProfile completion status: " . ($user->fresh()->isProfileComplete() ? 'COMPLETE' : 'INCOMPLETE') . "\n";
    
} else {
    echo "No user found\n";
}
