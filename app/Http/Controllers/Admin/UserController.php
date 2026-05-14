<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Available user roles
     */
    protected $roles = [
        'user' => 'Standard User',
        'admin' => 'Administrator'
    ];

    /**
     * Available gender options
     */
    protected $genders = [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'prefer_not_to_say' => 'Prefer not to say'
    ];

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::withTrashed()->withCount(['listings', 'sentMessages', 'receivedMessages']);
        
        // Search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && in_array($request->role, ['user', 'admin'])) {
            $query->where('is_admin', $request->role === 'admin');
        }
        
        // Status filter
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'trashed') {
                $query->onlyTrashed();
            }
        }
        
        $users = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.users.index', [
            'users' => $users,
            'roles' => $this->roles,
            'filters' => $request->only(['search', 'role', 'status'])
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create', [
            'genders' => $this->genders,
            'roles' => $this->roles
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin'],
            'gender' => ['nullable', 'string', 'in:' . implode(',', array_keys($this->genders))],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
        ]);

        $user = new User([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['role'] === 'admin',
            'is_active' => $request->has('is_active'),
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'email_verified_at' => now(),
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        // Log the activity
        ActivityLog::log('User created', $user, ['user_id' => $user->id], 'created');

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        $user->loadCount(['listings', 'sentMessages', 'receivedMessages'])
              ->load(['roommateProfile', 'userValidation', 'preference']);
        
        // Get recent activities (using database query log if ActivityLog model doesn't exist)
        $activities = [];
        if (class_exists('App\Models\ActivityLog')) {
            $activities = ActivityLog::where('causer_id', $user->id)
                ->orWhere('subject_id', $user->id)
                ->with('causer')
                ->latest()
                ->take(10)
                ->get();
        }
            
        return view('admin.users.show', [
            'user' => $user,
            'activities' => $activities,
            'genders' => $this->genders,
            'roles' => $this->roles
        ]);
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        return view('admin.users.edit', [
            'user' => $user,
            'genders' => $this->genders,
            'roles' => $this->roles
        ]);
    }
    
    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Debug: Log request data
        \Log::info('Update request data:', [
            'has_file' => $request->hasFile('profile_photo'),
            'file_valid' => $request->hasFile('profile_photo') ? $request->file('profile_photo')->isValid() : false,
            'all_files' => $request->allFiles(),
            'all_input' => $request->except('_token', '_method'),
            'is_active_raw' => $request->input('is_active'),
            'is_active_bool' => (bool) $request->input('is_active', 0),
            'user_current_is_active' => $user->is_active,
        ]);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin'],
            'gender' => ['nullable', 'string', 'in:male,female,other,prefer_not_to_say'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'in:0,1'],
        ]);

        \Log::info('Before update:', [
            'user_is_active' => $user->is_active,
            'new_is_active' => (bool) $request->input('is_active', 0),
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->is_admin = $validated['role'] === 'admin';
        
        // Check if status is changing before we set the new value
        $newIsActive = (bool) $request->input('is_active', 0);
        $wasActivated = !$user->is_active && $newIsActive;
        $wasDeactivated = $user->is_active && !$newIsActive;
        
        $user->is_active = $newIsActive;
        
        $user->gender = $validated['gender'] ?? null;
        $user->date_of_birth = $validated['date_of_birth'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->bio = $validated['bio'] ?? null;

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            // Store the new photo
            $file = $request->file('profile_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');
            
            // Set the correct permissions
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                chmod($fullPath, 0755);
            }
            
            $user->profile_photo_path = $path;
        } elseif ($request->has('remove_photo') && $user->profile_photo_path) {
            // Remove profile photo if requested
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
        }

        $user->save();

        // Debug: Verify the save worked
        $freshUser = User::find($user->id);
        \Log::info('After save verification:', [
            'user_id' => $user->id,
            'saved_is_active' => $user->is_active,
            'fresh_is_active' => $freshUser->is_active,
            'wasActivated' => $wasActivated,
            'wasDeactivated' => $wasDeactivated,
        ]);

        // Prepare success message based on status change
        if ($wasActivated) {
            $message = 'User has been activated successfully. They can now log in.';
        } elseif ($wasDeactivated) {
            $message = 'User has been deactivated successfully. They will be prevented from logging in.';
        } else {
            $message = 'User updated successfully.';
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.'
            ], 403);
        }

        try {
            // Delete associated files and data
            $this->cleanupUserData($user);
            
            // Permanently delete the user (not soft delete)
            $user->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'User has been permanently deleted.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up all user-associated data and files
     */
    private function cleanupUserData(User $user)
    {
        // Delete avatar file if exists
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }
        
        // Delete profile photo if exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        
        // Delete ID card files if exists
        if ($user->id_card_front && Storage::disk('public')->exists($user->id_card_front)) {
            Storage::disk('public')->delete($user->id_card_front);
        }
        
        if ($user->id_card_back && Storage::disk('public')->exists($user->id_card_back)) {
            Storage::disk('public')->delete($user->id_card_back);
        }
        
        // Delete roommate profile if exists
        if ($user->roommateProfile) {
            $user->roommateProfile->delete();
        }
        
        // Delete user validation if exists
        if ($user->userValidation) {
            $user->userValidation->delete();
        }
        
        // Delete messages sent by this user
        $user->sentMessages()->delete();
        
        // Delete messages received by this user
        $user->receivedMessages()->delete();
        
        // Delete listings if any
        $user->listings()->delete();
        
        // Log the deletion for audit purposes
        \Log::info('User permanently deleted by admin', [
            'deleted_user_id' => $user->id,
            'deleted_user_email' => $user->email,
            'deleted_by_admin' => auth()->id(),
            'deleted_at' => now()
        ]);
    }
    
    /**
     * Restore the specified user.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User has been restored.');
    }
    
    /**
     * Permanently delete the specified user.
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Delete profile photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        
        // Permanently delete the user
        $user->forceDelete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User has been permanently deleted.');
    }
    
    /**
     * Toggle user's active status.
     */
    public function toggleStatus($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent deactivating your own account
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.'
            ], 403);
        }
        
        $user->update([
            'is_active' => !$user->is_active
        ]);
        
        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => $user->is_active ? 'User has been activated.' : 'User has been deactivated.'
        ]);
    }
    
    /**
     * Display ID verification page.
     */
    public function idVerification()
    {
        $perPage = 20;
        $page = max(1, (int) request()->get('page', 1));

        $pending = User::query()
            ->where('verification_status', 'pending')
            ->whereNotNull('id_card_front')
            ->whereNotNull('id_card_back')
            ->with(['roommateProfile', 'preferences', 'userValidation'])
            ->orderByDesc('updated_at')
            ->get();

        $ready = $pending->filter(fn (User $user) => $user->isProfileComplete())->values();
        $total = $ready->count();
        $slice = $ready->slice(($page - 1) * $perPage, $perPage)->values();

        $users = new LengthAwarePaginator(
            $slice,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('admin.users.id-verification', compact('users'));
    }
    
    /**
     * Display pending approvals page.
     */
    public function pendingApprovals()
    {
        $users = User::where(function ($query) {
                $query->where('is_approved', false)
                      ->orWhereNull('is_approved');
            })
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(20);
            
        return view('admin.users.pending-approvals', compact('users'));
    }
    
    /**
     * Verify user's ID.
     */
    public function verifyId($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        if ($user->id_card_front && $user->verification_status === 'pending') {
            $user->update([
                'verification_status' => 'approved',
            ]);

            $user->userValidation?->update([
                'status' => 'approved',
                'verified_at' => now(),
                'rejection_reason' => null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'ID has been verified successfully.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No pending ID verification found for this user.'
        ]);
    }
    
    /**
     * Reject user's ID verification.
     */
    public function rejectId(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);
        
        if ($user->id_card_front && $user->verification_status === 'pending') {
            $user->update([
                'verification_status' => 'rejected',
            ]);

            $user->userValidation?->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'verified_at' => null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'ID verification has been rejected.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No pending ID verification found for this user.'
        ]);
    }

    /**
     * Approve user account.
     */
    public function approve($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->update(['is_approved' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'User account has been approved.'
        ]);
    }
}
