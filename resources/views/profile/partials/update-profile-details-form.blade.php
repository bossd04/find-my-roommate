<section class="mt-10">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Personal Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your personal information and preferences.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update.details') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" 
                        :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>

                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" 
                        :value="old('last_name', $user->last_name)" required autocomplete="family-name" />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                        :value="old('email', $user->email)" required autocomplete="email" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div>
                    <x-input-label for="phone" :value="__('Phone')" />
                    <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" 
                        :value="old('phone', $user->phone)" autocomplete="tel" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>

                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        <option value="prefer-not-to-say" {{ old('gender', $user->gender) == 'prefer-not-to-say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                </div>

                <div>
                    <x-input-label for="age" :value="__('Age')" />
                    <x-text-input id="age" name="age" type="number" min="18" max="100" class="mt-1 block w-full" 
                        :value="old('age', $user->age)" />
                    <x-input-error class="mt-2" :messages="$errors->get('age')" />
                </div>
            </div>

            <!-- Simple Avatar Upload -->
            @include('profile.partials.simple-avatar-upload')

            <div>
                <x-input-label for="bio" :value="__('Bio')" />
                <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            </div>
        </div>

        <!-- Education Information -->
        <div class="border-t border-gray-200 pt-6 mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Education Information') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <x-input-label for="university" :value="__('University')" />
                    <select id="university" name="university" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="Universidad de Dagupan" {{ old('university', $user->university) == 'Universidad de Dagupan' ? 'selected' : '' }}>Universidad de Dagupan</option>
                        <option value="Other" {{ !empty($user->university) && $user->university != 'Universidad de Dagupan' ? 'selected' : '' }}>Other</option>
                    </select>
                    <div id="otherUniversityContainer" class="mt-2" style="display: {{ !empty($user->university) && $user->university != 'Universidad de Dagupan' ? 'block' : 'none' }}">
                        <x-text-input id="other_university" name="other_university" type="text" class="block w-full" 
                            :value="!empty($user->university) && $user->university != 'Universidad de Dagupan' ? $user->university : ''" 
                            placeholder="Please specify university" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('university')" />
                </div>

                <div>
                    <x-input-label for="department" :value="__('Department')" />
                    <select id="department" name="department" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Department</option>
                        @php
                            $departments = [
                                'College of Arts and Sciences',
                                'College of Business and Accountancy',
                                'College of Computer Studies',
                                'College of Criminology',
                                'College of Education',
                                'College of Engineering and Architecture',
                                'College of Health Sciences',
                                'College of Law',
                                'College of Medical Technology',
                                'College of Nursing',
                                'College of Pharmacy',
                                'Graduate School',
                                'Senior High School',
                                'Other'
                            ];
                        @endphp
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ old('department', $user->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('department')" />
                </div>

                <div>
                    <x-input-label for="course" :value="__('Course')" />
                    <select id="course" name="course" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Course</option>
                        @php
                            $courses = [
                                'Bachelor of Arts in Communication',
                                'Bachelor of Arts in Political Science',
                                'Bachelor of Science in Accountancy',
                                'Bachelor of Science in Accounting Information System',
                                'Bachelor of Science in Business Administration',
                                'Bachelor of Science in Computer Science',
                                'Bachelor of Science in Criminology',
                                'Bachelor of Science in Education',
                                'Bachelor of Science in Entrepreneurship',
                                'Bachelor of Science in Hospitality Management',
                                'Bachelor of Science in Information Technology',
                                'Bachelor of Science in Medical Technology',
                                'Bachelor of Science in Nursing',
                                'Bachelor of Science in Pharmacy',
                                'Bachelor of Science in Psychology',
                                'Bachelor of Science in Tourism Management',
                                'Doctor of Dental Medicine',
                                'Doctor of Medicine',
                                'Juris Doctor',
                                'Other'
                            ];
                            
                            // Sort courses alphabetically
                            sort($courses);
                        @endphp
                        @foreach($courses as $course)
                            <option value="{{ $course }}" {{ old('course', $user->course) == $course ? 'selected' : '' }}>{{ $course }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('course')" />
                </div>

                <div>
                    <x-input-label for="year_level" :value="__('Year Level')" />
                    <select id="year_level" name="year_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Year</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="Year {{ $i }}" {{ old('year_level', $user->year_level) == "Year $i" ? 'selected' : '' }}>Year {{ $i }}</option>
                        @endfor
                        <option value="Postgraduate" {{ old('year_level', $user->year_level) == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('year_level')" />
                </div>
            </div>
        </div>

        <!-- Lifestyle Preferences -->
        <div class="border-t border-gray-200 pt-6 mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Lifestyle Preferences') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="cleanliness_level" :value="__('Cleanliness Level')" />
                    <select id="cleanliness_level" name="cleanliness_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Cleanliness Level</option>
                        <option value="very_neat" {{ old('cleanliness_level', $user->profile->cleanliness_level ?? '') == 'very_neat' ? 'selected' : '' }}>Very Neat</option>
                        <option value="neat" {{ old('cleanliness_level', $user->profile->cleanliness_level ?? '') == 'neat' ? 'selected' : '' }}>Neat</option>
                        <option value="average" {{ old('cleanliness_level', $user->profile->cleanliness_level ?? '') == 'average' ? 'selected' : '' }}>Average</option>
                        <option value="messy" {{ old('cleanliness_level', $user->profile->cleanliness_level ?? '') == 'messy' ? 'selected' : '' }}>Messy</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('cleanliness_level')" />
                </div>

                <div>
                    <x-input-label for="sleep_pattern" :value="__('Sleep Pattern')" />
                    <select id="sleep_pattern" name="sleep_pattern" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Sleep Pattern</option>
                        <option value="early_bird" {{ old('sleep_pattern', $user->profile->sleep_pattern ?? '') == 'early_bird' ? 'selected' : '' }}>Early Bird</option>
                        <option value="night_owl" {{ old('sleep_pattern', $user->profile->sleep_pattern ?? '') == 'night_owl' ? 'selected' : '' }}>Night Owl</option>
                        <option value="flexible" {{ old('sleep_pattern', $user->profile->sleep_pattern ?? '') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('sleep_pattern')" />
                </div>

                <div>
                    <x-input-label for="study_habit" :value="__('Study Habit')" />
                    <select id="study_habit" name="study_habit" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Study Habit</option>
                        <option value="intense" {{ old('study_habit', $user->profile->study_habit ?? '') == 'intense' ? 'selected' : '' }}>Intense</option>
                        <option value="moderate" {{ old('study_habit', $user->profile->study_habit ?? '') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="light" {{ old('study_habit', $user->profile->study_habit ?? '') == 'light' ? 'selected' : '' }}>Light</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('study_habit')" />
                </div>

                <div>
                    <x-input-label for="noise_tolerance" :value="__('Noise Tolerance')" />
                    <select id="noise_tolerance" name="noise_tolerance" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select Noise Tolerance</option>
                        <option value="quiet" {{ old('noise_tolerance', $user->profile->noise_tolerance ?? '') == 'quiet' ? 'selected' : '' }}>Quiet</option>
                        <option value="moderate" {{ old('noise_tolerance', $user->profile->noise_tolerance ?? '') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="loud" {{ old('noise_tolerance', $user->profile->noise_tolerance ?? '') == 'loud' ? 'selected' : '' }}>Loud</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('noise_tolerance')" />
                </div>

                <div>
                    <x-input-label for="budget_min" :value="__('Budget Range (per month)')" />
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            ₱
                        </span>
                        <input type="number" name="budget_min" id="budget_min" 
                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" 
                            placeholder="Min" value="{{ old('budget_min', $user->budget_min) }}">
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('budget_min')" />
                </div>

                <div>
                    <x-input-label for="budget_max" :value="__('&nbsp;')" />
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            ₱
                        </span>
                        <input type="number" name="budget_max" id="budget_max" 
                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" 
                            placeholder="Max" value="{{ old('budget_max', $user->budget_max) }}">
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('budget_max')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="hobbies" :value="__('Hobbies')" />
                    <div class="mt-1 space-y-2" id="hobbies-container">
                        @php
                            $hobbies = old('hobbies', is_array($user->hobbies) ? $user->hobbies : []);
                            if (is_string($hobbies)) {
                                $hobbies = array_filter(array_map('trim', explode(',', $hobbies)));
                            }
                        @endphp
                        @foreach($hobbies as $index => $hobby)
                            <div class="flex items-center gap-2">
                                <x-text-input 
                                    name="hobbies[]" 
                                    type="text" 
                                    class="block w-full" 
                                    :value="$hobby"
                                    placeholder="e.g., Reading, Hiking, Gaming"
                                />
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                        <div class="flex items-center gap-2">
                            <x-text-input 
                                name="hobbies[]" 
                                type="text" 
                                class="block w-full" 
                                placeholder="e.g., Reading, Hiking, Gaming"
                            />
                            <button type="button" class="text-green-600 hover:text-green-800" onclick="addHobbyField(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('hobbies')" />
                    <x-input-error class="mt-2" :messages="$errors->get('hobbies.*')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="lifestyle_tags" :value="__('Lifestyle Tags')" />
                    <div class="mt-1 space-y-2" id="lifestyle-tags-container">
                        @php
                            $tags = old('lifestyle_tags', is_array($user->lifestyle_tags) ? $user->lifestyle_tags : []);
                            if (is_string($tags)) {
                                $tags = array_filter(array_map('trim', explode(',', $tags)));
                            }
                        @endphp
                        @foreach($tags as $index => $tag)
                            <div class="flex items-center gap-2">
                                <x-text-input 
                                    name="lifestyle_tags[]" 
                                    type="text" 
                                    class="block w-full" 
                                    :value="$tag"
                                    placeholder="e.g., Vegetarian, Night Owl, Pet Lover"
                                />
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                        <div class="flex items-center gap-2">
                            <x-text-input 
                                name="lifestyle_tags[]" 
                                type="text" 
                                class="block w-full" 
                                placeholder="e.g., Vegetarian, Night Owl, Pet Lover"
                            />
                            <button type="button" class="text-green-600 hover:text-green-800" onclick="addLifestyleTagField(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('lifestyle_tags')" />
                    <x-input-error class="mt-2" :messages="$errors->get('lifestyle_tags.*')" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
        </div>
    </form>
</section>

<script>
// Toggle other university field
document.addEventListener('DOMContentLoaded', function() {
    const universitySelect = document.getElementById('university');
    const otherUniversityContainer = document.getElementById('otherUniversityContainer');
    
    if (universitySelect && otherUniversityContainer) {
        universitySelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherUniversityContainer.style.display = 'block';
                const otherInput = document.getElementById('other_university');
                if (otherInput) {
                    otherInput.setAttribute('name', 'university');
                }
            } else {
                otherUniversityContainer.style.display = 'none';
                const otherInput = document.getElementById('other_university');
                if (otherInput) {
                    otherInput.removeAttribute('name');
                }
            }
        });
        
        // Initialize with correct state
        if (universitySelect.value === 'Other') {
            otherUniversityContainer.style.display = 'block';
            const otherInput = document.getElementById('other_university');
            if (otherInput) {
                otherInput.setAttribute('name', 'university');
            }
        }
    }
});
</script>
