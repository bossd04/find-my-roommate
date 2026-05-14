@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $profile = $user ? $user->roommateProfile : null;
    
    // Use new isVerified() method
    $isVerified = $user && $user->isVerified();
    
    // Check if user has pending verification
    $hasPendingVerification = $user && $user->userValidation && $user->userValidation->status === 'pending';
    
    // ID verification is the primary unlock mechanism - profile completion is secondary
    $canAccessSystem = true; // Allow all users to access dashboard
    
    // Get verification status for display
    $verificationStatus = $isVerified ? 'verified' : ($hasPendingVerification ? 'pending' : 'not_submitted');
    
    // Calculate profile completion percentage
    $requiredFields = [
        'first_name' => $user && $user->first_name,
        'last_name' => $user && $user->last_name,
        'email' => $user && $user->email,
        'phone' => $user && $user->phone,
        'gender' => $user && $user->gender,
        'date_of_birth' => $user && $user->date_of_birth,
        'location' => $user && $user->location,
        'university' => $user && $user->university,
        'course' => $user && $user->course,
        'year_level' => $user && $user->year_level,
    ];
    
    $profileFields = [
        'cleanliness_level' => $profile && $profile->cleanliness_level,
        'sleep_pattern' => $profile && $profile->sleep_pattern,
        'study_habit' => $profile && $profile->study_habit,
        'noise_tolerance' => $profile && $profile->noise_tolerance,
        'budget_min' => $user && $user->budget_min,
        'budget_max' => $user && $user->budget_max,
    ];
    
    $allFields = array_merge($requiredFields, $profileFields);
    $completedCount = count(array_filter($allFields));
    $totalCount = count($allFields);
    $completionPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
    
    // Check individual profile sections
    $hasDateOfBirth = $user && $user->date_of_birth && !empty(optional($user->date_of_birth)->format('Y-m-d'));
    $hasPersonalInfo = $user && ($user->first_name && $user->last_name && $hasDateOfBirth && $user->gender && ($user->location || ($profile && $profile->apartment_location)));
    $hasEducationInfo = $user && ($user->university && ($user->course || ($profile && $profile->major)));
    $hasLifestylePrefs = $profile && ($profile->sleep_pattern && $profile->cleanliness_level && $profile->study_habit && $profile->noise_tolerance);
@endphp

@if(!$canAccessSystem)
<!-- Profile Completion Checklist -->
<div class="mb-6">
    <div class="system-access-card bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-orange-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Verify Your ID to Access All Features</h3>
                        <p class="text-sm text-gray-600">ID verification is required to unlock all roommate finding features</p>
                    </div>
                </div>
                <!-- Profile Completion Percentage -->
                <div class="text-right">
                    <div class="completion-status-text text-2xl font-bold {{ $isVerified ? 'text-green-600' : 'text-orange-600' }}">
                        {{ $isVerified ? '✓ Verified' : 'ID Required' }}
                    </div>
                    <div class="text-xs text-gray-500">{{ $isVerified ? 'Access Granted' : 'Verification Required' }}</div>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="completion-progress-bar bg-{{ $completionPercentage === 100 ? 'green' : 'orange' }}-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $completedCount }} of {{ $totalCount }} required fields completed
                </p>
            </div>
        </div>
        
        <div class="p-6 space-y-4">
            @if($completionPercentage < 100)
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <!-- New User Notifications -->
                            @php
                                $newUsers = \App\Models\User::where('created_at', '>', now()->subDays(7))->count();
                                $newMessages = \App\Models\Message::where('created_at', '>', now()->subHours(24))->where('receiver_id', auth()->id())->count();
                            @endphp
                            
                            @if($newUsers > 0)
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $newUsers }} new users joined</p>
                                        <p class="text-xs text-gray-500">In the last 7 days</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($newMessages > 0)
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $newMessages }} new messages</p>
                                        <p class="text-xs text-gray-500">In the last 24 hours</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="p-4 text-center">
                                <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all notifications</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endif

@if($canAccessSystem)
<!-- Main Dashboard Content -->
    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div class="bg-black bg-opacity-50 min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex gap-6">
                <!-- Left Sidebar Menu -->
                <div class="w-64 flex-shrink-0">
                    <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-4 border border-white border-opacity-50">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <span class="text-2xl mr-2">🧭</span>
                            Navigation
                        </h3>
                        <nav class="space-y-2">
                            <a href="{{ route('roommates.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">Find Roommates</div>
                                    <div class="text-xs text-gray-500">Browse compatible matches</div>
                                </div>
                            </a>

                            <a href="{{ route('matches.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">My Matches</div>
                                    <div class="text-xs text-gray-500">View your matches</div>
                                </div>
                            </a>

                            <a href="{{ route('messages.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">Messages</div>
                                    <div class="text-xs text-gray-500">Chat with roommates</div>
                                </div>
                            </a>


                            <a href="{{ route('activity.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">Activity</div>
                                    <div class="text-xs text-gray-500">Recent activity</div>
                                </div>
                            </a>

                            <!-- Theme Toggle -->
                            <label class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                    <svg id="theme-icon-light" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <svg id="theme-icon-dark" class="w-4 h-4 text-gray-600 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9 9 0 0012.707 8.293 9 9 0 0015.354 15.354zM9 12a3 3 0 106 0 3 3 0 00-6 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">Theme</div>
                                    <div class="text-xs text-gray-500">Dark / Light</div>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="theme-toggle" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </div>
                            </label>
                        </nav>
                    </div>
                </div>

            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- Find My Roommate Hero Section -->
                <div class="rounded-2xl p-8 mb-6 text-white relative overflow-hidden shadow-2xl" 
                     style="background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 50%, #EC4899 100%);">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse"></div>
                        <div class="absolute top-20 right-20 w-32 h-32 bg-blue-200 rounded-full blur-2xl animate-pulse delay-75"></div>
                        <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-purple-200 rounded-full blur-3xl animate-pulse delay-100"></div>
                        <div class="absolute bottom-10 right-10 w-24 h-24 bg-pink-200 rounded-full blur-xl animate-pulse delay-150"></div>
                    </div>
                    
                    <!-- Decorative Icons -->
                    <div class="absolute top-4 right-4 opacity-10">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 100 100">
                            <path d="M20 45 L50 20 L80 45 L80 80 L20 80 Z" fill="white"/>
                            <rect x="42" y="55" width="16" height="25" fill="#3B82F6"/>
                            <rect x="28" y="35" width="12" height="12" fill="#3B82F6"/>
                            <rect x="60" y="35" width="12" height="12" fill="#3B82F6"/>
                            <circle cx="35" cy="65" r="4" fill="#3B82F6"/>
                            <path d="M35 69 L35 75 M30 72 L40 72" stroke="#3B82F6" stroke-width="2"/>
                            <circle cx="65" cy="65" r="4" fill="#FB923C"/>
                            <path d="M65 69 L65 75 M60 72 L70 72" stroke="#FB923C" stroke-width="2"/>
                        </svg>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-center">
                            <div class="max-w-2xl">
                                <div class="flex items-center mb-4">
                                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3 mr-4">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 100 100">
                                            <path d="M20 45 L50 20 L80 45 L80 80 L20 80 Z" fill="white"/>
                                            <rect x="42" y="55" width="16" height="25" fill="#3B82F6"/>
                                            <rect x="28" y="35" width="12" height="12" fill="#3B82F6"/>
                                            <rect x="60" y="35" width="12" height="12" fill="#3B82F6"/>
                                            <circle cx="35" cy="65" r="4" fill="#3B82F6"/>
                                            <path d="M35 69 L35 75 M30 72 L40 72" stroke="#3B82F6" stroke-width="2"/>
                                            <circle cx="65" cy="65" r="4" fill="#FB923C"/>
                                            <path d="M65 69 L65 75 M60 72 L70 72" stroke="#FB923C" stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-4xl font-bold mb-2">Find My Roommate</h1>
                                        <div class="text-blue-100 text-lg">Your Perfect Living Space Awaits</div>
                                    </div>
                                </div>
                                <p class="text-blue-100 text-lg mb-6 text-lg leading-relaxed">
                                    Connect with compatible roommates, discover ideal living spaces, and build lasting friendships in your perfect home environment.
                                </p>
                                <div class="flex flex-wrap gap-4">
                                    <a href="{{ route('roommates.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Start Searching
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="hidden lg:block">
                                <div class="text-center">
                                    <div class="text-6xl mb-3 animate-bounce">🏠</div>
                                    <div class="text-blue-100 font-medium">Find Your Match</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overview Insights Section -->
                <div class="bg-white bg-opacity-95 backdrop-blur-md rounded-2xl shadow-xl p-6 mb-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="text-2xl mr-2">📈</span>
                        Overview Insights
                    </h2>
                    
                    @php
                        // User Compatibility Data - Based on actual interactions with other users
                        $user = auth()->user();
                        
                        // Get the highest compatibility score from actual roommate interactions
                        $bestCompatibility = \App\Models\UserCompatibility::where('user_id', $user->id)
                            ->where('preference_matches', '>', 0)
                            ->orderBy('compatibility_score', 'desc')
                            ->first();

                        $userCompatibility = $bestCompatibility ? $bestCompatibility->compatibility_score : 0;
                        $totalInteractions = $bestCompatibility ? $bestCompatibility->interaction_count : 0;
                        
                        // If no direct interactions yet, check general activity as a starting point
                        if ($userCompatibility === 0) {
                            $sentMessages = \App\Models\Message::where('sender_id', $user->id)->count();
                            $receivedMessages = \App\Models\Message::where('receiver_id', $user->id)->count();
                            $totalMessages = $sentMessages + $receivedMessages;
                            
                            $profileViews = \App\Models\ActivityLog::where('user_id', $user->id)
                                ->where('action', 'profile_view')
                                ->count();
                            
                            // Basic activity score (up to 20% max)
                            $userCompatibility = min(20, ($totalMessages * 5) + ($profileViews * 2));
                            $totalInteractions = $totalMessages + $profileViews;
                        }
                        
                        // Determine compatibility level based on the score
                        if ($totalInteractions === 0) {
                            $compatibilityLevel = 'Start Interacting';
                            $compatibilityColor = 'gray';
                        } elseif ($userCompatibility >= 90) {
                            $compatibilityLevel = 'Highly Compatible';
                            $compatibilityColor = 'green';
                        } elseif ($userCompatibility >= 70) {
                            $compatibilityLevel = 'Great Progress';
                            $compatibilityColor = 'blue';
                        } elseif ($userCompatibility >= 40) {
                            $compatibilityLevel = 'Building Connection';
                            $compatibilityColor = 'yellow';
                        } elseif ($userCompatibility >= 20) {
                            $compatibilityLevel = 'Getting Started';
                            $compatibilityColor = 'orange';
                        } else {
                            $compatibilityLevel = 'New User';
                            $compatibilityColor = 'red';
                        }
                        
                        // Pending Users Data
                        $pendingUsers = \App\Models\User::where('created_at', '>', now()->subDays(7))
                            ->whereDoesntHave('roommateProfile')
                            ->orWhereHas('roommateProfile', function($query) {
                                $query->whereNull('cleanliness_level')
                                      ->orWhereNull('sleep_pattern');
                            })
                            ->count();
                        
                        // Recent Chat Data - Group by conversation
                        $recentChats = \App\Models\Message::with(['sender', 'receiver'])
                            ->where(function($query) {
                                $query->where('sender_id', auth()->id())
                                      ->orWhere('receiver_id', auth()->id());
                            })
                            ->latest()
                            ->get()
                            ->unique(function ($item) {
                                return $item->sender_id === auth()->id() ? $item->receiver_id : $item->sender_id;
                            })
                            ->take(5);
                        
                        $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- User Compatibility Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Your Compatibility</h3>
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="text-center mb-4">
                                <div class="text-4xl font-bold text-blue-600 mb-2">{{ $userCompatibility }}%</div>
                                <div class="text-base font-semibold text-{{ $compatibilityColor }}-700">{{ $compatibilityLevel }}</div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-3 rounded-full transition-all duration-500" style="width: {{ $userCompatibility }}%"></div>
                            </div>
                            
                            <div class="text-sm font-medium text-gray-800 text-center mt-2">
                                Start messaging and interacting to improve compatibility
                            </div>
                            
                            @if($userCompatibility < 100)
                                <div class="mt-4">
                                    <a href="{{ route('profile.edit') }}" class="w-full bg-blue-600 text-white text-center py-2 rounded-xl hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-lg">
                                        Complete Profile
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Pending Users Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-200 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Pending Users</h3>
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="text-center mb-4">
                                <div class="text-4xl font-bold text-blue-600 mb-2">{{ $pendingUsers }}</div>
                                <div class="text-base font-semibold text-blue-800">Users need profile completion</div>
                            </div>
                            
                            <div class="space-y-3 mb-4 mt-2">
                                <div class="flex justify-between text-base">
                                    <span class="text-gray-800 font-medium">Joined this week</span>
                                    <span class="font-bold text-gray-900">{{ \App\Models\User::where('created_at', '>', now()->subDays(7))->count() }}</span>
                                </div>
                                <div class="flex justify-between text-base">
                                    <span class="text-gray-800 font-medium">Incomplete profiles</span>
                                    <span class="font-bold text-blue-700">{{ $pendingUsers }}</span>
                                </div>
                            </div>
                            
                            <div class="text-sm font-medium text-gray-800 text-center mt-2">
                                Help new users complete their profiles
                            </div>
                            
                            @if(auth()->user()->is_admin)
                                <div class="mt-4">
                                    <a href="{{ route('admin.users') }}" class="w-full bg-blue-600 text-white text-center py-2 rounded-xl hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-lg">
                                        Manage Users
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Deleted Users Card (Admin Only) -->
                        @if(auth()->user()->is_admin)
                        @php
                            $deletedUsers = \App\Models\User::onlyTrashed()
                                ->latest('deleted_at')
                                ->take(5)
                                ->get();
                            $deletedCount = \App\Models\User::onlyTrashed()->count();
                        @endphp
                        <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl p-6 border border-red-200 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Deleted Users</h3>
                                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                            </div>

                            <div class="text-center mb-4">
                                <div class="text-4xl font-bold text-red-600 mb-2">{{ $deletedCount }}</div>
                                <div class="text-base font-semibold text-red-800">Users deleted</div>
                            </div>

                            @if($deletedUsers->count() > 0)
                                <div class="space-y-3 mb-4 mt-2">
                                    @foreach($deletedUsers as $delUser)
                                        <div class="flex items-center justify-between text-sm">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-2">
                                                    <span class="text-red-600 font-bold text-xs">{{ strtoupper(substr($delUser->first_name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $delUser->first_name }} {{ $delUser->last_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $delUser->deleted_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-600">
                                    <p class="text-sm">No deleted users</p>
                                </div>
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('admin.users') }}?status=trashed" class="w-full bg-red-600 text-white text-center py-2 rounded-xl hover:bg-red-700 transition-all duration-200 text-sm font-medium shadow-lg">
                                    View Deleted Users
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Recent Chat Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-sky-50 rounded-2xl p-6 border border-blue-200 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Recent Chat</h3>
                                <div class="relative">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    @if($unreadMessages > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                            {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($recentChats->count() > 0)
                                <div class="space-y-3 mb-4">
                                    @foreach($recentChats as $message)
                                        @php
                                            $otherUser = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
                                        @endphp
                                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-blue-100 transition-colors cursor-pointer" onclick="window.location.href='{{ route('messages.show', $otherUser->id) }}'">
                                            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-blue-200">
                                                <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->first_name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-base font-semibold text-gray-900 truncate mr-2">
                                                        {{ $otherUser->first_name }}
                                                    </p>
                                                    @if($message->created_at)
                                                        <span class="text-sm font-medium text-gray-600 whitespace-nowrap">{{ $message->created_at->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-600 truncate">
                                                    {{ $message->sender_id === auth()->id() ? 'You: ' : '' }}{{ Str::limit($message->content, 30) }}
                                                </p>
                                            </div>
                                            @if($message->receiver_id === auth()->id() && !$message->read_at)
                                                <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-600">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <p class="text-base font-medium">No messages yet</p>
                                </div>
                            @endif
                            
                            <div class="mt-4">
                                <a href="{{ route('messages.index') }}" class="w-full bg-blue-600 text-white text-center py-2 rounded-xl hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-lg">
                                    {{ $unreadMessages > 0 ? 'View Messages (' . $unreadMessages . ')' : 'View Messages' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Listings Section -->
                <div class="bg-white bg-opacity-95 backdrop-blur-md rounded-2xl shadow-xl p-6 mb-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="text-2xl mr-2">🏠</span>
                            Available Listings
                        </h2>
                        <a href="{{ route('listings.index') }}" class="text-sm text-blue-600 hover:text-blue-700 transition-colors">View All</a>
                    </div>
                    
                    @php
                        $availableListings = \App\Models\Listing::where('is_active', true)
                            ->where('is_available', true)
                            ->latest()
                            ->take(3)
                            ->get();
                    @endphp
                    
                    @if($availableListings->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($availableListings as $listing)
                                <div class="bg-gradient-to-br from-blue-50 to-sky-50 rounded-xl p-4 border border-blue-200 shadow-lg hover:shadow-xl transition-all duration-300">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-xl">{{ $listing->title }}</h3>
                                            <p class="text-base font-medium text-gray-800">{{ $listing->location }}</p>
                                        </div>
                                        <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                                            Available
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-3 mt-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-base font-medium text-gray-800">Price:</span>
                                            <span class="text-lg font-bold text-blue-700">₱{{ number_format($listing->price ?? 0) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-base font-medium text-gray-800">Type:</span>
                                            <span class="text-base font-semibold text-gray-900">{{ ucfirst($listing->property_type) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-base font-medium text-gray-800">Bedrooms:</span>
                                            <span class="text-base font-semibold text-gray-900">{{ $listing->bedrooms }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('listings.show', $listing) }}" 
                                           class="w-full bg-blue-600 text-white text-center py-2 rounded-xl hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-lg">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-600">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-base font-medium">No listings available at the moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Account Management Section -->
                <div class="bg-white dark:bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-2xl shadow-xl p-6 mb-6 border border-gray-200 dark:border-gray-700" id="account-management">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <span class="text-2xl mr-2">⚙️</span>
                            Account Management
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Account Settings Link -->
                        <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 rounded-xl border border-blue-200 dark:border-blue-800 hover:shadow-lg transition-all duration-300 group">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100">Edit Profile</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Update your information and preferences</p>
                            </div>
                        </a>

                        <!-- Request Account Deletion -->
                        <div id="deletion-request-container" class="flex flex-col p-4 bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900 dark:to-rose-900 rounded-xl border border-red-200 dark:border-red-800">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-gray-100">Account Deletion</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Request to delete your account</p>
                                </div>
                            </div>

                            <!-- Normal State - Request Deletion Button -->
                            <div id="deletion-normal-state">
                                <button onclick="openDeletionModal()" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium">
                                    Request Account Deletion
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                                    This action requires admin approval and cannot be undone.
                                </p>
                            </div>

                            <!-- Pending Request State -->
                            <div id="deletion-pending-state" class="hidden">
                                <div class="bg-yellow-100 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 rounded-lg p-3 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Deletion Request Pending</span>
                                    </div>
                                    <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Requested: <span id="deletion-request-date"></span></p>
                                </div>
                                <button onclick="cancelDeletionRequest()" class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium">
                                    Cancel Request
                                </button>
                            </div>

                            <!-- Approved Request State -->
                            <div id="deletion-approved-state" class="hidden">
                                <div class="bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-red-800 dark:text-red-200">Deletion Approved</span>
                                    </div>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">Your account will be deleted soon.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Deletion Request Modal -->
                <div id="deletion-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md mx-4 shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Request Account Deletion</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                                Are you sure you want to request account deletion? This action requires admin approval and will permanently delete all your data.
                            </p>
                        </div>

                        <form id="deletion-request-form" class="space-y-4">
                            <div>
                                <label for="deletion-reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for leaving (optional)</label>
                                <textarea id="deletion-reason" name="reason" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white" placeholder="Tell us why you're leaving..."></textarea>
                            </div>

                            <div>
                                <label for="deletion-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm your password <span class="text-red-500">*</span></label>
                                <input type="password" id="deletion-password" name="password" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white" placeholder="Enter your password">
                                <p id="deletion-password-error" class="text-sm text-red-600 mt-1 hidden"></p>
                            </div>

                            <div class="flex space-x-3 mt-6">
                                <button type="button" onclick="closeDeletionModal()" class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" id="deletion-submit-btn" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div class="bg-white dark:bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-2xl shadow-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <span class="text-2xl mr-2">🔔</span>
                            Notifications
                        </h2>
                        @php
                            $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->whereNull('read_at')
                                ->count();
                        @endphp
                        @if($unreadNotifications > 0)
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ $unreadNotifications }} new
                            </span>
                        @endif
                    </div>
                    
                    @php
                        $notifications = \App\Models\Notification::where('user_id', auth()->id())
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($notifications->count() > 0)
                        <div class="space-y-3">
                            @foreach($notifications as $notification)
                                <div class="flex items-start p-3 border-l-4 {{ $notification->type === 'new_listing' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'border-gray-300 bg-gray-50 dark:bg-gray-700/50' }} {{ !$notification->read_at ? 'bg-yellow-50 dark:bg-yellow-900/30' : '' }} rounded-r-lg hover:shadow-md transition-all duration-200">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $notification->title }}</h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed">{{ $notification->message }}</p>
                                        
                                        @if($notification->type === 'new_listing' && isset($notification->data['listing_id']))
                                            <div class="mt-2">
                                                <a href="{{ route('listings.show', $notification->data['listing_id']) }}" 
                                                   class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-semibold transition-colors duration-200 hover:underline">
                                                    View Listing 
                                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if(!$notification->read_at)
                                        <button onclick="markAsRead({{ $notification->id }})" 
                                                class="ml-3 p-1 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-all duration-200"
                                                title="Mark as read">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
                            <a href="{{ route('notifications.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                View All Notifications
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.919 1.972 1.972 0 00.12-.7A2.032 2.032 0 0112 6c.634 0 1.237.298 1.65.768.413.47.696.856.855 1.405l1.405 1.405z" />
                            </svg>
                            <p class="text-base font-semibold text-gray-500 dark:text-gray-400">No notifications yet.</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">You'll see updates about new listings and matches here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    dropdown.classList.toggle('hidden');
}

// Mark notification as read
function markAsRead(notificationId) {
    fetch(`{{ route('notifications.read', ['notification' => ':id']) }}`.replace(':id', notificationId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to update the notification display
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

// Close notifications when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationsDropdown');
    const button = event.target.closest('button[onclick="toggleNotifications()"]');
    
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>

<!-- Profile Completion Celebration Notification -->
@if($canAccessSystem && session()->has('profile_just_completed'))
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 transform transition-all" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 4l4 4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">🎉 Profile Complete!</h3>
                <p class="text-sm text-gray-600 mb-6">Congratulations! Your profile is now complete and ready for roommate matching. You now have full access to all features!</p>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        ID Verification: {{ $isVerified ? 'Verified' : 'Complete' }}
                    </div>
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Profile Information: Complete
                    </div>
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        System Access: Unlocked
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-3">
                    <a href="{{ route('roommates.index') }}" @click="show = false" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors text-center">
                        Start Matching
                    </a>
                    <a href="{{ route('profile.show') }}" @click="show = false" class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors text-center">
                        View Profile
                    </a>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
    
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing theme toggle...');
            
            // Theme Toggle Functionality
            const themeToggle = document.getElementById('theme-toggle');
            const themeIconLight = document.getElementById('theme-icon-light');
            const themeIconDark = document.getElementById('theme-icon-dark');
            const dashboardContainer = document.querySelector('.min-h-screen');
            
            console.log('Elements found:', {
                themeToggle: !!themeToggle,
                themeIconLight: !!themeIconLight,
                themeIconDark: !!themeIconDark,
                dashboardContainer: !!dashboardContainer
            });
            
            if (!themeToggle) {
                console.error('Theme toggle element not found!');
                return;
            }
            
            // Check for saved theme preference or default to light
            const currentTheme = localStorage.getItem('theme') || 'light';
            console.log('Current theme:', currentTheme);
            
            // Apply the saved theme on page load
            if (currentTheme === 'dark') {
                document.documentElement.classList.add('dark');
                dashboardContainer.classList.remove('bg-cover', 'bg-center', 'bg-fixed');
                dashboardContainer.style.background = 'linear-gradient(135deg, #1f2937 0%, #374151 25%, #4b5563 50%, #6b7280 75%, #9ca3af 100%)';
                dashboardContainer.classList.add('min-h-screen');
                themeToggle.checked = true;
                themeIconLight.classList.add('hidden');
                themeIconDark.classList.remove('hidden');
                
                // Apply dark mode styles to all cards
                document.querySelectorAll('.bg-white').forEach(el => {
                    el.classList.remove('bg-white');
                    el.classList.add('bg-gray-800');
                });
                
                // Update all gradient backgrounds
                document.querySelectorAll('.bg-gradient-to-br').forEach(el => {
                    if (el.classList.contains('from-blue-50')) {
                        el.classList.remove('from-blue-50', 'to-indigo-50');
                        el.classList.add('from-blue-900', 'to-indigo-900');
                    } else if (el.classList.contains('from-orange-50')) {
                        el.classList.remove('from-orange-50', 'to-amber-50');
                        el.classList.add('from-orange-900', 'to-amber-900');
                    } else if (el.classList.contains('from-purple-50')) {
                        el.classList.remove('from-purple-50', 'to-pink-50');
                        el.classList.add('from-purple-900', 'to-pink-900');
                    } else if (el.classList.contains('from-green-50')) {
                        el.classList.remove('from-green-50', 'to-emerald-50');
                        el.classList.add('from-green-900', 'to-emerald-900');
                    } else if (el.classList.contains('from-cyan-50')) {
                        el.classList.remove('from-cyan-50', 'to-sky-50');
                        el.classList.add('from-cyan-900', 'to-sky-900');
                    }
                });
                
                // Update ALL text colors for dark mode - comprehensive approach
                const textMappings = [
                    { from: '.text-gray-900', to: '.text-gray-100' },
                    { from: '.text-gray-800', to: '.text-gray-200' },
                    { from: '.text-gray-700', to: '.text-gray-300' },
                    { from: '.text-gray-600', to: '.text-gray-400' },
                    { from: '.text-gray-500', to: '.text-gray-400' },
                    { from: '.text-gray-400', to: '.text-gray-500' },
                    { from: '.text-blue-900', to: '.text-blue-100' },
                    { from: '.text-blue-800', to: '.text-blue-200' },
                    { from: '.text-blue-700', to: '.text-blue-300' },
                    { from: '.text-blue-600', to: '.text-blue-400' },
                    { from: '.text-indigo-900', to: '.text-indigo-100' },
                    { from: '.text-indigo-800', to: '.text-indigo-200' },
                    { from: '.text-indigo-700', to: '.text-indigo-300' },
                    { from: '.text-indigo-600', to: '.text-indigo-400' },
                    { from: '.text-purple-900', to: '.text-purple-100' },
                    { from: '.text-purple-800', to: '.text-purple-200' },
                    { from: '.text-purple-700', to: '.text-purple-300' },
                    { from: '.text-purple-600', to: '.text-purple-400' },
                    { from: '.text-pink-900', to: '.text-pink-100' },
                    { from: '.text-pink-800', to: '.text-pink-200' },
                    { from: '.text-pink-700', to: '.text-pink-300' },
                    { from: '.text-pink-600', to: '.text-pink-400' },
                    { from: '.text-cyan-900', to: '.text-cyan-100' },
                    { from: '.text-cyan-800', to: '.text-cyan-200' },
                    { from: '.text-cyan-700', to: '.text-cyan-300' },
                    { from: '.text-cyan-600', to: '.text-cyan-400' },
                    { from: '.text-sky-900', to: '.text-sky-100' },
                    { from: '.text-sky-800', to: '.text-sky-200' },
                    { from: '.text-sky-700', to: '.text-sky-300' },
                    { from: '.text-sky-600', to: '.text-sky-400' }
                ];
                
                textMappings.forEach(mapping => {
                    document.querySelectorAll(mapping.from).forEach(el => {
                        el.classList.remove(...mapping.from.split(' ').filter(c => c.startsWith('text-')));
                        el.classList.add(...mapping.to.split(' ').filter(c => c.startsWith('text-')));
                    });
                });
                
                // Update borders for dark mode
                document.querySelectorAll('.border-gray-200').forEach(el => {
                    el.classList.remove('border-gray-200');
                    el.classList.add('border-gray-700');
                });
                
                document.querySelectorAll('.border-blue-200').forEach(el => {
                    el.classList.remove('border-blue-200');
                    el.classList.add('border-blue-800');
                });
                
                console.log('Applied dark mode on load');
            } else {
                dashboardContainer.classList.add('bg-cover', 'bg-center', 'bg-fixed');
                dashboardContainer.style.backgroundImage = "url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')";
                themeToggle.checked = false;
                themeIconLight.classList.remove('hidden');
                themeIconDark.classList.add('hidden');
                console.log('Applied light mode on load');
            }
            
            // Theme toggle event listener - multiple approaches for maximum compatibility
            function handleThemeToggle() {
                console.log('Theme toggle clicked! Checked:', themeToggle.checked);
                
                if (themeToggle.checked) {
                    // Switch to dark mode
                    console.log('Switching to dark mode...');
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    
                    // Remove image background and apply dark gradient
                    dashboardContainer.classList.remove('bg-cover', 'bg-center', 'bg-fixed');
                    dashboardContainer.style.backgroundImage = '';
                    dashboardContainer.style.background = 'linear-gradient(135deg, #1f2937 0%, #374151 25%, #4b5563 50%, #6b7280 75%, #9ca3af 100%)';
                    
                    // Show dark icon, hide light icon
                    themeIconLight.classList.add('hidden');
                    themeIconDark.classList.remove('hidden');
                    
                    // Apply dark mode styles to all cards
                    document.querySelectorAll('.bg-white').forEach(el => {
                        el.classList.remove('bg-white');
                        el.classList.add('bg-gray-800');
                    });
                    
                    // Update all gradient backgrounds
                    document.querySelectorAll('.bg-gradient-to-br').forEach(el => {
                        if (el.classList.contains('from-blue-50')) {
                            el.classList.remove('from-blue-50', 'to-indigo-50');
                            el.classList.add('from-blue-900', 'to-indigo-900');
                        } else if (el.classList.contains('from-orange-50')) {
                            el.classList.remove('from-orange-50', 'to-amber-50');
                            el.classList.add('from-orange-900', 'to-amber-900');
                        } else if (el.classList.contains('from-purple-50')) {
                            el.classList.remove('from-purple-50', 'to-pink-50');
                            el.classList.add('from-purple-900', 'to-pink-900');
                        } else if (el.classList.contains('from-green-50')) {
                            el.classList.remove('from-green-50', 'to-emerald-50');
                            el.classList.add('from-green-900', 'to-emerald-900');
                        } else if (el.classList.contains('from-cyan-50')) {
                            el.classList.remove('from-cyan-50', 'to-sky-50');
                            el.classList.add('from-cyan-900', 'to-sky-900');
                        }
                    });
                    
                    // Update ALL text colors for dark mode - comprehensive approach
                    const textMappings = [
                        { from: '.text-gray-900', to: '.text-gray-100' },
                        { from: '.text-gray-800', to: '.text-gray-200' },
                        { from: '.text-gray-700', to: '.text-gray-300' },
                        { from: '.text-gray-600', to: '.text-gray-400' },
                        { from: '.text-gray-500', to: '.text-gray-400' },
                        { from: '.text-gray-400', to: '.text-gray-500' },
                        { from: '.text-blue-900', to: '.text-blue-100' },
                        { from: '.text-blue-800', to: '.text-blue-200' },
                        { from: '.text-blue-700', to: '.text-blue-300' },
                        { from: '.text-blue-600', to: '.text-blue-400' },
                        { from: '.text-indigo-900', to: '.text-indigo-100' },
                        { from: '.text-indigo-800', to: '.text-indigo-200' },
                        { from: '.text-indigo-700', to: '.text-indigo-300' },
                        { from: '.text-indigo-600', to: '.text-indigo-400' },
                        { from: '.text-purple-900', to: '.text-purple-100' },
                        { from: '.text-purple-800', to: '.text-purple-200' },
                        { from: '.text-purple-700', to: '.text-purple-300' },
                        { from: '.text-purple-600', to: '.text-purple-400' },
                        { from: '.text-pink-900', to: '.text-pink-100' },
                        { from: '.text-pink-800', to: '.text-pink-200' },
                        { from: '.text-pink-700', to: '.text-pink-300' },
                        { from: '.text-pink-600', to: '.text-pink-400' },
                        { from: '.text-cyan-900', to: '.text-cyan-100' },
                        { from: '.text-cyan-800', to: '.text-cyan-200' },
                        { from: '.text-cyan-700', to: '.text-cyan-300' },
                        { from: '.text-cyan-600', to: '.text-cyan-400' },
                        { from: '.text-sky-900', to: '.text-sky-100' },
                        { from: '.text-sky-800', to: '.text-sky-200' },
                        { from: '.text-sky-700', to: '.text-sky-300' },
                        { from: '.text-sky-600', to: '.text-sky-400' }
                    ];
                    
                    textMappings.forEach(mapping => {
                        document.querySelectorAll(mapping.from).forEach(el => {
                            el.classList.remove(...mapping.from.split(' ').filter(c => c.startsWith('text-')));
                            el.classList.add(...mapping.to.split(' ').filter(c => c.startsWith('text-')));
                        });
                    });
                    
                    // Update borders for dark mode
                    document.querySelectorAll('.border-gray-200').forEach(el => {
                        el.classList.remove('border-gray-200');
                        el.classList.add('border-gray-700');
                    });
                    
                    document.querySelectorAll('.border-blue-200').forEach(el => {
                        el.classList.remove('border-blue-200');
                        el.classList.add('border-blue-800');
                    });
                    
                    console.log('Dark mode applied successfully');
                    
                } else {
                    // Switch to light mode
                    console.log('Switching to light mode...');
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    
                    // Apply image background
                    dashboardContainer.classList.add('bg-cover', 'bg-center', 'bg-fixed');
                    dashboardContainer.style.backgroundImage = "url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')";
                    dashboardContainer.style.background = '';
                    
                    // Show light icon, hide dark icon
                    themeIconLight.classList.remove('hidden');
                    themeIconDark.classList.add('hidden');
                    
                    // Revert to light mode styles
                    document.querySelectorAll('.bg-gray-800').forEach(el => {
                        el.classList.remove('bg-gray-800');
                        el.classList.add('bg-white');
                    });
                    
                    // Revert all gradient backgrounds
                    document.querySelectorAll('.from-blue-900').forEach(el => {
                        el.classList.remove('from-blue-900', 'to-indigo-900');
                        el.classList.add('from-blue-50', 'to-indigo-50');
                    });
                    
                    document.querySelectorAll('.from-orange-900').forEach(el => {
                        el.classList.remove('from-orange-900', 'to-amber-900');
                        el.classList.add('from-orange-50', 'to-amber-50');
                    });
                    
                    document.querySelectorAll('.from-purple-900').forEach(el => {
                        el.classList.remove('from-purple-900', 'to-pink-900');
                        el.classList.add('from-purple-50', 'to-pink-50');
                    });
                    
                    document.querySelectorAll('.from-green-900').forEach(el => {
                        el.classList.remove('from-green-900', 'to-emerald-900');
                        el.classList.add('from-green-50', 'to-emerald-50');
                    });
                    
                    document.querySelectorAll('.from-cyan-900').forEach(el => {
                        el.classList.remove('from-cyan-900', 'to-sky-900');
                        el.classList.add('from-cyan-50', 'to-sky-50');
                    });
                    
                    // Revert ALL text colors
                    const revertMappings = [
                        { from: '.text-gray-100', to: '.text-gray-900' },
                        { from: '.text-gray-200', to: '.text-gray-800' },
                        { from: '.text-gray-300', to: '.text-gray-700' },
                        { from: '.text-gray-400', to: '.text-gray-600' },
                        { from: '.text-gray-500', to: '.text-gray-500' },
                        { from: '.text-blue-100', to: '.text-blue-900' },
                        { from: '.text-blue-200', to: '.text-blue-800' },
                        { from: '.text-blue-300', to: '.text-blue-700' },
                        { from: '.text-blue-400', to: '.text-blue-600' },
                        { from: '.text-indigo-100', to: '.text-indigo-900' },
                        { from: '.text-indigo-200', to: '.text-indigo-800' },
                        { from: '.text-indigo-300', to: '.text-indigo-700' },
                        { from: '.text-indigo-400', to: '.text-indigo-600' },
                        { from: '.text-purple-100', to: '.text-purple-900' },
                        { from: '.text-purple-200', to: '.text-purple-800' },
                        { from: '.text-purple-300', to: '.text-purple-700' },
                        { from: '.text-purple-400', to: '.text-purple-600' },
                        { from: '.text-pink-100', to: '.text-pink-900' },
                        { from: '.text-pink-200', to: '.text-pink-800' },
                        { from: '.text-pink-300', to: '.text-pink-700' },
                        { from: '.text-pink-400', to: '.text-pink-600' },
                        { from: '.text-cyan-100', to: '.text-cyan-900' },
                        { from: '.text-cyan-200', to: '.text-cyan-800' },
                        { from: '.text-cyan-300', to: '.text-cyan-700' },
                        { from: '.text-cyan-400', to: '.text-cyan-600' },
                        { from: '.text-sky-100', to: '.text-sky-900' },
                        { from: '.text-sky-200', to: '.text-sky-800' },
                        { from: '.text-sky-300', to: '.text-sky-700' },
                        { from: '.text-sky-400', to: '.text-sky-600' }
                    ];
                    
                    revertMappings.forEach(mapping => {
                        document.querySelectorAll(mapping.from).forEach(el => {
                            el.classList.remove(...mapping.from.split(' ').filter(c => c.startsWith('text-')));
                            el.classList.add(...mapping.to.split(' ').filter(c => c.startsWith('text-')));
                        });
                    });
                    
                    // Revert borders
                    document.querySelectorAll('.border-gray-700').forEach(el => {
                        el.classList.remove('border-gray-700');
                        el.classList.add('border-gray-200');
                    });
                    
                    document.querySelectorAll('.border-blue-800').forEach(el => {
                        el.classList.remove('border-blue-800');
                        el.classList.add('border-blue-200');
                    });
                    
                    console.log('Light mode applied successfully');
                }
            }
            
            // Add theme toggle event listener
            themeToggle.addEventListener('change', handleThemeToggle);
            
            console.log('Theme toggle event listeners added');
        });
        
        // Clear the session flag after showing notification
        setTimeout(() => {
            fetch('{{ route("profile.completion.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
        }, 1000);
    </script>

<script>
function openAIAssistant() {
    alert('AI Assistant feature coming soon! This will help you find compatible roommates based on your preferences.');
}

// Account Deletion Request Functions
let deletionRequestStatus = null;

// Check deletion request status on page load
document.addEventListener('DOMContentLoaded', function() {
    checkDeletionRequestStatus();
});

function checkDeletionRequestStatus() {
    fetch('{{ route('account.deletion.status') }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateDeletionRequestUI(data);
        }
    })
    .catch(error => {
        console.error('Error checking deletion request status:', error);
    });
}

function updateDeletionRequestUI(data) {
    const normalState = document.getElementById('deletion-normal-state');
    const pendingState = document.getElementById('deletion-pending-state');
    const approvedState = document.getElementById('deletion-approved-state');

    if (!data.has_request) {
        // No request exists - show normal state
        normalState.classList.remove('hidden');
        pendingState.classList.add('hidden');
        approvedState.classList.add('hidden');
    } else if (data.status === 'pending') {
        // Pending request - show pending state
        normalState.classList.add('hidden');
        pendingState.classList.remove('hidden');
        approvedState.classList.add('hidden');

        // Update request date
        if (data.requested_at) {
            const date = new Date(data.requested_at);
            document.getElementById('deletion-request-date').textContent = date.toLocaleDateString();
        }
    } else if (data.status === 'approved') {
        // Approved request - show approved state
        normalState.classList.add('hidden');
        pendingState.classList.add('hidden');
        approvedState.classList.remove('hidden');
    }
}

function openDeletionModal() {
    document.getElementById('deletion-modal').classList.remove('hidden');
    document.getElementById('deletion-password').value = '';
    document.getElementById('deletion-reason').value = '';
    document.getElementById('deletion-password-error').classList.add('hidden');
}

function closeDeletionModal() {
    document.getElementById('deletion-modal').classList.add('hidden');
}

// Handle deletion request form submission
document.getElementById('deletion-request-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('deletion-submit-btn');
    const password = document.getElementById('deletion-password').value;
    const reason = document.getElementById('deletion-reason').value;
    const passwordError = document.getElementById('deletion-password-error');

    // Hide previous errors
    passwordError.classList.add('hidden');

    // Show loading state
    const originalBtnText = submitBtn.textContent;
    submitBtn.textContent = 'Submitting...';
    submitBtn.disabled = true;

    fetch('{{ route('account.deletion.request') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            password: password,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;

        if (data.success) {
            closeDeletionModal();
            // Show success message
            showDeletionNotification(data.message, 'success');
            // Update UI to show pending state
            checkDeletionRequestStatus();
        } else {
            if (data.errors && data.errors.password) {
                passwordError.textContent = data.errors.password[0];
                passwordError.classList.remove('hidden');
            } else {
                showDeletionNotification(data.message || 'An error occurred', 'error');
            }
        }
    })
    .catch(error => {
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
        console.error('Error submitting deletion request:', error);
        showDeletionNotification('Failed to submit request. Please try again.', 'error');
    });
});

function cancelDeletionRequest() {
    if (!confirm('Are you sure you want to cancel your account deletion request?')) {
        return;
    }

    fetch('{{ route('account.deletion.cancel') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showDeletionNotification(data.message, 'success');
            checkDeletionRequestStatus();
        } else {
            showDeletionNotification(data.message || 'Failed to cancel request', 'error');
        }
    })
    .catch(error => {
        console.error('Error cancelling deletion request:', error);
        showDeletionNotification('Failed to cancel request. Please try again.', 'error');
    });
}

function showDeletionNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                ${type === 'success'
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove after 4 seconds
    setTimeout(() => {
        notification.remove();
    }, 4000);
}

// Close modal when clicking outside
document.getElementById('deletion-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeletionModal();
    }
});
</script>
@endsection
