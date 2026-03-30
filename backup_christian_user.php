<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\User;
use App\Models\Message;
use App\Models\Preference;

echo "=== User Backup: christian@email.com ===\n\n";

try {
    // Find the user
    $user = User::where('email', 'christian@email.com')->first();
    
    if (!$user) {
        echo "❌ User not found in database\n";
        exit;
    }
    
    echo "✅ User Found:\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->first_name . " " . $user->last_name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Phone: " . $user->phone . "\n";
    echo "University: " . $user->university . "\n";
    echo "Course: " . $user->course . "\n";
    echo "Year Level: " . $user->year_level . "\n";
    echo "Budget: " . $user->budget_min . " - " . $user->budget_max . "\n";
    echo "Created: " . $user->created_at . "\n\n";
    
    // Get user preferences
    echo "📋 User Preferences:\n";
    $preferences = $user->preferences;
    if ($preferences) {
        echo "Cleanliness: " . ($preferences->cleanliness_level ?? 'N/A') . "\n";
        echo "Sleep Pattern: " . ($preferences->sleep_pattern ?? 'N/A') . "\n";
        echo "Study Habit: " . ($preferences->study_habit ?? 'N/A') . "\n";
        echo "Noise Tolerance: " . ($preferences->noise_tolerance ?? 'N/A') . "\n";
        echo "Smoking: " . ($preferences->smoking ?? 'N/A') . "\n";
        echo "Pets: " . ($preferences->pets ?? 'N/A') . "\n";
        echo "Overnight Visitors: " . ($preferences->overnight_visitors ?? 'N/A') . "\n";
        echo "Schedule: " . ($preferences->schedule ?? 'N/A') . "\n";
    } else {
        echo "No preferences found\n";
    }
    echo "\n";
    
    // Get message statistics
    echo "💬 Message Statistics:\n";
    $sentMessages = Message::where('sender_id', $user->id)->count();
    $receivedMessages = Message::where('receiver_id', $user->id)->count();
    $unreadMessages = Message::where('receiver_id', $user->id)->whereNull('read_at')->count();
    
    echo "Messages Sent: " . $sentMessages . "\n";
    echo "Messages Received: " . $receivedMessages . "\n";
    echo "Unread Messages: " . $unreadMessages . "\n";
    echo "\n";
    
    // Get recent conversations
    echo "👥 Recent Conversations:\n";
    $conversations = Message::where(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get()
        ->groupBy(function($message) use ($user) {
            return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
        });
    
    foreach ($conversations as $otherUserId => $messages) {
        $otherUser = $messages->first()->sender_id == $user->id 
            ? $messages->first()->receiver 
            : $messages->first()->sender;
        
        echo "- " . $otherUser->first_name . " " . $otherUser->last_name . " (" . $otherUser->email . ")\n";
        echo "  Last message: " . substr($messages->first()->content, 0, 50) . "...\n";
        echo "  Total messages: " . $messages->count() . "\n\n";
    }
    
    echo "=== Backup Complete ===\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
