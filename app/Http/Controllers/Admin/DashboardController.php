<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use App\Models\Listing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('admin');
    }

    /**
     * Show admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $stats = $this->getBasicStatistics();
        $analytics = $this->getPerformanceAnalytics();
        $pending = $this->getPendingStatistics();
        $recent = $this->getRecentActivity();
        $system = $this->getSystemInfo();
        $chart = $this->getRegistrationChart();
        
        return view('admin.dashboard', array_merge($stats, $analytics, $pending, $recent, $system, [
            'registrationChart' => $chart
        ]));
    }

    /**
     * Get basic statistics for dashboard.
     */
    private function getBasicStatistics(): array
    {
        return [
            'userCount' => User::count(),
            'activeUserCount' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
            'newUsersThisMonth' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'listingCount' => Listing::count(),
            'newListingsThisMonth' => Listing::where('created_at', '>=', now()->startOfMonth())->count(),
            'messageCount' => Message::count(),
            'unreadMessagesCount' => Message::where('read_at', null)->count(),
        ];
    }

    /**
     * Get performance analytics for dashboard.
     */
    private function getPerformanceAnalytics(): array
    {
        $previousMonthStart = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        
        $usersLastMonth = User::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $usersCurrentMonth = User::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        
        $listingsLastMonth = Listing::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $listingsCurrentMonth = Listing::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        
        $userGrowth = $usersLastMonth > 0 ? (($usersCurrentMonth - $usersLastMonth) / $usersLastMonth) * 100 : ($usersCurrentMonth > 0 ? 100 : 0);
        $listingGrowth = $listingsLastMonth > 0 ? (($listingsCurrentMonth - $listingsLastMonth) / $listingsLastMonth) * 100 : ($listingsCurrentMonth > 0 ? 100 : 0);
        
        return [
            'userGrowth' => round($userGrowth, 1),
            'listingGrowth' => round($listingGrowth, 1),
            'userGrowthData' => $this->getUserGrowthData(),
            'dailyRegistrationRate' => $this->getDailyRegistrationRate(),
            'retentionRate' => $this->getRetentionRate(),
            'peakRegistrationDay' => $this->getPeakRegistrationDay(),
            'monthlyTrends' => $this->getMonthlyTrends(),
            'avgListingsPerUser' => $this->getAvgListingsPerUser(),
            'avgMessagesPerUser' => $this->getAvgMessagesPerUser(),
        ];
    }

    /**
     * Get pending statistics for dashboard.
     */
    private function getPendingStatistics(): array
    {
        return [
            'pendingApprovalsCount' => User::where(function($q) {
                $q->where('is_approved', false)->orWhereNull('is_approved');
            })->where('is_admin', false)->count(),
            'pendingIdVerificationsCount' => User::whereHas('userValidation', function ($query) {
                $query->where('status', 'pending')->orWhereNull('status');
            })->count(),
        ];
    }

    /**
     * Get recent activity for dashboard.
     */
    private function getRecentActivity(): array
    {
        return [
            'recentUsers' => User::latest()->take(5)->get(),
            'recentListings' => Listing::with('images')->latest()->take(5)->get(),
        ];
    }

    /**
     * Get system information for dashboard.
     */
    private function getSystemInfo(): array
    {
        return [
            'systemInfo' => [
                'laravel_version' => app()->version(),
                'php_version' => phpversion(),
                'server_software' => request()->server('SERVER_SOFTWARE'),
                'database_connection' => config('database.default'),
                'database_name' => config('database.connections.'.config('database.default').'.database'),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size')
            ],
            'currentAdmin' => Auth::guard('admin')->user()
        ];
    }

    // Helper methods for analytics
    private function getUserGrowthData(): array
    {
        $userGrowthData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $userGrowthData[] = [
                'date' => $date->format('M j'),
                'new_users' => User::whereDate('created_at', $date->toDateString())->count(),
                'active_users' => User::whereDate('last_login_at', $date->toDateString())->count(),
                'total_users' => User::where('created_at', '<=', $date->endOfDay())->count()
            ];
        }
        return $userGrowthData;
    }

    private function getDailyRegistrationRate(): float
    {
        $userCount = User::count();
        if ($userCount === 0) {
            return 0;
        }
        
        try {
            $oldestUser = User::oldest()->first();
            if (!$oldestUser) {
                return 0;
            }
            
            $days = max(1, now()->diffInDays($oldestUser->created_at));
            return round($userCount / $days, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getRetentionRate(): float
    {
        $userCount = User::count();
        $activeUserCount = User::where('last_login_at', '>=', now()->subDays(30))->count();
        return $userCount > 0 ? round(($activeUserCount / $userCount) * 100, 1) : 0;
    }

    private function getPeakRegistrationDay()
    {
        try {
            if (config('database.default') === 'sqlite') {
                return User::selectRaw('strftime("%w", created_at) as day_of_week, COUNT(*) as count')
                    ->groupBy('strftime("%w", created_at)')
                    ->orderBy('count', 'desc')
                    ->first();
            } else {
                return User::selectRaw('DAYOFWEEK(created_at) as day_of_week, COUNT(*) as count')
                    ->groupBy('DAYOFWEEK(created_at)')
                    ->orderBy('count', 'desc')
                    ->first();
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getMonthlyTrends(): array
    {
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            $monthlyTrends[] = [
                'month' => $monthStart->format('M'),
                'registrations' => User::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'listings' => Listing::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'messages' => Message::whereBetween('created_at', [$monthStart, $monthEnd])->count()
            ];
        }
        return $monthlyTrends;
    }

    private function getAvgListingsPerUser(): float
    {
        $userCount = User::count();
        $listingCount = Listing::count();
        return $userCount > 0 ? round($listingCount / $userCount, 1) : 0;
    }

    private function getAvgMessagesPerUser(): float
    {
        $userCount = User::count();
        $messageCount = Message::count();
        return $userCount > 0 ? round($messageCount / $userCount, 1) : 0;
    }

    /**
     * Get registration chart data for the last 7 days.
     */
    private function getRegistrationChart(): array
    {
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $data[] = User::whereDate('created_at', $date->toDateString())->count();
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
