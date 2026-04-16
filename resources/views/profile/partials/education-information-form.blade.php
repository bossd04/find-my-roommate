@php
    use App\Models\User;
    
    $user = auth()->user();
    $profile = $user ? $user->roommateProfile : null;
    
    // Check if user has education info
    $hasEducationInfo = $user && (
        $user->university && 
        $user->course && 
        $user->year_level
    );
    
    // Define required fields for completion check
    $requiredFields = [
        'University' => $user && $user->university,
        'Course' => $user && $user->course,
        'Year Level' => $user && $user->year_level,
    ];
    
    $completedCount = count(array_filter($requiredFields));
    $totalCount = count($requiredFields);
    $completionPercentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
@endphp

<!-- Education Status Card -->
<section class="space-y-6">
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-purple-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Education Status</h3>
                    <p class="text-sm text-gray-600">Add your education details for better roommate matching</p>
                </div>
            </div>
            @if($hasEducationInfo)
                <div class="flex items-center space-x-2 bg-green-100 text-green-700 px-3 py-1.5 rounded-full">
                    <i class="fas fa-check-circle text-sm"></i>
                    <span class="text-sm font-medium">Complete</span>
                </div>
            @else
                <div class="flex items-center space-x-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full">
                    <i class="fas fa-exclamation-circle text-sm"></i>
                    <span class="text-sm font-medium">Incomplete</span>
                </div>
            @endif
        </div>
        
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Education Completion</span>
                <span>{{ $completionPercentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ $completionPercentage }}%"></div>
            </div>
        </div>
        
        <!-- Field Status -->
        <div class="grid grid-cols-2 gap-3">
            @foreach($requiredFields as $field => $filled)
                <div class="flex items-center space-x-2 p-2 rounded-lg @if($filled) bg-green-50 @else bg-red-50 @endif">
                    @if($filled)
                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                        <span class="text-sm text-gray-700">{{ $field }}</span>
                    @else
                        <i class="fas fa-times-circle text-red-500 text-sm"></i>
                        <span class="text-sm text-red-600 font-medium">{{ $field }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Education Information Form -->
    <form method="post" action="{{ route('profile.update.education') }}" enctype="multipart/form-data" class="p-6 space-y-6" id="education-form">
        @csrf
        <input type="hidden" name="form_section" value="education">

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
        <!-- University -->
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-input-label for="university" :value="__('University')" class="text-sm font-medium text-gray-700" />
                    <select id="university" name="university" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                        required>
                        <option value="">Select University</option>
                        <option value="Dagupan Colleges" {{ old('university', $user->university) == 'Dagupan Colleges' ? 'selected' : '' }}>Dagupan Colleges</option>
                        <option value="Universidad de Dagupan" {{ old('university', $user->university) == 'Universidad de Dagupan' ? 'selected' : '' }}>Universidad de Dagupan</option>
                        <option value="Lyceum Northwestern University" {{ old('university', $user->university) == 'Lyceum Northwestern University' ? 'selected' : '' }}>Lyceum Northwestern University</option>
                        <option value="Saint Columban College" {{ old('university', $user->university) == 'Saint Columban College' ? 'selected' : '' }}>Saint Columban College</option>
                        <option value="University of Luzon" {{ old('university', $user->university) == 'University of Luzon' ? 'selected' : '' }}>University of Luzon</option>
                        <option value="WCC Aeronautical and Technological College" {{ old('university', $user->university) == 'WCC Aeronautical and Technological College' ? 'selected' : '' }}>WCC Aeronautical and Technological College</option>
                        <option value="Other" {{ old('university', $user->university) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('university')" />
                </div>
            </div>
        </div>

        <!-- Other University Field -->
                    <div id="other-university-field" class="hidden space-y-2">
                        <x-input-label for="other_university" :value="__('Specify University')" />
                        <x-text-input id="other_university" name="other_university" type="text" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                            :value="old('other_university')" placeholder="Enter your university name" />
                        <x-input-error class="mt-2" :messages="$errors->get('other_university')" />
                    </div>

                    <!-- Course/Major -->
                    <div class="space-y-2">
                        <x-input-label for="course" :value="__('Course/Major')" class="text-sm font-medium text-gray-700" />
                        <select id="course" name="course" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                            required>
                            <option value="">Select Course</option>
                            
                            <!-- PSU Courses -->
                            <optgroup label="Pangasinan State University" data-university="Pangasinan State University">
                                <option value="Bachelor of Science in Computer Science" {{ old('course', $user->course) == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                                <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                                <option value="Bachelor of Science in Civil Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Civil Engineering' ? 'selected' : '' }}>Bachelor of Science in Civil Engineering</option>
                                <option value="Bachelor of Science in Electrical Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Electrical Engineering' ? 'selected' : '' }}>Bachelor of Science in Electrical Engineering</option>
                                <option value="Bachelor of Science in Mechanical Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Mechanical Engineering' ? 'selected' : '' }}>Bachelor of Science in Mechanical Engineering</option>
                                <option value="Bachelor of Science in Accountancy" {{ old('course', $user->course) == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                                <option value="Bachelor of Science in Business Administration" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                                <option value="Bachelor of Science in Business Administration Major in Marketing" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration Major in Marketing' ? 'selected' : '' }}>Bachelor of Science in Business Administration Major in Marketing</option>
                                <option value="Bachelor of Science in Business Administration Major in Human Resource" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration Major in Human Resource' ? 'selected' : '' }}>Bachelor of Science in Business Administration Major in Human Resource</option>
                                <option value="Bachelor of Science in Hospitality Management" {{ old('course', $user->course) == 'Bachelor of Science in Hospitality Management' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                                <option value="Bachelor of Arts in English" {{ old('course', $user->course) == 'Bachelor of Arts in English' ? 'selected' : '' }}>Bachelor of Arts in English</option>
                                <option value="Bachelor of Arts in Filipino" {{ old('course', $user->course) == 'Bachelor of Arts in Filipino' ? 'selected' : '' }}>Bachelor of Arts in Filipino</option>
                                <option value="Bachelor of Secondary Education" {{ old('course', $user->course) == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                                <option value="Bachelor of Secondary Education Major in English" {{ old('course', $user->course) == 'Bachelor of Secondary Education Major in English' ? 'selected' : '' }}>Bachelor of Secondary Education Major in English</option>
                                <option value="Bachelor of Secondary Education Major in Mathematics" {{ old('course', $user->course) == 'Bachelor of Secondary Education Major in Mathematics' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Mathematics</option>
                                <option value="Bachelor of Secondary Education Major in Science" {{ old('course', $user->course) == 'Bachelor of Secondary Education Major in Science' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Science</option>
                                <option value="Bachelor of Elementary Education" {{ old('course', $user->course) == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                                <option value="Bachelor of Science in Nursing" {{ old('course', $user->course) == 'Bachelor of Science in Nursing' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                                <option value="Bachelor of Science in Pharmacy" {{ old('course', $user->course) == 'Bachelor of Science in Pharmacy' ? 'selected' : '' }}>Bachelor of Science in Pharmacy</option>
                                <option value="Bachelor of Science in Biology" {{ old('course', $user->course) == 'Bachelor of Science in Biology' ? 'selected' : '' }}>Bachelor of Science in Biology</option>
                                <option value="Bachelor of Science in Mathematics" {{ old('course', $user->course) == 'Bachelor of Science in Mathematics' ? 'selected' : '' }}>Bachelor of Science in Mathematics</option>
                                <option value="Bachelor of Science in Psychology" {{ old('course', $user->course) == 'Bachelor of Science in Psychology' ? 'selected' : '' }}>Bachelor of Science in Psychology</option>
                                <option value="Bachelor of Science in Chemistry" {{ old('course', $user->course) == 'Bachelor of Science in Chemistry' ? 'selected' : '' }}>Bachelor of Science in Chemistry</option>
                                <option value="Bachelor of Science in Physics" {{ old('course', $user->course) == 'Bachelor of Science in Physics' ? 'selected' : '' }}>Bachelor of Science in Physics</option>
                                <option value="Bachelor of Science in Statistics" {{ old('course', $user->course) == 'Bachelor of Science in Statistics' ? 'selected' : '' }}>Bachelor of Science in Statistics</option>
                                <option value="Bachelor of Arts in Economics" {{ old('course', $user->course) == 'Bachelor of Arts in Economics' ? 'selected' : '' }}>Bachelor of Arts in Economics</option>
                                <option value="Bachelor of Arts in Political Science" {{ old('course', $user->course) == 'Bachelor of Arts in Political Science' ? 'selected' : '' }}>Bachelor of Arts in Political Science</option>
                                <option value="Bachelor of Arts in Sociology" {{ old('course', $user->course) == 'Bachelor of Arts in Sociology' ? 'selected' : '' }}>Bachelor of Arts in Sociology</option>
                                <option value="Bachelor of Science in Agriculture" {{ old('course', $user->course) == 'Bachelor of Science in Agriculture' ? 'selected' : '' }}>Bachelor of Science in Agriculture</option>
                                <option value="Bachelor of Science in Agricultural Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Agricultural Engineering' ? 'selected' : '' }}>Bachelor of Science in Agricultural Engineering</option>
                                <option value="Bachelor of Science in Food Technology" {{ old('course', $user->course) == 'Bachelor of Science in Food Technology' ? 'selected' : '' }}>Bachelor of Science in Food Technology</option>
                            <option value="Bachelor of Science in Fisheries" {{ old('course', $user->course) == 'Bachelor of Science in Fisheries' ? 'selected' : '' }}>Bachelor of Science in Fisheries</option>
                            <option value="Bachelor of Science in Forestry" {{ old('course', $user->course) == 'Bachelor of Science in Forestry' ? 'selected' : '' }}>Bachelor of Science in Forestry</option>
                            <option value="Bachelor of Science in Environmental Science" {{ old('course', $user->course) == 'Bachelor of Science in Environmental Science' ? 'selected' : '' }}>Bachelor of Science in Environmental Science</option>
                            <option value="Bachelor of Arts in Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Communication' ? 'selected' : '' }}>Bachelor of Arts in Communication</option>
                            <option value="Bachelor of Arts in Development Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Development Communication' ? 'selected' : '' }}>Bachelor of Arts in Development Communication</option>
                        </optgroup>
                        
                        <!-- UPang Courses -->
                        <optgroup label="University of Pangasinan" data-university="University of Pangasinan">
                            <option value="Bachelor of Science in Computer Science" {{ old('course', $user->course) == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="Bachelor of Science in Computer Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Computer Engineering' ? 'selected' : '' }}>Bachelor of Science in Computer Engineering</option>
                            <option value="Bachelor of Science in Accountancy" {{ old('course', $user->course) == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                            <option value="Bachelor of Science in Business Administration" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="Bachelor of Science in Business Administration Major in Marketing" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration Major in Marketing' ? 'selected' : '' }}>Bachelor of Science in Business Administration Major in Marketing</option>
                            <option value="Bachelor of Science in Business Administration Major in Management" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration Major in Management' ? 'selected' : '' }}>Bachelor of Science in Business Administration Major in Management</option>
                            <option value="Bachelor of Arts in Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Communication' ? 'selected' : '' }}>Bachelor of Arts in Communication</option>
                            <option value="Bachelor of Arts in Mass Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Mass Communication' ? 'selected' : '' }}>Bachelor of Arts in Mass Communication</option>
                            <option value="Bachelor of Arts in Journalism" {{ old('course', $user->course) == 'Bachelor of Arts in Journalism' ? 'selected' : '' }}>Bachelor of Arts in Journalism</option>
                            <option value="Bachelor of Arts in Political Science" {{ old('course', $user->course) == 'Bachelor of Arts in Political Science' ? 'selected' : '' }}>Bachelor of Arts in Political Science</option>
                            <option value="Bachelor of Arts in Economics" {{ old('course', $user->course) == 'Bachelor of Arts in Economics' ? 'selected' : '' }}>Bachelor of Arts in Economics</option>
                            <option value="Bachelor of Arts in Psychology" {{ old('course', $user->course) == 'Bachelor of Arts in Psychology' ? 'selected' : '' }}>Bachelor of Arts in Psychology</option>
                            <option value="Bachelor of Arts in Sociology" {{ old('course', $user->course) == 'Bachelor of Arts in Sociology' ? 'selected' : '' }}>Bachelor of Arts in Sociology</option>
                            <option value="Bachelor of Science in Tourism Management" {{ old('course', $user->course) == 'Bachelor of Science in Tourism Management' ? 'selected' : '' }}>Bachelor of Science in Tourism Management</option>
                            <option value="Bachelor of Science in Hotel and Restaurant Management" {{ old('course', $user->course) == 'Bachelor of Science in Hotel and Restaurant Management' ? 'selected' : '' }}>Bachelor of Science in Hotel and Restaurant Management</option>
                            <option value="Bachelor of Secondary Education" {{ old('course', $user->course) == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                            <option value="Bachelor of Secondary Education Major in English" {{ old('course', $user->course) == 'Bachelor of Secondary Education Major in English' ? 'selected' : '' }}>Bachelor of Secondary Education Major in English</option>
                            <option value="Bachelor of Secondary Education Major in Mathematics" {{ old('course', $user->course) == 'Bachelor of Secondary Education Major in Mathematics' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Mathematics</option>
                            <option value="Bachelor of Elementary Education" {{ old('course', $user->course) == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                            <option value="Bachelor of Elementary Education Major in Early Childhood Education" {{ old('course', $user->course) == 'Bachelor of Elementary Education Major in Early Childhood Education' ? 'selected' : '' }}>Bachelor of Elementary Education Major in Early Childhood Education</option>
                            <option value="Bachelor of Science in Nursing" {{ old('course', $user->course) == 'Bachelor of Science in Nursing' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                            <option value="Bachelor of Science in Medical Technology" {{ old('course', $user->course) == 'Bachelor of Science in Medical Technology' ? 'selected' : '' }}>Bachelor of Science in Medical Technology</option>
                            <option value="Bachelor of Science in Pharmacy" {{ old('course', $user->course) == 'Bachelor of Science in Pharmacy' ? 'selected' : '' }}>Bachelor of Science in Pharmacy</option>
                            <option value="Bachelor of Science in Physical Therapy" {{ old('course', $user->course) == 'Bachelor of Science in Physical Therapy' ? 'selected' : '' }}>Bachelor of Science in Physical Therapy</option>
                            <option value="Bachelor of Science in Radiologic Technology" {{ old('course', $user->course) == 'Bachelor of Science in Radiologic Technology' ? 'selected' : '' }}>Bachelor of Science in Radiologic Technology</option>
                            <option value="Bachelor of Science in Biology" {{ old('course', $user->course) == 'Bachelor of Science in Biology' ? 'selected' : '' }}>Bachelor of Science in Biology</option>
                            <option value="Bachelor of Science in Mathematics" {{ old('course', $user->course) == 'Bachelor of Science in Mathematics' ? 'selected' : '' }}>Bachelor of Science in Mathematics</option>
                            <option value="Bachelor of Laws" {{ old('course', $user->course) == 'Bachelor of Laws' ? 'selected' : '' }}>Bachelor of Laws</option>
                            <option value="Bachelor of Arts in International Studies" {{ old('course', $user->course) == 'Bachelor of Arts in International Studies' ? 'selected' : '' }}>Bachelor of Arts in International Studies</option>
                        </optgroup>
                        
                        <!-- Universidad de Dagupan Courses -->
                        <optgroup label="Universidad de Dagupan" data-university="Universidad de Dagupan">
                            <option value="Bachelor of Science in Computer Science" {{ old('course', $user->course) == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="Bachelor of Science in Computer Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Computer Engineering' ? 'selected' : '' }}>Bachelor of Science in Computer Engineering</option>
                            <option value="Bachelor of Science in Accountancy" {{ old('course', $user->course) == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                            <option value="Bachelor of Science in Business Administration" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="Bachelor of Science in Business Administration Major in Financial Management" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration Major in Financial Management' ? 'selected' : '' }}>Bachelor of Science in Business Administration Major in Financial Management</option>
                            <option value="Bachelor of Science in Business Administration Major in Marketing Management" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration Major in Marketing Management' ? 'selected' : '' }}>Bachelor of Science in Business Administration Major in Marketing Management</option>
                            <option value="Bachelor of Science in Business Administration Major in Human Resource Management" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration Major in Human Resource Management' ? 'selected' : '' }}>Bachelor of Science in Business Administration Major in Human Resource Management</option>
                            <option value="Bachelor of Arts in Mass Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Mass Communication' ? 'selected' : '' }}>Bachelor of Arts in Mass Communication</option>
                            <option value="Bachelor of Arts in Journalism" {{ old('course', $user->course) == 'Bachelor of Arts in Journalism' ? 'selected' : '' }}>Bachelor of Arts in Journalism</option>
                            <option value="Bachelor of Arts in Broadcasting" {{ old('course', $user->course) == 'Bachelor of Arts in Broadcasting' ? 'selected' : '' }}>Bachelor of Arts in Broadcasting</option>
                            <option value="Bachelor of Science in Tourism Management" {{ old('course', $user->course) == 'Bachelor of Science in Tourism Management' ? 'selected' : '' }}>Bachelor of Science in Tourism Management</option>
                            <option value="Bachelor of Science in Hotel and Restaurant Management" {{ old('course', $user->course) == 'Bachelor of Science in Hotel and Restaurant Management' ? 'selected' : '' }}>Bachelor of Science in Hotel and Restaurant Management</option>
                            <option value="Bachelor of Secondary Education" {{ old('course', $user->course) == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                            <option value="Bachelor of Secondary Education Major in English" {{ old('course', $user->course) == 'Bachelor of Secondary Education Major in English' ? 'selected' : '' }}>Bachelor of Secondary Education Major in English</option>
                            <option value="Bachelor of Secondary Education Major in Mathematics" {{ old('course', $user->course) == 'Bachelor of Secondary Education Major in Mathematics' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Mathematics</option>
                            <option value="Bachelor of Elementary Education" {{ old('course', $user->course) == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                            <option value="Bachelor of Elementary Education Major in Early Childhood Education" {{ old('course', $user->course) == 'Bachelor of Elementary Education Major in Early Childhood Education' ? 'selected' : '' }}>Bachelor of Elementary Education Major in Early Childhood Education</option>
                            <option value="Bachelor of Science in Nursing" {{ old('course', $user->course) == 'Bachelor of Science in Nursing' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                            <option value="Bachelor of Science in Physical Therapy" {{ old('course', $user->course) == 'Bachelor of Science in Physical Therapy' ? 'selected' : '' }}>Bachelor of Science in Physical Therapy</option>
                            <option value="Bachelor of Science in Occupational Therapy" {{ old('course', $user->course) == 'Bachelor of Science in Occupational Therapy' ? 'selected' : '' }}>Bachelor of Science in Occupational Therapy</option>
                            <option value="Bachelor of Science in Radiologic Technology" {{ old('course', $user->course) == 'Bachelor of Science in Radiologic Technology' ? 'selected' : '' }}>Bachelor of Science in Radiologic Technology</option>
                            <option value="Bachelor of Science in Medical Technology" {{ old('course', $user->course) == 'Bachelor of Science in Medical Technology' ? 'selected' : '' }}>Bachelor of Science in Medical Technology</option>
                            <option value="Bachelor of Science in Pharmacy" {{ old('course', $user->course) == 'Bachelor of Science in Pharmacy' ? 'selected' : '' }}>Bachelor of Science in Pharmacy</option>
                            <option value="Bachelor of Arts in Psychology" {{ old('course', $user->course) == 'Bachelor of Arts in Psychology' ? 'selected' : '' }}>Bachelor of Arts in Psychology</option>
                            <option value="Bachelor of Science in Biology" {{ old('course', $user->course) == 'Bachelor of Science in Biology' ? 'selected' : '' }}>Bachelor of Science in Biology</option>
                            <option value="Bachelor of Arts in English" {{ old('course', $user->course) == 'Bachelor of Arts in English' ? 'selected' : '' }}>Bachelor of Arts in English</option>
                            <option value="Bachelor of Arts in Filipino" {{ old('course', $user->course) == 'Bachelor of Arts in Filipino' ? 'selected' : '' }}>Bachelor of Arts in Filipino</option>
                        </optgroup>

                        <!-- Dagupan Colleges Courses -->
                        <optgroup label="Dagupan Colleges" data-university="Dagupan Colleges">
                            <option value="Bachelor of Science in Computer Science" {{ old('course', $user->course) == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="Bachelor of Science in Accountancy" {{ old('course', $user->course) == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                            <option value="Bachelor of Science in Business Administration" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="Bachelor of Arts in Mass Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Mass Communication' ? 'selected' : '' }}>Bachelor of Arts in Mass Communication</option>
                            <option value="Bachelor of Secondary Education" {{ old('course', $user->course) == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                            <option value="Bachelor of Elementary Education" {{ old('course', $user->course) == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                            <option value="Bachelor of Science in Hotel and Restaurant Management" {{ old('course', $user->course) == 'Bachelor of Science in Hotel and Restaurant Management' ? 'selected' : '' }}>Bachelor of Science in Hotel and Restaurant Management</option>
                            <option value="Bachelor of Science in Tourism Management" {{ old('course', $user->course) == 'Bachelor of Science in Tourism Management' ? 'selected' : '' }}>Bachelor of Science in Tourism Management</option>
                        </optgroup>

                        <!-- Lyceum Northwestern University Courses -->
                        <optgroup label="Lyceum Northwestern University" data-university="Lyceum Northwestern University">
                            <option value="Bachelor of Science in Computer Science" {{ old('course', $user->course) == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="Bachelor of Science in Accountancy" {{ old('course', $user->course) == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                            <option value="Bachelor of Science in Business Administration" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="Bachelor of Arts in Mass Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Mass Communication' ? 'selected' : '' }}>Bachelor of Arts in Mass Communication</option>
                            <option value="Bachelor of Science in Tourism Management" {{ old('course', $user->course) == 'Bachelor of Science in Tourism Management' ? 'selected' : '' }}>Bachelor of Science in Tourism Management</option>
                            <option value="Bachelor of Science in Hotel and Restaurant Management" {{ old('course', $user->course) == 'Bachelor of Science in Hotel and Restaurant Management' ? 'selected' : '' }}>Bachelor of Science in Hotel and Restaurant Management</option>
                            <option value="Bachelor of Science in Nursing" {{ old('course', $user->course) == 'Bachelor of Science in Nursing' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                            <option value="Bachelor of Science in Medical Technology" {{ old('course', $user->course) == 'Bachelor of Science in Medical Technology' ? 'selected' : '' }}>Bachelor of Science in Medical Technology</option>
                            <option value="Bachelor of Science in Pharmacy" {{ old('course', $user->course) == 'Bachelor of Science in Pharmacy' ? 'selected' : '' }}>Bachelor of Science in Pharmacy</option>
                            <option value="Bachelor of Secondary Education" {{ old('course', $user->course) == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                            <option value="Bachelor of Arts in Psychology" {{ old('course', $user->course) == 'Bachelor of Arts in Psychology' ? 'selected' : '' }}>Bachelor of Arts in Psychology</option>
                            <option value="Bachelor of Science in Marine Transportation" {{ old('course', $user->course) == 'Bachelor of Science in Marine Transportation' ? 'selected' : '' }}>Bachelor of Science in Marine Transportation</option>
                            <option value="Bachelor of Science in Marine Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Marine Engineering' ? 'selected' : '' }}>Bachelor of Science in Marine Engineering</option>
                        </optgroup>

                        <!-- Saint Columban College Courses -->
                        <optgroup label="Saint Columban College" data-university="Saint Columban College">
                            <option value="Bachelor of Science in Computer Science" {{ old('course', $user->course) == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="Bachelor of Science in Accountancy" {{ old('course', $user->course) == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                            <option value="Bachelor of Science in Business Administration" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="Bachelor of Arts in English" {{ old('course', $user->course) == 'Bachelor of Arts in English' ? 'selected' : '' }}>Bachelor of Arts in English</option>
                            <option value="Bachelor of Secondary Education" {{ old('course', $user->course) == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                            <option value="Bachelor of Elementary Education" {{ old('course', $user->course) == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                            <option value="Bachelor of Science in Nursing" {{ old('course', $user->course) == 'Bachelor of Science in Nursing' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                            <option value="Bachelor of Science in Hospitality Management" {{ old('course', $user->course) == 'Bachelor of Science in Hospitality Management' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                        </optgroup>

                        <!-- University of Luzon Courses -->
                        <optgroup label="University of Luzon" data-university="University of Luzon">
                            <option value="Bachelor of Science in Computer Science" {{ old('course', $user->course) == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="Bachelor of Science in Accountancy" {{ old('course', $user->course) == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                            <option value="Bachelor of Science in Business Administration" {{ old('course', $user->course) == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="Bachelor of Arts in Mass Communication" {{ old('course', $user->course) == 'Bachelor of Arts in Mass Communication' ? 'selected' : '' }}>Bachelor of Arts in Mass Communication</option>
                            <option value="Bachelor of Science in Tourism Management" {{ old('course', $user->course) == 'Bachelor of Science in Tourism Management' ? 'selected' : '' }}>Bachelor of Science in Tourism Management</option>
                            <option value="Bachelor of Science in Hotel and Restaurant Management" {{ old('course', $user->course) == 'Bachelor of Science in Hotel and Restaurant Management' ? 'selected' : '' }}>Bachelor of Science in Hotel and Restaurant Management</option>
                            <option value="Bachelor of Secondary Education" {{ old('course', $user->course) == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                            <option value="Bachelor of Science in Nursing" {{ old('course', $user->course) == 'Bachelor of Science in Nursing' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                            <option value="Bachelor of Science in Medical Technology" {{ old('course', $user->course) == 'Bachelor of Science in Medical Technology' ? 'selected' : '' }}>Bachelor of Science in Medical Technology</option>
                            <option value="Bachelor of Science in Pharmacy" {{ old('course', $user->course) == 'Bachelor of Science in Pharmacy' ? 'selected' : '' }}>Bachelor of Science in Pharmacy</option>
                        </optgroup>

                        <!-- WCC Courses -->
                        <optgroup label="WCC Aeronautical and Technological College" data-university="WCC Aeronautical and Technological College">
                            <option value="Bachelor of Science in Aeronautical Engineering" {{ old('course', $user->course) == 'Bachelor of Science in Aeronautical Engineering' ? 'selected' : '' }}>Bachelor of Science in Aeronautical Engineering</option>
                            <option value="Bachelor of Science in Aircraft Maintenance Technology" {{ old('course', $user->course) == 'Bachelor of Science in Aircraft Maintenance Technology' ? 'selected' : '' }}>Bachelor of Science in Aircraft Maintenance Technology</option>
                            <option value="Bachelor of Science in Aviation Electronics Technology" {{ old('course', $user->course) == 'Bachelor of Science in Aviation Electronics Technology' ? 'selected' : '' }}>Bachelor of Science in Aviation Electronics Technology</option>
                            <option value="Bachelor of Science in Tourism Management" {{ old('course', $user->course) == 'Bachelor of Science in Tourism Management' ? 'selected' : '' }}>Bachelor of Science in Tourism Management</option>
                            <option value="Bachelor of Science in Hospitality Management" {{ old('course', $user->course) == 'Bachelor of Science in Hospitality Management' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                            <option value="Bachelor of Science in Information Technology" {{ old('course', $user->course) == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                        </optgroup>

                        <option value="Other" {{ old('course', $user->course) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('course')" />
                </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="year_level" :value="__('Year Level')" />
                    <select id="year_level" name="year_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Select Year Level</option>
                        <option value="1st Year" {{ old('year_level', $user->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ old('year_level', $user->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ old('year_level', $user->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4th Year" {{ old('year_level', $user->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        <option value="5th Year" {{ old('year_level', $user->year_level) == '5th Year' ? 'selected' : '' }}>5th Year</option>
                        <option value="Graduate Student" {{ old('year_level', $user->year_level) == 'Graduate Student' ? 'selected' : '' }}>Graduate Student</option>
                        <option value="Alumni" {{ old('year_level', $user->year_level) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('year_level')" />
                </div>

                <div>
                    <x-input-label for="bio" :value="__('Bio/Description')" />
                    <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                        placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>
            </div>
        </div>

        <div class="mt-8">
            <button type="button" onclick="submitEducationForm(this)" id="education-submit-btn" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Education Information
            </button>
        </div>
    </form>
</section>

<script>
const universitySelect = document.getElementById('university');
const otherUniversityField = document.getElementById('other-university-field');

if (universitySelect && otherUniversityField) {
    universitySelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            otherUniversityField.classList.remove('hidden');
        } else {
            otherUniversityField.classList.add('hidden');
        }
    });
}
</script>
