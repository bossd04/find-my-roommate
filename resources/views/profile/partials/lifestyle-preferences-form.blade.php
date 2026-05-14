<section class="space-y-6">
    @php
        $lifestyleComplete = !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance);
        $lifestyleFields = [
            'Cleanliness Level' => !empty($profile->cleanliness_level),
            'Sleep Pattern' => !empty($profile->sleep_pattern),
            'Study Habit' => !empty($profile->study_habit),
            'Noise Tolerance' => !empty($profile->noise_tolerance),
        ];
    @endphp
    
    <div class="bg-gray-50 border rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold text-sm">Lifestyle Status</h3>
            @if($lifestyleComplete)
                <span class="text-green-600 text-sm font-bold">✓ Complete</span>
            @else
                <span class="text-orange-500 text-sm font-bold">⚠ Incomplete</span>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-2 text-xs">
            @foreach($lifestyleFields as $field => $filled)
                <div class="flex items-center">
                    @if($filled)
                        <span class="text-green-500 mr-1">✓</span>
                        <span class="text-gray-700">{{ $field }}</span>
                    @else
                        <span class="text-red-500 mr-1">✗</span>
                        <span class="text-red-600">{{ $field }} (missing)</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <form method="post" action="{{ route('profile.update.lifestyle') }}" enctype="multipart/form-data" id="lifestyle-form">
        @csrf
        <input type="hidden" name="form_section" value="lifestyle">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-green-800">{{ session('success') }}</h4>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Living Habits -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="cleanliness_level" :value="__('Cleanliness Level')" />
                    <select id="cleanliness_level" name="cleanliness_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200" required onchange="updateCleanlinessDisplay(this.value)">
                        <option value="">Select Cleanliness Level</option>
                        <option value="very_clean" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'very_clean' ? 'selected' : '' }}>Very Clean - Everything must be spotless</option>
                        <option value="somewhat_clean" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'somewhat_clean' ? 'selected' : '' }}>Somewhat Clean - Generally tidy</option>
                        <option value="average" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'average' ? 'selected' : '' }}>Average - Generally clean but not perfect</option>
                        <option value="somewhat_messy" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'somewhat_messy' ? 'selected' : '' }}>Somewhat Messy - A bit cluttered is okay</option>
                        <option value="very_messy" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'very_messy' ? 'selected' : '' }}>Very Messy - Cluttered is fine</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('cleanliness_level')" />
                    
                    <!-- Real-time Display -->
                    <div id="cleanliness-display" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div id="cleanliness-icon" class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3-1m3 1v6a1 1 0 001 1h6a1 1 0 001-1V7a1 1 0 00-1-1h-6a1 1 0 00-1 1v6a1 1 0 001 1m0 0V9a2 2 0 002 2h6a2 2 0 002-2V9a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Your Cleanliness Level</p>
                                <p id="cleanliness-description" class="text-xs text-gray-500">Select your preference</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="sleep_pattern" :value="__('Sleep Pattern')" />
                    <select id="sleep_pattern" name="sleep_pattern" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200" required onchange="updateSleepPatternDisplay(this.value)">
                        <option value="">Select Sleep Pattern</option>
                        <option value="early_bird" {{ old('sleep_pattern', $profile->sleep_pattern ?? '') == 'early_bird' ? 'selected' : '' }}>Early Bird - Sleep early, wake early</option>
                        <option value="night_owl" {{ old('sleep_pattern', $profile->sleep_pattern ?? '') == 'night_owl' ? 'selected' : '' }}>Night Owl - Sleep late, wake late</option>
                        <option value="flexible" {{ old('sleep_pattern', $profile->sleep_pattern ?? '') == 'flexible' ? 'selected' : '' }}>Flexible - Can adapt to any schedule</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('sleep_pattern')" />
                    
                    <!-- Real-time Display -->
                    <div id="sleep-display" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div id="sleep-icon" class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9 9 0 00-6.364 0L12 2.646l-2.292 2.292a9 9 0 00-6.364 0 9 9 0 0012.707 12.707z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Your Sleep Pattern</p>
                                <p id="sleep-description" class="text-xs text-gray-500">Select your preference</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="study_habit" :value="__('Study Habit')" />
                    <select id="study_habit" name="study_habit" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200" required onchange="updateStudyHabitDisplay(this.value)">
                        <option value="">Select Study Habit</option>
                        <option value="intense" {{ old('study_habit', $profile->study_habit ?? '') == 'intense' ? 'selected' : '' }}>Intense - Need complete quiet</option>
                        <option value="moderate" {{ old('study_habit', $profile->study_habit ?? '') == 'moderate' ? 'selected' : '' }}>Moderate - Some noise is okay</option>
                        <option value="social" {{ old('study_habit', $profile->study_habit ?? '') == 'social' ? 'selected' : '' }}>Social - Like studying with others</option>
                        <option value="quiet" {{ old('study_habit', $profile->study_habit ?? '') == 'quiet' ? 'selected' : '' }}>Quiet - Need complete silence</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('study_habit')" />
                    
                    <!-- Real-time Display -->
                    <div id="study-display" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div id="study-icon" class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.75 5H4.75C3.6 5 2.5 5.477 2.5 6.75v13C2.5 21.996 3.6 22.523 4.75 22.5h15.5c1.4 0 2.5-.527 2.5-1.75V6.75zM10.5 13.5H8.254l2.576 2.576c.143.06.283.123.43.123l2.576-2.576c.063-.063.06-.13.123-.197l2.576-2.576C13.536 12.924 14.063 12.8 14.5 12.8c.063 0 .13-.06.197-.123l2.576-2.576c.063-.063.06-.13.123-.197l-2.576 2.576z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Your Study Habit</p>
                                <p id="study-description" class="text-xs text-gray-500">Select your preference</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="noise_tolerance" :value="__('Noise Tolerance')" />
                    <select id="noise_tolerance" name="noise_tolerance" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200" required onchange="updateNoiseToleranceDisplay(this.value)">
                        <option value="">Select Noise Tolerance</option>
                        <option value="quiet" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'quiet' ? 'selected' : '' }}>Quiet - Prefer quiet environment</option>
                        <option value="moderate" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'moderate' ? 'selected' : '' }}>Moderate - Some noise is okay</option>
                        <option value="loud" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'loud' ? 'selected' : '' }}>Loud - Noise doesn't bother me</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('noise_tolerance')" />
                    
                    <!-- Real-time Display -->
                    <div id="noise-display" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div id="noise-icon" class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5.143 5.143 0 010-7.072 0 5.143 5.143 0 007.072 0zM12 13a3 3 0 100-6 3 3 0 006 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Your Noise Tolerance</p>
                                <p id="noise-description" class="text-xs text-gray-500">Select your preference</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="budget_min" :value="__('Minimum Budget')" />
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₱</span>
                        </div>
                        <x-text-input id="budget_min" name="budget_min" type="number" min="0" step="100" class="mt-1 block w-full pl-8" 
                            :value="old('budget_min', $profile->budget_min ?? '')" placeholder="0.00" oninput="updateBudgetDisplay()" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('budget_min')" />
                    
                    <!-- Real-time Budget Display -->
                    <div id="budget-display" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s.895 3 3 3 3-.895 3-3-.895-3-3-3zm0 14c-1.657 0-3 .895-3 3s.895 3 3 3 3-.895 3-3-.895-3-3-3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Your Budget Range</p>
                                <p id="budget-description" class="text-xs text-gray-500">Set your budget range</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="budget_max" :value="__('Maximum Budget')" />
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₱</span>
                        </div>
                        <x-text-input id="budget_max" name="budget_max" type="number" min="0" step="100" class="mt-1 block w-full pl-8" 
                            :value="old('budget_max', $profile->budget_max ?? '')" placeholder="0.00" oninput="updateBudgetDisplay()" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('budget_max')" />
                </div>
            </div>
        </div>

        <!-- Social and Daily Habits -->
        <div class="mt-8 pt-6 border-t border-gray-100">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Social and Daily Habits</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Smoking -->
                <div>
                    <x-input-label for="smoking" :value="__('Smoking Habits')" />
                    <select id="smoking" name="smoking" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200">
                        <option value="">No preference set</option>
                        <option value="never" {{ old('smoking', $preferences->smoking ?? '') == 'never' ? 'selected' : '' }}>Never - I don't smoke</option>
                        <option value="sometimes" {{ old('smoking', $preferences->smoking ?? '') == 'sometimes' ? 'selected' : '' }}>Sometimes - Occasional smoker</option>
                        <option value="regularly" {{ old('smoking', $preferences->smoking ?? '') == 'regularly' ? 'selected' : '' }}>Regularly - Daily smoker</option>
                        <option value="only_outside" {{ old('smoking', $preferences->smoking ?? '') == 'only_outside' ? 'selected' : '' }}>Only Outside - Smoke only in designated areas</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('smoking')" />
                </div>

                <!-- Pets -->
                <div>
                    <x-input-label for="pets" :value="__('Pet Preferences')" />
                    <select id="pets" name="pets" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200">
                        <option value="">No preference set</option>
                        <option value="none" {{ old('pets', $preferences->pets ?? '') == 'none' ? 'selected' : '' }}>No Pets - I don't have pets</option>
                        <option value="cats_ok" {{ old('pets', $preferences->pets ?? '') == 'cats_ok' ? 'selected' : '' }}>Cats OK - I like/have cats</option>
                        <option value="dogs_ok" {{ old('pets', $preferences->pets ?? '') == 'dogs_ok' ? 'selected' : '' }}>Dogs OK - I like/have dogs</option>
                        <option value="all_pets_ok" {{ old('pets', $preferences->pets ?? '') == 'all_pets_ok' ? 'selected' : '' }}>All Pets OK - I love all animals</option>
                        <option value="no_pets" {{ old('pets', $preferences->pets ?? '') == 'no_pets' ? 'selected' : '' }}>Strictly No Pets - Allergic/dislike pets</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('pets')" />
                </div>

                <!-- Overnight Visitors -->
                <div>
                    <x-input-label for="overnight_visitors" :value="__('Overnight Visitors')" />
                    <select id="overnight_visitors" name="overnight_visitors" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200">
                        <option value="">No preference set</option>
                        <option value="not_allowed" {{ old('overnight_visitors', $preferences->overnight_visitors ?? '') == 'not_allowed' ? 'selected' : '' }}>Not Allowed - No overnight guests</option>
                        <option value="with_notice" {{ old('overnight_visitors', $preferences->overnight_visitors ?? '') == 'with_notice' ? 'selected' : '' }}>With Notice - OK if told in advance</option>
                        <option value="anytime" {{ old('overnight_visitors', $preferences->overnight_visitors ?? '') == 'anytime' ? 'selected' : '' }}>Anytime - Flexible with guests</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('overnight_visitors')" />
                </div>

                <!-- Daily Schedule -->
                <div>
                    <x-input-label for="schedule" :value="__('Daily Schedule')" />
                    <select id="schedule" name="schedule" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200">
                        <option value="">No preference set</option>
                        <option value="morning" {{ old('schedule', $preferences->schedule ?? '') == 'morning' ? 'selected' : '' }}>Morning - Active during the day</option>
                        <option value="evening" {{ old('schedule', $preferences->schedule ?? '') == 'evening' ? 'selected' : '' }}>Evening - Active during afternoon/early night</option>
                        <option value="night_shift" {{ old('schedule', $preferences->schedule ?? '') == 'night_shift' ? 'selected' : '' }}>Night Shift - Work/active during late night</option>
                        <option value="irregular" {{ old('schedule', $preferences->schedule ?? '') == 'irregular' ? 'selected' : '' }}>Irregular - Schedule changes frequently</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('schedule')" />
                </div>
            </div>
        </div>

        <!-- Additional Preferences -->
        <div class="mt-6">
            <x-input-label for="hobbies" :value="__('Hobbies and Interests')" />
            <div class="mt-2 space-y-2">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Reading" {{ in_array('Reading', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">📚 Reading</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Gaming" {{ in_array('Gaming', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">🎮 Gaming</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Sports" {{ in_array('Sports', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">⚽ Sports</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Music" {{ in_array('Music', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">🎵 Music</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Movies" {{ in_array('Movies', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">🎬 Movies</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Cooking" {{ in_array('Cooking', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">👨‍🍳 Cooking</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Travel" {{ in_array('Travel', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">✈️ Travel</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Photography" {{ in_array('Photography', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">📷 Photography</span>
                    </label>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" name="hobbies[]" value="Art" {{ in_array('Art', old('hobbies', $profile->hobbies ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="updateHobbiesDisplay()">
                        <span class="ml-2 text-sm text-gray-700">🎨 Art</span>
                    </label>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('hobbies')" />
            
            <!-- Hobbies Display -->
            <div id="hobbies-display" class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Your Hobbies</p>
                        <p id="hobbies-description" class="text-xs text-gray-500">Select your interests</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <button type="button" onclick="submitLifestyleForm(this)" id="lifestyle-submit-btn" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Lifestyle Preferences
            </button>
        </div>
    </form>
</section>

<script>
function submitLifestyleForm(button) {
    console.log('Submitting lifestyle form...');
    console.log('Button element:', button);
    console.log('Form action:', document.querySelector('form[action*="profile.update.lifestyle"]')?.action);
    
    // Show loading state
    const submitBtn = button;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Saving...';
    submitBtn.disabled = true;
    
    // Create form data manually
    const form = document.querySelector('form[action*="profile.update.lifestyle"]');
    console.log('Form found:', form);
    
    if (!form) {
        console.error('Form not found!');
        alert('Form not found. Please refresh the page and try again.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }
    
    const formData = new FormData(form);
    
    // DEBUG: Log form data
    console.log('Form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }
    
    // DEBUG: Specifically check sleep_pattern value
    const sleepPattern = formData.get('sleep_pattern');
    console.log('Sleep pattern value being sent:', sleepPattern);
    console.log('Is sleep_pattern valid?', ['early_bird', 'night_owl', 'flexible'].includes(sleepPattern));
    
    // Client-side validation before submission
    if (!sleepPattern || !['early_bird', 'night_owl', 'flexible'].includes(sleepPattern)) {
        console.error('Invalid sleep pattern selected:', sleepPattern);
        alert('Please select a valid sleep pattern from the dropdown.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }
    
    // Submit via fetch to ensure proper handling
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Show success state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Saved!';
            submitBtn.classList.remove('bg-blue-600');
            submitBtn.classList.add('bg-green-600');
            
            // Reload page after short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            // Show error state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Error';
            submitBtn.classList.remove('bg-blue-600');
            submitBtn.classList.add('bg-red-600');
            
            // Reset button after delay
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-red-600');
                submitBtn.classList.add('bg-blue-600');
            }, 5000);
        }
    })
    .catch(error => {
        console.error('Submission error:', error);
        alert('❌ Network error occurred while saving your lifestyle preferences.\n\nPlease check your connection and try again.');
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-red-600');
        submitBtn.classList.add('bg-blue-600');
    });
}

function updateCleanlinessDisplay(value) {
    const display = document.getElementById('cleanliness-display');
    const icon = document.getElementById('cleanliness-icon');
    const description = document.getElementById('cleanliness-description');
    
    console.log('Updating cleanliness display with value:', value);
    
    const descriptions = {
        'very_clean': 'Everything must be spotless',
        'somewhat_clean': 'Generally tidy',
        'average': 'Generally clean but not perfect',
        'somewhat_messy': 'A bit cluttered is okay',
        'very_messy': 'Cluttered is fine'
    };
    
    const colors = {
        'very_clean': 'bg-green-100 text-green-600',
        'somewhat_clean': 'bg-blue-100 text-blue-600',
        'average': 'bg-yellow-100 text-yellow-600',
        'somewhat_messy': 'bg-orange-100 text-orange-600',
        'very_messy': 'bg-red-100 text-red-600'
    };
    
    const borderColors = {
        'very_clean': 'border-green-200',
        'somewhat_clean': 'border-blue-200',
        'average': 'border-yellow-200',
        'somewhat_messy': 'border-orange-200',
        'very_messy': 'border-red-200'
    };
    
    if (display && description && icon) {
        // Remove all border classes first
        display.className = display.className.replace(/border-\w+-200/g, '');
        
        if (value && descriptions[value]) {
            description.textContent = descriptions[value];
            icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 ' + colors[value];
            display.classList.add(borderColors[value]);
            display.classList.remove('border-gray-200');
            
            // Add persistent display indicator
            display.classList.add('bg-opacity-100');
            display.style.display = 'block';
            display.style.visibility = 'visible';
            
            console.log('Cleanliness display updated successfully:', descriptions[value]);
        } else {
            description.textContent = 'Select your preference';
            icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 text-gray-600';
            display.classList.add('border-gray-200');
            display.classList.remove('bg-opacity-100');
        }
    } else {
        console.error('Cleanliness display elements not found:', { display, icon, description });
    }
}

function updateSleepPatternDisplay(value) {
    const display = document.getElementById('sleep-display');
    const icon = document.getElementById('sleep-icon');
    const description = document.getElementById('sleep-description');
    
    console.log('Updating sleep pattern display with value:', value);
    
    const descriptions = {
        'early_bird': 'Sleep early, wake early',
        'night_owl': 'Sleep late, wake late',
        'flexible': 'Can adapt to any schedule'
    };
    
    const colors = {
        'early_bird': 'bg-yellow-100 text-yellow-600',
        'night_owl': 'bg-purple-100 text-purple-600',
        'flexible': 'bg-green-100 text-green-600'
    };
    
    const borderColors = {
        'early_bird': 'border-yellow-200',
        'night_owl': 'border-purple-200',
        'flexible': 'border-green-200'
    };
    
    if (display && description && icon) {
        // Remove all border classes first
        display.className = display.className.replace(/border-\w+-200/g, '');
        
        if (value && descriptions[value]) {
            description.textContent = descriptions[value];
            icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 ' + colors[value];
            display.classList.add(borderColors[value]);
            display.classList.remove('border-gray-200');
            
            // Add persistent display indicator
            display.classList.add('bg-opacity-100');
            display.style.display = 'block';
            
            console.log('Sleep pattern display updated successfully:', descriptions[value]);
        } else {
            description.textContent = 'Select your preference';
            icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 text-gray-600';
            display.classList.add('border-gray-200');
            display.classList.remove('bg-opacity-100');
        }
    } else {
        console.error('Sleep pattern display elements not found:', { display, icon, description });
    }
}

function updateStudyHabitDisplay(value) {
    const display = document.getElementById('study-display');
    const icon = document.getElementById('study-icon');
    const description = document.getElementById('study-description');
    
    console.log('Updating study habit display with value:', value);
    
    const descriptions = {
        'intense': 'Need complete quiet',
        'moderate': 'Some noise is okay',
        'social': 'Like studying with others',
        'quiet': 'Need complete silence'
    };
    
    const colors = {
        'intense': 'bg-red-100 text-red-600',
        'moderate': 'bg-blue-100 text-blue-600',
        'social': 'bg-green-100 text-green-600',
        'quiet': 'bg-purple-100 text-purple-600'
    };
    
    const borderColors = {
        'intense': 'border-red-200',
        'moderate': 'border-blue-200',
        'social': 'border-green-200',
        'quiet': 'border-purple-200'
    };
    
    if (display && description && icon) {
        // Remove all border classes first
        display.className = display.className.replace(/border-\w+-200/g, '');
        
        if (value && descriptions[value]) {
            description.textContent = descriptions[value];
            display.className = `mt-2 p-3 rounded-lg border ${colors[value]} ${borderColors[value]}`;
            icon.className = `fas fa-book text-2xl ${colors[value].split(' ')[1]}`;
        } else {
            description.textContent = 'Select your study preference';
            display.className = 'mt-2 p-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-600';
            icon.className = 'fas fa-book text-2xl text-gray-400';
        }
    }
}

// Update Noise Tolerance Display
function updateNoiseToleranceDisplay(value) {
    const display = document.getElementById('noise-display');
    const icon = document.getElementById('noise-icon');
    const description = document.getElementById('noise-description');
    
    console.log('Updating noise tolerance display with value:', value);
    
    const descriptions = {
        'quiet': 'Prefer quiet environment',
        'moderate': 'Some noise is okay',
        'loud': "Noise doesn't bother me"
    };
    
    const colors = {
        'quiet': 'bg-orange-100 text-orange-600',
        'moderate': 'bg-yellow-100 text-yellow-600',
        'loud': 'bg-green-100 text-green-600'
    };
    
    const borderColors = {
        'quiet': 'border-orange-200',
        'moderate': 'border-yellow-200',
        'loud': 'border-green-200'
    };
    
    if (display && description && icon) {
        // Remove all border classes first
        display.className = display.className.replace(/border-\w+-200/g, '');
        
        if (value && descriptions[value]) {
            description.textContent = descriptions[value];
            display.className = `mt-2 p-3 rounded-lg border ${colors[value]} ${borderColors[value]}`;
            icon.className = `fas fa-volume-down text-2xl ${colors[value].split(' ')[1]}`;
        } else {
            description.textContent = 'Select your noise tolerance level';
            display.className = 'mt-2 p-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-600';
            icon.className = 'fas fa-volume-down text-2xl text-gray-400';
        }
    }
}

// Update Budget Display
function updateBudgetDisplay() {
    const minBudget = document.getElementById('budget_min');
    const maxBudget = document.getElementById('budget_max');
    const display = document.getElementById('budget-display');
    const icon = document.getElementById('budget-icon');
    const description = document.getElementById('budget-description');
    
    console.log('Updating budget display...');
    
    const minValue = minBudget ? parseFloat(minBudget.value) || 0 : 0;
    const maxValue = maxBudget ? parseFloat(maxBudget.value) || 0 : 0;
    
    if (display && description && icon) {
        if (minValue > 0 || maxValue > 0) {
            let budgetText = '';
            if (minValue > 0 && maxValue > 0) {
                budgetText = `₱${minValue.toLocaleString()} - ₱${maxValue.toLocaleString()}`;
            } else if (minValue > 0) {
                budgetText = `₱${minValue.toLocaleString()}+`;
            } else if (maxValue > 0) {
                budgetText = `Up to ₱${maxValue.toLocaleString()}`;
            }
            
            description.textContent = budgetText;
            display.className = 'mt-2 p-3 rounded-lg border border-green-200 bg-green-50 text-green-600';
            icon.className = 'fas fa-peso-sign text-2xl text-green-600';
        } else {
            description.textContent = 'Set your budget range';
            display.className = 'mt-2 p-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-600';
            icon.className = 'fas fa-peso-sign text-2xl text-gray-400';
        }
    }
}

// Update Hobbies Display
function updateHobbiesDisplay() {
    const checkboxes = document.querySelectorAll('input[name="hobbies[]"]:checked');
    const display = document.getElementById('hobbies-display');
    const icon = document.getElementById('hobbies-icon');
    const description = document.getElementById('hobbies-description');
    
    console.log('Updating hobbies display...');
    
    if (display && description && icon) {
        const selectedHobbies = Array.from(checkboxes).map(cb => cb.value);
        
        if (selectedHobbies.length > 0) {
            const hobbiesText = selectedHobbies.length > 3 
                ? `${selectedHobbies.slice(0, 3).join(', ')} and ${selectedHobbies.length - 3} more`
                : selectedHobbies.join(', ');
            
            description.textContent = hobbiesText;
            display.className = 'mt-2 p-3 rounded-lg border border-purple-200 bg-purple-50 text-purple-600';
            icon.className = 'fas fa-heart text-2xl text-purple-600';
        } else {
            description.textContent = 'Select your hobbies';
            display.className = 'mt-2 p-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-600';
            icon.className = 'fas fa-heart text-2xl text-gray-400';
        }
    }
}

// Submit Lifestyle Form
function submitLifestyleForm(button) {
    console.log('Submitting lifestyle form...');
    console.log('Button element:', button);
    
    // Show loading state
    const submitBtn = button;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Saving...';
    submitBtn.disabled = true;
    
    // Get the form that contains this button
    let form = button.closest('form');
    if (!form) {
        // Fallback: find the lifestyle form
        form = document.querySelector('form[action*="profile.update.lifestyle"]');
    }
    
    if (!form) {
        console.error('Form not found for lifestyle submission');
        showNotification('Error: Could not find form. Please refresh the page and try again.', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }
    
    console.log('Form found:', form);
    console.log('Form action:', form.action);
    
    // Create form data manually
    const formData = new FormData(form);
    
    // Submit via fetch to ensure proper handling
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Show success state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Saved Successfully!';
            submitBtn.classList.remove('bg-blue-600');
            submitBtn.classList.add('bg-green-600');
            
            // Show success notification
            showNotification('Lifestyle preferences saved successfully!', 'success');
            
            // Reload page after short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            // Show error message
            showNotification(data.message || 'Error saving preferences', 'error');
            
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Submit error:', error);
        showNotification('Network error. Please try again.', 'error');
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Notification function
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

// Initialize displays on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing lifestyle preferences displays...');
    
    // Wait a bit for all elements to be properly loaded
    setTimeout(() => {
        // Initialize current values from the form (database values)
        const cleanlinessLevel = document.getElementById('cleanliness_level').value;
        const sleepPattern = document.getElementById('sleep_pattern').value;
        const studyHabit = document.getElementById('study_habit').value;
        const noiseTolerance = document.getElementById('noise_tolerance').value;
        
        console.log('Initializing displays with values:', {
            cleanliness: cleanlinessLevel,
            sleep: sleepPattern,
            study: studyHabit,
            noise: noiseTolerance
        });
        
        // Force display elements to be visible
        const cleanlinessDisplay = document.getElementById('cleanliness-display');
        const sleepDisplay = document.getElementById('sleep-display');
        const studyDisplay = document.getElementById('study-display');
        const noiseDisplay = document.getElementById('noise-display');
        
        if (cleanlinessDisplay) {
            cleanlinessDisplay.style.display = 'block';
            cleanlinessDisplay.style.visibility = 'visible';
        }
        
        if (sleepDisplay) {
            sleepDisplay.style.display = 'block';
            sleepDisplay.style.visibility = 'visible';
        }
        
        if (studyDisplay) {
            studyDisplay.style.display = 'block';
            studyDisplay.style.visibility = 'visible';
        }
        
        if (noiseDisplay) {
            noiseDisplay.style.display = 'block';
            noiseDisplay.style.visibility = 'visible';
        }
        
        // Update displays with current values
        if (cleanlinessLevel) {
            updateCleanlinessDisplay(cleanlinessLevel);
        }
        if (sleepPattern) {
            updateSleepPatternDisplay(sleepPattern);
        }
        if (studyHabit) {
            updateStudyHabitDisplay(studyHabit);
        }
        if (noiseTolerance) {
            updateNoiseToleranceDisplay(noiseTolerance);
        }
        
        // Update budget and hobbies displays
        updateBudgetDisplay();
        updateHobbiesDisplay();
        
        // Add enhanced event listeners to ensure displays update immediately
        ['cleanliness_level', 'sleep_pattern', 'study_habit', 'noise_tolerance'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                // Force update display on change
                element.addEventListener('change', function() {
                    console.log(`Element ${id} changed to:`, this.value);
                    setTimeout(() => {
                        switch(id) {
                            case 'cleanliness_level':
                                updateCleanlinessDisplay(this.value);
                                break;
                            case 'sleep_pattern':
                                updateSleepPatternDisplay(this.value);
                                break;
                            case 'study_habit':
                                updateStudyHabitDisplay(this.value);
                                break;
                            case 'noise_tolerance':
                                updateNoiseToleranceDisplay(this.value);
                                break;
                        }
                    }, 10);
                });
                
                // Also update on blur for immediate feedback
                element.addEventListener('blur', function() {
                    setTimeout(() => {
                        switch(id) {
                            case 'cleanliness_level':
                                updateCleanlinessDisplay(this.value);
                                break;
                            case 'sleep_pattern':
                                updateSleepPatternDisplay(this.value);
                                break;
                            case 'study_habit':
                                updateStudyHabitDisplay(this.value);
                                break;
                            case 'noise_tolerance':
                                updateNoiseToleranceDisplay(this.value);
                                break;
                        }
                    }, 10);
                });
            }
        });
        
        console.log('Lifestyle preferences displays initialized successfully');
    }, 100);
});
</script>
