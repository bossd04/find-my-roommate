<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoommateProfile;

class RoommateProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('id', '>', 1)->take(3)->get(); // Get a few users (skip admin)
        
        foreach ($users as $user) {
            RoommateProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => $user->first_name . ' ' . $user->last_name,
                    'age' => rand(18, 25),
                    'gender' => ['male', 'female', 'other'][rand(0, 2)],
                    'bio' => 'Looking for a compatible roommate!',
                    'university' => 'State University',
                    'major' => 'Computer Science',
                    'cleanliness_level' => 'average',
                    'noise_level' => 'moderate',
                    'schedule' => 'flexible',
                    'smoking_allowed' => false,
                    'pets_allowed' => false,
                    'has_apartment' => false,
                    'apartment_location' => 'Downtown Campus Area',
                    'phone' => '+1' . rand(200, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                    'budget_min' => 500.00,
                    'budget_max' => 1500.00,
                    'move_in_date' => now()->addMonths(1)->format('Y-m-d'),
                    'lease_duration' => '12 months',
                ]
            );
        }
    }
}
