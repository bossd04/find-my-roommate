<!-- Profile Picture Upload Section -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-medium text-gray-900">Profile Picture</h3>
            <p class="text-sm text-gray-600">Upload a photo to help others recognize you</p>
        </div>
    </div>

    <div class="flex items-center space-x-6">
        <!-- Current Avatar -->
        <div class="relative">
            <div id="avatar-preview" class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-lg">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url('avatars/' . auth()->user()->avatar) }}" 
                         alt="Profile Picture" 
                         class="w-full h-full object-cover"
                         id="current-avatar">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600">
                        <span class="text-white text-2xl font-bold">
                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>
            
            <!-- Upload Status Indicator -->
            <div id="upload-status" class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-green-500 border-2 border-white hidden">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <!-- Loading Indicator -->
            <div id="upload-loading" class="absolute inset-0 w-24 h-24 rounded-full bg-white/90 flex items-center justify-center hidden">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="flex-1">
            <form id="avatar-upload-form" enctype="multipart/form-data" class="space-y-4" onsubmit="return false;">
                @csrf
                <div>
                    <label for="avatar-upload" class="block text-sm font-medium text-gray-700 mb-2">
                        Choose Profile Picture
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="file" 
                               id="avatar-upload" 
                               name="avatar" 
                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                               class="hidden">
                        
                        <button type="button" 
                                id="upload-button"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center space-x-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span>Choose File</span>
                        </button>
                        
                        @if(auth()->user()->avatar)
                            <button type="button" 
                                    id="remove-avatar-btn"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-2 shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Remove</span>
                            </button>
                        @endif
                    </div>
                    
                    <p class="mt-2 text-xs text-gray-500">
                        Supported formats: JPEG, PNG, GIF, WebP. Maximum size: 5GB.
                    </p>
                </div>
                
                <!-- File Info Display -->
                <div id="file-info" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span id="file-name" class="text-sm text-blue-800 font-medium"></span>
                        </div>
                        <span id="file-size" class="text-xs text-blue-600"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .avatar-upload-btn:hover {
        transform: scale(1.05);
    }
    
    .avatar-upload-btn:active {
        transform: scale(0.95);
    }
    
    #avatar-preview {
        transition: all 0.3s ease;
    }
    
    #avatar-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarUpload = document.getElementById('avatar-upload');
    const uploadButton = document.getElementById('upload-button');
    const removeButton = document.getElementById('remove-avatar-btn');
    const avatarPreview = document.getElementById('avatar-preview');
    const currentAvatar = document.getElementById('current-avatar');
    const uploadLoading = document.getElementById('upload-loading');
    const uploadStatus = document.getElementById('upload-status');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    // Handle file selection
    uploadButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any form submission
        avatarUpload.click();
    });
    
    // Handle file change and upload
    avatarUpload.addEventListener('change', function(e) {
        console.log('File change event triggered');
        const fileInput = e.target;
        const file = fileInput.files[0];
        console.log('Selected file:', file);
        console.log('File input element:', fileInput);
        console.log('Files in input:', fileInput.files);
        
        if (!file) {
            console.log('No file selected');
            showNotification('Please select an image to upload', 'error');
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('Please select a valid image file (JPEG, PNG, GIF, or WebP)', 'error');
            return;
        }
        
        // Validate file size (5GB)
        if (file.size > 5 * 1024 * 1024 * 1024) {
            showNotification('File size must be less than 5GB', 'error');
            return;
        }
        
        // Show file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            avatarPreview.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Profile Picture Preview" 
                     class="w-full h-full object-cover">
            `;
        };
        reader.readAsDataURL(file);
        
        // Upload the file
        console.log('About to call uploadAvatar with file:', file);
        uploadAvatar(file);
    });
    
    // Handle avatar removal
    if (removeButton) {
        removeButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove your profile picture?')) {
                removeAvatar();
            }
        });
    }
    
    function uploadAvatar(file) {
        console.log('uploadAvatar called with file:', file);
        
        if (!file) {
            console.error('No file provided to uploadAvatar function');
            showNotification('Please select an image to upload', 'error');
            return;
        }
        
        const formData = new FormData();
        
        // Append file with proper field name
        formData.append('avatar', file, file.name);
        
        // Append CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            showNotification('Security token missing. Please refresh the page.', 'error');
            return;
        }
        formData.append('_token', csrfToken);
        
        // Debug: Check FormData contents
        console.log('FormData being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        // Show loading state only during upload
        uploadLoading.classList.remove('hidden');
        uploadButton.disabled = true;
        uploadButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Uploading...
        `;
        if (removeButton) removeButton.disabled = true;
        
        // Use XMLHttpRequest to bypass CSRF handler issues
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("profile.avatar.update") }}', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        // Don't set Content-Type - let browser set it for FormData
        
        xhr.onload = function() {
            // Hide loading state
            uploadLoading.classList.add('hidden');
            const originalText = `
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span>Choose File</span>
            `;
            uploadButton.disabled = false;
            uploadButton.innerHTML = originalText;
            if (removeButton) removeButton.disabled = false;
            
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        // Update avatar display
                        avatarPreview.innerHTML = `
                            <img src="${data.avatar_url}" 
                                 alt="Profile Picture" 
                                 class="w-full h-full object-cover">
                        `;
                        
                        // Show success status
                        uploadStatus.classList.remove('hidden');
                        setTimeout(() => {
                            uploadStatus.classList.add('hidden');
                        }, 3000);
                        
                        // Update all avatar images on page
                        document.querySelectorAll('img[alt*="Profile"], .user-avatar').forEach(img => {
                            if (img.src && img.src.includes('/storage/avatars/')) {
                                img.src = data.avatar_url + '?t=' + Date.now();
                            }
                        });
                        
                        // Show success notification
                        showNotification('Profile picture successfully changed and uploaded!', 'success');
                        fileInfo.classList.add('hidden');
                        
                        // Reset file input
                        avatarUpload.value = '';
                        
                        // Add remove button if it doesn't exist
                        if (!removeButton && document.getElementById('remove-avatar-btn') === null) {
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.id = 'remove-avatar-btn';
                            removeBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-2 shadow-sm';
                            removeBtn.innerHTML = `
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Remove</span>
                            `;
                            removeBtn.addEventListener('click', function() {
                                if (confirm('Are you sure you want to remove your profile picture?')) {
                                    removeAvatar();
                                }
                            });
                            
                            // Insert remove button after upload button
                            uploadButton.parentNode.insertBefore(removeBtn, uploadButton.nextSibling);
                        }
                    } else {
                        showNotification(data.message || 'Failed to update profile picture', 'error');
                    }
                } catch (e) {
                    console.error('Parse error:', e);
                    showNotification('Failed to process server response', 'error');
                }
            } else {
                console.error('HTTP error:', xhr.status, xhr.responseText);
                try {
                    const errorData = JSON.parse(xhr.responseText);
                    showNotification(errorData.message || 'Failed to upload profile picture', 'error');
                } catch (e) {
                    showNotification('Failed to upload profile picture', 'error');
                }
            }
        };
        
        xhr.onerror = function() {
            // Hide loading state
            uploadLoading.classList.add('hidden');
            const originalText = `
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span>Choose File</span>
            `;
            uploadButton.disabled = false;
            uploadButton.innerHTML = originalText;
            if (removeButton) removeButton.disabled = false;
            
            console.error('Network error');
            showNotification('Network error. Please check your connection.', 'error');
        };
        
        xhr.send(formData);
    }
    
    function removeAvatar() {
        fetch('{{ route("profile.avatar.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset to default avatar
                avatarPreview.innerHTML = `
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600">
                        <span class="text-white text-2xl font-bold">
                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                        </span>
                    </div>
                `;
                
                // Remove remove button
                removeButton.remove();
                
                // Update all avatar images on page
                document.querySelectorAll('img[alt*="Profile"], .user-avatar').forEach(img => {
                    if (img.src && img.src.includes('/storage/avatars/')) {
                        img.src = '/images/default-avatar.png';
                    }
                });
                
                showNotification('Profile picture removed successfully!', 'success');
            } else {
                showNotification(data.message || 'Failed to remove profile picture', 'error');
            }
        })
        .catch(error => {
            console.error('Remove error:', error);
            showNotification('Failed to remove profile picture', 'error');
        });
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 animate-fade-in-up z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                }
            </svg>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('opacity-0', 'translate-y-2', 'transition-all', 'duration-300');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
});
</script>
