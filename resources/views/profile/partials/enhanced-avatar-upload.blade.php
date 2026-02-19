<!-- Enhanced Avatar Upload Section -->
<div class="space-y-6">
    <div class="flex flex-col items-center">
        <!-- Main Avatar Upload Area - Fully Clickable -->
        <div class="relative group cursor-pointer" onclick="document.getElementById('avatar').click()">
            <div class="relative">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}?{{ time() }}" 
                         alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-full object-cover border-4 border-blue-500 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                @elseif($user->avatar_url)
                    <img src="{{ $user->avatar_url }}?{{ time() }}" 
                         alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-full object-cover border-4 border-blue-500 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-2xl font-bold text-white border-4 border-blue-500 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <input type="file" id="avatar" name="avatar" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/jpeg,image/png,image/gif,image/webp" onchange="handleAvatarUpload(event)">
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 cursor-pointer rounded-full">
                    <span class="text-white text-xs font-semibold px-3 py-1 bg-indigo-600 rounded-full">Change</span>
                </div>
            </div>
        </div>
        
        <!-- File Information with High Visibility -->
        <div class="text-center mt-4 space-y-2">
            <div id="file-name" class="text-sm font-bold text-black bg-gray-100 px-4 py-3 rounded-lg border border-gray-300 cursor-pointer hover:bg-gray-200 transition-colors" onclick="document.getElementById('avatar').click()">
                @if($user->avatar)
                    {{ basename($user->avatar) }}
                @else
                    No file chosen
                @endif
            </div>
            
            <div class="text-xs text-gray-500 mt-1">
                @if($user->avatar)
                    Current file: {{ basename($user->avatar) }}
                @else
                    No file uploaded yet
                @endif
            </div>
            
            <!-- Progress Bar with High Visibility -->
            <div id="upload-progress" class="hidden w-full max-w-xs mx-auto mt-3">
                <div class="bg-gray-300 rounded-full h-3 overflow-hidden border-2 border-gray-400">
                    <div id="progress-bar" class="bg-gradient-to-r from-green-500 to-blue-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div id="progress-text" class="text-sm font-bold text-gray-700 mt-2">Uploading...</div>
            </div>
            
            <!-- Error Message Display -->
            <div id="upload-error" class="hidden w-full max-w-xs mx-auto mt-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm"></div>
            
            <!-- File Requirements with Better Design -->
            <div class="bg-blue-100 border-2 border-blue-300 rounded-lg p-4 mt-4">
                <p class="text-sm font-bold text-blue-900">
                    📸 <strong>Requirements:</strong> JPEG, PNG, GIF, WebP<br>
                    📏 <strong>Max size:</strong> 5MB<br>
                    ⭐ <strong>Recommended:</strong> Square image for best results
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.drop-shadow-lg {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}
</style>

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
    const uploadError = document.getElementById('upload-error');
    const uploadProgress = document.getElementById('upload-progress');
    
    console.log('Avatar upload triggered:', file);
    
    // Clear previous errors and hide progress
    if (uploadError) {
        uploadError.textContent = '';
        uploadError.classList.add('hidden');
    }
    if (uploadProgress) {
        uploadProgress.classList.add('hidden');
    }
    
    if (!file) {
        if (fileName) fileName.textContent = 'No file chosen';
        return;
    }
    
    // Update file name
    if (fileName) {
        fileName.textContent = file.name;
    }
    
    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        if (uploadError) {
            uploadError.textContent = 'Please select a valid image file (JPEG, PNG, GIF, or WebP)';
            uploadError.classList.remove('hidden');
        }
        event.target.value = '';
        if (fileName) fileName.textContent = 'No file chosen';
        return;
    }
    
    // Validate file size (5MB - more reasonable limit)
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
        if (uploadError) {
            uploadError.textContent = `File size exceeds 5MB limit. Your file is ${formatFileSize(file.size)}.`;
            uploadError.classList.remove('hidden');
        }
        event.target.value = '';
        if (fileName) fileName.textContent = 'No file chosen';
        return;
    }
    
    // Show progress for large files
    if (file.size > 1024 * 1024) { // Files larger than 1MB
        if (uploadProgress) {
            uploadProgress.classList.remove('hidden');
        }
        updateProgress(0, 'Preparing upload...');
    }
    
    // Preview image
    const reader = new FileReader();
    reader.onload = function(e) {
        const avatarContainer = document.querySelector('.relative.group');
        if (avatarContainer) {
            avatarContainer.innerHTML = `
                <div class="relative">
                    <img src="${e.target.result}" 
                         alt="Avatar preview" 
                         class="w-20 h-20 rounded-full object-cover border-4 border-blue-500 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-blue-600/95 to-purple-600/95 flex items-center justify-center transition-all duration-300 border-4 border-white hover:opacity-100 opacity-90">
                        <div class="text-center">
                            <svg class="w-10 h-10 mx-auto mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Upload Status Indicator -->
                <div id="upload-status" class="absolute -top-2 -right-2 w-10 h-10 rounded-full bg-green-500 flex items-center justify-center opacity-100 transition-opacity duration-300 border-2 border-white">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            `;
        }
        
        // Hide progress after successful preview
        if (uploadProgress && file.size > 1024 * 1024) {
            setTimeout(() => {
                uploadProgress.classList.add('hidden');
            }, 1000);
        }
        
        console.log('Avatar preview loaded successfully');
    };
    
    reader.onerror = function() {
        if (uploadError) {
            uploadError.textContent = 'Error reading file. Please try again.';
            uploadError.classList.remove('hidden');
        }
        event.target.value = '';
        if (fileName) fileName.textContent = 'No file chosen';
    };
    
    reader.readAsDataURL(file);
}

function updateProgress(percent, text) {
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    if (progressBar) {
        progressBar.style.width = percent + '%';
    }
    if (progressText) {
        progressText.textContent = text;
    }
}

// Enhanced form submission handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[enctype="multipart/form-data"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default submission
            
            const fileInput = document.getElementById('avatar');
            const file = fileInput.files[0];
            const submitButton = form.querySelector('button[type="submit"]');
            
            console.log('Form submitted with file:', file);
            console.log('Form action:', form.action);
            
            // Show loading state
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...</span>';
            }
            
            // Create FormData
            const formData = new FormData(form);
            
            // AJAX submission
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                // Reset button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Changes';
                }
                
                if (data.success) {
                    // Update avatar display in real-time
                    if (data.avatar_url) {
                        const avatarContainer = document.querySelector('.relative.group');
                        if (avatarContainer) {
                            avatarContainer.innerHTML = `
                                <div class="relative">
                                    <img src="${data.avatar_url}" 
                                         alt="Updated avatar" 
                                         class="w-20 h-20 rounded-full object-cover border-4 border-blue-500 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                                    <input type="file" id="avatar" name="avatar" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/jpeg,image/png,image/gif,image/webp" onchange="handleAvatarUpload(event)">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 cursor-pointer rounded-full" onclick="document.getElementById('avatar').click()">
                                        <span class="text-white text-xs font-semibold px-3 py-1 bg-indigo-600 rounded-full">Change</span>
                                    </div>
                                </div>
                            `;
                        }
                        
                        // Update file name display
                        const fileName = document.getElementById('file-name');
                        if (fileName && data.avatar_url) {
                            const urlParts = data.avatar_url.split('/');
                            const filename = urlParts[urlParts.length - 1];
                            fileName.textContent = filename.split('?')[0]; // Remove timestamp
                        }
                        
                        // Update current file text
                        const currentFileText = document.querySelector('.text-xs.text-gray-500.mt-1');
                        if (currentFileText && data.avatar_url) {
                            const urlParts = data.avatar_url.split('/');
                            const filename = urlParts[urlParts.length - 1];
                            currentFileText.textContent = 'Current file: ' + filename.split('?')[0];
                        }
                    }
                    
                    // Show success message
                    showNotification('Profile updated successfully!', 'success');
                    
                    // Hide progress
                    const uploadProgress = document.getElementById('upload-progress');
                    if (uploadProgress) {
                        uploadProgress.classList.add('hidden');
                    }
                    
                } else {
                    // Show error message
                    showNotification(data.message || 'Error updating profile', 'error');
                    
                    // Hide progress
                    const uploadProgress = document.getElementById('upload-progress');
                    if (uploadProgress) {
                        uploadProgress.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                showNotification('Network error. Please try again.', 'error');
                
                // Reset button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Save Changes';
                }
                
                // Hide progress
                const uploadProgress = document.getElementById('upload-progress');
                if (uploadProgress) {
                    uploadProgress.classList.add('hidden');
                }
            });
        });
    }
});

// Notification function
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
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 4l4 4"></path>' :
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
</script>

<style>
.opacity-90 {
    opacity: 0.9;
}
</style>
