<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    public static function createNotification($userId, $type, $title, $message, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
    
    public static function createMessageNotification($userId, $senderId, $message = null)
    {
        $sender = User::find($senderId);
        return self::createNotification(
            $userId,
            'message',
            'New Message',
            $message ?? "You have a new message from {$sender->name}",
            ['sender_id' => $senderId]
        );
    }
    
    public static function createNewUserNotification($userId, $newUserId)
    {
        $newUser = User::find($newUserId);
        return self::createNotification(
            $userId,
            'new_user',
            'New User Joined',
            "{$newUser->name} has joined the platform",
            ['new_user_id' => $newUserId]
        );
    }
    
    public static function createMatchNotification($userId, $matchedUserId)
    {
        $matchedUser = User::find($matchedUserId);
        return self::createNotification(
            $userId,
            'match',
            'New Match',
            "You have a new match with {$matchedUser->name}",
            ['matched_user_id' => $matchedUserId]
        );
    }
    
    public static function createProfileViewNotification($userId, $viewerId)
    {
        $viewer = User::find($viewerId);
        return self::createNotification(
            $userId,
            'profile_view',
            'Profile Viewed',
            "{$viewer->name} viewed your profile",
            ['viewer_id' => $viewerId]
        );
    }
    
    public static function createLikeNotification($userId, $likerId)
    {
        $liker = User::find($likerId);
        return self::createNotification(
            $userId,
            'like',
            'New Like',
            "{$liker->name} liked your profile",
            ['liker_id' => $likerId]
        );
    }
}
