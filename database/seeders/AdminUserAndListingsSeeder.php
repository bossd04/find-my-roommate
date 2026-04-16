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
            // Dagupan City Listings near Arellano St
            [
                'title' => 'Student Apartment near Arellano St',
                'description' => 'Affordable apartment perfect for students. Walking distance to Arellano St and nearby universities. Fully furnished with study area.',
                'price' => 3500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'location' => 'Arellano St, Dagupan City',
                'latitude' => 16.0435,
                'longitude' => 120.3345,
                'type' => 'room',
                'property_type' => 'apartment',
                'is_available' => true,
                'area_sqft' => 20,
                'furnished' => true,
                'utilities_included' => true,
                'available_from' => now()->addDays(3),
                'lease_duration_months' => 12,
                'security_deposit' => 3500,
                'amenities' => json_encode(['wifi', 'fan', 'study_table', 'bed', 'kitchen_access']),
                'house_rules' => 'No smoking inside, Quiet hours 10PM-6AM, No overnight guests without permission',
                'landlord_id' => $admin->id,
            ],
            [
                'title' => 'Shared Room near Arellano St',
                'description' => 'Looking for a roommate! Shared room in a 2-bedroom apartment near Arellano St. Split rent and utilities. Friendly atmosphere.',
                'price' => 2500,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'location' => 'Arellano St Area, Dagupan City',
                'latitude' => 16.0440,
                'longitude' => 120.3350,
                'type' => 'roommate',
                'property_type' => 'apartment',
                'is_available' => true,
                'area_sqft' => 45,
                'furnished' => true,
                'utilities_included' => false,
                'available_from' => now()->addDays(5),
                'lease_duration_months' => 6,
                'security_deposit' => 2500,
                'amenities' => json_encode(['wifi', 'aircon', 'kitchen', 'laundry', 'living_room']),
                'house_rules' => 'Split utilities evenly, Clean common areas, No loud music after 9PM',
                'landlord_id' => $admin->id,
            ],
            [
                'title' => 'Budget Studio - Downtown Dagupan',
                'description' => 'Clean and affordable studio apartment in downtown Dagupan, near Arellano St. Perfect for single professionals or students.',
                'price' => 2800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'location' => 'Downtown Dagupan City',
                'latitude' => 16.0425,
                'longitude' => 120.3330,
                'type' => 'room',
                'property_type' => 'apartment',
                'is_available' => true,
                'area_sqft' => 18,
                'furnished' => true,
                'utilities_included' => false,
                'available_from' => now()->addDays(2),
                'lease_duration_months' => 12,
                'security_deposit' => 2800,
                'amenities' => json_encode(['wifi', 'electric_fan', 'bed', 'closet', 'shared_bathroom']),
                'house_rules' => 'Pay rent on time, Keep area clean, Respect neighbors',
                'landlord_id' => $admin->id,
            ],
            [
                'title' => '2BR House - Dagupan City Center',
                'description' => 'Spacious 2-bedroom house available for rent. Near Arellano St and commercial areas. Perfect for families or roommates.',
                'price' => 8000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'location' => 'City Center, Dagupan City',
                'latitude' => 16.0450,
                'longitude' => 120.3360,
                'type' => 'apartment',
                'property_type' => 'house',
                'is_available' => true,
                'area_sqft' => 80,
                'furnished' => false,
                'utilities_included' => false,
                'available_from' => now()->addDays(10),
                'lease_duration_months' => 12,
                'security_deposit' => 8000,
                'amenities' => json_encode(['parking', 'yard', 'kitchen', 'laundry_area']),
                'house_rules' => 'No pets without approval, Maintain yard, No subletting',
                'landlord_id' => $admin->id,
            ],
            [
                'title' => 'Private Room with Bath - Dagupan',
                'description' => 'Private room with own bathroom in a shared house. Walking distance to Arellano St. Looking for responsible roommate.',
                'price' => 4000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'location' => 'Near Arellano St, Dagupan City',
                'latitude' => 16.0438,
                'longitude' => 120.3342,
                'type' => 'roommate',
                'property_type' => 'house',
                'is_available' => true,
                'area_sqft' => 15,
                'furnished' => true,
                'utilities_included' => true,
                'available_from' => now()->addDays(7),
                'lease_duration_months' => 6,
                'security_deposit' => 4000,
                'amenities' => json_encode(['wifi', 'aircon', 'own_bathroom', 'kitchen_access', 'living_room']),
                'house_rules' => 'No smoking, No overnight guests without notice, Clean up after yourself',
                'landlord_id' => $admin->id,
            ],
        ];

        foreach ($listings as $listing) {
            Listing::create($listing);
        }
    }
}
