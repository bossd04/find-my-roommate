<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $users = \App\Models\User::with('preferences')
            ->withLastLogin()
            ->orderBy('last_login_at', 'desc')
            ->get();
            
        $adminCount = $users->where('role', 'admin')->count();
        $userCount = $users->count() - $adminCount;
        
        return view('admin.dashboard.index', [
            'users' => $users,
            'adminCount' => $adminCount,
            'userCount' => $userCount
        ]);
    }
}
