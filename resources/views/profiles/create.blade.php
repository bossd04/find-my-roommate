@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Create Your Profile
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Tell potential roommates about yourself and your living preferences.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profiles.store') }}" class="mt-8 space-y-8 divide-y divide-gray-200" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-8 divide-y divide-gray-200">
                        <!-- Basic Information -->
                        <div class="pt-8">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Basic Information</h3>
                                <p class="mt-1 text-sm text-gray-500">This information will be displayed publicly so be careful what you share.</p>
                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name</label>
                                    <div class="mt-1">
                                        <input type="text" name="display_name" id="display_name" value="{{ old('display_name') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('display_name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('display_name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                                    <div class="mt-1">
                                        <input type="number" name="age" id="age" min="18" max="120" value="{{ old('age') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('age') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('age')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                    <div class="mt-1">
                                        <select id="gender" name="gender" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('gender') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                            <option value="prefer_not_to_say" {{ old('gender') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                        </select>
                                        @error('gender')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="bio" class="block text-sm font-medium text-gray-700">About You</label>
                                    <div class="mt-1">
                                        <textarea id="bio" name="bio" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md @error('bio') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">{{ old('bio') }}</textarea>
                                        <p class="mt-2 text-sm text-gray-500">Tell potential roommates about yourself, your interests, and what you're looking for in a roommate.</p>
                                        @error('bio')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="university" class="block text-sm font-medium text-gray-700">University/Institution</label>
                                    <div class="mt-1">
                                        <input type="text" name="university" id="university" value="{{ old('university') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="major" class="block text-sm font-medium text-gray-700">Major/Field of Study</label>
                                    <div class="mt-1">
                                        <input type="text" name="major" id="major" value="{{ old('major') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Living Preferences -->
                        <div class="pt-8">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Living Preferences</h3>
                                <p class="mt-1 text-sm text-gray-500">Help us match you with compatible roommates.</p>
                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="cleanliness_level" class="block text-sm font-medium text-gray-700">Cleanliness Level</label>
                                    <div class="mt-1">
                                        <select id="cleanliness_level" name="cleanliness_level" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('cleanliness_level') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="" disabled {{ old('cleanliness_level') ? '' : 'selected' }}>Select cleanliness level</option>
                                            <option value="very_messy" {{ old('cleanliness_level') == 'very_messy' ? 'selected' : '' }}>Very Messy</option>
                                            <option value="somewhat_messy" {{ old('cleanliness_level') == 'somewhat_messy' ? 'selected' : '' }}>Somewhat Messy</option>
                                            <option value="average" {{ old('cleanliness_level') == 'average' ? 'selected' : '' }}>Average</option>
                                            <option value="somewhat_clean" {{ old('cleanliness_level') == 'somewhat_clean' ? 'selected' : '' }}>Somewhat Clean</option>
                                            <option value="very_clean" {{ old('cleanliness_level') == 'very_clean' ? 'selected' : '' }}>Very Clean</option>
                                        </select>
                                        @error('cleanliness_level')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="noise_level" class="block text-sm font-medium text-gray-700">Noise Level</label>
                                    <div class="mt-1">
                                        <select id="noise_level" name="noise_level" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('noise_level') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="" disabled {{ old('noise_level') ? '' : 'selected' }}>Select noise level</option>
                                            <option value="very_quiet" {{ old('noise_level') == 'very_quiet' ? 'selected' : '' }}>Very Quiet</option>
                                            <option value="quiet" {{ old('noise_level') == 'quiet' ? 'selected' : '' }}>Quiet</option>
                                            <option value="moderate" {{ old('noise_level') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                            <option value="lively" {{ old('noise_level') == 'lively' ? 'selected' : '' }}>Lively</option>
                                            <option value="very_loud" {{ old('noise_level') == 'very_loud' ? 'selected' : '' }}>Very Loud</option>
                                        </select>
                                        @error('noise_level')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="schedule" class="block text-sm font-medium text-gray-700">Daily Schedule</label>
                                    <div class="mt-1">
                                        <select id="schedule" name="schedule" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('schedule') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="" disabled {{ old('schedule') ? '' : 'selected' }}>Select your schedule</option>
                                            <option value="morning_person" {{ old('schedule') == 'morning_person' ? 'selected' : '' }}>Morning Person</option>
                                            <option value="night_owl" {{ old('schedule') == 'night_owl' ? 'selected' : '' }}>Night Owl</option>
                                            <option value="flexible" {{ old('schedule') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                            <option value="irregular" {{ old('schedule') == 'irregular' ? 'selected' : '' }}>Irregular Schedule</option>
                                        </select>
                                        @error('schedule')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <fieldset>
                                        <legend class="text-sm font-medium text-gray-700">Lifestyle</legend>
                                        <div class="mt-2 space-y-2">
                                            <div class="flex items-center">
                                                <input id="smoking_allowed" name="smoking_allowed" type="checkbox" value="1" {{ old('smoking_allowed') ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="smoking_allowed" class="ml-2 block text-sm text-gray-700">Smoking allowed</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="pets_allowed" name="pets_allowed" type="checkbox" value="1" {{ old('pets_allowed') ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="pets_allowed" class="ml-2 block text-sm text-gray-700">Pets allowed</label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>

                        <!-- Housing Information -->
                        <div class="pt-8">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Housing Information</h3>
                                <p class="mt-1 text-sm text-gray-500">Let us know about your current or desired housing situation.</p>
                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-6">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="has_apartment" name="has_apartment" type="checkbox" value="1" {{ old('has_apartment') ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="has_apartment" class="font-medium text-gray-700">I already have an apartment/room to share</label>
                                            <p class="text-gray-500">Check this if you're looking for someone to move into your current place.</p>
                                        </div>
                                    </div>
                                </div>

                                <div id="apartment-fields" class="sm:col-span-6 pl-6 border-l-2 border-gray-200 {{ old('has_apartment') ? '' : 'hidden' }}">
                                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div class="sm:col-span-6">
                                            <label for="apartment_location" class="block text-sm font-medium text-gray-700">Apartment Location</label>
                                            <div class="mt-1">
                                                <input type="text" name="apartment_location" id="apartment_location" value="{{ old('apartment_location') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                <p class="mt-1 text-sm text-gray-500">Address or general area of your apartment</p>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="budget_min" class="block text-sm font-medium text-gray-700">Minimum Budget ($/month)</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" name="budget_min" id="budget_min" min="0" step="50" value="{{ old('budget_min') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="budget_max" class="block text-sm font-medium text-gray-700">Maximum Budget ($/month)</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" name="budget_max" id="budget_max" min="0" step="50" value="{{ old('budget_max') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="move_in_date" class="block text-sm font-medium text-gray-700">Move-in Date</label>
                                            <div class="mt-1">
                                                <input type="date" name="move_in_date" id="move_in_date" value="{{ old('move_in_date') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="lease_duration" class="block text-sm font-medium text-gray-700">Lease Duration</label>
                                            <div class="mt-1">
                                                <select id="lease_duration" name="lease_duration" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    <option value="" {{ old('lease_duration') ? '' : 'selected' }}>Select duration</option>
                                                    <option value="1 month" {{ old('lease_duration') == '1 month' ? 'selected' : '' }}>1 Month</option>
                                                    <option value="3 months" {{ old('lease_duration') == '3 months' ? 'selected' : '' }}>3 Months</option>
                                                    <option value="6 months" {{ old('lease_duration') == '6 months' ? 'selected' : '' }}>6 Months</option>
                                                    <option value="1 year" {{ old('lease_duration') == '1 year' ? 'selected' : '' }}>1 Year</option>
                                                    <option value="Flexible" {{ old('lease_duration') == 'Flexible' ? 'selected' : '' }}>Flexible</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="{{ route('home') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle apartment fields based on checkbox
    document.getElementById('has_apartment').addEventListener('change', function() {
        const apartmentFields = document.getElementById('apartment-fields');
        if (this.checked) {
            apartmentFields.classList.remove('hidden');
        } else {
            apartmentFields.classList.add('hidden');
        }
    });

    // Initialize the apartment fields visibility based on the checkbox state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const hasApartment = document.getElementById('has_apartment');
        const apartmentFields = document.getElementById('apartment-fields');
        
        if (hasApartment.checked) {
            apartmentFields.classList.remove('hidden');
        }
    });
</script>
@endpush
@endsection
