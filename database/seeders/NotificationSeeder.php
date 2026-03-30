<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Create sample notifications for each user
            Notification::create([
                'user_id' => $user->id,
                'type' => 'message',
                'title' => 'New Message',
                'message' => 'You have received a new message from another user',
                'data' => ['sender_id' => 1],
                'read_at' => null,
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_user',
                'title' => 'New User Joined',
                'message' => 'A new user has joined the platform',
                'data' => ['new_user_id' => 2],
                'read_at' => now(),
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'type' => 'match',
                'title' => 'New Match',
                'message' => 'You have a new roommate match',
                'data' => ['matched_user_id' => 3],
                'read_at' => null,
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'type' => 'profile_view',
                'title' => 'Profile Viewed',
                'message' => 'Someone viewed your profile',
                'data' => ['viewer_id' => 4],
                'read_at' => now(),
            ]);
        }
    }
}
