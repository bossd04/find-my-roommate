@extends('admin.layouts.app')

@section('title', 'Roommate Preferences')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Roommate Preferences</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    Manage user roommate matching preferences and criteria
                    <span class="text-indigo-500 dark:text-indigo-400 font-black ml-1">({{ $preferences->total() }} total)</span>
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" 
               class="mt-6 md:mt-0 inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-500/20 shadow-lg transition-all active:scale-95">
                <i class="fas fa-arrow-left mr-3"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 mb-8">
        <form method="GET" action="{{ route('admin.preferences.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Search User</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Name or email..."
                       class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Preferred Gender</label>
                <select name="gender" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">All Genders</option>
                    <option value="no_preference" {{ request('gender') === 'no_preference' ? 'selected' : '' }}>No Preference</option>
                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Smoking OK</label>
                <select name="smoking" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Any</option>
                    <option value="1" {{ request('smoking') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('smoking') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white text-xs font-black uppercase tracking-wider rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.preferences.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-black uppercase tracking-wider rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Preferences Table -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        @if($preferences->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">User</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Preferences</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Budget</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Age Range</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Location</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($preferences as $pref)
                            <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-sm"
                                                 src="{{ $pref->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($pref->user->full_name) . '&color=7F9CF5&background=EBF4FF' }}"
                                                 alt="{{ $pref->user->full_name }}">
                                        </div>
                                        <div class="ml-4 min-w-0">
                                            <div class="text-sm font-black text-gray-900 dark:text-gray-50 group-hover:text-indigo-600 transition-colors truncate">
                                                {{ $pref->user->full_name }}
                                            </div>
                                            <div class="text-[11px] font-medium text-gray-400 dark:text-gray-500 truncate">{{ $pref->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if($pref->preferred_gender)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                                {{ ucfirst(str_replace('_', ' ', $pref->preferred_gender)) }}
                                            </span>
                                        @endif
                                        @if($pref->smoking_ok !== null)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $pref->smoking_ok ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' }}">
                                                Smoking {{ $pref->smoking_ok ? 'OK' : 'No' }}
                                            </span>
                                        @endif
                                        @if($pref->pets_ok !== null)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $pref->pets_ok ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                                                Pets {{ $pref->pets_ok ? 'OK' : 'No' }}
                                            </span>
                                        @endif
                                        @if($pref->preferred_cleanliness)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400">
                                                {{ ucfirst(str_replace('_', ' ', $pref->preferred_cleanliness)) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($pref->min_budget || $pref->max_budget)
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            ₱{{ number_format($pref->min_budget ?? 0, 0) }} - ₱{{ number_format($pref->max_budget ?? 0, 0) }}
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($pref->min_age || $pref->max_age)
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $pref->min_age ?? '?' }} - {{ $pref->max_age ?? '?' }} yrs
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 truncate max-w-[150px]" title="{{ $pref->preferred_location }}">
                                        {{ $pref->preferred_location ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.preferences.edit', $pref) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                            <i class="fas fa-edit mr-1.5"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.preferences.destroy', $pref) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete these preferences?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-xs font-bold hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">
                                                <i class="fas fa-trash mr-1.5"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($preferences->hasPages())
                <div class="bg-gray-50/50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $preferences->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800 mb-4">
                    <i class="fas fa-sliders-h text-3xl text-gray-300 dark:text-gray-600"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No Preferences Found</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                    No roommate preferences match your current filters. Try adjusting your search criteria.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
