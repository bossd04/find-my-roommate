<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCompatibility;
use App\Models\Message;

class CompatibilityService
{
    /**
     * Track message interaction between users
     */
    public function trackMessageInteraction(User $sender, User $receiver): void
    {
        // Update both users' compatibility records
        $senderCompatibility = $sender->getCompatibilityWith($receiver);
        $receiverCompatibility = $receiver->getCompatibilityWith($sender);
        
        $senderCompatibility->addInteraction('message');
        $receiverCompatibility->addInteraction('message');
    }
    
    /**
     * Track profile view interaction
     */
    public function trackProfileView(User $viewer, User $viewed): void
    {
        $compatibility = $viewer->getCompatibilityWith($viewed);
        $compatibility->addInteraction('profile_view');
    }
    
    /**
     * Track violation (bad manners, harassment, etc)
     */
    public function trackViolation(User $reporter, User $reported): void
    {
        $compatibility = $reporter->getCompatibilityWith($reported);
        $compatibility->addInteraction('violation');
        
        // Also update the reverse compatibility if it exists
        $reverseCompatibility = $reported->getCompatibilityWith($reporter);
        $reverseCompatibility->addInteraction('violation');
    }
    
    /**
     * Check if users can become roommates (100% compatibility)
     */
    public function canBecomeRoommates(User $user1, User $user2): bool
    {
        $compatibility = $user1->getCompatibilityWith($user2);
        return $compatibility->is_fully_compatible && $compatibility->compatibility_score >= 100;
    }
    
    /**
     * Get compatibility growth suggestions
     */
    public function getCompatibilitySuggestions(User $user1, User $user2): array
    {
        $compatibility = $user1->getCompatibilityWith($user2);
        $suggestions = [];
        
        if ($compatibility->profile_views === 0) {
            $suggestions[] = "View their profile to learn more about them (+5%)";
        }
        
        if ($compatibility->messages_exchanged === 0) {
            $suggestions[] = "Send a message to start a conversation (+10%)";
        }
        
        if ($compatibility->compatibility_score < 50) {
            $suggestions[] = "Keep interacting to build compatibility";
        } elseif ($compatibility->compatibility_score < 80) {
            $suggestions[] = "You're building good compatibility! Keep it up!";
        } else {
            $suggestions[] = "Great compatibility! You're almost ready to become roommates!";
        }
        
        return $suggestions;
    }
}
