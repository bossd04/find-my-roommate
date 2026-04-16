@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Roommate Preferences
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Tell us about your ideal roommate to help us find the best matches for you.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('preferences.update', $preferences) }}" class="mt-8 space-y-8 divide-y divide-gray-200">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-8 divide-y divide-gray-200">
                        <!-- Basic Preferences -->
                        <div class="pt-8">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Basic Preferences</h3>
                                <p class="mt-1 text-sm text-gray-500">Your general preferences for a potential roommate.</p>
                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="preferred_gender" class="block text-sm font-medium text-gray-700">Preferred Gender</label>
                                    <div class="mt-1">
                                        <select id="preferred_gender" name="preferred_gender" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no_preference" {{ old('preferred_gender', $preferences->preferred_gender) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                            <option value="male" {{ old('preferred_gender', $preferences->preferred_gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('preferred_gender', $preferences->preferred_gender) == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('preferred_gender', $preferences->preferred_gender) == 'other' ? 'selected' : '' }}>Other/Non-binary</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="age_range_min" class="block text-sm font-medium text-gray-700">Age Range</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <div class="relative flex-grow focus-within:z-10">
                                            <input type="number" name="age_range_min" id="age_range_min" min="18" max="120" value="{{ old('age_range_min', $preferences->age_range_min) }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300" placeholder="Min">
                                        </div>
                                        <span class="inline-flex items-center px-3 rounded-r-none border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            to
                                        </span>
                                        <div class="relative flex-grow focus-within:z-10">
                                            <input type="number" name="age_range_max" id="age_range_max" min="18" max="120" value="{{ old('age_range_max', $preferences->age_range_max) }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="Max">
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="cleanliness" class="block text-sm font-medium text-gray-700">Cleanliness Level</label>
                                    <div class="mt-1">
                                        <select id="cleanliness" name="cleanliness" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no_preference" {{ old('cleanliness', $preferences->cleanliness) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                            <option value="very_messy" {{ old('cleanliness', $preferences->cleanliness) == 'very_messy' ? 'selected' : '' }}>Very Messy</option>
                                            <option value="somewhat_messy" {{ old('cleanliness', $preferences->cleanliness) == 'somewhat_messy' ? 'selected' : '' }}>Somewhat Messy</option>
                                            <option value="average" {{ old('cleanliness', $preferences->cleanliness) == 'average' ? 'selected' : '' }}>Average</option>
                                            <option value="somewhat_clean" {{ old('cleanliness', $preferences->cleanliness) == 'somewhat_clean' ? 'selected' : '' }}>Somewhat Clean</option>
                                            <option value="very_clean" {{ old('cleanliness', $preferences->cleanliness) == 'very_clean' ? 'selected' : '' }}>Very Clean</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="noise_level" class="block text-sm font-medium text-gray-700">Noise Level</label>
                                    <div class="mt-1">
                                        <select id="noise_level" name="noise_level" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no_preference" {{ old('noise_level', $preferences->noise_level) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                            <option value="very_quiet" {{ old('noise_level', $preferences->noise_level) == 'very_quiet' ? 'selected' : '' }}>Very Quiet</option>
                                            <option value="quiet" {{ old('noise_level', $preferences->noise_level) == 'quiet' ? 'selected' : '' }}>Quiet</option>
                                            <option value="moderate" {{ old('noise_level', $preferences->noise_level) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                            <option value="lively" {{ old('noise_level', $preferences->noise_level) == 'lively' ? 'selected' : '' }}>Lively</option>
                                            <option value="very_loud" {{ old('noise_level', $preferences->noise_level) == 'very_loud' ? 'selected' : '' }}>Very Loud</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="schedule" class="block text-sm font-medium text-gray-700">Preferred Schedule</label>
                                    <div class="mt-1">
                                        <select id="schedule" name="schedule" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no_preference" {{ old('schedule', $preferences->schedule) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                            <option value="morning_person" {{ old('schedule', $preferences->schedule) == 'morning_person' ? 'selected' : '' }}>Morning Person</option>
                                            <option value="night_owl" {{ old('schedule', $preferences->schedule) == 'night_owl' ? 'selected' : '' }}>Night Owl</option>
                                            <option value="flexible" {{ old('schedule', $preferences->schedule) == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <fieldset>
                                        <legend class="block text-sm font-medium text-gray-700">Lifestyle Preferences</legend>
                                        <div class="mt-2 space-y-2">
                                            <div class="flex items-center">
                                                <input id="smoking_ok" name="smoking_ok" type="checkbox" value="1" {{ old('smoking_ok', $preferences->smoking_ok) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="smoking_ok" class="ml-2 block text-sm text-gray-700">Smoking is okay</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="pets_ok" name="pets_ok" type="checkbox" value="1" {{ old('pets_ok', $preferences->pets_ok) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="pets_ok" class="ml-2 block text-sm text-gray-700">Pets are okay</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="guests_ok" name="guests_ok" type="checkbox" value="1" {{ old('guests_ok', $preferences->guests_ok) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="guests_ok" class="ml-2 block text-sm text-gray-700">Frequent guests are okay</label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>

                        <!-- Housing Preferences -->
                        <div class="pt-8">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Housing Preferences</h3>
                                <p class="mt-1 text-sm text-gray-500">What kind of living situation are you looking for?</p>
                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="apartment_type" class="block text-sm font-medium text-gray-700">Apartment Type</label>
                                    <div class="mt-1">
                                        <select id="apartment_type" name="apartment_type" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no_preference" {{ old('apartment_type', $preferences->apartment_type) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                            <option value="apartment" {{ old('apartment_type', $preferences->apartment_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                            <option value="house" {{ old('apartment_type', $preferences->apartment_type) == 'house' ? 'selected' : '' }}>House</option>
                                            <option value="condo" {{ old('apartment_type', $preferences->apartment_type) == 'condo' ? 'selected' : '' }}>Condo</option>
                                            <option value="townhouse" {{ old('apartment_type', $preferences->apartment_type) == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                                            <option value="other" {{ old('apartment_type', $preferences->apartment_type) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="furnished" class="block text-sm font-medium text-gray-700">Furnishing</label>
                                    <div class="mt-1">
                                        <select id="furnished" name="furnished" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no_preference" {{ old('furnished', $preferences->furnished) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                            <option value="furnished" {{ old('furnished', $preferences->furnished) == 'furnished' ? 'selected' : '' }}>Furnished</option>
                                            <option value="unfurnished" {{ old('furnished', $preferences->furnished) == 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                                            <option value="partially_furnished" {{ old('furnished', $preferences->furnished) == 'partially_furnished' ? 'selected' : '' }}>Partially Furnished</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="sharing_room" class="block text-sm font-medium text-gray-700">Room Sharing</label>
                                    <div class="mt-1">
                                        <select id="sharing_room" name="sharing_room" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no" {{ old('sharing_room', $preferences->sharing_room) == 'no' ? 'selected' : '' }}>Private Room</option>
                                            <option value="yes" {{ old('sharing_room', $preferences->sharing_room) == 'yes' ? 'selected' : '' }}>Willing to Share Room</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="number_of_roommates" class="block text-sm font-medium text-gray-700">Number of Roommates</label>
                                    <div class="mt-1">
                                        <select id="number_of_roommates" name="number_of_roommates" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="1" {{ old('number_of_roommates', $preferences->number_of_roommates) == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('number_of_roommates', $preferences->number_of_roommates) == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('number_of_roommates', $preferences->number_of_roommates) == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('number_of_roommates', $preferences->number_of_roommates) == '4' ? 'selected' : '' }}>4+</option>
                                            <option value="no_preference" {{ old('number_of_roommates', $preferences->number_of_roommates) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="budget_min" class="block text-sm font-medium text-gray-700">Minimum Budget ($/month)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="budget_min" id="budget_min" min="0" step="50" value="{{ old('budget_min', $preferences->budget_min) }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="budget_max" class="block text-sm font-medium text-gray-700">Maximum Budget ($/month)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="budget_max" id="budget_max" min="0" step="50" value="{{ old('budget_max', $preferences->budget_max) }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="lease_duration" class="block text-sm font-medium text-gray-700">Lease Duration</label>
                                    <div class="mt-1">
                                        <select id="lease_duration" name="lease_duration" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="no_preference" {{ old('lease_duration', $preferences->lease_duration) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                                            <option value="1 month" {{ old('lease_duration', $preferences->lease_duration) == '1 month' ? 'selected' : '' }}>1 Month</option>
                                            <option value="3 months" {{ old('lease_duration', $preferences->lease_duration) == '3 months' ? 'selected' : '' }}>3 Months</option>
                                            <option value="6 months" {{ old('lease_duration', $preferences->lease_duration) == '6 months' ? 'selected' : '' }}>6 Months</option>
                                            <option value="1 year" {{ old('lease_duration', $preferences->lease_duration) == '1 year' ? 'selected' : '' }}>1 Year</option>
                                            <option value="flexible" {{ old('lease_duration', $preferences->lease_duration) == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="move_in_date" class="block text-sm font-medium text-gray-700">Preferred Move-in Date</label>
                                    <div class="mt-1">
                                        <input type="date" name="move_in_date" id="move_in_date" value="{{ old('move_in_date', $preferences->move_in_date ? $preferences->move_in_date->format('Y-m-d') : '') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Preferences -->
                        <div class="pt-8">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Additional Preferences</h3>
                                <p class="mt-1 text-sm text-gray-500">Any other preferences or requirements you might have.</p>
                            </div>

                            <div class="mt-6">
                                <label for="additional_preferences" class="block text-sm font-medium text-gray-700">Other Preferences</label>
                                <div class="mt-1">
                                    <textarea id="additional_preferences" name="additional_preferences" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('additional_preferences', $preferences->additional_preferences) }}</textarea>
                                    <p class="mt-2 text-sm text-gray-500">Any other preferences or requirements not covered above.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="{{ route('dashboard') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Back to Dashboard
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Preferences
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
