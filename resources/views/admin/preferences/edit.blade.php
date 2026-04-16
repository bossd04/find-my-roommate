@extends('admin.layouts.app')

@section('title', 'Edit Roommate Preferences')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Edit Roommate Preferences</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    Modifying preferences for <span class="text-indigo-500 dark:text-indigo-400">{{ $preference->user->full_name }}</span>
                </p>
            </div>
            <div class="mt-6 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.preferences.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-xl font-black text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all active:scale-95">
                    <i class="fas fa-arrow-left mr-3"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <form method="POST" action="{{ route('admin.preferences.update', $preference) }}" class="p-8">
            @csrf
            @method('PUT')

            <!-- User Info Card -->
            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-6 mb-8 border border-indigo-100 dark:border-indigo-800">
                <div class="flex items-center">
                    <img src="{{ $preference->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($preference->user->full_name) . '&color=7F9CF5&background=EBF4FF' }}" 
                         alt="{{ $preference->user->full_name }}" 
                         class="h-16 w-16 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $preference->user->full_name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $preference->user->email }}</p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                            <i class="fas fa-university mr-1"></i> {{ $preference->user->university ?: 'No university' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Preferences -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-user-friends mr-2 text-indigo-500"></i>Basic Preferences
                    </h3>

                    <!-- Preferred Gender -->
                    <div>
                        <label for="preferred_gender" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Preferred Gender
                        </label>
                        <select name="preferred_gender" id="preferred_gender" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">No Preference</option>
                            <option value="male" {{ old('preferred_gender', $preference->preferred_gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('preferred_gender', $preference->preferred_gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('preferred_gender', $preference->preferred_gender) === 'other' ? 'selected' : '' }}>Other</option>
                            <option value="no_preference" {{ old('preferred_gender', $preference->preferred_gender) === 'no_preference' ? 'selected' : '' }}>No Preference</option>
                        </select>
                    </div>

                    <!-- Age Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="min_age" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Minimum Age
                            </label>
                            <input type="number" name="min_age" id="min_age" min="18" max="120"
                                   value="{{ old('min_age', $preference->min_age) }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="18">
                        </div>
                        <div>
                            <label for="max_age" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Maximum Age
                            </label>
                            <input type="number" name="max_age" id="max_age" min="18" max="120"
                                   value="{{ old('max_age', $preference->max_age) }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="65">
                        </div>
                    </div>

                    <!-- Budget Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="min_budget" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Minimum Budget (₱)
                            </label>
                            <input type="number" name="min_budget" id="min_budget" min="0" step="100"
                                   value="{{ old('min_budget', $preference->min_budget) }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label for="max_budget" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Maximum Budget (₱)
                            </label>
                            <input type="number" name="max_budget" id="max_budget" min="0" step="100"
                                   value="{{ old('max_budget', $preference->max_budget) }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="50000">
                        </div>
                    </div>

                    <!-- Preferred Location -->
                    <div>
                        <label for="preferred_location" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Preferred Location
                        </label>
                        <input type="text" name="preferred_location" id="preferred_location"
                               value="{{ old('preferred_location', $preference->preferred_location) }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="e.g., Dagupan City, Pangasinan">
                    </div>
                </div>

                <!-- Lifestyle Preferences -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-home mr-2 text-indigo-500"></i>Lifestyle Preferences
                    </h3>

                    <!-- Cleanliness -->
                    <div>
                        <label for="preferred_cleanliness" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Preferred Cleanliness Level
                        </label>
                        <select name="preferred_cleanliness" id="preferred_cleanliness"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">No Preference</option>
                            <option value="very_clean" {{ old('preferred_cleanliness', $preference->preferred_cleanliness) === 'very_clean' ? 'selected' : '' }}>Very Clean</option>
                            <option value="somewhat_clean" {{ old('preferred_cleanliness', $preference->preferred_cleanliness) === 'somewhat_clean' ? 'selected' : '' }}>Somewhat Clean</option>
                            <option value="average" {{ old('preferred_cleanliness', $preference->preferred_cleanliness) === 'average' ? 'selected' : '' }}>Average</option>
                            <option value="somewhat_messy" {{ old('preferred_cleanliness', $preference->preferred_cleanliness) === 'somewhat_messy' ? 'selected' : '' }}>Somewhat Messy</option>
                            <option value="very_messy" {{ old('preferred_cleanliness', $preference->preferred_cleanliness) === 'very_messy' ? 'selected' : '' }}>Very Messy</option>
                        </select>
                    </div>

                    <!-- Noise Level -->
                    <div>
                        <label for="preferred_noise_level" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Preferred Noise Level
                        </label>
                        <select name="preferred_noise_level" id="preferred_noise_level"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">No Preference</option>
                            <option value="quiet" {{ old('preferred_noise_level', $preference->preferred_noise_level) === 'quiet' ? 'selected' : '' }}>Quiet</option>
                            <option value="moderate" {{ old('preferred_noise_level', $preference->preferred_noise_level) === 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="loud" {{ old('preferred_noise_level', $preference->preferred_noise_level) === 'loud' ? 'selected' : '' }}>Loud</option>
                        </select>
                    </div>

                    <!-- Schedule -->
                    <div>
                        <label for="preferred_schedule" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Preferred Schedule
                        </label>
                        <select name="preferred_schedule" id="preferred_schedule"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">No Preference</option>
                            <option value="morning_person" {{ old('preferred_schedule', $preference->preferred_schedule) === 'morning_person' ? 'selected' : '' }}>Morning Person</option>
                            <option value="night_owl" {{ old('preferred_schedule', $preference->preferred_schedule) === 'night_owl' ? 'selected' : '' }}>Night Owl</option>
                            <option value="flexible" {{ old('preferred_schedule', $preference->preferred_schedule) === 'flexible' ? 'selected' : '' }}>Flexible</option>
                        </select>
                    </div>

                    <!-- Smoking & Pets -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Smoking OK?
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="smoking_ok" value="1" 
                                           {{ old('smoking_ok', $preference->smoking_ok) == '1' ? 'checked' : '' }}
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="smoking_ok" value="0"
                                           {{ old('smoking_ok', $preference->smoking_ok) == '0' || old('smoking_ok', $preference->smoking_ok) === null ? 'checked' : '' }}
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Pets OK?
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="pets_ok" value="1"
                                           {{ old('pets_ok', $preference->pets_ok) == '1' ? 'checked' : '' }}
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="pets_ok" value="0"
                                           {{ old('pets_ok', $preference->pets_ok) == '0' || old('pets_ok', $preference->pets_ok) === null ? 'checked' : '' }}
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Housing Preferences -->
            <div class="mt-8 space-y-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    <i class="fas fa-building mr-2 text-indigo-500"></i>Housing Preferences
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Has Apartment Preferred -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Has Apartment
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_apartment_preferred" value="1"
                                       {{ old('has_apartment_preferred', $preference->has_apartment_preferred) == '1' ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_apartment_preferred" value="0"
                                       {{ old('has_apartment_preferred', $preference->has_apartment_preferred) == '0' || old('has_apartment_preferred', $preference->has_apartment_preferred) === null ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Willing to Share Room -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Willing to Share Room
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="willing_to_share_room" value="1"
                                       {{ old('willing_to_share_room', $preference->willing_to_share_room) == '1' ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="willing_to_share_room" value="0"
                                       {{ old('willing_to_share_room', $preference->willing_to_share_room) == '0' || old('willing_to_share_room', $preference->willing_to_share_room) === null ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Furnished Preferred -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Furnished Preferred
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="furnished_preferred" value="1"
                                       {{ old('furnished_preferred', $preference->furnished_preferred) == '1' ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="furnished_preferred" value="0"
                                       {{ old('furnished_preferred', $preference->furnished_preferred) == '0' || old('furnished_preferred', $preference->furnished_preferred) === null ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Utilities Included -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Utilities Included
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="utilities_included_preferred" value="1"
                                       {{ old('utilities_included_preferred', $preference->utilities_included_preferred) == '1' ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="utilities_included_preferred" value="0"
                                       {{ old('utilities_included_preferred', $preference->utilities_included_preferred) == '0' || old('utilities_included_preferred', $preference->utilities_included_preferred) === null ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Room Type -->
                    <div>
                        <label for="preferred_room_type" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Room Type
                        </label>
                        <input type="text" name="preferred_room_type" id="preferred_room_type"
                               value="{{ old('preferred_room_type', $preference->preferred_room_type) }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="e.g., Single, Shared">
                    </div>

                    <!-- Lease Duration -->
                    <div>
                        <label for="preferred_lease_duration" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Lease Duration
                        </label>
                        <input type="text" name="preferred_lease_duration" id="preferred_lease_duration"
                               value="{{ old('preferred_lease_duration', $preference->preferred_lease_duration) }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="e.g., 6 months, 1 year">
                    </div>
                </div>

                <!-- Move-in Date -->
                <div>
                    <label for="preferred_move_in_date" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Preferred Move-in Date
                    </label>
                    <input type="date" name="preferred_move_in_date" id="preferred_move_in_date"
                           value="{{ old('preferred_move_in_date', $preference->preferred_move_in_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-10 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                <a href="{{ route('admin.preferences.index') }}" 
                   class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-save mr-2"></i> Update Preferences
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
