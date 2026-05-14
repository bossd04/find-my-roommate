<div x-data="profileFormUpdate()" class="space-y-6">

<script>
    function profileFormUpdate() {
        return {
            idType: '{{ old('id_type', '') }}',
            updateIdType(value) {
                this.idType = value;
            },
            completionPercentage: '{{ $completionPercentage }}',
            notifications: [],
            showNotification(message, type = 'success') {
                const notificationId = Date.now();
                this.notifications.push({ id: notificationId, message, type });
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== notificationId);
                }, 3000);
            },
             async submitForm(formElement, endpoint, section) {
                const form = (typeof formElement === 'string') ? document.getElementById(formElement) : formElement;
                if (!form) {
                    console.error('❌ Form Not Found:', formElement);
                    this.showNotification('Error: Form not found (Internal Error)', 'error');
                    return;
                }

                const formData = new FormData(form);
                
                const submitBtn = form.querySelector('button[type=submit]');
                const originalText = submitBtn.innerHTML;
                
                // CRITICAL: Robust field population
                // If FormData is empty or missing fields, manually add them from Alpine state or form elements
                if (section === 'profile_photo') {
                    // Manually ensure avatar file is present
                    const avatarInput = form.querySelector('[name="avatar"]');
                    if (avatarInput && avatarInput.files.length > 0) {
                        formData.set('avatar', avatarInput.files[0]);
                        console.log('✅ Avatar file set:', avatarInput.files[0].name);
                    } else {
                        console.error('❌ No avatar file found in form');
                        this.showNotification('Please select a profile photo to upload', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        return;
                    }
                } else if (section === 'id_verification') {
                    if (!formData.has('id_type') && this.idType) formData.append('id_type', this.idType);
                    // Manually ensure files are present with correct field names
                    const idCardFront = form.querySelector('[name="id_card_front"]');
                    const idCardBack = form.querySelector('[name="id_card_back"]');
                    if (idCardFront && idCardFront.files.length > 0) formData.set('id_card_front', idCardFront.files[0]);
                    if (idCardBack && idCardBack.files.length > 0) formData.set('id_card_back', idCardBack.files[0]);
                } else if (section === 'lifestyle') {
                    // Debug: Log form data for lifestyle section
                    console.log('🔍 Lifestyle Form Debug:');
                    console.log('Sleep Pattern:', formData.get('sleep_pattern'));
                    console.log('Study Habit:', formData.get('study_habit'));
                    console.log('Noise Tolerance:', formData.get('noise_tolerance'));
                    console.log('Cleanliness Level:', formData.get('cleanliness_level'));
                    
                    // Ensure all lifestyle fields are present
                    const sleepPattern = form.querySelector('[name="sleep_pattern"]');
                    const studyHabit = form.querySelector('[name="study_habit"]');
                    const noiseTolerance = form.querySelector('[name="noise_tolerance"]');
                    const cleanlinessLevel = form.querySelector('[name="cleanliness_level"]');
                    
                    if (sleepPattern) formData.set('sleep_pattern', sleepPattern.value);
                    if (studyHabit) formData.set('study_habit', studyHabit.value);
                    if (noiseTolerance) formData.set('noise_tolerance', noiseTolerance.value);
                    if (cleanlinessLevel) formData.set('cleanliness_level', cleanlinessLevel.value);
                    
                    console.log('✅ Lifestyle fields ensured:', {
                        sleep_pattern: formData.get('sleep_pattern'),
                        study_habit: formData.get('study_habit'),
                        noise_tolerance: formData.get('noise_tolerance'),
                        cleanliness_level: formData.get('cleanliness_level')
                    });
                }

                try {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                    
                    console.log(`📤 Submitting to ${endpoint}...`);
                    console.log('Payload keys:', Array.from(formData.keys()));

                    const response = await fetch(endpoint, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    const text = await response.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('❌ Server returned non-JSON:', text);
                        throw new Error('Server returned an invalid response');
                    }
                    
                    if (data.success) {
                        this.showNotification('Update successful - ' + data.message, 'success');
                        const errorElements = form.querySelectorAll('.text-red-500');
                        errorElements.forEach(el => el.remove());
                        
                        // Check if should redirect to dashboard
                        if (data.redirect_to_dashboard) {
                            setTimeout(() => {
                                window.location.href = '/dashboard';
                            }, 2000);
                        }
                        
                        // Special handling for profile photo upload - show success message and update avatar
                        if (section === 'profile_photo') {
                            // Update profile picture in the header if avatar_url is returned
                            if (data.avatar_url) {
                                const avatarElements = document.querySelectorAll('.user-avatar, .profile-avatar');
                                avatarElements.forEach(el => {
                                    if (el.tagName === 'IMG') {
                                        el.src = data.avatar_url;
                                    }
                                });
                            }
                            // Show success notification specifically for profile picture
                            this.showNotification('Profile picture successfully updated!', 'success');
                        }
                        
                        // Special handling for location information - update completion badge
                        if (section === 'location_information') {
                            const locationSection = document.querySelector('[data-section="location_information"]');
                            if (locationSection) {
                                const badge = locationSection.querySelector('.bg-orange-100, .bg-green-100');
                                if (badge) {
                                    badge.className = 'ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                                    badge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Complete';
                                }
                            }
                        }
                        
                        // Special handling for ID verification - show pending status without reload
                        if (section === 'id_verification' && data.status === 'pending') {
                            // Hide the form
                            form.style.display = 'none';
                            
                            // Show pending status message
                            const pendingDiv = document.createElement('div');
                            pendingDiv.className = 'p-4 bg-yellow-50 border border-yellow-200 rounded-md mb-4';
                            pendingDiv.innerHTML = `
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800">ID Verification Pending</h4>
                                        <p class="text-sm text-yellow-700 mt-1">Your ID has been submitted successfully and is now pending admin review. You will be notified once the verification is complete.</p>
                                    </div>
                                </div>
                            `;
                            
                            // Insert pending message after the form
                            form.parentNode.insertBefore(pendingDiv, form.nextSibling);
                            
                            // Show success message
                            this.showNotification('ID verification submitted successfully! Status is now pending.', 'success');
                        }
                        
                        // Update completion status if needed
                        if (data.completion_percentage !== undefined) {
                            this.completionPercentage = data.completion_percentage;
                        }

                        // Real-time Avatar update
                        if (section === 'profile_photo' && data.avatar_url) {
                            const avatarImg = document.getElementById('profile-avatar-display-img');
                            const avatarPlaceholder = document.getElementById('profile-avatar-display-placeholder');
                            
                            if (avatarImg) {
                                avatarImg.src = data.avatar_url;
                                avatarImg.style.display = 'block';
                                if (avatarPlaceholder) avatarPlaceholder.style.display = 'none';
                            }
                            
                            // Update sidebar/nav instances if they have specific classes
                            document.querySelectorAll('.global-avatar-update').forEach(img => {
                                img.src = data.avatar_url;
                            });

                            this.showNotification('Profile photo updated successfully!', 'success');
                        }
                        
                        // Reload page after a short delay (only for non-ID verification forms)
                        if (section !== 'id_verification') {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    } else {
                        this.showNotification(data.message || 'Error saving information', 'error');
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    this.showNotification('Network error. Please try again.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }
        };
    }
    
    // Preview profile photo when file is selected
    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const avatarDisplay = document.getElementById('profile-avatar-display');
                if (avatarDisplay) {
                    // If it's a div, replace it with an img
                    if (avatarDisplay.tagName === 'DIV') {
                        const parent = avatarDisplay.parentNode;
                        const newImg = document.createElement('img');
                        newImg.id = 'profile-avatar-display';
                        newImg.src = e.target.result;
                        newImg.alt = 'Profile Photo';
                        newImg.className = 'w-full h-full object-cover';
                        
                        // Replace the div with img
                        parent.replaceChild(newImg, avatarDisplay);
                    } else if (avatarDisplay.tagName === 'IMG') {
                        // If it's already an img, just update the src
                        avatarDisplay.src = e.target.result;
                    }
                }
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
    
    <!-- Notifications -->
    <div x-show="notifications.length > 0" class="fixed top-4 right-4 z-50 space-y-2">
        <template x-for="notification in notifications" :key="notification.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-full"
                 class="p-4 rounded-lg shadow-lg"
                 :class="notification.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
                <div class="flex items-center">
                    <svg x-show="notification.type === 'success'" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <svg x-show="notification.type === 'error'" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span x-text="notification.message"></span>
                </div>
            </div>
        </template>
    </div>

    <!-- ID Verification Section -->
    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-blue-500">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            ID Verification
            @if($user->isVerified())
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Verified
                </span>
            @else
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-exclamation-circle mr-1"></i> Not Verified
                </span>
            @endif
        </h2>
        
        <div class="mb-4 p-4 bg-blue-50 rounded-md">
            <p class="text-sm text-blue-800">
                <strong>Why ID verification is required:</strong> To ensure the safety and security of all users, we require ID verification to confirm your identity. This helps prevent fake profiles and creates a trustworthy community.
            </p>
        </div>
        
        @if($user->isVerified())
            <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-green-800 font-medium">Your ID has been verified and approved.</span>
                </div>
            </div>
        @else
            <form id="id-verification-form-enhanced" @submit.prevent="submitForm($el, '/profile/update-id-verification', 'id_verification')" method="POST" class="space-y-4" enctype="multipart/form-data">
                @csrf
                
                <div>
                    <label for="id_type" class="block text-sm font-medium text-gray-700">ID Type *</label>
                    <select id="id_type" name="id_type" required
                            x-model="idType"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select ID Type</option>
                        <option value="passport">Passport</option>
                        <option value="driver_license">Driver's License</option>
                        <option value="national_id">National ID</option>
                        <option value="student_id">Student ID</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div>
                    <label for="id_number" class="block text-sm font-medium text-gray-700">ID Number *</label>
                    <input type="text" id="id_number" name="id_number" required
                           placeholder="Enter your ID number"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="id_card_front" class="block text-sm font-medium text-gray-700">Front of ID Document *</label>
                        <input type="file" id="id_card_front" name="id_card_front" required
                               accept="image/*,.pdf"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Upload front side of your ID (PDF, JPG, PNG - Max 5MB)</p>
                    </div>
                    
                    <div>
                        <label for="id_card_back" class="block text-sm font-medium text-gray-700">Back of ID Document</label>
                        <input type="file" id="id_card_back" name="id_card_back"
                               accept="image/*,.pdf"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Upload back side of your ID (PDF, JPG, PNG - Max 5MB) - Optional</p>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Submit ID for Verification
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Profile Photo Section -->
    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-indigo-500">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profile Photo
            @if($user->avatar)
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Uploaded
                </span>
            @else
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-1"></i> Not Uploaded
                </span>
            @endif
        </h2>
        
        <form id="profile-photo-form-enhanced" @submit.prevent="submitForm($el, '/profile/update-photo', 'profile_photo')" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            
            <div class="flex items-center space-x-6">
                <!-- Current Avatar Display -->
                 <div class="flex-shrink-0">
                    <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl bg-gray-100 flex items-center justify-center" style="min-width: 128px; min-height: 128px; width: 128px; height: 128px;">
                        <img src="{{ $user->avatar ? $user->avatar_url : '' }}" 
                             alt="{{ $user->full_name }}" 
                             class="w-full h-full object-cover" 
                             id="profile-avatar-display-img"
                             style="{{ $user->avatar ? 'display: block;' : 'display: none;' }}">
                        
                        <div id="profile-avatar-display-placeholder" 
                             class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-indigo-600"
                             style="{{ $user->avatar ? 'display: none;' : 'display: flex;' }}">
                            <span class="text-white text-3xl font-bold">
                                {{ substr($user->first_name ?? '?', 0, 1) }}{{ substr($user->last_name ?? '?', 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Upload Form -->
                <div class="flex-1">
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700">Upload Profile Photo *</label>
                        <input type="file" id="avatar" name="avatar" 
                               accept="image/*" onchange="previewProfilePhoto(this)"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Upload JPG, PNG or GIF. Maximum file size: 2MB.</p>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.887 3.877A3 3 0 004 4v4a3 3 0 004 4h4a3 3 0 004-4V7a3 3 0 01-4-4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.5 11.5L19 16l-4.5-4.5" />
                            </svg>
                            Upload Profile Photo
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Basic Information Section -->
    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-green-500">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Basic Information
            @if(!empty($user->first_name) && !empty($user->last_name) && !empty($user->email) && !empty($user->phone) && !empty($user->gender) && !empty($user->date_of_birth))
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Complete
                </span>
            @else
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    <i class="fas fa-exclamation-circle mr-1"></i> Incomplete
                </span>
            @endif
        </h2>
        
        <form id="basic-information-form-enhanced" @submit.prevent="submitForm($el, '/profile/update-basic-information', 'basic_information')" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ $user->first_name ?? '' }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ $user->last_name ?? '' }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}" required
                       placeholder="Enter your phone number"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                    <select id="gender" name="gender" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Gender</option>
                        <option value="male" {{ ($user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ ($user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ ($user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                        <option value="prefer_not_to_say" {{ ($user->gender ?? '') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                </div>
                
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" 
                           value="{{ optional($user->date_of_birth)->format('Y-m-d') ?? '' }}" 
                           max="{{ now()->subYears(18)->format('Y-m-d') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="text-xs text-gray-500 mt-1">Age will be calculated automatically</p>
                </div>
            </div>
            
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea id="bio" name="bio" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $user->bio ?? '' }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Tell us about yourself (max 1000 characters)</p>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Basic Information
                </button>
            </div>
        </form>
    </div>

    <!-- Location Information Section -->
    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-purple-500" data-section="location_information">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Location Information
            @php
                $locationComplete = !empty($user->roommateProfile->apartment_location);
            @endphp
            @if($locationComplete)
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Complete
                </span>
            @else
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    <i class="fas fa-exclamation-circle mr-1"></i> Incomplete
                </span>
            @endif
        </h2>
        
        <form id="location-information-form-enhanced" @submit.prevent="submitForm($el, '/profile/update-location-information', 'location_information')" method="POST" class="space-y-4">
            @csrf
            
            @php
                $savedLocation = $user->roommateProfile ? $user->roommateProfile->apartment_location : '';
                $savedMoveInDate = $user->roommateProfile ? $user->roommateProfile->move_in_date : '';
            @endphp
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Current Location *</label>
                <input type="text" id="location" name="location" value="{{ $savedLocation }}" required
                       placeholder="Enter your city and province (e.g., Dagupan City, Pangasinan)"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="mt-1 text-sm text-gray-500">This helps us find roommates near you</p>
            </div>
            
            <div>
                <label for="move_in_date" class="block text-sm font-medium text-gray-700">Preferred Move-in Date (Optional)</label>
                <input type="date" id="move_in_date" name="move_in_date" 
                       value="{{ $savedMoveInDate }}" 
                       min="{{ now()->format('Y-m-d') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="mt-1 text-sm text-gray-500">When are you planning to move?</p>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    💾 Save Location Information
                </button>
            </div>
        </form>
    </div>

    <!-- Education Information Section -->
    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-yellow-500">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Education Information
            @if(!empty($user->university) && !empty($user->course) && !empty($user->year_level))
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Complete
                </span>
            @else
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    <i class="fas fa-exclamation-circle mr-1"></i> Incomplete
                </span>
            @endif
        </h2>
        
        <form id="education-form-enhanced" @submit.prevent="submitForm($el, '/profile/update-education', 'education')" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="university" class="block text-sm font-medium text-gray-700">University *</label>
                <select id="university" name="university" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select University</option>
                    <option value="Dagupan Colleges" {{ ($user->university ?? '') == 'Dagupan Colleges' ? 'selected' : '' }}>Dagupan Colleges</option>
                    <option value="Universidad de Dagupan" {{ ($user->university ?? '') == 'Universidad de Dagupan' ? 'selected' : '' }}>Universidad de Dagupan</option>
                    <option value="Lyceum Northwestern University" {{ ($user->university ?? '') == 'Lyceum Northwestern University' ? 'selected' : '' }}>Lyceum Northwestern University</option>
                    <option value="Saint Columban College" {{ ($user->university ?? '') == 'Saint Columban College' ? 'selected' : '' }}>Saint Columban College</option>
                    <option value="University of Luzon" {{ ($user->university ?? '') == 'University of Luzon' ? 'selected' : '' }}>University of Luzon</option>
                    <option value="WCC Aeronautical and Technological College" {{ ($user->university ?? '') == 'WCC Aeronautical and Technological College' ? 'selected' : '' }}>WCC Aeronautical and Technological College</option>
                    <option value="Other" {{ ($user->university ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="course" class="block text-sm font-medium text-gray-700">Course *</label>
                    <select id="course" name="course" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm filter-dropdown">
                        <option value="">Select Course</option>
                        
                        <!-- College of Arts and Sciences -->
                        <optgroup label="College of Arts and Sciences">
                            <option value="Bachelor of Arts in Communication" {{ ($user->course ?? '') == 'Bachelor of Arts in Communication' ? 'selected' : '' }}>Bachelor of Arts in Communication</option>
                            <option value="Bachelor of Arts in Political Science" {{ ($user->course ?? '') == 'Bachelor of Arts in Political Science' ? 'selected' : '' }}>Bachelor of Arts in Political Science</option>
                            <option value="Bachelor of Arts in Psychology" {{ ($user->course ?? '') == 'Bachelor of Arts in Psychology' ? 'selected' : '' }}>Bachelor of Arts in Psychology</option>
                            <option value="Bachelor of Arts in English Language" {{ ($user->course ?? '') == 'Bachelor of Arts in English Language' ? 'selected' : '' }}>Bachelor of Arts in English Language</option>
                            <option value="Bachelor of Arts in History" {{ ($user->course ?? '') == 'Bachelor of Arts in History' ? 'selected' : '' }}>Bachelor of Arts in History</option>
                        </optgroup>
                        
                        <!-- College of Business and Accountancy -->
                        <optgroup label="College of Business and Accountancy">
                            <option value="Bachelor of Science in Accountancy" {{ ($user->course ?? '') == 'Bachelor of Science in Accountancy' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                            <option value="Bachelor of Science in Accounting Information System" {{ ($user->course ?? '') == 'Bachelor of Science in Accounting Information System' ? 'selected' : '' }}>Bachelor of Science in Accounting Information System</option>
                            <option value="Bachelor of Science in Business Administration" {{ ($user->course ?? '') == 'Bachelor of Science in Business Administration' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="Bachelor of Science in Entrepreneurship" {{ ($user->course ?? '') == 'Bachelor of Science in Entrepreneurship' ? 'selected' : '' }}>Bachelor of Science in Entrepreneurship</option>
                            <option value="Bachelor of Science in Marketing Management" {{ ($user->course ?? '') == 'Bachelor of Science in Marketing Management' ? 'selected' : '' }}>Bachelor of Science in Marketing Management</option>
                            <option value="Bachelor of Science in Human Resource Management" {{ ($user->course ?? '') == 'Bachelor of Science in Human Resource Management' ? 'selected' : '' }}>Bachelor of Science in Human Resource Management</option>
                        </optgroup>
                        
                        <!-- College of Computer Studies -->
                        <optgroup label="College of Computer Studies">
                            <option value="Bachelor of Science in Computer Science" {{ ($user->course ?? '') == 'Bachelor of Science in Computer Science' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="Bachelor of Science in Information Technology" {{ ($user->course ?? '') == 'Bachelor of Science in Information Technology' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="Bachelor of Science in Information Systems" {{ ($user->course ?? '') == 'Bachelor of Science in Information Systems' ? 'selected' : '' }}>Bachelor of Science in Information Systems</option>
                            <option value="Bachelor of Science in Computer Engineering" {{ ($user->course ?? '') == 'Bachelor of Science in Computer Engineering' ? 'selected' : '' }}>Bachelor of Science in Computer Engineering</option>
                            <option value="Associate in Computer Technology" {{ ($user->course ?? '') == 'Associate in Computer Technology' ? 'selected' : '' }}>Associate in Computer Technology</option>
                        </optgroup>
                        
                        <!-- College of Criminology -->
                        <optgroup label="College of Criminology">
                            <option value="Bachelor of Science in Criminology" {{ ($user->course ?? '') == 'Bachelor of Science in Criminology' ? 'selected' : '' }}>Bachelor of Science in Criminology</option>
                            <option value="Bachelor of Science in Criminal Justice" {{ ($user->course ?? '') == 'Bachelor of Science in Criminal Justice' ? 'selected' : '' }}>Bachelor of Science in Criminal Justice</option>
                            <option value="Certificate in Criminal Justice" {{ ($user->course ?? '') == 'Certificate in Criminal Justice' ? 'selected' : '' }}>Certificate in Criminal Justice</option>
                        </optgroup>
                        
                        <!-- College of Education -->
                        <optgroup label="College of Education">
                            <option value="Bachelor of Science in Education" {{ ($user->course ?? '') == 'Bachelor of Science in Education' ? 'selected' : '' }}>Bachelor of Science in Education</option>
                            <option value="Bachelor of Elementary Education" {{ ($user->course ?? '') == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                            <option value="Bachelor of Secondary Education" {{ ($user->course ?? '') == 'Bachelor of Secondary Education' ? 'selected' : '' }}>Bachelor of Secondary Education</option>
                            <option value="Bachelor of Technical Teacher Education" {{ ($user->course ?? '') == 'Bachelor of Technical Teacher Education' ? 'selected' : '' }}>Bachelor of Technical Teacher Education</option>
                        </optgroup>
                        
                        <!-- College of Engineering and Architecture -->
                        <optgroup label="College of Engineering and Architecture">
                            <option value="Bachelor of Science in Civil Engineering" {{ ($user->course ?? '') == 'Bachelor of Science in Civil Engineering' ? 'selected' : '' }}>Bachelor of Science in Civil Engineering</option>
                            <option value="Bachelor of Science in Computer Engineering" {{ ($user->course ?? '') == 'Bachelor of Science in Computer Engineering' ? 'selected' : '' }}>Bachelor of Science in Computer Engineering</option>
                            <option value="Bachelor of Science in Electrical Engineering" {{ ($user->course ?? '') == 'Bachelor of Science in Electrical Engineering' ? 'selected' : '' }}>Bachelor of Science in Electrical Engineering</option>
                            <option value="Bachelor of Science in Mechanical Engineering" {{ ($user->course ?? '') == 'Bachelor of Science in Mechanical Engineering' ? 'selected' : '' }}>Bachelor of Science in Mechanical Engineering</option>
                            <option value="Bachelor of Science in Architecture" {{ ($user->course ?? '') == 'Bachelor of Science in Architecture' ? 'selected' : '' }}>Bachelor of Science in Architecture</option>
                        </optgroup>
                        
                        <!-- College of Health Sciences -->
                        <optgroup label="College of Health Sciences">
                            <option value="Bachelor of Science in Nursing" {{ ($user->course ?? '') == 'Bachelor of Science in Nursing' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                            <option value="Bachelor of Science in Medical Technology" {{ ($user->course ?? '') == 'Bachelor of Science in Medical Technology' ? 'selected' : '' }}>Bachelor of Science in Medical Technology</option>
                            <option value="Bachelor of Science in Pharmacy" {{ ($user->course ?? '') == 'Bachelor of Science in Pharmacy' ? 'selected' : '' }}>Bachelor of Science in Pharmacy</option>
                            <option value="Bachelor of Science in Radiologic Technology" {{ ($user->course ?? '') == 'Bachelor of Science in Radiologic Technology' ? 'selected' : '' }}>Bachelor of Science in Radiologic Technology</option>
                            <option value="Bachelor of Science in Physical Therapy" {{ ($user->course ?? '') == 'Bachelor of Science in Physical Therapy' ? 'selected' : '' }}>Bachelor of Science in Physical Therapy</option>
                        </optgroup>
                        
                        <!-- College of Law -->
                        <optgroup label="College of Law">
                            <option value="Juris Doctor" {{ ($user->course ?? '') == 'Juris Doctor' ? 'selected' : '' }}>Juris Doctor</option>
                            <option value="Bachelor of Laws" {{ ($user->course ?? '') == 'Bachelor of Laws' ? 'selected' : '' }}>Bachelor of Laws</option>
                            <option value="Pre-Law Program" {{ ($user->course ?? '') == 'Pre-Law Program' ? 'selected' : '' }}>Pre-Law Program</option>
                        </optgroup>
                        
                        <!-- College of Hospitality Management -->
                        <optgroup label="College of Hospitality Management">
                            <option value="Bachelor of Science in Hospitality Management" {{ ($user->course ?? '') == 'Bachelor of Science in Hospitality Management' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                            <option value="Bachelor of Science in Tourism Management" {{ ($user->course ?? '') == 'Bachelor of Science in Tourism Management' ? 'selected' : '' }}>Bachelor of Science in Tourism Management</option>
                            <option value="Bachelor of Science in Hotel and Restaurant Management" {{ ($user->course ?? '') == 'Bachelor of Science in Hotel and Restaurant Management' ? 'selected' : '' }}>Bachelor of Science in Hotel and Restaurant Management</option>
                        </optgroup>
                        
                        <!-- Graduate School -->
                        <optgroup label="Graduate School">
                            <option value="Master of Arts in Education" {{ ($user->course ?? '') == 'Master of Arts in Education' ? 'selected' : '' }}>Master of Arts in Education</option>
                            <option value="Master of Business Administration" {{ ($user->course ?? '') == 'Master of Business Administration' ? 'selected' : '' }}>Master of Business Administration</option>
                            <option value="Master of Science in Computer Science" {{ ($user->course ?? '') == 'Master of Science in Computer Science' ? 'selected' : '' }}>Master of Science in Computer Science</option>
                            <option value="Doctor of Philosophy" {{ ($user->course ?? '') == 'Doctor of Philosophy' ? 'selected' : '' }}>Doctor of Philosophy</option>
                            <option value="Doctor of Education" {{ ($user->course ?? '') == 'Doctor of Education' ? 'selected' : '' }}>Doctor of Education</option>
                        </optgroup>
                        
                        <!-- Senior High School -->
                        <optgroup label="Senior High School">
                            <option value="STEM Strand" {{ ($user->course ?? '') == 'STEM Strand' ? 'selected' : '' }}>STEM Strand</option>
                            <option value="ABM Strand" {{ ($user->course ?? '') == 'ABM Strand' ? 'selected' : '' }}>ABM Strand</option>
                            <option value="HUMSS Strand" {{ ($user->course ?? '') == 'HUMSS Strand' ? 'selected' : '' }}>HUMSS Strand</option>
                            <option value="GAS Strand" {{ ($user->course ?? '') == 'GAS Strand' ? 'selected' : '' }}>GAS Strand</option>
                            <option value="TVL Strand" {{ ($user->course ?? '') == 'TVL Strand' ? 'selected' : '' }}>TVL Strand</option>
                        </optgroup>
                    </select>
                </div>
                
                <div>
                    <label for="year_level" class="block text-sm font-medium text-gray-700">Year Level *</label>
                    <select id="year_level" name="year_level" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Year Level</option>
                        <option value="1st Year" {{ ($user->year_level ?? '') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ ($user->year_level ?? '') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ ($user->year_level ?? '') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4th Year" {{ ($user->year_level ?? '') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        <option value="5th Year" {{ ($user->year_level ?? '') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                        <option value="Graduate student" {{ ($user->year_level ?? '') == 'Graduate student' ? 'selected' : '' }}>Graduate student</option>
                        <option value="Alumni" {{ ($user->year_level ?? '') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Save Education Information
                </button>
            </div>
        </form>
    </div>

    <!-- Lifestyle Preferences Section -->
    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-indigo-500">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            Lifestyle Preferences
            @if($profile && !empty($profile->cleanliness_level) && !empty($profile->sleep_pattern) && !empty($profile->study_habit) && !empty($profile->noise_tolerance))
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Complete
                </span>
            @else
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    <i class="fas fa-exclamation-circle mr-1"></i> Incomplete
                </span>
            @endif
        </h2>
        
        <form id="lifestyle-form-enhanced" @submit.prevent="submitForm($el, '/profile/update-lifestyle', 'lifestyle')" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cleanliness_level" class="block text-sm font-medium text-gray-700">Cleanliness Level *</label>
                    <select id="cleanliness_level" name="cleanliness_level" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Cleanliness Level</option>
                        <option value="very_messy" {{ ($profile->cleanliness_level ?? '') == 'very_messy' ? 'selected' : '' }}>Very Messy</option>
                        <option value="somewhat_messy" {{ ($profile->cleanliness_level ?? '') == 'somewhat_messy' ? 'selected' : '' }}>Somewhat Messy</option>
                        <option value="average" {{ ($profile->cleanliness_level ?? '') == 'average' ? 'selected' : '' }}>Average</option>
                        <option value="somewhat_clean" {{ ($profile->cleanliness_level ?? '') == 'somewhat_clean' ? 'selected' : '' }}>Somewhat Clean</option>
                        <option value="very_clean" {{ ($profile->cleanliness_level ?? '') == 'very_clean' ? 'selected' : '' }}>Very Clean</option>
                    </select>
                </div>
                
                <div>
                    <label for="sleep_pattern" class="block text-sm font-medium text-gray-700">Sleep Pattern *</label>
                    <select id="sleep_pattern" name="sleep_pattern" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Sleep Pattern</option>
                        <option value="early_bird" {{ ($profile->sleep_pattern ?? '') == 'early_bird' ? 'selected' : '' }}>Early Bird</option>
                        <option value="night_owl" {{ ($profile->sleep_pattern ?? '') == 'night_owl' ? 'selected' : '' }}>Night Owl</option>
                        <option value="flexible" {{ ($profile->sleep_pattern ?? '') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="study_habit" class="block text-sm font-medium text-gray-700">Study Habit *</label>
                    <select id="study_habit" name="study_habit" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Study Habit</option>
                        <option value="intense" {{ ($profile->study_habit ?? '') == 'intense' ? 'selected' : '' }}>Intense</option>
                        <option value="moderate" {{ ($profile->study_habit ?? '') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="social" {{ ($profile->study_habit ?? '') == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="quiet" {{ ($profile->study_habit ?? '') == 'quiet' ? 'selected' : '' }}>Quiet</option>
                    </select>
                </div>
                
                <div>
                    <label for="noise_tolerance" class="block text-sm font-medium text-gray-700">Noise Tolerance *</label>
                    <select id="noise_tolerance" name="noise_tolerance" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Noise Tolerance</option>
                        <option value="quiet" {{ ($profile->noise_tolerance ?? '') == 'quiet' ? 'selected' : '' }}>Quiet</option>
                        <option value="moderate" {{ ($profile->noise_tolerance ?? '') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="loud" {{ ($profile->noise_tolerance ?? '') == 'loud' ? 'selected' : '' }}>Loud</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="budget_min" class="block text-sm font-medium text-gray-700">Minimum Budget (₱) *</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₱</span>
                        </div>
                        <input type="number" id="budget_min" name="budget_min" value="{{ $user->budget_min ?? '' }}" min="0" step="100" required
                               class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label for="budget_max" class="block text-sm font-medium text-gray-700">Maximum Budget (₱) *</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₱</span>
                        </div>
                        <input type="number" id="budget_max" name="budget_max" value="{{ $user->budget_max ?? '' }}" min="0" step="100" required
                               class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hobbies and Interests</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    @php
                        $userHobbies = is_array($user->hobbies) ? $user->hobbies : (is_string($user->hobbies) ? explode(',', $user->hobbies) : []);
                        $availableHobbies = [
                            'Reading' => '📚', 'Gaming' => '🎮', 'Sports' => '⚽', 'Music' => '🎵',
                            'Movies' => '🎬', 'Cooking' => '👨‍🍳', 'Travel' => '✈️', 'Photography' => '📷',
                            'Art' => '🎨', 'Fitness' => '💪', 'Coding' => '💻', 'Nature' => '🌳'
                        ];
                    @endphp
                    @foreach($availableHobbies as $hobby => $icon)
                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-white p-2 rounded transition-colors group">
                            <input type="checkbox" name="hobbies[]" value="{{ $hobby }}" 
                                   {{ in_array($hobby, $userHobbies) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 group-hover:text-indigo-600">{{ $icon }} {{ $hobby }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <div>
                <label for="lifestyle_tags" class="block text-sm font-medium text-gray-700">Additional Lifestyle Tags</label>
                <input type="text" id="lifestyle_tags" name="lifestyle_tags[]" value="{{ is_array($user->lifestyle_tags) ? implode(', ', $user->lifestyle_tags) : ($user->lifestyle_tags ?? '') }}" 
                       placeholder="Enter tags separated by commas (e.g. non-smoker, quiet, pet-friendly)"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Lifestyle Preferences
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Dashboard Button - Show when profile is complete -->
@if($completionPercentage >= 100)
<div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6 text-center">
    <div class="flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 4l4 4" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <h3 class="text-lg font-semibold text-green-800">🎉 Profile Complete!</h3>
    </div>
    <p class="text-green-700 mb-4">Your profile is 100% complete. You now have full access to all roommate finding features!</p>
    
    <div class="flex gap-4 justify-center">
        <button onclick="goToDashboard()" 
                class="inline-flex items-center px-8 py-4 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
            <svg class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7 7M5 10v7a7 7 0 0014 0H3a7 7 0 01-7-7v-4a7 7 0 0114 0h4a7 7 0 017 7v4" />
            </svg>
            🚀 Go to Dashboard
        </button>
        
        <a href="/test-dashboard" 
           class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-lg shadow text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            Dashboard (Direct Link)
        </a>
        
        <button onclick="window.location.reload()" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 14H2m16 0v-5a4 4 0 00-4-4H4a4 4 0 00-4 4v16a4 4 0 004 4h16a4 4 0 004-4z" />
            </svg>
            Refresh
        </button>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-reload page after successful form submission to update completion status
    const originalSubmitForm = profileFormUpdate().submitForm;
    
    // Dashboard redirect function
    window.goToDashboard = function() {
        console.log('🚀 Going to dashboard...');
        
        // Try multiple methods to ensure redirect works
        try {
            // Method 1: Direct window.location change
            window.location.href = '/test-dashboard';
        } catch (e) {
            console.error('Method 1 failed:', e);
            
            // Method 2: Using window.location.replace
            try {
                window.location.replace('/test-dashboard');
            } catch (e2) {
                console.error('Method 2 failed:', e2);
                
                // Method 3: Force hard redirect
                window.location.href = window.location.origin + '/test-dashboard';
            }
        }
    };
    
    // Rest of existing JavaScript...
    
    // Add real-time validation feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-red-300');
                    this.classList.add('border-green-300');
                } else {
                    this.classList.remove('border-green-300');
                    this.classList.add('border-red-300');
                }
            });
        });
    });
});
</script>


<script>
    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('profile-avatar-display-img');
                const placeholder = document.getElementById('profile-avatar-display-placeholder');
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
