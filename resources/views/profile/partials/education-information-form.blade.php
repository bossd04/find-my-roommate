<section class="space-y-6">
    <form method="post" action="{{ route('profile.update.details') }}" enctype="multipart/form-data" id="education-info-form">
        @csrf
        @method('patch')
        <input type="hidden" name="form_section" value="education_information">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- University Information -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="university" :value="__('University')" />
                    <select id="university" name="university" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required onchange="filterCoursesByUniversity(this.value)">
                        <option value="">Select University</option>
                        <option value="Pangasinan State University" {{ old('university', $user->university) == 'Pangasinan State University' ? 'selected' : '' }}>Pangasinan State University</option>
                        <option value="University of Pangasinan" {{ old('university', $user->university) == 'University of Pangasinan' ? 'selected' : '' }}>University of Pangasinan</option>
                        <option value="Universidad de Dagupan" {{ old('university', $user->university) == 'Universidad de Dagupan' ? 'selected' : '' }}>Universidad de Dagupan</option>
                        <option value="Dagupan Colleges" {{ old('university', $user->university) == 'Dagupan Colleges' ? 'selected' : '' }}>Dagupan Colleges</option>
                        <option value="Lyceum Northwestern University" {{ old('university', $user->university) == 'Lyceum Northwestern University' ? 'selected' : '' }}>Lyceum Northwestern University</option>
                        <option value="Saint Columban College" {{ old('university', $user->university) == 'Saint Columban College' ? 'selected' : '' }}>Saint Columban College</option>
                        <option value="University of Luzon" {{ old('university', $user->university) == 'University of Luzon' ? 'selected' : '' }}>University of Luzon</option>
                        <option value="WCC Aeronautical and Technological College" {{ old('university', $user->university) == 'WCC Aeronautical and Technological College' ? 'selected' : '' }}>WCC Aeronautical and Technological College</option>
                        <option value="Other" {{ old('university', $user->university) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('university')" />
                </div>

                <div id="other-university-field" class="hidden">
                    <x-input-label for="other_university" :value="__('Specify University')" />
                    <x-text-input id="other_university" name="other_university" type="text" class="mt-1 block w-full" 
                        :value="old('other_university')" placeholder="Enter your university name" />
                    <x-input-error class="mt-2" :messages="$errors->get('other_university')" />
                </div>

                <div>
                    <x-input-label for="course" :value="__('Course/Major')" />
                    <select id="course" name="course" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required onchange="updateDepartmentOptions()">
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

                        <!-- Lyceum Northwestern Courses -->
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
            <button type="button" onclick="submitEducationForm()" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" id="education-info-submit">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Education Information
            </button>
        </div>
    </form>
</section>

<script>
function submitEducationForm() {
    console.log('Submitting education form...');
    
    // Show loading state
    const submitBtn = document.getElementById('education-info-submit');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Saving...';
    submitBtn.disabled = true;
    
    // Create form data manually
    const form = document.getElementById('education-info-form');
    const formData = new FormData(form);
    
    // Add form section
    formData.append('form_section', 'education_information');
    
    // Log form data for debugging
    console.log('Form data being submitted:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Submit via fetch to ensure proper handling
    fetch('/profile/details', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Show success state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Saved!';
            submitBtn.classList.remove('bg-blue-600');
            submitBtn.classList.add('bg-green-600');
            
            // Show success alert with completion status
            alert('✅ Education information updated successfully!\n\nYour profile completion status has been updated.');
            
            // Check if all required sections are complete
            setTimeout(() => {
                checkProfileCompletion();
            }, 500);
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Show error message
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Error';
            submitBtn.classList.remove('bg-blue-600');
            submitBtn.classList.add('bg-red-600');
            
            // Show error alert with details
            alert('❌ Error: ' + (data.message || 'Unknown error occurred') + '\n\nPlease try again or contact support if the issue persists.');
            
            // Reset button after delay
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-red-600');
                submitBtn.classList.add('bg-blue-600');
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Submission error:', error);
        
        // Show error message
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Error';
        submitBtn.classList.remove('bg-blue-600');
        submitBtn.classList.add('bg-red-600');
        
        alert('❌ Network error occurred while submitting the form.\n\nPlease check your connection and try again.');
        
        // Reset button after delay
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-red-600');
            submitBtn.classList.add('bg-blue-600');
        }, 3000);
    });
}

// Function to check profile completion status
function checkProfileCompletion() {
    fetch('/api/profile/completion', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Profile completion status:', data);
        
        // Update completion indicators if they exist
        const completionIndicator = document.getElementById('profile-completion-status');
        if (completionIndicator) {
            if (data.complete) {
                completionIndicator.innerHTML = '<span class="text-green-600">✅ Profile Complete</span>';
                completionIndicator.classList.remove('text-yellow-600');
                completionIndicator.classList.add('text-green-600');
            } else {
                completionIndicator.innerHTML = '<span class="text-yellow-600">⚠️ Profile Incomplete</span>';
                completionIndicator.classList.remove('text-green-600');
                completionIndicator.classList.add('text-yellow-600');
            }
        }
    })
    .catch(error => {
        console.log('Could not check profile completion:', error);
    });
}

// ✅ SHOW "OTHER UNIVERSITY"
function toggleOtherUniversity(value) {
    const otherField = document.getElementById('other-university-field');
    if (value === 'Other') {
        otherField.classList.remove('hidden');
    } else {
        otherField.classList.add('hidden');
    }
}

// ✅ FILTER COURSES BY UNIVERSITY
function filterCoursesByUniversity(university) {
    const courseSelect = document.getElementById('course');
    const optgroups = courseSelect.querySelectorAll('optgroup');

    courseSelect.value = ""; // reset selection

    optgroups.forEach(group => {
        if (group.getAttribute('data-university') === university) {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    });

    // Show all if no university selected
    if (!university) {
        optgroups.forEach(group => group.style.display = 'block');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== EDUCATION FORM INIT ===');
    
    const universitySelect = document.getElementById('university');
    const courseSelect = document.getElementById('course');

    console.log('Elements found:', {
        university: !!universitySelect,
        course: !!courseSelect
    });

    // ✅ UNIVERSITY CHANGE
    if (universitySelect) {
        universitySelect.addEventListener('change', function() {
            console.log('University changed to:', this.value);
            toggleOtherUniversity(this.value);
            filterCoursesByUniversity(this.value);
        });
    }

    // ✅ LOAD DEFAULT STATE (IMPORTANT for edit mode)
    if (universitySelect) {
        toggleOtherUniversity(universitySelect.value);
        filterCoursesByUniversity(universitySelect.value);
    }
    
    // Check profile completion on load
    checkProfileCompletion();
    
    console.log('=== EDUCATION FORM INIT COMPLETE ===');
});
</script>
