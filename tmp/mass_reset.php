<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\RoommateMatch;
use App\Models\UserCompatibility;

$names = ['jhonrey', 'monterola', 'jamess', 'john', 'test', 'christian', 'gween', 'arnold'];

foreach ($names as $name) {
    $users = User::where('first_name', 'like', "%{$name}%")
                ->orWhere('name', 'like', "%{$name}%")
                ->get();

    foreach ($users as $user) {
        // Reset Compatibility Scores
        UserCompatibility::where('user_id', $user->id)
            ->orWhere('target_user_id', $user->id)
            ->update([
                'compatibility_score' => 0,
                'interaction_count' => 0,
                'profile_views' => 0,
                'messages_exchanged' => 0,
                'preference_matches' => 0,
                'is_fully_compatible' => false
            ]);

        // Reset Match Status (Change 'accepted' to 'pending' to hide message button)
        RoommateMatch::where(function($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('matched_user_id', $user->id);
        })->where('status', 'accepted')->update(['status' => 'pending']);

        echo "Reset scores and match status for user: {$user->name} (ID: {$user->id})\n";
    }
}
