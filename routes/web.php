<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// CSRF Token Refresh
Route::get('/refresh-csrf', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
})->name('refresh.csrf');

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Pages
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

// Auth Routes
Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');
    
    // User Profile - Make profiles viewable by anyone, but handle sensitive data in the controller
    Route::get('/profile/{user}', [ProfileController::class, 'show'])
        ->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'updateDetails'])->name('profile.update');
    Route::patch('/profile/details', [ProfileController::class, 'updateDetails'])->name('profile.update.details');

    // Listings
    Route::get('/listings', [\App\Http\Controllers\ListingController::class, 'index'])->name('listings.index');

    // Roommates
    Route::get('/roommates', [\App\Http\Controllers\RoommateController::class, 'index'])->name('roommates.index');

    // Activity Feed
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');
    Route::post('/activity/{id}/mark-as-read', [\App\Http\Controllers\ActivityController::class, 'markAsRead'])->name('activity.mark-as-read');

    // Notifications
    Route::prefix('notifications')->group(function () {
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
        Route::post('/{user}/like', function (\App\Models\User $user) {
            $existingMatch = \App\Models\RoommateMatch::where('user_id', auth()->id())
                ->where('matched_user_id', $user->id)
                ->first();
                
            if (!$existingMatch) {
                // Check if the other user has already liked this user
                $mutualMatch = \App\Models\RoommateMatch::where('user_id', $user->id)
                    ->where('matched_user_id', auth()->id())
                    ->where('user_action', 'liked')
                    ->first();
                
                $match = auth()->user()->matches()->create([
                    'matched_user_id' => $user->id,
                    'user_action' => 'liked',
                    'status' => $mutualMatch ? 'accepted' : 'pending',
                    'is_mutual' => (bool)$mutualMatch
                ]);
                
                if ($mutualMatch) {
                    $mutualMatch->update([
                        'status' => 'accepted',
                        'is_mutual' => true
                    ]);
                }
                
                return back()->with('status', $mutualMatch ? 'It\'s a match!' : 'Liked!');
            } else if ($existingMatch->user_action !== 'liked') {
                $existingMatch->update([
                    'user_action' => 'liked',
                    'status' => 'pending',
                    'updated_at' => now()
                ]);
                
                // Check if the other user has already liked this user
                $mutualMatch = \App\Models\RoommateMatch::where('user_id', $user->id)
                    ->where('matched_user_id', auth()->id())
                    ->where('user_action', 'liked')
                    ->first();
                    
                if ($mutualMatch) {
                    $existingMatch->update([
                        'status' => 'accepted',
                        'is_mutual' => true
                    ]);
                    
                    $mutualMatch->update([
                        'status' => 'accepted',
                        'is_mutual' => true
                    ]);
                    
                    return back()->with('status', 'It\'s a match!');
                }
                
                return back()->with('status', 'Liked!');
            }
            
            return back()->with('status', 'Already liked this user');
        })->name('matches.like');
        
        // Accept a match
        Route::post('/{match}/accept', function (\App\Models\RoommateMatch $match) {
            // Verify the current user is the one being matched with
            if ($match->matched_user_id !== auth()->id()) {
                return back()->with('error', 'Unauthorized action');
            }
            
            $match->update([
                'status' => 'accepted',
                'is_mutual' => true
            ]);
            
            // Update the other side of the match if it exists
            $mutualMatch = \App\Models\RoommateMatch::where('user_id', $match->matched_user_id)
                ->where('matched_user_id', $match->user_id)
                ->first();
                
            if ($mutualMatch) {
                $mutualMatch->update([
                    'status' => 'accepted',
                    'is_mutual' => true
                ]);
            }
            
            return back()->with('status', 'Match accepted!');
        })->name('matches.accept');
        
        // Reject a match
        Route::post('/{match}/reject', function (\App\Models\RoommateMatch $match) {
            // Verify the current user is the one being matched with
            if ($match->matched_user_id !== auth()->id()) {
                return back()->with('error', 'Unauthorized action');
            }
            
            $match->update([
                'status' => 'rejected'
            ]);
            
            return back()->with('status', 'Match rejected');
        })->name('matches.reject');
        
        Route::post('/{user}/dislike', function (\App\Models\User $user) {
            $existingMatch = \App\Models\RoommateMatch::where('user_id', auth()->id())
                ->where('matched_user_id', $user->id)
                ->first();
                
            if (!$existingMatch) {
                auth()->user()->matches()->create([
                    'matched_user_id' => $user->id,
                    'user_action' => 'disliked',
                    'status' => 'rejected'
                ]);
            } else if ($existingMatch->user_action !== 'disliked') {
                $existingMatch->update([
                    'user_action' => 'disliked',
                    'status' => 'rejected',
                    'updated_at' => now()
                ]);
            }
            return back()->with('status', 'Disliked');
        })->name('matches.dislike');
        
        // Accept a match
        Route::post('/{match}/accept', function (\App\Models\RoommateMatch $match) {
            // Ensure the current user is the recipient of the match
            if ($match->matched_user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }
            
            // Update the match status to accepted, but keep user_action as 'liked'
            $match->update([
                'status' => 'accepted',
                'user_action' => 'liked',
                'is_mutual' => true, // Since both users have now liked each other
                'updated_at' => now()
            ]);
            
            // Create a notification for the other user
            $match->user->notify(new \App\Notifications\MatchAccepted($match->matchedUser));
            
            return back()->with('status', 'Match accepted!');
        })->name('matches.accept');
        
        // Reject a match
        Route::delete('/{match}/reject', function (\App\Models\RoommateMatch $match) {
            // Ensure the current user is the recipient of the match
            if ($match->matched_user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }
            
            $match->update([
                'status' => 'rejected',
                'user_action' => 'rejected',
                'updated_at' => now()
            ]);
            
            return back()->with('status', 'Match rejected');
        })->name('matches.reject');
    });

    // Messages
    Route::prefix('messages')->group(function () {
        Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
        Route::post('/{user}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        Route::post('/{message}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');
        Route::delete('/{user}/clear', [\App\Http\Controllers\MessageController::class, 'clearChat'])->name('messages.clear');
    });

    // User management routes
    Route::prefix('users')->group(function () {
        Route::post('/{user}/block', [\App\Http\Controllers\UserController::class, 'block'])->name('users.block');
        Route::post('/{user}/report', [\App\Http\Controllers\UserController::class, 'report'])->name('users.report');
        Route::post('/{user}/restrict', [\App\Http\Controllers\UserController::class, 'restrict'])->name('users.restrict');
    });

    // Activity
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');

    // Listings
    Route::get('/listings/create', [\App\Http\Controllers\ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [\App\Http\Controllers\ListingController::class, 'store'])->name('listings.store');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Authentication Routes
require __DIR__.'/auth.php';

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

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::patch('/profile/details', [ProfileController::class, 'updateDetails'])
    ->name('profile.update.details')
    ->middleware(['auth']);