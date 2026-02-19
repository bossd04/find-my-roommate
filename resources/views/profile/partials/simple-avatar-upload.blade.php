<!-- Simple Avatar Upload Section -->
<div class="space-y-6">
    <div class="flex flex-col items-center">
        <!-- Avatar Display -->
        <div class="relative group" id="avatar-display">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}?{{ time() }}" 
                     alt="{{ $user->name }}" 
                     class="w-16 h-16 rounded-full object-cover border-2 border-blue-500 shadow-md">
            @elseif($user->avatar_url)
                <img src="{{ $user->avatar_url }}?{{ time() }}" 
                     alt="{{ $user->name }}" 
                     class="w-16 h-16 rounded-full object-cover border-2 border-blue-500 shadow-md">
            @else
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-lg font-bold text-white border-2 border-blue-500 shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        
        <!-- Upload Form -->
        <div class="mt-4 text-center">
            <input type="file" 
                   id="avatar" 
                   name="avatar" 
                   class="hidden" 
                   accept="image/jpeg,image/png,image/gif,image/webp"
                   onchange="previewAvatar(event)">
            
            <button type="button" 
                    onclick="document.getElementById('avatar').click()" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors text-sm">
                Choose Avatar
            </button>
            
            <div id="preview-container" class="mt-4"></div>
            <div id="upload-message" class="mt-2 text-sm"></div>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
    </div>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const uploadMessage = document.getElementById('upload-message');
    const avatarDisplay = document.getElementById('avatar-display');
    
    if (file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            uploadMessage.innerHTML = '<span class="text-red-600">Please select a valid image file (JPEG, PNG, GIF, or WebP)</span>';
            event.target.value = '';
            return;
        }
        
        // Validate file size (5MB)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            uploadMessage.innerHTML = '<span class="text-red-600">File size should not exceed 5MB</span>';
            event.target.value = '';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update avatar display with preview
            avatarDisplay.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Avatar preview" 
                     class="w-16 h-16 rounded-full object-cover border-2 border-green-500 shadow-md">
            `;
            
            // Show preview container with file info
            previewContainer.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" 
                         alt="Avatar preview" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-green-500 shadow-sm">
                    <div class="absolute -top-1 -right-1 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                        Ready
                    </div>
                </div>
            `;
            uploadMessage.innerHTML = '<span class="text-green-600">File selected: ' + file.name + '</span>';
        };
        reader.readAsDataURL(file);
    } else {
        // Reset to original avatar
        uploadMessage.innerHTML = '';
        previewContainer.innerHTML = '';
    }
}

// Handle form submission with AJAX for real-time updates
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[enctype="multipart/form-data"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            // Find the submit button
            const submitButton = form.querySelector('button[type="submit"]') || 
                               form.querySelector('.bg-indigo-600') || 
                               form.querySelector('button');
            
            // Show loading state
            if (submitButton) {
                const originalText = submitButton.innerHTML;
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
            .then(response => response.json())
            .then(data => {
                // Reset button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
                
                if (data.success) {
                    // Update avatar display in real-time
                    if (data.avatar_url) {
                        const avatarDisplay = document.getElementById('avatar-display');
                        if (avatarDisplay) {
                            avatarDisplay.innerHTML = `
                                <img src="${data.avatar_url}" 
                                     alt="Updated avatar" 
                                     class="w-16 h-16 rounded-full object-cover border-2 border-blue-500 shadow-md">
                            `;
                        }
                        
                        // Update file name display
                        const uploadMessage = document.getElementById('upload-message');
                        if (uploadMessage) {
                            uploadMessage.innerHTML = '<span class="text-green-600">Avatar updated successfully!</span>';
                        }
                        
                        // Clear preview
                        const previewContainer = document.getElementById('preview-container');
                        if (previewContainer) {
                            previewContainer.innerHTML = '';
                        }
                        
                        // Clear file input
                        document.getElementById('avatar').value = '';
                    }
                    
                    // Show success notification
                    showNotification('Avatar updated successfully!', 'success');
                    
                } else {
                    // Show error message
                    showNotification(data.message || 'Error updating avatar', 'error');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                showNotification('Network error. Please try again.', 'error');
                
                // Reset button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
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
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
