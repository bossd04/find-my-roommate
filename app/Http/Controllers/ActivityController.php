<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ActivityController extends Controller
{
    private function getActivities()
    {
        return [
            [
                'id' => 1,
                'type' => 'new_match',
                'title' => 'New Roommate Match!',
                'description' => 'You matched with John D. from your building',
                'time' => Carbon::now()->subMinutes(15),
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'color' => 'green',
                'action' => 'View Profile',
                'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'url' => '#',
                'is_read' => false
            ],
            [
                'id' => 2,
                'type' => 'message',
                'title' => 'New Message',
                'description' => 'Sarah sent you a message about your listing',
                'time' => Carbon::now()->subHours(2),
                'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
                'color' => 'blue',
                'action' => 'Reply',
                'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'url' => route('messages.index'),
                'is_read' => false
            ],
            [
                'id' => 3,
                'type' => 'profile_view',
                'title' => 'Profile Viewed',
                'description' => 'Your profile was viewed by 5 potential roommates',
                'time' => Carbon::now()->subDay(),
                'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                'color' => 'yellow',
                'action' => 'See Who',
                'avatar' => 'https://randomuser.me/api/portraits/men/22.jpg',
                'url' => route('profile.show', ['user' => auth()->id()]),
                'is_read' => true
            ],
            [
                'id' => 4,
                'type' => 'listing_approved',
                'title' => 'Listing Approved',
                'description' => 'Your room listing "Sunny Dorm Room" was approved',
                'time' => Carbon::now()->subDays(2),
                'icon' => 'M5 13l4 4L19 7',
                'color' => 'green',
                'action' => 'View Listing',
                'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'url' => '#', // Fallback URL since listings.show route doesn't exist
                'is_read' => true
            ],
            [
                'id' => 5,
                'type' => 'new_feature',
                'title' => 'New Feature',
                'description' => 'Check out our new roommate compatibility score!',
                'time' => Carbon::now()->subWeek(),
                'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                'color' => 'purple',
                'action' => 'Learn More',
                'avatar' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'url' => '#',
                'is_read' => true
            ],
            // More activities for pagination
            [
                'id' => 6,
                'type' => 'new_match',
                'title' => 'New Roommate Match!',
                'description' => 'You matched with Alex K. from nearby',
                'time' => Carbon::now()->subDays(3),
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'color' => 'green',
                'action' => 'View Profile',
                'avatar' => 'https://randomuser.me/api/portraits/women/22.jpg',
                'url' => '#',
                'is_read' => true
            ],
            [
                'id' => 7,
                'type' => 'message',
                'title' => 'New Message',
                'description' => 'Mike replied to your message',
                'time' => Carbon::now()->subDays(4),
                'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
                'color' => 'blue',
                'action' => 'Reply',
                'avatar' => 'https://randomuser.me/api/portraits/men/45.jpg',
                'url' => route('messages.index'),
                'is_read' => true
            ]
        ];
    }

    public function index(Request $request)
    {
        $allActivities = collect($this->getActivities());
        
        // Apply filters if any
        $type = $request->query('type');
        $search = $request->query('search');
        
        $filteredActivities = $allActivities->filter(function ($activity) use ($type, $search) {
            // Skip all activity types except those explicitly allowed
            $allowedTypes = [];  // Empty array means no activities will be shown
            if (!in_array($activity['type'], $allowedTypes)) {
                return false;
            }
            
            $matches = true;
            
            if ($type && $activity['type'] !== $type) {
                $matches = false;
            }
            
            if ($search) {
                $search = strtolower($search);
                $activityText = strtolower($activity['title'] . ' ' . $activity['description']);
                if (strpos($activityText, $search) === false) {
                    $matches = false;
                }
            }
            
            return $matches;
        });
        
        // Paginate the results
        $perPage = 5;
        $currentPage = request()->get('page', 1);
        $currentItems = $filteredActivities->slice(($currentPage - 1) * $perPage, $perPage);
        
        $activities = new LengthAwarePaginator(
            $currentItems,
            $filteredActivities->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return response()->json([
                'html' => view('activity.partials.activities', compact('activities'))->render(),
                'next' => $activities->nextPageUrl()
            ]);
        }

        return view('activity.index', compact('activities', 'type', 'search'));
    }
    
    public function markAsRead($id)
    {
        // In a real app, you would update the activity as read in the database
        return response()->json(['success' => true]);
    }
}
