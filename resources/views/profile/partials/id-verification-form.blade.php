@php
    $user = auth()->user();
    $userValidation = $user ? $user->userValidation : null;
    
    // Check ID verification status
    $hasIdVerification = $userValidation && $userValidation->status !== null;
    $isIdVerified = $userValidation && $userValidation->status === 'approved';
    $isIdPending = $userValidation && $userValidation->status === 'pending';
    $isIdRejected = $userValidation && $userValidation->status === 'rejected';
@endphp

<!-- ID Verification Status Card -->
<div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-id-card text-red-600"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">ID Verification Status</h3>
                <p class="text-sm text-gray-600">Required for system access</p>
            </div>
        </div>
        @if($isIdVerified)
            <div class="flex items-center space-x-2 bg-green-100 text-green-700 px-3 py-1.5 rounded-full">
                <i class="fas fa-check-circle text-sm"></i>
                <span class="text-sm font-medium">Verified</span>
            </div>
        @elseif($isIdPending)
            <div class="flex items-center space-x-2 bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-full">
                <i class="fas fa-clock text-sm"></i>
                <span class="text-sm font-medium">Pending</span>
            </div>
        @elseif($isIdRejected)
            <div class="flex items-center space-x-2 bg-red-100 text-red-700 px-3 py-1.5 rounded-full">
                <i class="fas fa-times-circle text-sm"></i>
                <span class="text-sm font-medium">Rejected</span>
            </div>
        @else
            <div class="flex items-center space-x-2 bg-red-100 text-red-700 px-3 py-1.5 rounded-full">
                <i class="fas fa-exclamation-triangle text-sm"></i>
                <span class="text-sm font-medium">Required</span>
            </div>
        @endif
    </div>
    
    @if($isIdVerified)
        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-green-800">Verification Status</h4>
                        <p class="text-sm text-green-600 mt-1">Your ID is verified and approved. No further action needed.</p>
                    </div>
                </div>
                <button type="button" onclick="saveVerificationStatus(this)" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md" 
                        id="save-verification-status">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Updated Status
                </button>
            </div>
        </div>
    @elseif($isIdPending)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-clock text-yellow-500 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-yellow-800">Verification Pending</h4>
                    <p class="text-sm text-yellow-600 mt-1">Your ID is currently under review. Please wait for admin approval.</p>
                </div>
            </div>
        </div>
    @elseif($isIdRejected)
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-red-500 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-red-800">Verification Rejected</h4>
                    <p class="text-sm text-red-600 mt-1">
                        @if($userValidation && $userValidation->rejection_reason)
                            Reason: {{ $userValidation->rejection_reason }}
                        @else
                            Your ID verification was rejected. Please upload a valid ID for verification.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-red-800">ID Verification Required</h4>
                    <p class="text-sm text-red-600 mt-1">You must upload a valid ID for verification to access system features.</p>
                </div>
            </div>
        </div>
    @endif
</div>

@if(!$isIdVerified)
    <!-- ID Verification Form -->
    <div class="mt-6 bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="fas fa-upload text-red-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Upload ID for Verification</h3>
                    <p class="text-sm text-gray-600">Submit a valid government ID for account verification</p>
                </div>
            </div>
        </div>
        
        <!-- Success Message -->
        <div id="id-success-message" class="hidden mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-green-800">ID Uploaded Successfully!</h4>
                    <p class="text-sm text-green-600 mt-1">Your ID is now pending admin review.</p>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div id="id-error-message" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-red-800">Upload Failed</h4>
                    <p class="text-sm text-red-600 mt-1" id="id-error-details">Please check form and try again.</p>
                </div>
            </div>
        </div>
        
        <form method="post" action="{{ route('validation.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ID Type -->
                <div class="space-y-2">
                    <x-input-label for="id_type" :value="__('ID Type')" class="text-sm font-medium text-gray-700" />
                    <select id="id_type" name="id_type" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
                        <option value="">Select ID Type</option>
                        <option value="national_id">National ID</option>
                        <option value="government_id">Government ID</option>
                        <option value="umid_id">UMID ID</option>
                        <option value="passport">Passport</option>
                        <option value="drivers_license">Driver's License</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('id_type')" />
                </div>
                
                <!-- ID Number -->
                <div class="space-y-2">
                    <x-input-label for="id_number" :value="__('ID Number')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="id_number" name="id_number" type="text" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                        placeholder="Enter your ID number" required />
                    <x-input-error class="mt-2" :messages="$errors->get('id_number')" />
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ID Front Image -->
                <div class="space-y-2">
                    <x-input-label for="id_front_image" :value="__('Front of ID')" class="text-sm font-medium text-gray-700" />
                    <input type="file" id="id_front_image" name="id_front_image" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" 
                        accept="image/*" required />
                    <p class="text-xs text-gray-500 mt-1">Upload front side of your ID (JPG, PNG, max 5MB)</p>
                    <x-input-error class="mt-2" :messages="$errors->get('id_front_image')" />
                </div>
                
                <!-- ID Back Image -->
                <div class="space-y-2">
                    <x-input-label for="id_back_image" :value="__('Back of ID')" class="text-sm font-medium text-gray-700" />
                    <input type="file" id="id_back_image" name="id_back_image" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" 
                        accept="image/*" />
                    <p class="text-xs text-gray-500 mt-1">Upload back side of your ID (JPG, PNG, max 5MB) - Optional</p>
                    <x-input-error class="mt-2" :messages="$errors->get('id_back_image')" />
                </div>
            </div>
            
            <div class="mt-8">
                <button type="button" onclick="submitIdVerificationForm(this)" 
                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-sm hover:shadow-md" 
                    id="id-verification-submit">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3" />
                    </svg>
                    Submit ID for Verification
                </button>
            </div>
        </form>
    </div>
@endif

<script>
function submitIdVerificationForm(button) {
    console.log('Submitting ID verification form...');
    
    // Show loading state
    const submitBtn = button;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Submitting...';
    submitBtn.disabled = true;
    
    // Create form data manually
    const form = document.querySelector('form[action*="validation.store"]');
    const formData = new FormData(form);
    
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
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Hide error message if shown
            document.getElementById('id-error-message').classList.add('hidden');
            
            // Show success message
            const successMsg = document.getElementById('id-success-message');
            successMsg.classList.remove('hidden');
            
            // Show success state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Submitted for Review!';
            submitBtn.classList.remove('from-red-600', 'to-orange-600');
            submitBtn.classList.add('from-green-600', 'to-green-700');
            
            // IMMEDIATELY update status to "Pending" - like Facebook
            updateIdVerificationStatusToPending();
            
            // Show pending notification
            showNotification('ID submitted successfully! Your ID is now pending admin verification.', 'success');
            
            // Log debug info
            console.log('ID verification submitted successfully:', data.message);
            
            // Reload page after short delay to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Hide success message if shown
            document.getElementById('id-success-message').classList.add('hidden');
            
            // Show error message
            const errorMsg = document.getElementById('id-error-message');
            const errorDetails = document.getElementById('id-error-details');
            errorDetails.textContent = data.message || 'Unknown error occurred. Please check form and try again.';
            errorMsg.classList.remove('hidden');
            
            // Show error state
            submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Error';
            submitBtn.classList.remove('from-red-600', 'to-orange-600');
            submitBtn.classList.add('from-red-600', 'to-red-700');
            
            // Reset button after delay
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('from-red-600', 'to-red-700');
                submitBtn.classList.add('from-red-600', 'to-orange-600');
                document.getElementById('id-error-message').classList.add('hidden');
            }, 5000);
        }
    })
    .catch(error => {
        console.error('Submission error:', error);
        
        // Show error message
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Error';
        submitBtn.classList.remove('from-red-600', 'to-orange-600');
        submitBtn.classList.add('from-red-600', 'to-red-700');
        
        alert('Network error occurred while uploading ID. Please check your connection and try again.');
        
        // Reset button after delay
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('from-red-600', 'to-red-700');
            submitBtn.classList.add('from-red-600', 'to-orange-600');
        }, 3000);
    });
}

// Save Verification Status (for admin approval workflow)
function saveVerificationStatus(button) {
    console.log('Saving verification status...');
    
    // Show loading state
    const submitBtn = button;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Saving...';
    submitBtn.disabled = true;
    
    // Show success notification
    showNotification('Verification status updated successfully!', 'success');
    
    // Show success state
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Saved!';
    submitBtn.classList.remove('bg-green-600');
    submitBtn.classList.add('bg-blue-600');
    
    // Reset button after delay
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-blue-600');
        submitBtn.classList.add('bg-green-600');
    }, 2000);
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

// Update ID verification status to pending immediately
function updateIdVerificationStatusToPending() {
    // Find the status badge
    const statusBadge = document.querySelector('.bg-orange-100');
    if (statusBadge) {
        statusBadge.classList.remove('bg-orange-100', 'text-orange-700');
        statusBadge.classList.add('bg-yellow-100', 'text-yellow-700');
        const badgeText = statusBadge.querySelector('span');
        if (badgeText) {
            badgeText.textContent = 'Pending';
        }
    }
    
    // Update the status text
    const statusText = document.querySelector('p.text-gray-600');
    if (statusText) {
        statusText.innerHTML = '⏳ Your ID is pending admin verification';
        statusText.classList.remove('text-gray-600');
        statusText.classList.add('text-yellow-600');
    }
    
    // Hide the form since it's submitted
    const form = document.querySelector('form[action*="validation.store"]');
    if (form) {
        form.style.display = 'none';
    }
    
    // Show pending message
    const pendingMessage = document.createElement('div');
    pendingMessage.className = 'mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg';
    pendingMessage.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h4 class="text-sm font-medium text-yellow-800">ID Verification Pending</h4>
                <p class="text-sm text-yellow-700 mt-1">Your ID has been submitted and is currently under review by our admin team. You will be notified once verification is complete.</p>
            </div>
        </div>
    `;
    
    // Insert pending message after the form
    if (form && form.parentNode) {
        form.parentNode.insertBefore(pendingMessage, form.nextSibling);
    }
    
    console.log('ID verification status updated to Pending');
}
</script>
