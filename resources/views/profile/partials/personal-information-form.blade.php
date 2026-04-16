<section class="space-y-6">
    @php
        $user = auth()->user();
        $profile = $user ? $user->roommateProfile : null;
        
        // Calculate personal information completion
        $personalComplete = !empty($user->first_name) && !empty($user->last_name) && !empty($user->email) && 
                          !empty($user->phone) && !empty($user->gender) && !empty($user->date_of_birth) && 
                          !empty($user->location);
        
        $personalFields = [
            'First Name' => !empty($user->first_name),
            'Last Name' => !empty($user->last_name),
            'Email' => !empty($user->email),
            'Phone' => !empty($user->phone),
            'Gender' => !empty($user->gender),
            'Date of Birth' => !empty($user->date_of_birth),
            'Location' => !empty($user->location),
        ];
        
        // Get saved location for form
        $savedLocation = $user->location ?: ($user->roommateProfile ? $user->roommateProfile->apartment_location : null);
    @endphp
    
    <!-- Profile Status Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Profile Status</h3>
                    <p class="text-sm text-gray-600">Complete your information to get better matches</p>
                </div>
            </div>
            @if($personalComplete)
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
                <span>Profile Completion</span>
                <span>{{ round((array_sum($personalFields) / count($personalFields)) * 100) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ (array_sum($personalFields) / count($personalFields)) * 100 }}%"></div>
            </div>
        </div>
        
        <!-- Field Status -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($personalFields as $field => $filled)
                <?php 
                $fieldNameMap = [
                    'First Name' => 'first_name',
                    'Last Name' => 'last_name', 
                    'Email' => 'email',
                    'Phone' => 'phone',
                    'Gender' => 'gender',
                    'Date of Birth' => 'date_of_birth',
                    'Location' => 'location'
                ];
                $dataField = $fieldNameMap[$field] ?? strtolower(str_replace(' ', '_', $field));
                ?>
                <div class="flex items-center space-x-2 p-2 rounded-lg @if($filled) bg-green-50 @else bg-red-50 @endif" data-field="{{ $dataField }}">
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
        
        <!-- Saved Information Display -->
        @if($personalComplete)
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <h4 class="text-sm font-medium text-green-800 mb-2">✅ Profile Information Complete</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Phone:</span>
                        <span class="font-medium text-gray-900">{{ $user->phone }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Gender:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($user->gender) }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Location:</span>
                        <span class="font-medium text-gray-900">{{ $user->location }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Date of Birth:</span>
                        <span class="font-medium text-gray-900">{{ optional($user->date_of_birth)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium text-gray-900">{{ $user->email }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Personal Information Form -->
    <form method="post" action="{{ route('profile.update.personal') }}" enctype="multipart/form-data" id="personal-info-form" class="space-y-8">
        @csrf
        <input type="hidden" name="form_section" value="personal_information">

        <!-- Success Message -->
        <div id="success-message" class="hidden mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-green-800">Personal Information Updated Successfully!</h4>
                    <p class="text-sm text-green-600 mt-1">Your profile completion status has been updated.</p>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error-message" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-red-800">Error Updating Information</h4>
                    <p class="text-sm text-red-600 mt-1" id="error-details">Please check the form and try again.</p>
                </div>
            </div>
        </div>

        <!-- Basic Information Section -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-user-edit text-indigo-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                        <p class="text-sm text-gray-600">Your personal details</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="space-y-2">
                        <x-input-label for="first_name" :value="__('First Name')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="first_name" name="first_name" type="text" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                            :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" 
                            placeholder="Enter your first name" />
                        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                    </div>

                    <!-- Last Name -->
                    <div class="space-y-2">
                        <x-input-label for="last_name" :value="__('Last Name')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="last_name" name="last_name" type="text" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                            :value="old('last_name', $user->last_name)" required autocomplete="family-name" 
                            placeholder="Enter your last name" />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Email Address')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="email" name="email" type="email" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                            :value="old('email', $user->email)" required autocomplete="email" 
                            placeholder="your.email@example.com" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <!-- Phone -->
                    <div class="space-y-2">
                        <x-input-label for="phone" :value="__('Phone Number')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="phone" name="phone" type="tel" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                            :value="old('phone', $user->phone)" required autocomplete="tel" 
                            placeholder="+63 XXX XXX XXXX" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <!-- Gender -->
                    <div class="space-y-2">
                        <x-input-label for="gender" :value="__('Gender')" class="text-sm font-medium text-gray-700" />
                        <select id="gender" name="gender" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            <option value="prefer-not-to-say" {{ old('gender', $user->gender) == 'prefer-not-to-say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                    </div>

                    <!-- Date of Birth -->
                    <div class="space-y-2">
                        <x-input-label for="date_of_birth" :value="__('Date of Birth')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                            value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                        <p class="text-xs text-gray-500 mt-1">Your age will be calculated automatically from your birthdate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Information Section -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Location Information</h3>
                        <p class="text-sm text-gray-600">Where are you located?</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Location Dropdown -->
                <div class="space-y-2">
                    <x-input-label for="location" :value="__('Location')" class="text-sm font-medium text-gray-700" />
                    <select id="location" name="location" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors filter-dropdown" required>
                        <option value="">Select your location</option>
                        <option value="other" {{ (old('location') == 'other' || $savedLocation == 'other') ? 'selected' : '' }}>Other (specify below)</option>
                        
                        <!-- Cities -->
                        <optgroup label="Cities">
                            <option value="Alaminos City" {{ (old('location') == 'Alaminos City' || $savedLocation == 'Alaminos City') ? 'selected' : '' }}>Alaminos City</option>
                            <option value="Dagupan City" {{ (old('location') == 'Dagupan City' || $savedLocation == 'Dagupan City') ? 'selected' : '' }}>Dagupan City</option>
                            <option value="San Carlos City" {{ (old('location') == 'San Carlos City' || $savedLocation == 'San Carlos City') ? 'selected' : '' }}>San Carlos City</option>
                            <option value="Urdaneta City" {{ (old('location') == 'Urdaneta City' || $savedLocation == 'Urdaneta City') ? 'selected' : '' }}>Urdaneta City</option>
                        </optgroup>
                        
                        <!-- Municipalities -->
                        <optgroup label="Municipalities">
                            <option value="Agno" {{ (old('location') == 'Agno' || $savedLocation == 'Agno') ? 'selected' : '' }}>Agno</option>
                            <option value="Aguilar" {{ (old('location') == 'Aguilar' || $savedLocation == 'Aguilar') ? 'selected' : '' }}>Aguilar</option>
                            <option value="Alcala" {{ (old('location') == 'Alcala' || $savedLocation == 'Alcala') ? 'selected' : '' }}>Alcala</option>
                            <option value="Anda" {{ (old('location') == 'Anda' || $savedLocation == 'Anda') ? 'selected' : '' }}>Anda</option>
                            <option value="Asingan" {{ (old('location') == 'Asingan' || $savedLocation == 'Asingan') ? 'selected' : '' }}>Asingan</option>
                            <option value="Balungao" {{ (old('location') == 'Balungao' || $savedLocation == 'Balungao') ? 'selected' : '' }}>Balungao</option>
                            <option value="Bani" {{ (old('location') == 'Bani' || $savedLocation == 'Bani') ? 'selected' : '' }}>Bani</option>
                            <option value="Basista" {{ (old('location') == 'Basista' || $savedLocation == 'Basista') ? 'selected' : '' }}>Basista</option>
                            <option value="Bautista" {{ (old('location') == 'Bautista' || $savedLocation == 'Bautista') ? 'selected' : '' }}>Bautista</option>
                            <option value="Bayambang" {{ (old('location') == 'Bayambang' || $savedLocation == 'Bayambang') ? 'selected' : '' }}>Bayambang</option>
                            <option value="Binalonan" {{ (old('location') == 'Binalonan' || $savedLocation == 'Binalonan') ? 'selected' : '' }}>Binalonan</option>
                            <option value="Binmaley" {{ (old('location') == 'Binmaley' || $savedLocation == 'Binmaley') ? 'selected' : '' }}>Binmaley</option>
                            <option value="Bolinao" {{ (old('location') == 'Bolinao' || $savedLocation == 'Bolinao') ? 'selected' : '' }}>Bolinao</option>
                            <option value="Buenavista" {{ (old('location') == 'Buenavista' || $savedLocation == 'Buenavista') ? 'selected' : '' }}>Buenavista</option>
                            <option value="Bugallon" {{ (old('location') == 'Bugallon' || $savedLocation == 'Bugallon') ? 'selected' : '' }}>Bugallon</option>
                            <option value="Burgos" {{ (old('location') == 'Burgos' || $savedLocation == 'Burgos') ? 'selected' : '' }}>Burgos</option>
                            <option value="Calasiao" {{ (old('location') == 'Calasiao' || $savedLocation == 'Calasiao') ? 'selected' : '' }}>Calasiao</option>
                            <option value="Dasol" {{ (old('location') == 'Dasol' || $savedLocation == 'Dasol') ? 'selected' : '' }}>Dasol</option>
                            <option value="Infanta" {{ (old('location') == 'Infanta' || $savedLocation == 'Infanta') ? 'selected' : '' }}>Infanta</option>
                            <option value="Labrador" {{ (old('location') == 'Labrador' || $savedLocation == 'Labrador') ? 'selected' : '' }}>Labrador</option>
                            <option value="Laoac" {{ (old('location') == 'Laoac' || $savedLocation == 'Laoac') ? 'selected' : '' }}>Laoac</option>
                            <option value="Lingayen" {{ (old('location') == 'Lingayen' || $savedLocation == 'Lingayen') ? 'selected' : '' }}>Lingayen</option>
                            <option value="Mabini" {{ (old('location') == 'Mabini' || $savedLocation == 'Mabini') ? 'selected' : '' }}>Mabini</option>
                            <option value="Malasiqui" {{ (old('location') == 'Malasiqui' || $savedLocation == 'Malasiqui') ? 'selected' : '' }}>Malasiqui</option>
                            <option value="Mangaldan" {{ (old('location') == 'Mangaldan' || $savedLocation == 'Mangaldan') ? 'selected' : '' }}>Mangaldan</option>
                            <option value="Mapandan" {{ (old('location') == 'Mapandan' || $savedLocation == 'Mapandan') ? 'selected' : '' }}>Mapandan</option>
                            <option value="Natividad" {{ (old('location') == 'Natividad' || $savedLocation == 'Natividad') ? 'selected' : '' }}>Natividad</option>
                            <option value="Pozorrubio" {{ (old('location') == 'Pozorrubio' || $savedLocation == 'Pozorrubio') ? 'selected' : '' }}>Pozorrubio</option>
                            <option value="Quezon" {{ (old('location') == 'Quezon' || $savedLocation == 'Quezon') ? 'selected' : '' }}>Quezon</option>
                            <option value="Rosales" {{ (old('location') == 'Rosales' || $savedLocation == 'Rosales') ? 'selected' : '' }}>Rosales</option>
                            <option value="Rosario" {{ (old('location') == 'Rosario' || $savedLocation == 'Rosario') ? 'selected' : '' }}>Rosario</option>
                            <option value="San Fabian" {{ (old('location') == 'San Fabian' || $savedLocation == 'San Fabian') ? 'selected' : '' }}>San Fabian</option>
                            <option value="San Jacinto" {{ (old('location') == 'San Jacinto' || $savedLocation == 'San Jacinto') ? 'selected' : '' }}>San Jacinto</option>
                            <option value="San Manuel" {{ (old('location') == 'San Manuel' || $savedLocation == 'San Manuel') ? 'selected' : '' }}>San Manuel</option>
                            <option value="San Nicolas" {{ (old('location') == 'San Nicolas' || $savedLocation == 'San Nicolas') ? 'selected' : '' }}>San Nicolas</option>
                            <option value="San Quintin" {{ (old('location') == 'San Quintin' || $savedLocation == 'San Quintin') ? 'selected' : '' }}>San Quintin</option>
                            <option value="Santa Barbara" {{ (old('location') == 'Santa Barbara' || $savedLocation == 'Santa Barbara') ? 'selected' : '' }}>Santa Barbara</option>
                            <option value="Santa Maria" {{ (old('location') == 'Santa Maria' || $savedLocation == 'Santa Maria') ? 'selected' : '' }}>Santa Maria</option>
                            <option value="Santo Tomas" {{ (old('location') == 'Santo Tomas' || $savedLocation == 'Santo Tomas') ? 'selected' : '' }}>Santo Tomas</option>
                            <option value="Sison" {{ (old('location') == 'Sison' || $savedLocation == 'Sison') ? 'selected' : '' }}>Sison</option>
                            <option value="Tayug" {{ (old('location') == 'Tayug' || $savedLocation == 'Tayug') ? 'selected' : '' }}>Tayug</option>
                            <option value="Umingan" {{ (old('location') == 'Umingan' || $savedLocation == 'Umingan') ? 'selected' : '' }}>Umingan</option>
                            <option value="Urbiztondo" {{ (old('location') == 'Urbiztondo' || $savedLocation == 'Urbiztondo') ? 'selected' : '' }}>Urbiztondo</option>
                            <option value="Villasis" {{ (old('location') == 'Villasis' || $savedLocation == 'Villasis') ? 'selected' : '' }}>Villasis</option>
                        </optgroup>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('location')" />
                </div>

                <!-- Custom Location Field -->
                <div id="custom-location-field" class="hidden space-y-2">
                    <x-input-label for="custom_location" :value="__('Custom Location')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="custom_location" name="custom_location" type="text" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                        :value="old('custom_location')" placeholder="Enter your location" />
                    <x-input-error class="mt-2" :messages="$errors->get('custom_location')" />
                </div>
            </div>
        </div>

        <!-- Profile Photo Section -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Profile Avatar Display -->
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden border-2 border-white shadow-lg">
                            @if($user->avatar)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}" 
                                     class="w-full h-full object-cover" id="profile-avatar-display">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-indigo-600" id="profile-avatar-display">
                                    <span class="text-white text-xl font-bold">
                                        {{ substr($user->first_name ?? '?', 0, 1) }}{{ substr($user->last_name ?? '?', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
                            <p class="text-sm text-gray-600" id="photo-status-text">
                                @if($user->avatar)
                                    ✅ Photo uploaded successfully
                                @else
                                    Upload a photo to help others recognize you
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($user->avatar)
                        <button type="button" onclick="removeProfilePhoto()" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.132 21H7.868a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Remove Photo
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- Upload Form -->
            <div class="px-6 py-4">
                <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="avatar" :value="__('Upload New Photo')" class="text-sm font-medium text-gray-700" />
                        <input type="file" id="avatar" name="avatar" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                               accept="image/*" onchange="previewAvatar(this)" />
                        <p class="mt-2 text-sm text-gray-500">Upload JPG, PNG or GIF. Maximum file size: 5GB.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" onclick="uploadProfilePhoto(this)" 
                                class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" 
                                id="upload-photo-btn">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.887 3.877A3 3 0 004 4v4a3 3 0 014 4h4a3 3 0 014-4V7a3 3 0 01-4-4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.5 11.5L19 16l-4.5-4.5" />
                            </svg>
                            Upload Photo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="button" onclick="submitPersonalForm(this)" 
                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" 
                id="personal-info-submit">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Personal Information
            </button>
        </div>
    </form>
</section>

<script>
// Submit Personal Form
globalThis.submitPersonalForm = function(button) {
    console.log('Submitting personal form...');
    
    // Show loading state
    const submitBtn = button;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Saving...';
    submitBtn.disabled = true;
    
    // Create form data manually
    const form = document.getElementById('personal-info-form');
    const formData = new FormData(form);
    
    // DEBUG: Log form data
    console.log('Form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
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
            // Hide error message if shown
            const errorMsg = document.getElementById('error-message');
            if (errorMsg) errorMsg.classList.add('hidden');
            
            // Show success notification
            showNotification('Personal information updated successfully!', 'success');
            
            // Show success message
            const successMsg = document.getElementById('success-message');
            if (successMsg) {
                successMsg.classList.remove('hidden');
            }
            
            // Show success state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Updated Successfully!';
            submitBtn.classList.remove('from-blue-600', 'to-indigo-600');
            submitBtn.classList.add('from-green-600', 'to-green-700');
            
            // Update profile completion status immediately
            updateProfileCompletionStatus();
            
            // Update field status indicators
            updateFieldStatusIndicators(formData);
            
            // Show saved information display
            showSavedInformation(formData);
            
            // Log debug info
            console.log('Profile updated successfully:', data.message);
            
            // Update progress indicators if they exist
            if (data.debug_info && data.debug_info.required_fields_status) {
                updateProgressIndicators(data.debug_info.required_fields_status);
            }
            
            // Update completion status in dashboard
            if (data.debug_info) {
                updateDashboardCompletion(data.debug_info.profile_complete);
            }
            
            // Update the completion status display immediately
            updateCompletionStatusDisplay();
            
            // Reload page after short delay to show updated data in all sections
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            // Hide success message if shown
            const successMsg = document.getElementById('success-message');
            if (successMsg) successMsg.classList.add('hidden');
            
            // Show error message
            const errorMsg = document.getElementById('error-message');
            const errorDetails = document.getElementById('error-details');
            if (errorMsg && errorDetails) {
                errorDetails.textContent = data.message || 'Unknown error occurred. Please check form and try again.';
                errorMsg.classList.remove('hidden');
            }
            
            // Show error state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Error';
            submitBtn.classList.remove('from-blue-600', 'to-indigo-600');
            submitBtn.classList.add('from-red-600', 'to-red-700');
            
            // Reset button after delay
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('from-red-600', 'to-red-700');
                submitBtn.classList.add('from-blue-600', 'to-indigo-600');
                if (errorMsg) errorMsg.classList.add('hidden');
            }, 5000);
        }
    })
    .catch(error => {
        console.error('Submission error:', error);
        
        // Hide success message if shown
        const successMsg = document.getElementById('success-message');
        if (successMsg) successMsg.classList.add('hidden');
        
        // Show error message
        const errorMsg = document.getElementById('error-message');
        const errorDetails = document.getElementById('error-details');
        if (errorMsg && errorDetails) {
            errorDetails.textContent = 'Network error occurred. Please try again.';
            errorMsg.classList.remove('hidden');
        }
        
        // Show error state
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Error';
        submitBtn.classList.remove('from-blue-600', 'to-indigo-600');
        submitBtn.classList.add('from-red-600', 'to-red-700');
        
        // Reset button after delay
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('from-red-600', 'to-red-700');
            submitBtn.classList.add('from-blue-600', 'to-indigo-600');
        }, 3000);
    });
};

// Update profile completion status
function updateProfileCompletionStatus() {
    // Update progress bar to 100%
    const progressBar = document.querySelector('.bg-gradient-to-r.from-blue-500');
    if (progressBar) {
        progressBar.style.width = '100%';
    }
    
    // Update completion badge to "Complete"
    const completionBadge = document.querySelector('.bg-orange-100');
    if (completionBadge) {
        completionBadge.classList.remove('bg-orange-100', 'text-orange-700');
        completionBadge.classList.add('bg-green-100', 'text-green-700');
        const badgeText = completionBadge.querySelector('span');
        if (badgeText) {
            badgeText.textContent = 'Complete';
        }
    }
    
    // Update ALL field status indicators from "x" to "✓"
    const allFieldStatuses = document.querySelectorAll('[data-field]');
    allFieldStatuses.forEach(status => {
        // Remove incomplete styling
        status.classList.remove('bg-red-50');
        // Add complete styling
        status.classList.add('bg-green-50');
        
        // Update icon from times-circle to check-circle
        const icon = status.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-times-circle', 'text-red-500');
            icon.classList.add('fa-check-circle', 'text-green-500');
        }
        
        // Update text color from red to gray
        const textSpan = status.querySelector('span');
        if (textSpan) {
            textSpan.classList.remove('text-red-600', 'font-medium');
            textSpan.classList.add('text-gray-700');
        }
    });
    
    // Also update any remaining red indicators
    const redIndicators = document.querySelectorAll('.bg-red-50');
    redIndicators.forEach(indicator => {
        indicator.classList.remove('bg-red-50');
        indicator.classList.add('bg-green-50');
    });
    
    // Update all red icons to green
    const redIcons = document.querySelectorAll('.fa-times-circle.text-red-500');
    redIcons.forEach(icon => {
        icon.classList.remove('fa-times-circle', 'text-red-500');
        icon.classList.add('fa-check-circle', 'text-green-500');
    });
    
    // Update completion percentage text if exists
    const percentageText = document.querySelector('.text-2xl.font-bold');
    if (percentageText) {
        percentageText.textContent = '100%';
    }
    
    console.log('Profile completion status updated to Complete');
}

// Update field status indicators
function updateFieldStatusIndicators(formData) {
    // Update individual field indicators based on form data
    const fields = ['first_name', 'last_name', 'email', 'phone', 'gender', 'date_of_birth', 'location'];
    
    fields.forEach(fieldName => {
        const fieldValue = formData.get(fieldName);
        const fieldStatus = document.querySelector(`[data-field="${fieldName}"]`);
        
        if (fieldStatus && fieldValue) {
            fieldStatus.classList.remove('bg-red-50');
            fieldStatus.classList.add('bg-green-50');
            const icon = fieldStatus.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times-circle', 'text-red-500');
                icon.classList.add('fa-check-circle', 'text-green-500');
            }
        }
    });
}

// Show saved information display
function showSavedInformation(formData) {
    // Create or update saved information display
    let savedInfoDiv = document.getElementById('saved-information-display');
    
    if (!savedInfoDiv) {
        savedInfoDiv = document.createElement('div');
        savedInfoDiv.id = 'saved-information-display';
        savedInfoDiv.className = 'mt-4 p-4 bg-green-50 border border-green-200 rounded-lg';
        
        // Insert after the success message
        const successMsg = document.getElementById('success-message');
        if (successMsg) {
            successMsg.parentNode.insertBefore(savedInfoDiv, successMsg.nextSibling);
        }
    }
    
    // Update the display with saved data
    savedInfoDiv.innerHTML = `
        <h4 class="text-sm font-medium text-green-800 mb-2">✅ Profile Information Complete</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div class="flex items-center space-x-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900">${formData.get('first_name')} ${formData.get('last_name')}</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-600">Phone:</span>
                <span class="font-medium text-gray-900">${formData.get('phone')}</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-600">Gender:</span>
                <span class="font-medium text-gray-900">${formData.get('gender')}</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-600">Location:</span>
                <span class="font-medium text-gray-900">${formData.get('location')}</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-600">Date of Birth:</span>
                <span class="font-medium text-gray-900">${formData.get('date_of_birth')}</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-600">Email:</span>
                <span class="font-medium text-gray-900">${formData.get('email')}</span>
            </div>
        </div>
    `;
    
    savedInfoDiv.style.display = 'block';
}

// Update dashboard completion status
function updateDashboardCompletion(isComplete) {
    // Update completion status in dashboard if elements exist
    const completionBadge = document.querySelector('.completion-status-badge');
    const progressBar = document.querySelector('.completion-progress-bar');
    const statusText = document.querySelector('.completion-status-text');
    const systemAccessCard = document.querySelector('.system-access-card');
    
    if (completionBadge) {
        if (isComplete) {
            completionBadge.innerHTML = '<i class="fas fa-check-circle text-sm"></i><span class="text-sm font-medium">Complete</span>';
            completionBadge.className = 'flex items-center space-x-2 bg-green-100 text-green-700 px-3 py-1.5 rounded-full';
        } else {
            completionBadge.innerHTML = '<i class="fas fa-exclamation-circle text-sm"></i><span class="text-sm font-medium">Incomplete</span>';
            completionBadge.className = 'flex items-center space-x-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full';
        }
    }
    
    if (progressBar) {
        progressBar.style.width = isComplete ? '100%' : '0%';
    }
    
    if (statusText) {
        statusText.textContent = isComplete ? '100%' : '0%';
    }
    
    if (systemAccessCard) {
        if (isComplete) {
            systemAccessCard.classList.remove('bg-red-50', 'border-red-200');
            systemAccessCard.classList.add('bg-green-50', 'border-green-200');
        } else {
            systemAccessCard.classList.remove('bg-green-50', 'border-green-200');
            systemAccessCard.classList.add('bg-red-50', 'border-red-200');
        }
    }
    
    console.log('Dashboard completion updated:', isComplete);
}

    // Update progress indicators after successful save
function updateProgressIndicators(fieldStatus) {
    // Update field status indicators in profile completion card
    Object.keys(fieldStatus).forEach(field => {
        const statusElement = document.querySelector(`[data-field="${field}"]`);
        if (statusElement) {
            if (fieldStatus[field]) {
                statusElement.innerHTML = '<i class="fas fa-check-circle text-green-500 text-sm"></i><span class="text-sm text-gray-700">' + getFieldDisplayName(field) + '</span>';
                statusElement.parentElement.classList.add('bg-green-50');
                statusElement.parentElement.classList.remove('bg-red-50');
            } else {
                statusElement.innerHTML = '<i class="fas fa-times-circle text-red-500 text-sm"></i><span class="text-sm text-red-600 font-medium">' + getFieldDisplayName(field) + '</span>';
                statusElement.parentElement.classList.add('bg-red-50');
                statusElement.parentElement.classList.remove('bg-green-50');
            }
        }
    });
    
    // Update overall progress bar
    const completedCount = Object.values(fieldStatus).filter(status => status).length;
    const totalCount = Object.keys(fieldStatus).length;
    const percentage = Math.round((completedCount / totalCount) * 100);
    
    const progressBar = document.querySelector('.progress-bar-fill');
    if (progressBar) {
        progressBar.style.width = percentage + '%';
    }
    
    const progressText = document.querySelector('.progress-text');
    if (progressText) {
        progressText.textContent = percentage + '%';
    }
}

// Update form field values in real-time
function updateFormFields(fieldStatus) {
    // Update actual form field values to show saved data
    const fieldMappings = {
        'first_name': 'first_name',
        'last_name': 'last_name',
        'email': 'email',
        'phone': 'phone',
        'gender': 'gender',
        'date_of_birth': 'date_of_birth',
        'location': 'location'
    };
    
    Object.keys(fieldMappings).forEach(dbField => {
        const formFieldId = fieldMappings[dbField];
        const formField = document.getElementById(formFieldId);
        if (formField && fieldStatus[dbField]) {
            // For select fields, we need to update the selected option
            if (formField.tagName === 'SELECT') {
                // Find and select the correct option
                const option = formField.querySelector(`option[value="${formField.value}"]`);
                if (option) {
                    formField.value = option.value;
                }
            } else {
                // For input fields, update the value with the actual saved value
                // Get the current value from the form to preserve it
                formField.value = formField.value || '';
            }
        }
    });
}

// Get display name for field
function getFieldDisplayName(field) {
    const names = {
        'first_name': 'First Name',
        'last_name': 'Last Name',
        'email': 'Email',
        'phone': 'Phone',
        'gender': 'Gender',
        'date_of_birth': 'Date of Birth',
        'location': 'Location',
        'university': 'University',
        'course': 'Course',
        'year_level': 'Year Level',
        'budget_min': 'Budget Min',
        'budget_max': 'Budget Max'
    };
    return names[field] || field;
}

// Remove Profile Photo
function removeProfilePhoto() {
    if (confirm('Are you sure you want to remove your profile photo?')) {
        fetch('{{ route("profile.avatar.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Reload page to show updated photo
                window.location.reload();
            } else {
                alert('Error removing photo: ' + (data.message || 'Unknown error occurred'));
            }
        })
        .catch(error => {
            console.error('Removal error:', error);
            alert('Network error occurred while removing photo. Please try again.');
        });
    }
}

// Handle location dropdown change
document.addEventListener('DOMContentLoaded', function() {
    const locationSelect = document.getElementById('location');
    const customLocationField = document.getElementById('custom-location-field');
    
    if (locationSelect && customLocationField) {
        locationSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                customLocationField.classList.remove('hidden');
            } else {
                customLocationField.classList.add('hidden');
            }
        });
    }
});

// Update completion status display immediately
function updateCompletionStatusDisplay() {
    // Update field status indicators
    const fieldStatuses = document.querySelectorAll('.field-status');
    fieldStatuses.forEach(status => {
        status.classList.remove('bg-red-50');
        status.classList.add('bg-green-50');
    });
    
    // Update progress bar
    const progressBar = document.querySelector('.bg-gradient-to-r.from-blue-500');
    if (progressBar) {
        progressBar.style.width = '100%';
    }
    
    // Update completion badge
    const completionBadge = document.querySelector('.bg-orange-100');
    if (completionBadge) {
        completionBadge.classList.remove('bg-orange-100', 'text-orange-700');
        completionBadge.classList.add('bg-green-100', 'text-green-700');
        const badgeText = completionBadge.querySelector('span');
        if (badgeText) {
            badgeText.textContent = 'Complete';
        }
    }
    
    // Show saved information display
    const savedInfoSection = document.querySelector('.mt-4.p-4.bg-green-50');
    if (savedInfoSection) {
        savedInfoSection.style.display = 'block';
    }
}

// Global notification function (in case it's not defined)
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
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>' :
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
