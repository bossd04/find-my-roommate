<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\User;

echo "=== All Users in Database ===\n\n";

try {
    $users = User::all();
    
    if ($users->isEmpty()) {
        echo "❌ No users found in database\n";
        exit;
    }
    
    foreach ($users as $user) {
        echo "ID: " . $user->id . "\n";
        echo "Name: " . $user->first_name . " " . $user->last_name . "\n";
        echo "Email: " . $user->email . "\n";
        echo "Role: " . $user->role . "\n";
        echo "Admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";
        echo "Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
        echo "Created: " . $user->created_at . "\n";
        echo "---\n";
    }
    
    echo "Total Users: " . $users->count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
