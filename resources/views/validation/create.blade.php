<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ID Verification
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Submit Your ID for Verification
                        </h3>
                        <p class="text-sm text-gray-600">
                            To ensure the safety and security of our community, please submit a valid government-issued ID for verification.
                            Your information will be kept confidential and will only be used for verification purposes.
                        </p>
                    </div>

                    <form action="{{ route('validation.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- ID Type -->
                            <div>
                                <label for="id_type" class="block text-sm font-medium text-gray-700">
                                    ID Type <span class="text-red-500">*</span>
                                </label>
                                <select name="id_type" id="id_type" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select ID Type</option>
                                    <option value="national_id">National ID</option>
                                    <option value="government_id">Government ID</option>
                                    <option value="umid_id">UMID ID</option>
                                    <option value="passport">Passport</option>
                                    <option value="drivers_license">Driver's License</option>
                                </select>
                                @error('id_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ID Number -->
                            <div>
                                <label for="id_number" class="block text-sm font-medium text-gray-700">
                                    ID Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="id_number" id="id_number" required
                                    value="{{ old('id_number') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Enter your ID number">
                                @error('id_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Front Image -->
                            <div>
                                <label for="id_front_image" class="block text-sm font-medium text-gray-700">
                                    Front of ID <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="id_front_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                                <input id="id_front_image" name="id_front_image" type="file" class="sr-only" accept="image/*" required>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, up to 5MB</p>
                                    </div>
                                </div>
                                @error('id_front_image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Back Image -->
                            <div>
                                <label for="id_back_image" class="block text-sm font-medium text-gray-700">
                                    Back of ID (Optional)
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="id_back_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                                <input id="id_back_image" name="id_back_image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, up to 5MB</p>
                                    </div>
                                </div>
                                @error('id_back_image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('profile.show', auth()->user()->id) }}" 
                                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400 transition-colors mr-3">
                                    Cancel
                                </a>
                                <button type="submit" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors">
                                    Submit for Verification
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle file input changes to show file names
    const frontImageInput = document.getElementById('id_front_image');
    const backImageInput = document.getElementById('id_back_image');
    
    if (frontImageInput) {
        frontImageInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file chosen';
            const label = frontImageInput.parentElement.querySelector('span');
            if (label) {
                label.textContent = fileName || 'Upload a file';
            }
        });
    }
    
    if (backImageInput) {
        backImageInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file chosen';
            const label = backImageInput.parentElement.querySelector('span');
            if (label) {
                label.textContent = fileName || 'Upload a file';
            }
        });
    }
    
    // Handle drag and drop
    const dropZones = document.querySelectorAll('.border-dashed');
    
    dropZones.forEach(zone => {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            zone.classList.add('border-indigo-500', 'bg-indigo-50');
        });
        
        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            zone.classList.remove('border-indigo-500', 'bg-indigo-50');
        });
        
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            zone.classList.remove('border-indigo-500', 'bg-indigo-50');
            
            const files = e.dataTransfer.files;
            const fileInput = zone.querySelector('input[type="file"]');
            
            if (files.length > 0 && fileInput) {
                fileInput.files = files;
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        });
    });
});
</script>
