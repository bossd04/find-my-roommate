<?php

// Database backup script for all users
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Connect to database
try {
    // Get all users with their profiles
    $users = DB::table('users')
        ->leftJoin('roommate_profiles', 'users.id', '=', 'roommate_profiles.user_id')
        ->select(
            'users.id',
            'users.first_name',
            'users.last_name', 
            'users.email',
            'users.gender',
            'users.phone',
            'users.date_of_birth',
            'users.course',
            'users.year_level',
            'users.university',
            'users.is_verified',
            'users.created_at',
            'users.updated_at',
            'roommate_profiles.apartment_location',
            'roommate_profiles.city',
            'roommate_profiles.university',
            'roommate_profiles.major',
            'roommate_profiles.budget_min',
            'roommate_profiles.budget_max',
            'roommate_profiles.lifestyle_preferences',
            'roommate_profiles.cleanliness_habits',
            'roommate_profiles.study_habits',
            'roommate_profiles.sleep_schedule',
            'roommate_profiles.smoking_preference',
            'roommate_profiles.pets_preference',
            'roommate_profiles.guest_preference',
            'roommate_profiles.music_preference',
            'roommate_profiles.party_preference'
        )
        ->orderBy('users.created_at', 'desc')
        ->get();

    // Create backup data array
    $backupData = [];
    foreach ($users as $user) {
        $backupData[] = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'date_of_birth' => $user->date_of_birth,
            'course' => $user->course,
            'year_level' => $user->year_level,
            'university' => $user->university,
            'is_verified' => $user->is_verified,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'apartment_location' => $user->apartment_location,
            'city' => $user->city,
            'profile_university' => $user->profile_university,
            'major' => $user->major,
            'budget_min' => $user->budget_min,
            'budget_max' => $user->budget_max,
            'lifestyle_preferences' => $user->lifestyle_preferences,
            'cleanliness_habits' => $user->cleanliness_habits,
            'study_habits' => $user->study_habits,
            'sleep_schedule' => $user->sleep_schedule,
            'smoking_preference' => $user->smoking_preference,
            'pets_preference' => $user->pets_preference,
            'guest_preference' => $user->guest_preference,
            'music_preference' => $user->music_preference,
            'party_preference' => $user->party_preference
        ];
    }

    // Create backup filename with timestamp
    $timestamp = date('Y_m_d_His');
    $filename = "users_backup_{$timestamp}.json";
    
    // Save to database/backups directory
    $backupPath = database_path("backups/{$filename}");
    
    // Write backup to JSON file
    file_put_contents($backupPath, json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo "✅ Successfully backed up " . count($users) . " users to {$filename}\n";
    echo "📁 Backup saved to: {$backupPath}\n";
    echo "📊 Total backup size: " . number_format(filesize($backupPath) / 1024 / 1024, 2) . " MB\n";
    
} catch (Exception $e) {
    echo "❌ Error creating backup: " . $e->getMessage() . "\n";
    echo "📍 File: " . $backupPath . "\n";
}
