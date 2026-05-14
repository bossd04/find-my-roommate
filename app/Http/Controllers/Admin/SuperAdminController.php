<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Message;
use App\Models\ActivityLog;
use App\Models\AccountDeletionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin', 'superadmin']);
    }

    /**
     * Show super admin dashboard.
     */
    public function dashboard(): \Illuminate\View\View
    {
        $stats = [
            'totalAdmins' => User::where('is_admin', true)->count(),
            'totalSuperAdmins' => User::where('is_superadmin', true)->count(),
            'totalUsers' => User::where('is_admin', false)->count(),
            'totalListings' => Listing::count(),
            'totalMessages' => Message::count(),
            'recentActivities' => ActivityLog::with('causer')
                ->latest()
                ->take(10)
                ->get(),
            'pendingDeletionRequests' => AccountDeletionRequest::where('status', 'pending')
                ->with('user')
                ->take(5)
                ->get(),
            'pendingDeletionCount' => AccountDeletionRequest::where('status', 'pending')->count(),
        ];

        return view('admin.superadmin.dashboard', $stats);
    }

    /**
     * List all admin users.
     */
    public function admins(): \Illuminate\View\View
    {
        $admins = User::where('is_admin', true)
            ->orderBy('is_superadmin', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.superadmin.admins', compact('admins'));
    }

    /**
     * Show form to create a new admin.
     */
    public function createAdmin(): \Illuminate\View\View
    {
        return view('admin.superadmin.create-admin');
    }

    /**
     * Store a new admin user.
     */
    public function storeAdmin(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_superadmin' => ['boolean'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'is_admin' => true,
            'is_superadmin' => $validated['is_superadmin'] ?? false,
            'is_active' => true,
            'is_approved' => true,
        ]);

        // Log the activity
        ActivityLog::create([
            'log_name' => 'superadmin',
            'description' => "Created new admin: {$user->fullName()}",
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth('admin')->id(),
            'properties' => ['admin_email' => $user->email],
        ]);

        return redirect()->route('admin.superadmin.admins')
            ->with('success', 'Admin user created successfully.');
    }

    /**
     * Toggle admin status (promote/demote).
     */
    public function toggleAdminStatus(User $user): \Illuminate\Http\RedirectResponse
    {
        // Prevent self-demotion
        if ($user->id === auth('admin')->id() && $user->is_superadmin) {
            return back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'promoted to admin' : 'demoted from admin';

        ActivityLog::create([
            'log_name' => 'superadmin',
            'description' => "{$user->fullName()} was {$status}",
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth('admin')->id(),
        ]);

        return back()->with('success', "User has been {$status}.");
    }

    /**
     * Toggle superadmin status.
     */
    public function toggleSuperAdminStatus(User $user): \Illuminate\Http\RedirectResponse
    {
        // Prevent self-demotion
        if ($user->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot remove your own superadmin privileges.');
        }

        // Must be admin first
        if (!$user->is_admin) {
            return back()->with('error', 'User must be an admin before becoming a superadmin.');
        }

        $user->update(['is_superadmin' => !$user->is_superadmin]);

        $status = $user->is_superadmin ? 'promoted to superadmin' : 'demoted from superadmin';

        ActivityLog::create([
            'log_name' => 'superadmin',
            'description' => "{$user->fullName()} was {$status}",
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth('admin')->id(),
        ]);

        return back()->with('success', "User has been {$status}.");
    }

    /**
     * Delete an admin user.
     */
    public function destroyAdmin(User $user): \Illuminate\Http\RedirectResponse
    {
        // Prevent self-deletion
        if ($user->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->fullName();
        $user->delete();

        ActivityLog::create([
            'log_name' => 'superadmin',
            'description' => "Deleted admin: {$name}",
            'causer_type' => User::class,
            'causer_id' => auth('admin')->id(),
        ]);

        return back()->with('success', 'Admin user deleted successfully.');
    }

    /**
     * List all account deletion requests.
     */
    public function deletionRequests(): \Illuminate\View\View
    {
        $pendingRequests = AccountDeletionRequest::where('status', 'pending')
            ->with('user')
            ->latest('requested_at')
            ->paginate(10, ['*'], 'pending_page');

        $processedRequests = AccountDeletionRequest::whereIn('status', ['approved', 'rejected', 'cancelled'])
            ->with(['user', 'processor'])
            ->latest('processed_at')
            ->paginate(10, ['*'], 'processed_page');

        return view('admin.superadmin.deletion-requests', compact('pendingRequests', 'processedRequests'));
    }

    /**
     * Approve an account deletion request.
     */
    public function approveDeletionRequest(AccountDeletionRequest $request): \Illuminate\Http\RedirectResponse
    {
        if (!$request->isPending()) {
            return back()->with('error', 'This request has already been processed.');
        }

        $user = $request->user;
        $userName = $user->fullName();

        // Approve the deletion request
        $request->approve(auth('admin')->id(), 'Account deletion approved by admin');

        // Soft delete the user
        $user->delete();

        // Log the activity
        ActivityLog::create([
            'log_name' => 'superadmin',
            'description' => "Approved account deletion for: {$userName}",
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth('admin')->id(),
        ]);

        return back()->with('success', "Account deletion request for {$userName} has been approved and the user has been deleted.");
    }

    /**
     * Reject an account deletion request.
     */
    public function rejectDeletionRequest(Request $request, AccountDeletionRequest $deletionRequest): \Illuminate\Http\RedirectResponse
    {
        if (!$deletionRequest->isPending()) {
            return back()->with('error', 'This request has already been processed.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $userName = $deletionRequest->user->fullName();

        // Reject the deletion request
        $deletionRequest->reject(auth('admin')->id(), $validated['admin_notes'] ?? 'Request rejected by admin');

        // Log the activity
        ActivityLog::create([
            'log_name' => 'superadmin',
            'description' => "Rejected account deletion request for: {$userName}",
            'subject_type' => User::class,
            'subject_id' => $deletionRequest->user_id,
            'causer_type' => User::class,
            'causer_id' => auth('admin')->id(),
        ]);

        return back()->with('success', "Account deletion request for {$userName} has been rejected.");
    }
}
