// Simple AJAX form submission for profile forms
document.addEventListener('DOMContentLoaded', function() {
    // Handle all profile forms
    const profileForms = document.querySelectorAll('[id$="-form"]');
    
    profileForms.forEach(form => {
        if (form.id.includes('personal') || form.id.includes('education') || form.id.includes('lifestyle')) {
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!submitBtn) return;
                
                // Show loading state
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;
                
                // Create FormData
                const formData = new FormData(form);
                
                // Submit using XMLHttpRequest for better compatibility
                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');
                
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                showSuccessNotification(response.message);
                                updateButtonSuccess(submitBtn, originalText);
                                updateProfileCompletion(form.id);
                            } else {
                                showErrorNotification(response.message || 'Save failed');
                                resetButton(submitBtn, originalText);
                            }
                        } catch (e) {
                            // If JSON parsing fails, assume success based on status
                            showSuccessNotification('Information saved successfully!');
                            updateButtonSuccess(submitBtn, originalText);
                            updateProfileCompletion(form.id);
                        }
                    } else if (xhr.status === 422) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            const errors = Object.values(response.errors || {}).flat();
                            showErrorNotification(errors.join(', ') || 'Validation failed');
                        } catch (e) {
                            showErrorNotification('Validation failed. Please check all required fields.');
                        }
                        resetButton(submitBtn, originalText);
                    } else {
                        showErrorNotification('Server error. Please try again.');
                        resetButton(submitBtn, originalText);
                    }
                };
                
                xhr.onerror = function() {
                    console.error('Network error occurred');
                    showErrorNotification('Network error. Please check your connection and try again.');
                    resetButton(submitBtn, originalText);
                };
                
                xhr.send(formData);
            });
        }
    });
});

function showSuccessNotification(message) {
    removeNotifications();
    
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg z-50 max-w-sm';
    notification.style.cssText = 'animation: slideInRight 0.3s ease-out;';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-600 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-green-800 font-medium">Success!</p>
                <p class="text-green-600 text-sm mt-1">${message}</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => removeNotification(notification), 5000);
}

function showErrorNotification(message) {
    removeNotifications();
    
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg z-50 max-w-sm';
    notification.style.cssText = 'animation: slideInRight 0.3s ease-out;';
    notification.innerHTML = `
        <div class="flex items-start">
            <svg class="h-5 w-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-red-800 font-medium">Save Failed</p>
                <p class="text-red-600 text-sm mt-1">${message}</p>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-red-600 text-xs underline mt-2 hover:text-red-800">Dismiss</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => removeNotification(notification), 10000);
}

function removeNotifications() {
    document.querySelectorAll('.fixed.top-4.right-4').forEach(el => el.remove());
}

function removeNotification(notification) {
    if (notification && notification.parentNode) {
        notification.style.cssText = 'animation: slideOutRight 0.3s ease-in;';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
}

function updateButtonSuccess(button, originalText) {
    button.innerHTML = `
        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Saved Successfully
    `;
    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    button.classList.add('bg-green-600', 'hover:bg-green-700');
    
    setTimeout(() => {
        resetButton(button, originalText);
    }, 3000);
}

function resetButton(button, originalText) {
    button.disabled = false;
    button.innerHTML = originalText;
    button.classList.remove('bg-green-600', 'hover:bg-green-700');
    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
}

function updateProfileCompletion(formId) {
    const sectionMap = {
        'personal-info-form': 'personal',
        'education-info-form': 'education',
        'lifestyle-form': 'lifestyle'
    };
    
    const section = sectionMap[formId];
    if (!section) return;
    
    // Update status indicator
    const statusElement = document.querySelector(`#${section}-status`);
    if (statusElement) {
        statusElement.classList.remove('bg-red-100');
        statusElement.classList.add('bg-green-100');
    }
    
    // Update check mark
    const checkElement = document.querySelector(`#${section}-check`);
    if (checkElement) {
        checkElement.classList.remove('text-red-600');
        checkElement.classList.add('text-green-600');
        // Change X to checkmark
        checkElement.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
    }
    
    // Update text
    const textElement = document.querySelector(`#${section}-text`);
    if (textElement) {
        textElement.classList.remove('text-gray-600');
        textElement.classList.add('text-green-700');
        textElement.textContent = 'Complete';
    }
    
    // Show completion notification
    showCompletionNotification(section);
}

function showCompletionNotification(section) {
    const sectionNames = {
        'personal': 'Personal Information',
        'education': 'Education Information',
        'lifestyle': 'Lifestyle Preferences'
    };
    
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-4 right-4 bg-blue-50 border border-blue-200 rounded-lg p-3 shadow-lg z-50 max-w-sm';
    notification.style.cssText = 'animation: slideInUp 0.3s ease-out;';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="h-4 w-4 text-blue-600 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-blue-800 text-sm font-medium">${sectionNames[section]} completed!</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => removeNotification(notification), 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    @keyframes slideInUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideOutDown {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
