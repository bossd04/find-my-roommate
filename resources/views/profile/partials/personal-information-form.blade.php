<section class="space-y-6">
    <form method="post" action="{{ route('profile.update.details') }}" enctype="multipart/form-data" id="personal-info-form">
        @csrf
        @method('patch')
        <input type="hidden" name="form_section" value="personal_information">

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
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" 
                        :value="old('phone', $user->phone)" required autocomplete="tel" 
                        placeholder="+63 XXX XXX XXXX" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        <option value="prefer-not-to-say" {{ old('gender', $user->gender) == 'prefer-not-to-say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                </div>

                <div>
                    <x-input-label for="location" :value="__('Location')" />
                    <select id="location" name="location" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm filter-dropdown" required>
                        <option value="">Select your location</option>
                        <option value="other" {{ old('location') == 'other' ? 'selected' : '' }}>Other (specify below)</option>
                        
                        @php
                            $savedLocation = $user->roommateProfile ? $user->roommateProfile->apartment_location : null;
                        @endphp
                        
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

                <div id="custom-location-field" class="hidden">
                    <x-input-label for="custom_location" :value="__('Custom Location')" />
                    <x-text-input id="custom_location" name="custom_location" type="text" class="mt-1 block w-full" 
                        :value="old('custom_location')" placeholder="Enter your location" />
                    <x-input-error class="mt-2" :messages="$errors->get('custom_location')" />
                </div>
            </div>
        </div>

        <div class="mt-8">
            <button type="button" onclick="submitPersonalForm()" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" id="personal-info-submit">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Personal Information
            </button>
        </div>
    </form>
</section>
