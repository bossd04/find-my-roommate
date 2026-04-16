<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserReport;
use App\Models\UserRestriction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Block a user
     */
    public function block(User $user, Request $request)
    {
        $currentUser = Auth::user();

        // Prevent blocking self
        if ($currentUser->id === $user->id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot block yourself'], 400);
        }

        // Check if already blocked
        $existingBlock = UserBlock::where('blocker_id', $currentUser->id)
            ->where('blocked_id', $user->id)
            ->active()
            ->first();

        if ($existingBlock) {
            return response()->json(['status' => 'error', 'message' => 'User is already blocked'], 400);
        }

        try {
            UserBlock::create([
                'blocker_id' => $currentUser->id,
                'blocked_id' => $user->id,
                'reason' => $request->input('reason'),
                'blocked_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'User blocked successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to block user'], 500);
        }
    }

    /**
     * Unblock a user
     */
    public function unblock(User $user)
    {
        $currentUser = Auth::user();

        $block = UserBlock::where('blocker_id', $currentUser->id)
            ->where('blocked_id', $user->id)
            ->active()
            ->first();

        if (!$block) {
            return response()->json(['status' => 'error', 'message' => 'User is not blocked'], 400);
        }

        $block->delete();

        return response()->json(['status' => 'success', 'message' => 'User unblocked successfully']);
    }

    /**
     * Check if current user has blocked a specific user
     */
    public function isBlocked(User $user)
    {
        $currentUser = Auth::user();

        $isBlocked = UserBlock::where('blocker_id', $currentUser->id)
            ->where('blocked_id', $user->id)
            ->active()
            ->exists();

        return response()->json(['is_blocked' => $isBlocked]);
    }

    /**
     * Report a user
     */
    public function report(User $user, Request $request)
    {
        $currentUser = Auth::user();

        // Prevent reporting self
        if ($currentUser->id === $user->id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot report yourself'], 400);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'report_type' => 'required|string|in:inappropriate_behavior,harassment,spam,fake_profile,scam,other',
        ]);

        try {
            // Check if user has already reported this user with pending status
            $existingReport = UserReport::where('reporter_id', $currentUser->id)
                ->where('reported_id', $user->id)
                ->whereIn('status', ['pending', 'reviewing'])
                ->first();

            if ($existingReport) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have already reported this user. An admin is reviewing your report.'
                ], 400);
            }

            $report = UserReport::create([
                'reporter_id' => $currentUser->id,
                'reported_id' => $user->id,
                'report_type' => $validated['report_type'],
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]);

            // Log for admin visibility
            \Log::info('User reported', [
                'report_id' => $report->id,
                'reported_by' => $currentUser->id,
                'reported_user' => $user->id,
                'report_type' => $validated['report_type'],
                'reason' => $validated['reason']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User reported successfully. An admin will review your report.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to submit report'], 500);
        }
    }

    /**
     * Restrict a user (Admin only - users cannot restrict each other)
     */
    public function restrict(User $user, Request $request)
    {
        $currentUser = Auth::user();

        // Only admins can restrict users
        if (!$currentUser->is_admin) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        // Prevent restricting self
        if ($currentUser->id === $user->id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot restrict yourself'], 400);
        }

        $validated = $request->validate([
            'restriction_type' => 'required|integer|between:1,4',
            'reason' => 'required|string|max:500',
            'duration_days' => 'nullable|integer|min:1|max:365',
        ]);

        try {
            $expiresAt = null;
            if (!empty($validated['duration_days'])) {
                $expiresAt = now()->addDays($validated['duration_days']);
            }

            $restriction = UserRestriction::create([
                'restricted_id' => $user->id,
                'restricted_by' => $currentUser->id,
                'restriction_type' => $validated['restriction_type'],
                'reason' => $validated['reason'],
                'starts_at' => now(),
                'expires_at' => $expiresAt,
                'is_active' => true,
            ]);

            // Log for audit trail
            \Log::info('User restricted by admin', [
                'restriction_id' => $restriction->id,
                'restricted_by' => $currentUser->id,
                'restricted_user' => $user->id,
                'restriction_type' => $validated['restriction_type'],
                'reason' => $validated['reason'],
                'expires_at' => $expiresAt,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User restricted successfully',
                'restriction' => [
                    'type' => $restriction->getRestrictionTypeLabel(),
                    'description' => $restriction->getDescription(),
                    'expires_at' => $expiresAt?->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to restrict user'], 500);
        }
    }

    /**
     * Remove a restriction (Admin only)
     */
    public function removeRestriction(UserRestriction $restriction)
    {
        $currentUser = Auth::user();

        if (!$currentUser->is_admin) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $restriction->update([
            'is_active' => false,
            'expires_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Restriction removed successfully']);
    }

    /**
     * Get current user's blocked users list
     */
    public function getBlockedUsers()
    {
        $currentUser = Auth::user();

        $blockedUsers = UserBlock::with('blocked')
            ->where('blocker_id', $currentUser->id)
            ->active()
            ->get()
            ->map(function ($block) {
                return [
                    'id' => $block->blocked->id,
                    'name' => $block->blocked->fullName(),
                    'avatar' => $block->blocked->avatar_url,
                    'blocked_at' => $block->blocked_at,
                    'reason' => $block->reason,
                ];
            });

        return response()->json(['blocked_users' => $blockedUsers]);
    }
}
