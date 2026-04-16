<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// CSRF Token Refresh
Route::get('/refresh-csrf', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
})->name('refresh.csrf');

// Serve message images directly (fixes Windows symlink issues)
Route::get('/message-image/{filename}', function ($filename) {
    // Try multiple path formats
    $paths = [
        storage_path('app/private/public/messages/' . $filename),  // Actual location
        storage_path('app/public/messages/' . $filename),
        storage_path('app/public/' . $filename),
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            return response()->file($path);
        }
    }
    
    abort(404);
})->name('message.image');

// Serve profile photos directly (fixes Windows symlink issues)
Route::get('/profile-photo/{filename}', function ($filename) {
    $path = storage_path('app/public/profile-photos/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('profile.photo.serve');

// Serve avatars directly (fixes Windows symlink issues)
Route::get('/avatar/{filename}', function ($filename) {
    $paths = [
        storage_path('app/public/avatars/' . $filename),
        storage_path('app/private/public/avatars/' . $filename),
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            return response()->file($path);
        }
    }
    
    abort(404);
})->name('avatar.serve');

// Serve listing images directly (fixes Windows symlink issues)
Route::get('/listing-image/{filename}', function ($filename) {
    $paths = [
        storage_path('app/public/listings/' . $filename),
        storage_path('app/private/public/listings/' . $filename),
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            return response()->file($path);
        }
    }
    
    abort(404);
})->name('listing.image.serve');

// Serve ID card images directly (fixes Windows symlink issues)
Route::get('/id-card-image/{path}', function ($path) {
    // URL decode the path
    $path = urldecode($path);
    
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    
    // Try the exact path first (e.g., "id_cards/filename.jpg")
    if ($disk->exists($path)) {
        $fullPath = $disk->path($path);
        $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=86400'
        ]);
    }
    
    // If path contains subdirectory, also try extracting just the filename
    $filename = basename($path);
    $subDir = dirname($path);
    
    // Common storage locations to check
    $locations = [
        $path,                              // Original: "id_cards/filename.jpg"
        'id_cards/' . $filename,            // Just in case
        'validations/' . $filename,          // Alternative location
        $filename,                          // Root of public storage
    ];
    
    foreach ($locations as $tryPath) {
        if ($disk->exists($tryPath)) {
            $fullPath = $disk->path($tryPath);
            $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400'
            ]);
        }
    }
    
    // Fallback: try direct file system paths
    $directPaths = [
        storage_path('app/public/' . $path),
        storage_path('app/public/id_cards/' . $filename),
        storage_path('app/public/validations/' . $filename),
        storage_path('app/private/public/' . $path),
        storage_path('app/private/public/id_cards/' . $filename),
    ];
    
    foreach ($directPaths as $fullPath) {
        if (file_exists($fullPath) && is_readable($fullPath)) {
            $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400'
            ]);
        }
    }
    
    // Debug logging
    \Log::warning('ID card image not found', [
        'requested_path' => $path,
        'filename' => $filename,
        'tried_storage_paths' => $locations,
        'tried_direct_paths' => $directPaths,
    ]);
    
    abort(404);
})->name('id.card.serve')->where('path', '.*');

// Clear profile session flag
Route::get('/clear-profile-session', function () {
    session()->forget('from_profile_edit');
    session()->forget('profile_redirected');
    return response()->json(['success' => true]);
})->name('clear.profile.session');

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Pages
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Deactivated Account Page (accessible to everyone)
Route::get('/account-deactivated', function () {
    return view('auth.deactivated');
})->name('account.deactivated');

// Auth Routes
Route::middleware(['auth', 'check.user.active'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])
        ->name('dashboard');
    
    // User Profile - Make profiles viewable by anyone, but handle sensitive data in the controller
    Route::get('/profile/{user}', [ProfileController::class, 'show'])
        ->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateDetails'])->name('profile.update');
    Route::patch('/profile/details', [ProfileController::class, 'updateDetails'])->name('profile.update.details');
    
    // Profile Section Updates
    Route::post('/profile/update-personal', [ProfileController::class, 'updatePersonal'])->name('profile.update.personal');
    Route::post('/profile/update-basic-information', [ProfileController::class, 'updateBasicInformation'])->name('profile.update.basic.information');
    Route::post('/profile/update-location-information', [ProfileController::class, 'updateLocationInformation'])->name('profile.update.location.information');
    Route::post('/profile/update-education', [ProfileController::class, 'updateEducation'])->name('profile.update.education');
    Route::post('/profile/update-lifestyle', [ProfileController::class, 'updateLifestyle'])->name('profile.update.lifestyle');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
    Route::post('/profile/update-id-verification', [ProfileController::class, 'updateIdVerification'])->name('profile.update.id.verification');
    Route::post('/profile/update-roommate-preferences', [ProfileController::class, 'updateRoommatePreferences'])->name('profile.update.roommate.preferences');

    // Listings
    Route::get('/listings', [\App\Http\Controllers\ListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/create', [\App\Http\Controllers\ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [\App\Http\Controllers\ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}', [\App\Http\Controllers\ListingController::class, 'show'])->name('listings.show');

    // Roommates
    Route::get('/roommates', [\App\Http\Controllers\RoommateController::class, 'index'])->name('roommates.index');
    Route::get('/roommates/search', [\App\Http\Controllers\RoommateController::class, 'searchByLocation'])->name('roommates.search');
    Route::get('/roommates/{user}', [\App\Http\Controllers\RoommateController::class, 'show'])->name('roommates.show');

    // Activity Feed
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');
    Route::post('/activity/{id}/mark-as-read', [\App\Http\Controllers\ActivityController::class, 'markAsRead'])->name('activity.mark-as-read');

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    });

    // Matches
    Route::prefix('matches')->group(function () {
        Route::get('/', [\App\Http\Controllers\MatchController::class, 'index'])->name('matches.index');
        
        // API Routes for match actions
        Route::post('/', [\App\Http\Controllers\MatchController::class, 'store'])->name('matches.store');
        Route::put('/{match}', [\App\Http\Controllers\MatchController::class, 'update'])->name('matches.update');
        
        // Match actions
        Route::post('/{user}/like', [\App\Http\Controllers\MatchController::class, 'like'])->name('matches.like');
        Route::post('/{user}/dislike', [\App\Http\Controllers\MatchController::class, 'dislike'])->name('matches.dislike');
        Route::match(['post', 'put'], '/{match}/accept', [\App\Http\Controllers\MatchController::class, 'accept'])->name('matches.accept');
        Route::delete('/{match}/reject', [\App\Http\Controllers\MatchController::class, 'reject'])->name('matches.reject');
    });

    // Messages
    Route::prefix('messages')->group(function () {
        Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
        Route::post('/{user}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        Route::post('/{message}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');
        Route::delete('/{user}/clear', [\App\Http\Controllers\MessageController::class, 'clearChat'])->name('messages.clear');
        Route::get('/{user}/search', [\App\Http\Controllers\MessageController::class, 'searchMessages'])->name('messages.search');
        Route::post('/{user}/mute', [\App\Http\Controllers\MessageController::class, 'muteConversation'])->name('messages.mute');
        Route::post('/{user}/unmute', [\App\Http\Controllers\MessageController::class, 'unmuteConversation'])->name('messages.unmute');
        Route::get('/{user}/muted', [\App\Http\Controllers\MessageController::class, 'isMuted'])->name('messages.is-muted');
    });

    // Calls
    Route::prefix('calls')->group(function () {
        Route::post('/{user}/initiate', [\App\Http\Controllers\CallController::class, 'initiate'])->name('calls.initiate');
        Route::get('/incoming', [\App\Http\Controllers\CallController::class, 'checkIncoming'])->name('calls.incoming');
        Route::post('/force-end-all', [\App\Http\Controllers\CallController::class, 'forceEndAll'])->name('calls.force-end-all');
        Route::get('/{call}', [\App\Http\Controllers\CallController::class, 'show'])->name('calls.show');
        Route::get('/{call}/status', [\App\Http\Controllers\CallController::class, 'status'])->name('calls.status');
        Route::post('/{call}/accept', [\App\Http\Controllers\CallController::class, 'accept'])->name('calls.accept');
        Route::post('/{call}/decline', [\App\Http\Controllers\CallController::class, 'decline'])->name('calls.decline');
        Route::post('/{call}/end', [\App\Http\Controllers\CallController::class, 'end'])->name('calls.end');
        Route::post('/{call}/ice-candidate', [\App\Http\Controllers\CallController::class, 'sendIceCandidate'])->name('calls.ice-candidate');
        Route::get('/{call}/ice-candidate', [\App\Http\Controllers\CallController::class, 'getIceCandidate'])->name('calls.get-ice-candidate');
        Route::post('/{call}/offer', [\App\Http\Controllers\CallController::class, 'updateOffer'])->name('calls.update-offer');
    });

    // User management routes
    Route::prefix('users')->group(function () {
        Route::post('/{user}/block', [\App\Http\Controllers\UserController::class, 'block'])->name('users.block');
        Route::post('/{user}/unblock', [\App\Http\Controllers\UserController::class, 'unblock'])->name('users.unblock');
        Route::post('/{user}/report', [\App\Http\Controllers\UserController::class, 'report'])->name('users.report');
        Route::post('/{user}/restrict', [\App\Http\Controllers\UserController::class, 'restrict'])->name('users.restrict');
    });

    // Activity
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile', [ProfileController::class, 'updateDetails'])
        ->name('profile.update')
        ->middleware(['auth']);
    
    // ID Validation Routes
    Route::get('/validation/create', [\App\Http\Controllers\UserValidationController::class, 'create'])->name('validation.create');
    Route::post('/validation', [\App\Http\Controllers\UserValidationController::class, 'store'])->name('validation.store');
    
    // Department Courses API
    Route::get('/departments/{department}/courses', [DepartmentController::class, 'getCoursesByDepartment'])
        ->name('departments.courses');
});

// Admin Routes
require __DIR__.'/admin.php';

// Company Routes
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/careers', function () {
    return view('pages.careers');
})->name('careers');

Route::get('/press', function () {
    return view('pages.press');
})->name('press');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

// Support Routes
Route::get('/help', function () {
    return view('pages.help');
})->name('help');

Route::get('/safety-tips', function () {
    return view('pages.safety-tips');
})->name('safety');

Route::get('/community-guidelines', function () {
    return view('pages.guidelines');
})->name('guidelines');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/contact-support', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.support');
Route::post('/contact-support', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');
Route::post('/contact-admin', [\App\Http\Controllers\ContactController::class, 'contactAdmin'])->name('contact.admin')->middleware('auth');

Route::patch('/profile/information', [ProfileController::class, 'updateProfileInformation'])
    ->name('profile.update.information')
    ->middleware(['auth']);

Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
    ->name('profile.avatar.update')
    ->middleware(['auth']);

Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])
    ->name('profile.avatar.remove')
    ->middleware(['auth']);

Route::post('/profile/completion/clear', [ProfileController::class, 'clearCompletionFlag'])
    ->name('profile.completion.clear')
    ->middleware(['auth']);

Route::get('/test-dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    return view('dashboard', ['user' => auth()->user()]);
})->middleware(['auth'])->name('test.dashboard');

Route::get('/clear-redirect-loop', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $user = auth()->user();
    
    // Clear all redirect-related flags
    $user->update(['profile_completed_redirect' => false]);
    session()->forget('from_profile_edit');
    
    return redirect()->route('profile.edit')->with('success', 'Redirect loop cleared. You can now access your profile.');
})->middleware(['auth']);

// Temporary debug route for profile completion
Route::get('/debug-profile-completion', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $user = auth()->user();
    $controller = new ProfileController();
    
    // Use reflection to access private method
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('calculateCompletionPercentage');
    $method->setAccessible(true);
    
    $percentage = $method->invoke($controller, $user);
    
    return response()->json([
        'user_id' => $user->id,
        'completion_percentage' => $percentage,
        'user_data' => [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'date_of_birth' => $user->date_of_birth,
            'bio' => $user->bio,
            'university' => $user->university,
            'course' => $user->course,
            'year_level' => $user->year_level,
            'budget_min' => $user->budget_min,
            'budget_max' => $user->budget_max,
            'hobbies' => $user->hobbies,
            'is_verified' => $user->isVerified(),
            'verification_status' => $user->verification_status,
        ],
        'profile_data' => $user->roommateProfile ? [
            'apartment_location' => $user->roommateProfile->apartment_location,
            'cleanliness_level' => $user->roommateProfile->cleanliness_level,
            'sleep_pattern' => $user->roommateProfile->sleep_pattern,
            'study_habit' => $user->roommateProfile->study_habit,
            'noise_tolerance' => $user->roommateProfile->noise_tolerance,
        ] : null,
        'validation_data' => $user->userValidation ? [
            'status' => $user->userValidation->status,
            'id_card_front' => $user->userValidation->id_card_front,
            'id_card_back' => $user->userValidation->id_card_back,
        ] : null
    ]);
})->middleware(['auth']);

Route::patch('/profile/details', [ProfileController::class, 'updateDetails'])
    ->name('profile.update.details')
    ->middleware(['auth']);

// Chatbot Routes
Route::get('/chatbot', [\App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index')->middleware('auth');
Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'send'])->middleware('auth');

// Preferences Routes
Route::get('/preferences', [\App\Http\Controllers\RoommatePreferenceController::class, 'index'])->name('preferences.index')->middleware('auth');
Route::get('/preferences/{preferences}/edit', [\App\Http\Controllers\RoommatePreferenceController::class, 'edit'])->name('preferences.edit')->middleware('auth');
Route::put('/preferences/{preferences}', [\App\Http\Controllers\RoommatePreferenceController::class, 'update'])->name('preferences.update')->middleware('auth');

// Debug routes
include __DIR__.'/debug.php';
include __DIR__.'/test_id_images.php';