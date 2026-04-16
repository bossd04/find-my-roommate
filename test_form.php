<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Testing Form Data Display ===\n";

// Get first user
$user = User::find(1);

if ($user) {
    echo "Current user data:\n";
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
    
    // Test setting some data
    echo "\n=== Setting Test Data ===\n";
    $user->update([
        'phone' => '09123456789',
        'gender' => 'male',
        'date_of_birth' => '2000-01-01',
        'location' => 'Dagupan City',
        'university' => 'Pangasinan State University',
        'department' => 'Computer Science',
        'course' => 'Bachelor of Science in Computer Science',
        'year_level' => '3rd Year'
    ]);
    
    echo "Data updated successfully!\n";
    
    // Refresh and check
    $user->refresh();
    echo "\n=== After Update ===\n";
    echo "Phone: " . ($user->phone ?? 'NULL') . "\n";
    echo "Gender: " . ($user->gender ?? 'NULL') . "\n";
    echo "Date of Birth: " . ($user->date_of_birth ?? 'NULL') . "\n";
    echo "Location: " . ($user->location ?? 'NULL') . "\n";
    echo "University: " . ($user->university ?? 'NULL') . "\n";
    echo "Department: " . ($user->department ?? 'NULL') . "\n";
    echo "Course: " . ($user->course ?? 'NULL') . "\n";
    echo "Year Level: " . ($user->year_level ?? 'NULL') . "\n";
    
    // Create roommate profile if needed
    if (!$user->roommateProfile) {
        $user->roommateProfile()->create([
            'display_name' => $user->first_name . ' ' . $user->last_name,
            'age' => 24,
            'gender' => $user->gender,
            'cleanliness_level' => 'average',
            'sleep_pattern' => 'flexible',
            'study_habit' => 'moderate',
            'noise_tolerance' => 'moderate',
        ]);
        echo "\nRoommate profile created!\n";
    }
    
    echo "\nProfile completion status: " . ($user->isProfileComplete() ? 'COMPLETE' : 'INCOMPLETE') . "\n";
    
} else {
    echo "No user found\n";
}
