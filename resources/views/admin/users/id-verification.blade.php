@extends('admin.layouts.app')

@section('title', 'ID Verification')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">ID Verification</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest max-w-2xl">
                    Review full member profiles with ID documents. Only users who completed every required profile field and uploaded front and back ID appear here—so you can compare account details against the ID and reduce fake accounts.
                    <span class="text-indigo-500 dark:text-indigo-400 font-black ml-1">({{ $users->total() }} in queue)</span>
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="mt-6 md:mt-0 inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-500/20 shadow-lg transition-all active:scale-95">
                <i class="fas fa-arrow-left mr-3"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-8">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">User</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Profile</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">ID &amp; review</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Status</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Submitted</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($users as $user)
                            @php $reviewPayload = $user->toAdminIdReviewPayload(); @endphp
                            <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition-colors" id="user-row-{{ $user->id }}">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-sm"
                                                 src="{{ $reviewPayload['avatarUrl'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($reviewPayload['userName']) . '&color=7F9CF5&background=EBF4FF' }}"
                                                 alt="{{ $reviewPayload['userName'] }}">
                                        </div>
                                        <div class="ml-4 min-w-0">
                                            <div class="text-sm font-black text-gray-900 dark:text-gray-50 group-hover:text-indigo-600 transition-colors truncate">{{ $reviewPayload['userName'] }}</div>
                                            <div class="text-[11px] font-medium text-gray-400 dark:text-gray-500 leading-none truncate">{{ $user->email }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 mt-1 truncate">{{ $reviewPayload['userUniversity'] }} · {{ $reviewPayload['userLocation'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 align-top">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                                        <i class="fas fa-check mr-1.5"></i> Full profile
                                    </span>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2 max-w-[10rem] leading-relaxed">Bio, education, lifestyle, budget, and both ID images on file.</p>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                $hasFrontId = $user->id_card_front || $user->userValidation?->id_front_image;
                                $hasBackId = $user->id_card_back || $user->userValidation?->id_back_image;
                            @endphp
                            @if($hasFrontId && $hasBackId)
                                        <button type="button"
                                                class="js-open-id-review inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl text-xs font-black uppercase tracking-widest border border-indigo-100 dark:border-indigo-800 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                                data-payload='@json($reviewPayload)'>
                                            <i class="fas fa-user-shield mr-2 scale-110"></i> Review profile &amp; ID
                                        </button>
                                    @else
                                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 italic">Missing front or back ID</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">
                                        Pending
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-black text-gray-500 dark:text-gray-400 leading-none">
                                        {{ $user->updated_at->format('M j, Y') }}
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-400 dark:text-gray-400 mt-1 uppercase tracking-tighter">
                                        {{ $user->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="verifyId({{ $user->id }})" 
                                                class="px-4 py-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-500/20 active:scale-95 transition-all">
                                            <i class="fas fa-check mr-2"></i> Verify
                                        </button>
                                        <button onclick="rejectId({{ $user->id }})" 
                                                class="px-4 py-2 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-700 shadow-md shadow-red-500/20 active:scale-95 transition-all">
                                            <i class="fas fa-times mr-2"></i> Reject
                                        </button>
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Premium Pagination -->
            @if($users->hasPages())
                <div class="bg-gray-50/50 dark:bg-gray-900/50 px-8 py-6 border-t border-gray-100 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="p-20 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-emerald-50 dark:bg-emerald-900/20 mb-6 group">
                    <i class="fas fa-check-double text-4xl text-emerald-500 group-hover:scale-125 transition-transform duration-500"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">System All Clear!</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2 max-w-md mx-auto">No users in the review queue.</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-8 max-w-lg mx-auto">Users appear here only after they complete every required profile field and upload both sides of their ID with status pending.</p>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/30 transition-all active:scale-95">
                    <i class="fas fa-users mr-3"></i> View All Users
                </a>
            </div>
        @endif
    </div>
</div>
</div>

<!-- ID Document Modal -->
<div id="idDocumentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeIdModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-gray-900 rounded-lg text-left overflow-y-auto max-h-[85vh] shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <div class="bg-white dark:bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <div class="space-y-6">
                            {{-- Section 1: Complete User Information --}}
                            <div class="pb-6 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Member profile (cross-check with ID)</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div>
                                        <h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3">Identity &amp; contact</h5>
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-xs text-gray-500">Full name</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserName"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Email</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 break-all" id="modalUserEmail"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Phone</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserPhone"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Gender</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserGender"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Date of birth</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserDateOfBirth"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Age (on account)</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserAge"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3">Location &amp; housing</h5>
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-xs text-gray-500">Primary location</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserLocation"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Apartment / area (profile)</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserApartmentLocation"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Preferred location</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserPreferredLocation"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Budget range</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserBudget"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Has apartment</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserHasApartment"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Move-in date</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserMoveInDate"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Lease duration</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserLeaseDuration"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3">Education</h5>
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-xs text-gray-500">University</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserUniversity"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Course / major</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserCourse"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Year level</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserYearLevel"></p>
                                            </div>
                                        </div>
                                        <h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3 mt-5">Lifestyle (roommate)</h5>
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-xs text-gray-500">Study habit</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserStudyHabit"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Sleep pattern</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserSleepPattern"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Noise tolerance</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserNoiseTolerance"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Lifestyle tags</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserLifestyle"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Hobbies</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserHobbies"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3">Living preferences</h5>
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-xs text-gray-500">Cleanliness</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserCleanliness"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Noise level</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserNoise"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Schedule</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserSchedule"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Smoking allowed</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserSmoking"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Pets allowed</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalUserPets"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3">Bio</h5>
                                    <div class="bg-gray-50 dark:bg-gray-800/80 rounded-lg p-4">
                                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap" id="modalUserBio"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: ID Information --}}
                            <div class="pb-6 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">ID Verification Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <p class="text-xs text-gray-500">ID Type</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalIdType"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">ID Number</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100" id="modalIdNumber"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Verification Status</p>
                                        <span id="modalStatusBadge"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: Images --}}
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">ID Images</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 mb-2">Front of ID</p>
                                        <div class="aspect-video bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded-xl overflow-hidden flex items-center justify-center relative">
                                            <img id="modalIdImageFront" src="" alt="Front of ID" 
                                                 class="max-h-full max-w-full object-contain">
                                            <div id="modalIdImageFrontError" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm">Front ID not available</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 mb-2">Back of ID</p>
                                        <div class="aspect-video bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded-xl overflow-hidden flex items-center justify-center relative">
                                            <img id="modalIdImageBack" src="" alt="Back of ID" 
                                                 class="max-h-full max-w-full object-contain">
                                            <div id="modalIdImageBackError" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm">Back ID not available</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-center space-x-4">
                            <button id="modalVerifyBtn" onclick="" 
                                    class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-bold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all transform hover:scale-105">
                                <i class="fas fa-check mr-2"></i> Approve ID
                            </button>
                            <button id="modalRejectBtn" onclick="" 
                                    class="inline-flex items-center px-6 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-all transform hover:scale-105">
                                <i class="fas fa-times mr-2"></i> Reject ID
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeIdModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-5 py-2 bg-white dark:bg-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto">
                    Back to List
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for ID Verification -->
<script>
function setModalText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = (value !== undefined && value !== null && value !== '') ? value : '—';
}

function viewIdDocument(data) {
    console.log('Opening ID document modal with data:', data);
    
    // Set all user profile fields
    setModalText('modalUserName', data.userName);
    setModalText('modalUserEmail', data.userEmail);
    setModalText('modalUserPhone', data.userPhone);
    setModalText('modalUserGender', data.userGender);
    setModalText('modalUserDateOfBirth', data.userDateOfBirth);
    setModalText('modalUserAge', data.userAge);
    setModalText('modalUserLocation', data.userLocation);
    setModalText('modalUserApartmentLocation', data.userApartmentLocation);
    setModalText('modalUserPreferredLocation', data.userPreferredLocation);
    setModalText('modalUserBudget', data.userBudget);
    setModalText('modalUserHasApartment', data.userHasApartment);
    setModalText('modalUserMoveInDate', data.userMoveInDate);
    setModalText('modalUserLeaseDuration', data.userLeaseDuration);
    setModalText('modalUserUniversity', data.userUniversity);
    setModalText('modalUserCourse', data.userCourse);
    setModalText('modalUserYearLevel', data.userYearLevel);
    setModalText('modalUserStudyHabit', data.userStudyHabit);
    setModalText('modalUserSleepPattern', data.userSleepPattern);
    setModalText('modalUserNoiseTolerance', data.userNoiseTolerance);
    setModalText('modalUserLifestyle', data.userLifestyle);
    setModalText('modalUserHobbies', data.userHobbies);
    setModalText('modalUserCleanliness', data.userCleanliness);
    setModalText('modalUserNoise', data.userNoise);
    setModalText('modalUserSchedule', data.userSchedule);
    setModalText('modalUserSmoking', data.userSmoking);
    setModalText('modalUserPets', data.userPets);
    setModalText('modalUserBio', data.userBio);
    setModalText('modalIdType', data.idType);
    setModalText('modalIdNumber', data.idNumber);
    
    // Set Status Badge
    const badge = document.getElementById('modalStatusBadge');
    if (badge) {
        if (data.status === 'approved') {
            badge.innerHTML = '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Verified</span>';
        } else if (data.status === 'rejected') {
            badge.innerHTML = '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Rejected</span>';
        } else {
            badge.innerHTML = '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>';
        }
    } else {
        console.error('Status badge element not found');
    }

    // Set ID images with error handling
    const frontImage = document.getElementById('modalIdImageFront');
    const backImage = document.getElementById('modalIdImageBack');
    const frontError = document.getElementById('modalIdImageFrontError');
    const backError = document.getElementById('modalIdImageBackError');
    
    console.log('Image URLs from payload:', {
        imageFront: data.imageFront,
        imageBack: data.imageBack
    });
    
    if (frontImage && frontError) {
        // Reset display first
        frontImage.style.display = 'none';
        frontError.classList.add('hidden');
        frontError.style.display = 'none';
        
        if (data.imageFront) {
            console.log('Setting front image src to:', data.imageFront);
            
            // Set up load handler first
            frontImage.onload = function() {
                console.log('Front image loaded successfully');
                frontImage.style.display = 'block';
                frontError.classList.add('hidden');
                frontError.style.display = 'none';
            };
            
            // Set up error handler
            frontImage.onerror = function() {
                console.error('Failed to load front ID image:', data.imageFront);
                frontImage.style.display = 'none';
                frontError.classList.remove('hidden');
                frontError.style.display = 'flex';
            };
            
            // Now set the src
            frontImage.src = data.imageFront;
        } else {
            console.log('No front image URL in payload');
            frontImage.src = '';
            frontError.classList.remove('hidden');
            frontError.style.display = 'flex';
        }
    } else {
        console.error('Front ID image or error element not found');
    }
    
    if (backImage && backError) {
        // Reset display first
        backImage.style.display = 'none';
        backError.classList.add('hidden');
        backError.style.display = 'none';
        
        if (data.imageBack) {
            console.log('Setting back image src to:', data.imageBack);
            
            // Set up load handler first
            backImage.onload = function() {
                console.log('Back image loaded successfully');
                backImage.style.display = 'block';
                backError.classList.add('hidden');
                backError.style.display = 'none';
            };
            
            // Set up error handler
            backImage.onerror = function() {
                console.error('Failed to load back ID image:', data.imageBack);
                backImage.style.display = 'none';
                backError.classList.remove('hidden');
                backError.style.display = 'flex';
            };
            
            // Now set the src
            backImage.src = data.imageBack;
        } else {
            console.log('No back image URL in payload');
            backImage.src = '';
            backError.classList.remove('hidden');
            backError.style.display = 'flex';
        }
    } else {
        console.error('Back ID image or error element not found');
    }
    
    // Set button actions
    const verifyBtn = document.getElementById('modalVerifyBtn');
    const rejectBtn = document.getElementById('modalRejectBtn');
    
    if (verifyBtn) {
        verifyBtn.onclick = function() { verifyId(data.userId); };
    } else {
        console.error('Verify button not found');
    }
    
    if (rejectBtn) {
        rejectBtn.onclick = function() { rejectId(data.userId); };
    } else {
        console.error('Reject button not found');
    }
    
    // Show the modal
    const modal = document.getElementById('idDocumentModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'block';
        modal.style.zIndex = '9999';
        modal.style.position = 'fixed';
        console.log('Modal should now be visible');
        console.log('Modal classes:', modal.className);
        console.log('Modal styles:', modal.style.cssText);
        
        // Force modal to be visible
        setTimeout(() => {
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            console.log('Forced modal visibility after timeout');
        }, 100);
    } else {
        console.error('Modal element not found');
    }
}

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.js-open-id-review');
    if (!btn || !btn.getAttribute('data-payload')) return;
    
    console.log('Review button clicked');
    
    try {
        const payload = JSON.parse(btn.getAttribute('data-payload'));
        console.log('Parsed payload:', payload);
        viewIdDocument(payload);
    } catch (err) {
        console.error('Error parsing payload:', err);
        console.error('Raw payload:', btn.getAttribute('data-payload'));
        alert('Could not load review data. Please refresh the page.');
    }
});

function closeIdModal() {
    const modal = document.getElementById('idDocumentModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        console.log('Modal hidden');
    }
}

function verifyId(userId) {
    if (confirm('Are you sure you want to verify this user\'s ID?')) {
        const url = '{{ route("admin.users.id-verify", ":id") }}'.replace(':id', userId);
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeIdModal();
                // Remove the row from the table
                const row = document.getElementById('user-row-' + userId);
                if (row) {
                    row.remove();
                }
                // Check if table is empty
                const tbody = document.querySelector('tbody');
                if (tbody.children.length === 0) {
                    location.reload();
                }
            } else {
                alert(data.message || 'An error occurred while verifying the ID.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while verifying the ID.');
        });
    }
}

function rejectId(userId) {
    const reason = prompt('Please enter the reason for rejection:');
    if (reason === null) return; // User cancelled
    
    if (reason.trim() === '') {
        alert('Rejection reason is required.');
        return;
    }
    
    if (confirm('Are you sure you want to reject this user\'s ID verification?')) {
        const url = '{{ route("admin.users.id-reject", ":id") }}'.replace(':id', userId);
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('rejection_reason', reason);
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeIdModal();
                // Remove the row from the table
                const row = document.getElementById('user-row-' + userId);
                if (row) {
                    row.remove();
                }
                // Check if table is empty
                const tbody = document.querySelector('tbody');
                if (tbody.children.length === 0) {
                    location.reload();
                }
            } else {
                alert(data.message || 'An error occurred while rejecting the ID.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while rejecting the ID.');
        });
    }
}
</script>
@endsection
