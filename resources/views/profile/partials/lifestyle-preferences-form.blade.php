<section class="space-y-6">
    <form method="post" action="{{ route('profile.update.details') }}" enctype="multipart/form-data" id="lifestyle-form">
        @csrf
        @method('patch')
        <input type="hidden" name="form_section" value="lifestyle_preferences">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Living Habits -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="cleanliness_level" :value="__('Cleanliness Level')" />
                    <select id="cleanliness_level" name="cleanliness_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-200" required onchange="updateCleanlinessDisplay(this.value)">
                        <option value="">Select Cleanliness Level</option>
                        <option value="very_clean" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'very_clean' ? 'selected' : '' }}>Very Clean - Everything must be spotless</option>
                        <option value="clean" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'clean' ? 'selected' : '' }}>Clean - Tidy and organized</option>
                        <option value="average" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'average' ? 'selected' : '' }}>Average - Generally clean but not perfect</option>
                        <option value="messy" {{ old('cleanliness_level', $profile->cleanliness_level ?? '') == 'messy' ? 'selected' : '' }}>Messy - A bit cluttered is okay</option>
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
                        <option value="light_sleeper" {{ old('sleep_pattern', $profile->sleep_pattern ?? '') == 'light_sleeper' ? 'selected' : '' }}>Light Sleeper - Easily disturbed</option>
                        <option value="heavy_sleeper" {{ old('sleep_pattern', $profile->sleep_pattern ?? '') == 'heavy_sleeper' ? 'selected' : '' }}>Heavy Sleeper - Hard to disturb</option>
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
                        <option value="silent" {{ old('study_habit', $profile->study_habit ?? '') == 'silent' ? 'selected' : '' }}>Silent - Need complete quiet</option>
                        <option value="background_noise" {{ old('study_habit', $profile->study_habit ?? '') == 'background_noise' ? 'selected' : '' }}>Background Noise - Music/TV okay</option>
                        <option value="library" {{ old('study_habit', $profile->study_habit ?? '') == 'library' ? 'selected' : '' }}>Library - Prefer studying elsewhere</option>
                        <option value="flexible" {{ old('study_habit', $profile->study_habit ?? '') == 'flexible' ? 'selected' : '' }}>Flexible - Can study anywhere</option>
                        <option value="group_study" {{ old('study_habit', $profile->study_habit ?? '') == 'group_study' ? 'selected' : '' }}>Group Study - Like studying with others</option>
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
                        <option value="very_sensitive" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'very_sensitive' ? 'selected' : '' }}>Very Sensitive - Need complete silence</option>
                        <option value="sensitive" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'sensitive' ? 'selected' : '' }}>Sensitive - Prefer quiet environment</option>
                        <option value="moderate" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'moderate' ? 'selected' : '' }}>Moderate - Some noise is okay</option>
                        <option value="tolerant" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'tolerant' ? 'selected' : '' }}>Tolerant - Can handle noise</option>
                        <option value="very_tolerant" {{ old('noise_tolerance', $profile->noise_tolerance ?? '') == 'very_tolerant' ? 'selected' : '' }}>Very Tolerant - Noise doesn't bother me</option>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3-2s-.895-3-3-3 1.657 0 3 .895 3 3-.895 3-3 3-3zm0 14c-1.657 0-3 .895-3 3s.895 3 3 3-.895 3-3 3-3 3-3-.895-3-3z"></path>
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
            <button type="button" onclick="submitLifestyleForm()" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" id="lifestyle-submit">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Lifestyle Preferences
            </button>
        </div>
    </form>
</section>

<script>
// Real-time display functions
function updateCleanlinessDisplay(value) {
    const display = document.getElementById('cleanliness-display');
    const icon = document.getElementById('cleanliness-icon');
    const description = document.getElementById('cleanliness-description');
    
    console.log('Updating cleanliness display with value:', value);
    
    const descriptions = {
        'very_clean': 'Everything must be spotless',
        'clean': 'Tidy and organized',
        'average': 'Generally clean but not perfect',
        'messy': 'A bit cluttered is okay'
    };
    
    const colors = {
        'very_clean': 'bg-green-100 text-green-600',
        'clean': 'bg-blue-100 text-blue-600',
        'average': 'bg-yellow-100 text-yellow-600',
        'messy': 'bg-orange-100 text-orange-600'
    };
    
    const borderColors = {
        'very_clean': 'border-green-200',
        'clean': 'border-blue-200',
        'average': 'border-yellow-200',
        'messy': 'border-orange-200'
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
        'flexible': 'Can adapt to any schedule',
        'light_sleeper': 'Easily disturbed',
        'heavy_sleeper': 'Hard to disturb'
    };
    
    const colors = {
        'early_bird': 'bg-yellow-100 text-yellow-600',
        'night_owl': 'bg-purple-100 text-purple-600',
        'flexible': 'bg-green-100 text-green-600',
        'light_sleeper': 'bg-red-100 text-red-600',
        'heavy_sleeper': 'bg-indigo-100 text-indigo-600'
    };
    
    const borderColors = {
        'early_bird': 'border-yellow-200',
        'night_owl': 'border-purple-200',
        'flexible': 'border-green-200',
        'light_sleeper': 'border-red-200',
        'heavy_sleeper': 'border-indigo-200'
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
        'silent': 'Need complete quiet',
        'background_noise': 'Music/TV okay',
        'library': 'Prefer studying elsewhere',
        'flexible': 'Can study anywhere',
        'group_study': 'Like studying with others'
    };
    
    const colors = {
        'silent': 'bg-red-100 text-red-600',
        'background_noise': 'bg-blue-100 text-blue-600',
        'library': 'bg-green-100 text-green-600',
        'flexible': 'bg-purple-100 text-purple-600',
        'group_study': 'bg-orange-100 text-orange-600'
    };
    
    const borderColors = {
        'silent': 'border-red-200',
        'background_noise': 'border-blue-200',
        'library': 'border-green-200',
        'flexible': 'border-purple-200',
        'group_study': 'border-orange-200'
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
            
            console.log('Study habit display updated successfully:', descriptions[value]);
        } else {
            description.textContent = 'Select your preference';
            icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 text-gray-600';
            display.classList.add('border-gray-200');
            display.classList.remove('bg-opacity-100');
        }
    } else {
        console.error('Study habit display elements not found:', { display, icon, description });
    }
}

function updateNoiseToleranceDisplay(value) {
    const display = document.getElementById('noise-display');
    const icon = document.getElementById('noise-icon');
    const description = document.getElementById('noise-description');
    
    console.log('Updating noise tolerance display with value:', value);
    
    const descriptions = {
        'very_sensitive': 'Need complete silence',
        'sensitive': 'Prefer quiet environment',
        'moderate': 'Some noise is okay',
        'tolerant': 'Can handle noise',
        'very_tolerant': 'Noise doesn\'t bother me'
    };
    
    const colors = {
        'very_sensitive': 'bg-red-100 text-red-600',
        'sensitive': 'bg-orange-100 text-orange-600',
        'moderate': 'bg-yellow-100 text-yellow-600',
        'tolerant': 'bg-green-100 text-green-600',
        'very_tolerant': 'bg-blue-100 text-blue-600'
    };
    
    const borderColors = {
        'very_sensitive': 'border-red-200',
        'sensitive': 'border-orange-200',
        'moderate': 'border-yellow-200',
        'tolerant': 'border-green-200',
        'very_tolerant': 'border-blue-200'
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
            
            console.log('Noise tolerance display updated successfully:', descriptions[value]);
        } else {
            description.textContent = 'Select your preference';
            icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 text-gray-600';
            display.classList.add('border-gray-200');
            display.classList.remove('bg-opacity-100');
        }
    } else {
        console.error('Noise tolerance display elements not found:', { display, icon, description });
    }
}

function updateBudgetDisplay() {
    const minBudget = document.getElementById('budget_min').value || 0;
    const maxBudget = document.getElementById('budget_max').value || 0;
    const display = document.getElementById('budget-display');
    const description = document.getElementById('budget-description');
    
    if (display && description) {
        // Remove all border classes first
        display.className = display.className.replace(/border-\w+-200/g, '');
        
        if (minBudget || maxBudget) {
            const range = minBudget && maxBudget ? `₱${minBudget} - ₱${maxBudget}` : 'Set your budget range';
            description.textContent = range;
            display.classList.add('border-green-200');
            display.classList.remove('border-gray-200');
        } else {
            description.textContent = 'Set your budget range';
            display.classList.add('border-gray-200');
        }
    }
}

function updateHobbiesDisplay() {
    const checkboxes = document.querySelectorAll('input[name="hobbies[]"]:checked');
    const display = document.getElementById('hobbies-display');
    const description = document.getElementById('hobbies-description');
    
    const selectedHobbies = Array.from(checkboxes).map(cb => cb.value);
    
    if (display && description) {
        // Remove all border classes first
        display.className = display.className.replace(/border-\w+-200/g, '');
        
        if (selectedHobbies.length > 0) {
            description.textContent = selectedHobbies.join(', ') || 'Select your interests';
            display.classList.add('border-purple-200');
            display.classList.remove('border-gray-200');
        } else {
            description.textContent = 'Select your interests';
            display.classList.add('border-gray-200');
        }
    }
}

function submitLifestyleForm() {
    const form = document.getElementById('lifestyle-form');
    const submitButton = document.getElementById('lifestyle-submit');
    
    // Show loading state
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...</span>';
    }
    
    // Get current form values before submission
    const currentValues = {
        cleanliness_level: document.getElementById('cleanliness_level').value,
        sleep_pattern: document.getElementById('sleep_pattern').value,
        study_habit: document.getElementById('study_habit').value,
        noise_tolerance: document.getElementById('noise_tolerance').value,
        budget_min: document.getElementById('budget_min').value,
        budget_max: document.getElementById('budget_max').value
    };
    
    // Get selected hobbies
    const selectedHobbies = Array.from(document.querySelectorAll('input[name="hobbies[]"]:checked')).map(cb => cb.value);
    
    // Create FormData
    const formData = new FormData(form);
    
    // AJAX submission
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Save Lifestyle Preferences';
        }
        
        if (data.success) {
            showNotification('Lifestyle preferences saved successfully!', 'success');
            
            // Update all real-time displays with current values with enhanced persistence
            if (currentValues.cleanliness_level) {
                updateCleanlinessDisplay(currentValues.cleanliness_level);
            }
            if (currentValues.sleep_pattern) {
                updateSleepPatternDisplay(currentValues.sleep_pattern);
            }
            if (currentValues.study_habit) {
                updateStudyHabitDisplay(currentValues.study_habit);
            }
            if (currentValues.noise_tolerance) {
                updateNoiseToleranceDisplay(currentValues.noise_tolerance);
            }
            
            // Update budget display
            updateBudgetDisplay();
            
            // Update hobbies display
            updateHobbiesDisplay();
            
            // Force displays to remain visible after submission
            setTimeout(() => {
                const cleanlinessDisplay = document.getElementById('cleanliness-display');
                const sleepDisplay = document.getElementById('sleep-display');
                const studyDisplay = document.getElementById('study-display');
                const noiseDisplay = document.getElementById('noise-display');
                
                if (cleanlinessDisplay && currentValues.cleanliness_level) {
                    cleanlinessDisplay.style.display = 'block';
                    cleanlinessDisplay.style.visibility = 'visible';
                    cleanlinessDisplay.classList.add('bg-opacity-100');
                }
                
                if (sleepDisplay && currentValues.sleep_pattern) {
                    sleepDisplay.style.display = 'block';
                    sleepDisplay.style.visibility = 'visible';
                    sleepDisplay.classList.add('bg-opacity-100');
                }
                
                if (studyDisplay && currentValues.study_habit) {
                    studyDisplay.style.display = 'block';
                    studyDisplay.style.visibility = 'visible';
                    studyDisplay.classList.add('bg-opacity-100');
                }
                
                if (noiseDisplay && currentValues.noise_tolerance) {
                    noiseDisplay.style.display = 'block';
                    noiseDisplay.style.visibility = 'visible';
                    noiseDisplay.classList.add('bg-opacity-100');
                }
            }, 50);
            
            // Ensure form values are preserved (in case of any form reset)
            document.getElementById('cleanliness_level').value = currentValues.cleanliness_level;
            document.getElementById('sleep_pattern').value = currentValues.sleep_pattern;
            document.getElementById('study_habit').value = currentValues.study_habit;
            document.getElementById('noise_tolerance').value = currentValues.noise_tolerance;
            document.getElementById('budget_min').value = currentValues.budget_min;
            document.getElementById('budget_max').value = currentValues.budget_max;
            
            // Ensure checkboxes remain checked
            document.querySelectorAll('input[name="hobbies[]"]').forEach(checkbox => {
                checkbox.checked = selectedHobbies.includes(checkbox.value);
            });
            
        } else {
            showNotification(data.message || 'Error saving preferences', 'error');
        }
    })
    .catch(error => {
        console.error('Submit error:', error);
        showNotification('Network error. Please try again.', 'error');
        
        // Reset button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Save Lifestyle Preferences';
        }
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
