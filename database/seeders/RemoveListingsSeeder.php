<?php

namespace Database\Seeders;

use App\Models\Listing;
use Illuminate\Database\Seeder;

class RemoveListingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove specific listings
        $titlesToRemove = [
            'Cozy Studio in Makati',
            'Spacious 2BR Condo in BGC'
        ];

        foreach ($titlesToRemove as $title) {
            $deleted = Listing::where('title', $title)->delete();
            if ($deleted > 0) {
                $this->command->info("Removed listing: {$title}");
            } else {
                $this->command->info("Listing not found: {$title}");
            }
        }

        $this->command->info('Listings removal completed!');
    }
}
