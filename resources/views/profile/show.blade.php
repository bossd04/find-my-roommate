<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $user->fullName() }}
            </h2>
            </h2>
            @if(isset($compatibilityScore))
            <div class="flex items-center">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-300 mr-2">Compatibility:</span>
                    <div class="relative">
                        <div class="h-6 w-32 bg-gray-700 rounded-full overflow-hidden">
                            @php
                                $percentage = min(100, max(0, $compatibilityScore));
                                $color = $percentage >= 80 ? 'bg-green-500' : ($percentage >= 60 ? 'bg-blue-500' : 'bg-yellow-500');
                            @endphp
                            <div class="h-full {{ $color }} rounded-full transition-all duration-500 ease-in-out" 
                                 style="width: {{ $percentage }}%">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ $percentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section with Background -->
            <div class="mb-8 rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #000000 0%, #1a1a2e 50%, #16213e 100%);">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute top-20 right-20 w-32 h-32 bg-blue-600 rounded-full blur-2xl animate-pulse delay-75"></div>
                    <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-blue-700 rounded-full blur-3xl animate-pulse delay-100"></div>
                    <div class="absolute bottom-10 right-10 w-24 h-24 bg-blue-500 rounded-full blur-xl animate-pulse delay-150"></div>
                </div>
                
                <div class="relative z-10 flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        @if($user->avatar_url || $user->profile_photo_url)
                            <img src="{{ $user->avatar_url ?? $user->profile_photo_url }}" 
                                 alt="{{ $user->fullName() }}" 
                                 class="h-24 w-24 rounded-full object-cover border-4 border-blue-500 shadow-lg">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gray-700 flex items-center justify-center text-3xl font-bold text-blue-400 border-4 border-blue-500">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $user->fullName() }}</h1>
                        <p class="text-blue-200">{{ $user->email }}</p>
                        
                        <!-- Looking for Roommate Status -->
                        <div class="mt-3">
                            @if($user->looking_for_roommate)
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-pink-500 bg-opacity-90 text-white shadow-lg">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                    Looking for Roommate
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-gray-600 bg-opacity-80 text-gray-300 shadow-lg">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Not Looking for Roommate
                                </span>
                            @endif
                        </div>
                        
                        @if($profile)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if($profile->bio)
                                    <p class="text-gray-300">{{ $profile->bio }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 bg-opacity-95 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-2xl border border-gray-700">
                <div class="p-6 bg-gray-800 border-b border-gray-700">

                    <!-- Profile Completion Status -->
                    @if(auth()->check() && auth()->id() === $user->id)
                    <div class="mt-8 bg-gradient-to-r from-gray-900 to-black border border-gray-700 rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center">
                                    <i class="fas fa-clipboard-check text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white">Profile Completion Status</h3>
                                    <p class="text-sm text-gray-400">Complete all required sections to access system features</p>
                                </div>
                            </div>
                            @if($user->isProfileComplete())
                                <div class="flex items-center space-x-2 bg-green-900 bg-opacity-50 text-green-300 px-3 py-1.5 rounded-full border border-green-700">
                                    <i class="fas fa-check-circle text-sm"></i>
                                    <span class="text-sm font-medium">Complete</span>
                                </div>
                            @else
                                <div class="flex items-center space-x-2 bg-orange-900 bg-opacity-50 text-orange-300 px-3 py-1.5 rounded-full border border-orange-700">
                                    <i class="fas fa-exclamation-circle text-sm"></i>
                                    <span class="text-sm font-medium">Incomplete</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm text-gray-400 mb-2">
                                <span>Overall Completion</span>
                                <span>{{ $completionPercentage ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $completionPercentage ?? 0 }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Field Completion Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <!-- ID Verification -->
                            <div class="flex items-center justify-between p-3 rounded-lg @if($user->isVerified()) bg-green-900 bg-opacity-30 border border-green-800 @else bg-red-900 bg-opacity-30 border border-red-800 @endif">
                                <div class="flex items-center space-x-2">
                                    @if($user->isVerified())
                                        <svg class="w-4 h-4 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-gray-300">ID Verification</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l1.293 1.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-red-300 font-medium">ID Verification</span>
                                    @endif
                                </div>
                                @if($user->isVerified())
                                    <span class="text-xs text-green-400 font-medium">Verified</span>
                                @else
                                    <span class="text-xs text-red-400 font-medium">Not Verified</span>
                                @endif
                            </div>
                            
                            <!-- Basic Information -->
                            <div class="flex items-center justify-between p-3 rounded-lg @if(!empty($user->first_name) && !empty($user->last_name) && !empty($user->email) && !empty($user->phone) && !empty($user->gender) && !empty($user->date_of_birth)) bg-green-900 bg-opacity-30 border border-green-800 @else bg-red-900 bg-opacity-30 border border-red-800 @endif">
                                <div class="flex items-center space-x-2">
                                    @if(!empty($user->first_name) && !empty($user->last_name) && !empty($user->email) && !empty($user->phone) && !empty($user->gender) && !empty($user->date_of_birth))
                                        <svg class="w-4 h-4 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-gray-300">Basic Information</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l1.293 1.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-red-300 font-medium">Basic Information</span>
                                    @endif
                                </div>
                                @if(!empty($user->first_name) && !empty($user->last_name) && !empty($user->email) && !empty($user->phone) && !empty($user->gender) && !empty($user->date_of_birth))
                                    <span class="text-xs text-green-400 font-medium">Complete</span>
                                @else
                                    <span class="text-xs text-red-400 font-medium">Incomplete</span>
                                @endif
                            </div>
                            
                            <!-- Location Information -->
                            <div class="flex items-center justify-between p-3 rounded-lg @if(!empty($user->location)) bg-green-900 bg-opacity-30 border border-green-800 @else bg-red-900 bg-opacity-30 border border-red-800 @endif">
                                <div class="flex items-center space-x-2">
                                    @if(!empty($user->location))
                                        <i class="fas fa-check-circle text-green-400 text-sm"></i>
                                        <span class="text-sm text-gray-300">Location Information</span>
                                    @else
                                        <i class="fas fa-times-circle text-red-400 text-sm"></i>
                                        <span class="text-sm text-red-300 font-medium">Location Information</span>
                                    @endif
                                </div>
                                @if(!empty($user->location))
                                    <span class="text-xs text-green-400 font-medium">Complete</span>
                                @else
                                    <span class="text-xs text-red-400 font-medium">Incomplete</span>
                                @endif
                            </div>
                            
                            <!-- Education Information -->
                            <div class="flex items-center justify-between p-3 rounded-lg @if(!empty($user->university) && !empty($user->course) && !empty($user->year_level)) bg-green-900 bg-opacity-30 border border-green-800 @else bg-red-900 bg-opacity-30 border border-red-800 @endif">
                                <div class="flex items-center space-x-2">
                                    @if(!empty($user->university) && !empty($user->course) && !empty($user->year_level))
                                        <svg class="w-4 h-4 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-gray-300">Education Information</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l1.293 1.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-red-300 font-medium">Education Information</span>
                                    @endif
                                </div>
                                @if(!empty($user->university) && !empty($user->course) && !empty($user->year_level))
                                    <span class="text-xs text-green-400 font-medium">Complete</span>
                                @else
                                    <span class="text-xs text-red-400 font-medium">Incomplete</span>
                                @endif
                            </div>
                            
                            <!-- Lifestyle Preferences -->
                            <div class="flex items-center justify-between p-3 rounded-lg @if($profile && !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance)) bg-green-50 @else bg-red-50 @endif">
                                <div class="flex items-center space-x-2">
                                    @if($profile && !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance))
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-gray-700">Lifestyle Preferences</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l1.293 1.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8.586 10l1.293 1.293a1 1 0 001.414 0z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm text-red-600 font-medium">Lifestyle Preferences</span>
                                    @endif
                                </div>
                                @if($profile && !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance))
                                    <span class="text-xs text-green-600 font-medium">✓ Complete</span>
                                @else
                                    <span class="text-xs text-red-600 font-medium">✗ Incomplete</span>
                                @endif
                            
                            <!-- Lifestyle Preferences -->
                            <div class="flex items-center justify-between p-3 rounded-lg @if($profile && !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance)) bg-green-50 @else bg-red-50 @endif">
                                <div class="flex items-center space-x-2">
                                    @if($profile && !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance))
                                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                        <span class="text-sm text-gray-700">Lifestyle Preferences</span>
                                    @else
                                        <i class="fas fa-times-circle text-red-500 text-sm"></i>
                                        <span class="text-sm text-red-600 font-medium">Lifestyle Preferences</span>
                                    @endif
                                </div>
                                @if($profile && !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance))
                                    <span class="text-xs text-green-600 font-medium">✓ Complete</span>
                                @else
                                    <span class="text-xs text-red-600 font-medium">✗ Incomplete</span>
                                @endif
                            </div>
                            
                            <!-- Budget Information -->
                            <div class="flex items-center justify-between p-3 rounded-lg @if(!empty($user->budget_min) && !empty($user->budget_max)) bg-green-50 @else bg-red-50 @endif">
                                <div class="flex items-center space-x-2">
                                    @if(!empty($user->budget_min) && !empty($user->budget_max))
                                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                        <span class="text-sm text-gray-700">Budget Information</span>
                                    @else
                                        <i class="fas fa-times-circle text-red-500 text-sm"></i>
                                        <span class="text-sm text-red-600 font-medium">Budget Information</span>
                                    @endif
                                </div>
                                @if(!empty($user->budget_min) && !empty($user->budget_max))
                                    <span class="text-xs text-green-600 font-medium">✓ Complete</span>
                                @else
                                    <span class="text-xs text-red-600 font-medium">✗ Incomplete</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-6 flex space-x-3">
                            @if(!$user->isProfileComplete())
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-edit mr-2"></i>
                                    Complete Profile
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($profile)
                        <!-- About / Bio Section -->
                        @if($profile->bio || $user->bio)
                        <div class="mt-8 bg-gray-700 bg-opacity-50 rounded-lg p-6 border border-gray-600">
                            <h2 class="text-lg font-medium text-white mb-3">About</h2>
                            <p class="text-gray-300">{{ $profile->bio ?? $user->bio }}</p>
                        </div>
                        @endif

                        <!-- Personal Information -->
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Personal Information</h2>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                                @if($user->age)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Age</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ abs($user->age) }} years old</p>
                                    </div>
                                @endif
                                @if($user->gender || $profile->gender)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Gender</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst($user->gender ?? $profile->gender) }}</p>
                                    </div>
                                @endif
                                @if($user->phone)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Phone</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->phone }}</p>
                                    </div>
                                @endif
                                @if($user->email)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Email</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->email }}</p>
                                    </div>
                                @endif
                                @if($user->date_of_birth)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Date of Birth</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ \Carbon\Carbon::parse($user->date_of_birth)->format('F j, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Education Information -->
                        @if($user->university || $user->course || $user->year_level || $profile->university || $profile->major)
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Education</h2>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                                @if($user->university || $profile->university)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">University</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->university ?? $profile->university }}</p>
                                    </div>
                                @endif
                                @if($user->course || $profile->major)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Course/Major</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->course ?? $profile->major }}</p>
                                    </div>
                                @endif
                                @if($user->year_level)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Year Level</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->year_level }}</p>
                                    </div>
                                @endif
                                @if($user->department)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Department</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->department }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Location Information -->
                        @if($user->location || $profile->apartment_location || $profile->city)
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Location</h2>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @if($user->location || $profile->apartment_location || $profile->city)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Location</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->location ?? $profile->apartment_location ?? $profile->city }}</p>
                                    </div>
                                @endif
                                @if($profile->move_in_date)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Move-in Date</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ \Carbon\Carbon::parse($profile->move_in_date)->format('F j, Y') }}</p>
                                    </div>
                                @endif
                                @if($profile->lease_duration)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Lease Duration</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $profile->lease_duration }} months</p>
                                    </div>
                                @endif
                                @if($profile->has_apartment !== null)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Has Apartment</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $profile->has_apartment ? 'Yes' : 'No' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Lifestyle Preferences -->
                        @if($profile->cleanliness_level || $profile->sleep_pattern || $profile->study_habit || $profile->noise_tolerance || $profile->noise_level || $profile->schedule)
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Lifestyle Preferences</h2>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                                @if($profile->cleanliness_level)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Cleanliness Level</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst(str_replace('_', ' ', $profile->cleanliness_level)) }}</p>
                                    </div>
                                @endif
                                @if($profile->sleep_pattern)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Sleep Pattern</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst(str_replace('_', ' ', $profile->sleep_pattern)) }}</p>
                                    </div>
                                @endif
                                @if($profile->study_habit)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Study Habit</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst(str_replace('_', ' ', $profile->study_habit)) }}</p>
                                    </div>
                                @endif
                                @if($profile->noise_tolerance)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Noise Tolerance</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst(str_replace('_', ' ', $profile->noise_tolerance)) }}</p>
                                    </div>
                                @endif
                                @if($profile->noise_level)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Noise Level</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst(str_replace('_', ' ', $profile->noise_level)) }}</p>
                                    </div>
                                @endif
                                @if($profile->schedule)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Schedule</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst(str_replace('_', ' ', $profile->schedule)) }}</p>
                                    </div>
                                @endif
                                @if($profile->smoking_allowed !== null)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Smoking</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $profile->smoking_allowed ? 'Allowed' : 'Not Allowed' }}</p>
                                    </div>
                                @endif
                                @if($profile->pets_allowed !== null)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Pets</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $profile->pets_allowed ? 'Allowed' : 'Not Allowed' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Budget Information -->
                        @if($user->budget_min || $user->budget_max || $profile->budget_min || $profile->budget_max)
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Budget</h2>
                            <div class="mt-4 bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                <p class="text-sm text-gray-400">Monthly Budget Range</p>
                                <p class="mt-1 text-lg font-medium text-gray-200">
                                    ₱{{ number_format($user->budget_min ?? $profile->budget_min ?? 0) }} - ₱{{ number_format($user->budget_max ?? $profile->budget_max ?? 0) }}/month
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Roommate Preferences -->
                        @if($user->preference && ($user->preference->number_of_roommates || $user->preference->preferred_gender))
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Roommate Preferences</h2>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @if($user->preference->number_of_roommates)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Number of Roommates Wanted</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ $user->preference->number_of_roommates }}</p>
                                    </div>
                                @endif
                                @if($user->preference->preferred_gender)
                                    <div class="bg-gray-700 bg-opacity-50 rounded-lg p-4 border border-gray-600">
                                        <p class="text-sm text-gray-400">Preferred Gender of Roommate</p>
                                        <p class="mt-1 text-sm font-medium text-gray-200">{{ ucfirst($user->preference->preferred_gender) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Hobbies -->
                        @php
                            $hobbies = null;
                            if ($user->hobbies) {
                                if (is_string($user->hobbies)) {
                                    $hobbies = json_decode($user->hobbies, true);
                                } elseif (is_array($user->hobbies)) {
                                    $hobbies = $user->hobbies;
                                }
                            } elseif ($profile->hobbies) {
                                if (is_string((string)$profile->hobbies)) {
                                    $hobbies = json_decode((string)$profile->hobbies, true);
                                } elseif (is_array($profile->hobbies)) {
                                    $hobbies = $profile->hobbies;
                                }
                            }
                        @endphp
                        @if(is_array($hobbies) && count($hobbies) > 0)
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Hobbies</h2>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($hobbies as $hobby)
                                    <span class="px-3 py-1 bg-blue-900 bg-opacity-50 text-blue-300 rounded-full text-sm font-medium border border-blue-800">{{ $hobby }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Lifestyle Tags -->
                        @php
                            $tags = null;
                            if ($user->lifestyle_tags) {
                                if (is_string($user->lifestyle_tags)) {
                                    $tags = json_decode($user->lifestyle_tags, true);
                                } elseif (is_array($user->lifestyle_tags)) {
                                    $tags = $user->lifestyle_tags;
                                }
                            } elseif ($profile->lifestyle_tags) {
                                if (is_string((string)$profile->lifestyle_tags)) {
                                    $tags = json_decode((string)$profile->lifestyle_tags, true);
                                } elseif (is_array($profile->lifestyle_tags)) {
                                    $tags = $profile->lifestyle_tags;
                                }
                            }
                        @endphp
                        @if(is_array($tags) && count($tags) > 0)
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-white">Lifestyle Tags</h2>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <span class="px-3 py-1 bg-indigo-900 bg-opacity-50 text-indigo-300 rounded-full text-sm font-medium border border-indigo-800">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endif

                    @if(auth()->check() && auth()->id() !== $user->id)
                        <div class="mt-8 pt-6 border-t border-gray-700 flex items-center justify-end space-x-4">
                            @if($isMatch ?? false)
                                <a href="{{ route('messages.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    Message
                                </a>
                            @else
                                <form action="{{ route('matches.like', $user) }}" method="POST" class="inline" onsubmit="handleLikeSubmit(event)">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                        Like
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ $backUrl ?? route('matches.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-600 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $backLabel ?? 'Back to Matches' }}
                            </a>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function handleLikeSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const button = form.querySelector('button[type="submit"]');
    
    // Show loading state
    if (button) {
        button.disabled = true;
        button.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4.374a6.375 6.375 0 014.625 1.25H6.375A6.375 6.375 0 00-6.375-6.375H12a1 1 0 011 1v3a1 1 0 011-1h.375A6.375 6.375 0 011.625 1.25H12a1 1 0 011-1V8a8 8 0 018-8z"></path>
            </svg>
            Liking...
        `;
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json, text/html',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        }
        // If not JSON (e.g., redirect), treat as success and reload
        if (response.ok || response.status === 302) {
            return { success: true, reload: true };
        }
        throw new Error('Network response was not ok');
    })
    .then(data => {
        if (data.success) {
            if (data.reload) {
                window.location.reload();
                return;
            }
            // Show success feedback
            if (button) {
                button.innerHTML = `
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Liked!
                `;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-gray-400', 'cursor-not-allowed');
                button.disabled = true;
            }
            
            // Show success notification
            showNotification('Profile liked successfully!', 'success');
            
            // Redirect if provided
            if (data.redirect_url) {
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            }
        } else {
            // Show error feedback
            if (button) {
                button.innerHTML = `
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Like
                `;
                button.disabled = false;
            }
            
            showNotification(data.message || 'Error liking profile', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // If it's a redirect or successful response, just reload
        if (error.message && error.message.includes('JSON')) {
            window.location.reload();
            return;
        }
        
        if (button) {
            button.innerHTML = `
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Like
                `;
            button.disabled = false;
        }
        
        showNotification('Network error. Please try again.', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotification = document.querySelector('.notification-toast');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-xl flex items-center space-x-3 z-50 animate-fade-in-up ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white`;
    
    notification.innerHTML = `
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            ${type === 'success' ? 
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 4l4 4"></path>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M16 12h4M12 20h.01"></path>'
            }
        </svg>
        <span class="font-medium">${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'translate-y-2', 'transition-all', 'duration-300');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.cursor-not-allowed {
    cursor: not-allowed;
}
</style>
