<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ID Verification Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <!-- User Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Name</p>
                                <p class="text-sm text-gray-900">{{ $validation->user->fullName() }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-sm text-gray-900">{{ $validation->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- ID Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">ID Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">ID Type</p>
                                <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $validation->id_type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">ID Number</p>
                                <p class="text-sm text-gray-900">{{ $validation->id_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <div>{!! $validation->status_badge !!}</div>
                            </div>
                        </div>
                    </div>

                    <!-- ID Images -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">ID Images</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($validation->id_front_image)
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-2">Front of ID</p>
                                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $validation->id_front_image) }}" 
                                             alt="Front of ID" 
                                             class="w-full h-auto max-h-96 object-cover">
                                    </div>
                                </div>
                            @endif

                            @if($validation->id_back_image)
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-2">Back of ID</p>
                                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $validation->id_back_image) }}" 
                                             alt="Back of ID" 
                                             class="w-full h-auto max-h-96 object-cover">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Rejection Reason -->
                    @if($validation->status === 'rejected' && $validation->rejection_reason)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Rejection Reason</h3>
                            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                                <p class="text-sm text-red-800">{{ $validation->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Verification Date -->
                    @if($validation->verified_at)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Verification Date</h3>
                            <p class="text-sm text-gray-900">{{ $validation->verified_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    @endif

                    <!-- Admin Actions -->
                    @if(auth()->check() && auth()->user()->is_admin)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Actions</h3>
                            <div class="flex space-x-4">
                                @if($validation->status === 'pending')
                                    <form action="{{ route('admin.validations.approve', $validation) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors"
                                                onclick="return confirm('Are you sure you want to approve this ID verification?')">
                                            Approve
                                        </button>
                                    </form>

                                    <button onclick="showRejectionForm()" 
                                            class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                                        Reject
                                    </button>
                                @endif
                            </div>

                            <!-- Rejection Form (Hidden by default) -->
                            <div id="rejectionForm" class="hidden mt-4">
                                <form action="{{ route('admin.validations.reject', $validation) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            Rejection Reason <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                  placeholder="Please provide a reason for rejection..."></textarea>
                                    </div>
                                    <div class="flex space-x-4">
                                        <button type="submit" 
                                                class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                                            Submit Rejection
                                        </button>
                                        <button type="button" onclick="hideRejectionForm()" 
                                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400 transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Back Button -->
                    <div class="flex justify-end">
                        <a href="{{ auth()->user()->is_admin ? route('admin.validations.index') : route('profile.show', $validation->user->id) }}" 
                           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400 transition-colors">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRejectionForm() {
            document.getElementById('rejectionForm').classList.remove('hidden');
        }

        function hideRejectionForm() {
            document.getElementById('rejectionForm').classList.add('hidden');
        }
    </script>
</x-app-layout>
