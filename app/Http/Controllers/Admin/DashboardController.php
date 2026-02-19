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
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // User statistics
        $userCount = User::count();
        $activeUserCount = User::where('last_login_at', '>=', now()->subDays(30))->count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
        
        // Listing statistics
        $listingCount = Listing::count();
        $newListingsThisMonth = Listing::where('created_at', '>=', now()->startOfMonth())->count();
        
        // Message statistics
        $messageCount = Message::count();
        $unreadMessagesCount = Message::where('read_at', null)->count();
        
        // Recent activities
        $recentUsers = User::latest()->take(5)->get();
        $recentListings = Listing::with('images')
            ->latest()
            ->take(5)
            ->get();
            
        // System information
        $systemInfo = [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'server_software' => request()->server('SERVER_SOFTWARE'),
            'database_connection' => config('database.default'),
            'database_name' => config('database.connections.'.config('database.default').'.database'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ];
        
        return view('admin.dashboard', [
            // Statistics
            'userCount' => $userCount,
            'activeUserCount' => $activeUserCount,
            'newUsersThisMonth' => $newUsersThisMonth,
            'listingCount' => $listingCount,
            'newListingsThisMonth' => $newListingsThisMonth,
            'messageCount' => $messageCount,
            'unreadMessagesCount' => $unreadMessagesCount,
            
            // Recent data
            'recentUsers' => $recentUsers,
            'recentListings' => $recentListings,
            
            // System info
            'systemInfo' => $systemInfo,
            
            // Current admin
            'currentAdmin' => Auth::guard('admin')->user()
        ]);
    }
}
