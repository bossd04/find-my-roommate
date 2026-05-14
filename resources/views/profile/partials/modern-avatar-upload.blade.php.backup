<!-- Modern Avatar Upload Section -->
<div class="space-y-6">
    <div class="flex flex-col items-center">
        <div class="relative group">
            <!-- Avatar Display/Upload Area -->
            <div class="relative">
                <label for="avatar" class="cursor-pointer block">
                    @if($user->avatar && Storage::exists($user->avatar))
                        <img src="{{ Storage::url($user->avatar) }}?{{ time() }}" 
                             alt="{{ $user->fullName() }}" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-xl transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-4xl font-bold text-white border-4 border-blue-500 shadow-xl transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <!-- Upload Overlay with Better Visibility -->
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-blue-600/90 to-purple-600/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 border-4 border-white">
                        <div class="text-center">
                            <svg class="w-10 h-10 mx-auto mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-white text-sm font-bold">{{ $user->avatar ? 'Change Photo' : 'Upload Photo' }}</p>
                        </div>
                    </div>
                </label>
                
                <!-- Upload Status Indicator -->
                <div id="upload-status" class="absolute -top-2 -right-2 w-10 h-10 rounded-full bg-green-500 flex items-center justify-center opacity-0 transition-opacity duration-300 border-2 border-white">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Hidden File Input -->
        <input type="file" 
               id="avatar" 
               name="avatar" 
               class="hidden" 
               accept="image/jpeg,image/png,image/gif,image/webp"
               onchange="handleAvatarUpload(event)">
        
        <!-- File Information with Better Visibility -->
        <div class="text-center mt-4 space-y-2">
            <div id="file-name" class="text-sm font-bold text-gray-800 bg-gray-100 px-3 py-2 rounded-lg">
                {{ $user->avatar ? basename($user->avatar) : 'No file chosen' }}
            </div>
            <div id="file-size" class="text-sm font-medium text-blue-600">
                {{ $user->avatar ? 'Current avatar' : '' }}
            </div>
            <div id="upload-error" class="text-red-600 text-sm font-bold mt-2 bg-red-50 px-3 py-2 rounded-lg border border-red-200"></div>
            
            <!-- Progress Bar with Better Visibility -->
            <div id="upload-progress" class="hidden w-full max-w-xs mx-auto mt-3">
                <div class="bg-gray-300 rounded-full h-3 overflow-hidden border border-gray-400">
                    <div id="progress-bar" class="bg-gradient-to-r from-green-500 to-blue-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div id="progress-text" class="text-sm font-bold text-gray-700 mt-2">Uploading...</div>
            </div>
            
            <!-- File Requirements with Better Design -->
            <div class="bg-blue-100 border-2 border-blue-300 rounded-lg p-4 mt-4">
                <p class="text-sm font-bold text-blue-900">
                    📸 <strong>Requirements:</strong> JPEG, PNG, GIF, WebP<br>
                    📏 <strong>Max size:</strong> 5GB<br>
                    ⭐ <strong>Recommended:</strong> Square image for best results
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function handleAvatarUpload(event) {
    const file = event.target.files[0];
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const uploadError = document.getElementById('upload-error');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const uploadStatus = document.getElementById('upload-status');
    
    // Reset states
    uploadError.textContent = '';
    uploadProgress.classList.add('hidden');
    uploadStatus.style.opacity = '0';
    
    if (!file) {
        fileName.textContent = 'No file chosen';
        fileSize.textContent = '';
        return;
    }
    
    // Update file info
    fileName.textContent = file.name;
    fileSize.textContent = `Size: ${formatFileSize(file.size)}`;
    
    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        uploadError.textContent = 'Please select a valid image file (JPEG, PNG, GIF, or WebP)';
        event.target.value = '';
        return;
    }
    
    // Validate file size (5GB)
    const maxSize = 5 * 1024 * 1024 * 1024;
    if (file.size > maxSize) {
        uploadError.textContent = `File size exceeds 5GB limit. Your file is ${formatFileSize(file.size)}.`;
        event.target.value = '';
        return;
    }
    
    // Show progress for large files
    if (file.size > 10 * 1024 * 1024) { // Files larger than 10MB
        uploadProgress.classList.remove('hidden');
        simulateUploadProgress();
    }
    
    // Preview image
    const reader = new FileReader();
    reader.onload = function(e) {
        const label = document.querySelector('label[for="avatar"]');
        if (label) {
            label.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Avatar preview" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl">
                <div class="absolute inset-0 rounded-full bg-black bg-opacity-60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="text-white text-sm font-medium">Change Photo</p>
                    </div>
                </div>
            `;
        }
        
        // Show success indicator
        setTimeout(() => {
            uploadStatus.style.opacity = '1';
            uploadProgress.classList.add('hidden');
        }, 1000);
    };
    
    reader.onerror = function() {
        uploadError.textContent = 'Error reading file. Please try again.';
        event.target.value = '';
    };
    
    reader.readAsDataURL(file);
}

function simulateUploadProgress() {
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    let progress = 0;
    
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) {
            progress = 90;
            clearInterval(interval);
            progressText.textContent = 'Processing...';
        } else {
            progressText.textContent = `Uploading... ${Math.round(progress)}%`;
        }
        progressBar.style.width = progress + '%';
    }, 200);
}

// Auto-submit form when file is selected (optional enhancement)
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar');
    const form = document.querySelector('form[enctype="multipart/form-data"]');
    
    // Add form submission enhancement for avatar uploads
    if (form) {
        form.addEventListener('submit', function(e) {
            const file = avatarInput.files[0];
            const uploadProgress = document.getElementById('upload-progress');
            const progressText = document.getElementById('progress-text');
            const uploadError = document.getElementById('upload-error');
            
            // Clear any previous errors
            if (uploadError) uploadError.textContent = '';
            
            if (file) {
                // Prevent default form submission for AJAX
                e.preventDefault();
                
                // Show progress for large files
                if (file.size > 10 * 1024 * 1024) {
                    uploadProgress.classList.remove('hidden');
                    progressText.textContent = 'Saving avatar...';
                }
                
                // Add loading state to submit button
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    `;
                }
                
                // Handle form submission with AJAX
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                
                // Track upload progress
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable && file.size > 10 * 1024 * 1024) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        const progressBar = document.getElementById('progress-bar');
                        if (progressBar) {
                            progressBar.style.width = percentComplete + '%';
                        }
                        progressText.textContent = `Uploading... ${Math.round(percentComplete)}%`;
                    }
                });
                
                xhr.addEventListener('load', function() {
                    console.log('Response status:', xhr.status);
                    console.log('Response text:', xhr.responseText);
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            console.log('Parsed response:', response);
                            
                            if (response.success) {
                                // Show success
                                const uploadStatus = document.getElementById('upload-status');
                                if (uploadStatus) {
                                    uploadStatus.style.opacity = '1';
                                }
                                if (uploadProgress) {
                                    uploadProgress.classList.add('hidden');
                                }
                                
                                // Show success notification
                                showSuccessNotification('Profile updated successfully!');
                                
                                // Update avatar URL if provided
                                if (response.avatar_url) {
                                    const avatarImg = document.querySelector('label[for="avatar"] img');
                                    if (avatarImg) {
                                        avatarImg.src = response.avatar_url;
                                    }
                                }
                                
                                // Reload page after short delay to show updated avatar
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                if (uploadError) {
                                    uploadError.textContent = response.message || 'Error updating profile';
                                }
                            }
                        } catch (e) {
                            console.log('JSON parse error:', e);
                            // If not JSON, treat as regular form submission
                            console.log('Falling back to regular form submission');
                            form.submit();
                        }
                    } else {
                        if (uploadError) {
                            uploadError.textContent = 'Error updating profile. Please try again.';
                        }
                    }
                    
                    // Reset submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Save Changes';
                    }
                });
                
                xhr.addEventListener('error', function() {
                    console.log('XHR error occurred');
                    if (uploadError) {
                        uploadError.textContent = 'Network error. Please try again.';
                    }
                    if (uploadProgress) {
                        uploadProgress.classList.add('hidden');
                    }
                    
                    // Reset submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Save Changes';
                    }
                });
                
                xhr.open('POST', form.action);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                xhr.send(formData);
            } else {
                // No file selected, submit normally
                console.log('No file selected, submitting normally');
            }
        });
    }
});

function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center space-x-3 z-50 animate-fade-in-up';
    notification.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <div class="font-semibold">Success!</div>
            <div class="text-sm">${message}</div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'translate-y-2', 'transition-all', 'duration-300');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
