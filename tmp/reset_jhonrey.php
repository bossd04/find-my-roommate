<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\UserCompatibility;

$user = User::where('first_name', 'like', '%jhonrey%')
            ->orWhere('name', 'like', '%jhonrey%')
            ->first();

if ($user) {
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
    echo "Reset scores for user {$user->name} (ID: {$user->id})\n";
} else {
    echo "User jhonrey not found\n";
}
