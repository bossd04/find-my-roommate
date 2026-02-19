<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserAndListingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user with correct credentials
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'remember_token' => Str::random(10),
            ]
        );
        
        // Ensure the user has admin privileges
        $admin->update([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'is_admin' => true,
            'password' => Hash::make('admin123'),
        ]);

        // Create test listings
        $listings = [
            [
                'title' => 'Cozy Studio in Makati',
                'description' => 'A cozy studio apartment located in the heart of Makati City. Perfect for young professionals.',
                'price' => 15000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'location' => 'Makati City',
                'type' => 'room',
                'property_type' => 'apartment',
                'is_available' => true,
                'area_sqft' => 25,
                'furnished' => true,
                'utilities_included' => false,
                'available_from' => now()->addDays(7),
                'lease_duration_months' => 12,
                'security_deposit' => 15000,
                'amenities' => json_encode(['wifi', 'aircon', 'kitchen', 'laundry']),
                'house_rules' => 'No smoking, No pets, No parties',
                'landlord_id' => $admin->id,
            ],
            [
                'title' => 'Spacious 2BR Condo in BGC',
                'description' => 'Modern 2-bedroom condo in Bonifacio Global City with great amenities.',
                'price' => 35000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'location' => 'Taguig City',
                'type' => 'apartment',
                'property_type' => 'condo',
                'is_available' => true,
                'area_sqft' => 60,
                'furnished' => true,
                'utilities_included' => true,
                'available_from' => now()->addDays(14),
                'lease_duration_months' => 24,
                'security_deposit' => 35000,
                'amenities' => json_encode(['wifi', 'aircon', 'kitchen', 'laundry', 'gym', 'pool']),
                'house_rules' => 'No smoking, No pets, Maximum 2 occupants',
                'landlord_id' => $admin->id,
            ],
        ];

        foreach ($listings as $listing) {
            Listing::create($listing);
        }
    }
}
