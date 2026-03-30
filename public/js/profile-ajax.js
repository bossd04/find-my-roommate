// Simple and reliable AJAX form handler for profile forms
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Profile forms AJAX handler loaded');
        
        // Initialize course/university functionality
        initializeCourseDepartmentFunctionality();
        
        // Handle all profile forms
        const forms = [
            'personal-info-form',
            'education-info-form', 
            'lifestyle-form'
        ];
        
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                console.log('Found form:', formId);
                
                // Remove any existing submit handlers
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);
                
                // Add our submit handler
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Form submitted:', formId);
                    
                    const submitBtn = newForm.querySelector('button[type="submit"]');
                    if (!submitBtn) {
                        console.error('Submit button not found for form:', formId);
                        return;
                    }
                    
                    // Store original button content
                    const originalContent = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    `;
                    
                    // Create FormData
                    const formData = new FormData(newForm);
                    
                    // Log form data for debugging
                    console.log('Form data being sent:');
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                    
                    // Send AJAX request
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', newForm.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Accept', 'application/json');
                    
                    xhr.onload = function() {
                        console.log('XHR response status:', xhr.status);
                        console.log('XHR response text:', xhr.responseText);
                        
                        if (xhr.status >= 200 && xhr.status < 300) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                console.log('Parsed response:', response);
                                
                                if (response.success) {
                                    showSuccessNotification(response.message || 'Information saved successfully!');
                                    updateButtonSuccess(submitBtn, originalContent);
                                    updateProfileCompletion(formId);
                                } else {
                                    showErrorNotification(response.message || 'Save failed');
                                    resetButton(submitBtn, originalContent);
                                }
                            } catch (e) {
                                console.log('JSON parse failed, assuming success');
                                // If JSON parsing fails, assume success based on status
                                showSuccessNotification('Information saved successfully!');
                                updateButtonSuccess(submitBtn, originalContent);
                                updateProfileCompletion(formId);
                            }
                        } else if (xhr.status === 422) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                const errors = Object.values(response.errors || {}).flat();
                                showErrorNotification(errors.join(', ') || 'Validation failed');
                            } catch (e) {
                                showErrorNotification('Validation failed. Please check all required fields.');
                            }
                            resetButton(submitBtn, originalContent);
                        } else {
                            showErrorNotification('Server error. Please try again.');
                            resetButton(submitBtn, originalContent);
                        }
                    };
                    
                    xhr.onerror = function() {
                        console.error('XHR error occurred');
                        showErrorNotification('Network error. Please check your connection and try again.');
                        resetButton(submitBtn, originalContent);
                    };
                    
                    xhr.send(formData);
                });
            } else {
                console.log('Form not found:', formId);
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
    
    function updateButtonSuccess(button, originalContent) {
        button.innerHTML = `
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Saved Successfully
        `;
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            resetButton(button, originalContent);
        }, 3000);
    }
    
    function resetButton(button, originalContent) {
        button.disabled = false;
        button.innerHTML = originalContent;
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
        
        console.log('Updating profile completion for section:', section);
        
        // Update status indicator
        const statusElement = document.querySelector(`#${section}-status`);
        if (statusElement) {
            console.log('Found status element:', statusElement);
            statusElement.classList.remove('bg-red-100');
            statusElement.classList.add('bg-green-100');
        }
        
        // Update check mark
        const checkElement = document.querySelector(`#${section}-check`);
        if (checkElement) {
            console.log('Found check element:', checkElement);
            checkElement.classList.remove('text-red-600');
            checkElement.classList.add('text-green-600');
            // Change X to checkmark
            checkElement.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
        }
        
        // Update text
        const textElement = document.querySelector(`#${section}-text`);
        if (textElement) {
            console.log('Found text element:', textElement);
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
    
    function initializeCourseDepartmentFunctionality() {
        // Course-Department mapping
        const courseDepartmentMapping = {
            // Computer Studies and IT
            'Bachelor of Science in Computer Science': ['College of Computer Studies', 'Department of Computer Science'],
            'Bachelor of Science in Information Technology': ['College of Computer Studies', 'Department of Information Technology'],
            'Bachelor of Science in Computer Engineering': ['College of Computer Studies', 'Department of Computer Engineering'],
            'Bachelor of Science in Information Systems': ['College of Computer Studies', 'Department of Information Systems'],
            'Bachelor of Science in Cybersecurity': ['College of Computer Studies', 'Department of Cybersecurity'],
            
            // Engineering
            'Bachelor of Science in Civil Engineering': ['College of Engineering', 'Department of Civil Engineering'],
            'Bachelor of Science in Electrical Engineering': ['College of Engineering', 'Department of Electrical Engineering'],
            'Bachelor of Science in Mechanical Engineering': ['College of Engineering', 'Department of Mechanical Engineering'],
            'Bachelor of Science in Electronics Engineering': ['College of Engineering', 'Department of Electronics Engineering'],
            'Bachelor of Science in Industrial Engineering': ['College of Engineering', 'Department of Industrial Engineering'],
            
            // Business and Management
            'Bachelor of Science in Accountancy': ['College of Business', 'Department of Accountancy'],
            'Bachelor of Science in Business Administration': ['College of Business', 'Department of Business Administration'],
            'Bachelor of Science in Business Administration Major in Financial Management': ['College of Business', 'Department of Financial Management'],
            'Bachelor of Science in Business Administration Major in Marketing Management': ['College of Business', 'Department of Marketing Management'],
            'Bachelor of Science in Business Administration Major in Human Resource Management': ['College of Business', 'Department of Human Resource Management'],
            'Bachelor of Science in Business Administration Major in Operations Management': ['College of Business', 'Department of Operations Management'],
            
            // Arts and Sciences
            'Bachelor of Arts in Mass Communication': ['College of Arts and Sciences', 'Department of Communication'],
            'Bachelor of Arts in Journalism': ['College of Arts and Sciences', 'Department of Journalism'],
            'Bachelor of Arts in Broadcasting': ['College of Arts and Sciences', 'Department of Broadcasting'],
            'Bachelor of Arts in International Studies': ['College of Arts and Sciences', 'Department of International Studies'],
            'Bachelor of Science in Psychology': ['College of Arts and Sciences', 'Department of Psychology'],
            'Bachelor of Science in Biology': ['College of Arts and Sciences', 'Department of Biology'],
            'Bachelor of Science in Mathematics': ['College of Arts and Sciences', 'Department of Mathematics'],
            
            // Health Sciences
            'Bachelor of Science in Nursing': ['College of Health Sciences', 'Department of Nursing'],
            'Bachelor of Science in Medical Technology': ['College of Health Sciences', 'Department of Medical Technology'],
            'Bachelor of Science in Pharmacy': ['College of Health Sciences', 'Department of Pharmacy'],
            'Bachelor of Science in Physical Therapy': ['College of Health Sciences', 'Department of Physical Therapy'],
            'Bachelor of Science in Radiologic Technology': ['College of Health Sciences', 'Department of Radiologic Technology'],
            
            // Education
            'Bachelor of Secondary Education': ['College of Education', 'Department of Secondary Education'],
            'Bachelor of Secondary Education Major in English': ['College of Education', 'Department of English Education'],
            'Bachelor of Secondary Education Major in Mathematics': ['College of Education', 'Department of Mathematics Education'],
            'Bachelor of Secondary Education Major in Science': ['College of Education', 'Department of Science Education'],
            'Bachelor of Elementary Education': ['College of Education', 'Department of Elementary Education'],
            
            // Tourism and Hospitality
            'Bachelor of Science in Tourism Management': ['College of Tourism and Hospitality', 'Department of Tourism Management'],
            'Bachelor of Science in Hotel and Restaurant Management': ['College of Tourism and Hospitality', 'Department of Hotel and Restaurant Management'],
            
            // Social Sciences
            'Bachelor of Arts in Political Science': ['College of Social Sciences', 'Department of Political Science'],
            'Bachelor of Arts in Economics': ['College of Social Sciences', 'Department of Economics'],
            'Bachelor of Arts in Sociology': ['College of Social Sciences', 'Department of Sociology'],
            'Bachelor of Arts in History': ['College of Social Sciences', 'Department of History'],
            
            // Law and Legal Studies
            'Bachelor of Laws': ['College of Law', 'Department of Legal Studies'],
            'Bachelor of Arts in Legal Management': ['College of Law', 'Department of Legal Management'],
            
            // Other Fields
            'Bachelor of Science in Architecture': ['College of Architecture', 'Department of Architectural Design'],
            'Bachelor of Fine Arts': ['College of Fine Arts', 'Department of Visual Arts'],
            'Bachelor of Music': ['College of Fine Arts', 'Department of Music'],
            'Bachelor of Physical Education': ['College of Sports Science', 'Department of Physical Education'],
            'Bachelor of Library and Information Science': ['College of Information Science', 'Department of Library Science']
        };

        function handleUniversityChange(select) {
            const courseSelect = document.getElementById('course');
            const departmentSelect = document.getElementById('department');
            
            if (!courseSelect || !departmentSelect) return;
            
            // Get selected university
            const selectedUniversity = select.value;
            
            // Filter courses based on university
            const courseGroups = courseSelect.querySelectorAll('optgroup');
            courseGroups.forEach(group => {
                if (selectedUniversity === '' || group.getAttribute('data-university') === selectedUniversity) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
            
            // Reset department
            departmentSelect.innerHTML = '<option value="">Select Department</option>';
        }

        function handleCourseChange(select) {
            const departmentSelect = document.getElementById('department');
            const selectedCourse = select.value;
            
            if (!departmentSelect) return;
            
            // Clear department options
            departmentSelect.innerHTML = '<option value="">Select Department</option>';
            
            if (selectedCourse && courseDepartmentMapping[selectedCourse]) {
                const [college, department] = courseDepartmentMapping[selectedCourse];
                
                // Add college as optgroup
                const collegeGroup = document.createElement('optgroup');
                collegeGroup.label = college;
                
                // Add department option
                const departmentOption = document.createElement('option');
                departmentOption.value = department;
                departmentOption.textContent = department;
                collegeGroup.appendChild(departmentOption);
                
                departmentSelect.appendChild(collegeGroup);
                departmentSelect.value = department;
            }
        }

        // Initialize university and course handlers
        const universitySelect = document.getElementById('university');
        if (universitySelect) {
            handleUniversityChange(universitySelect);
            universitySelect.addEventListener('change', function() {
                handleUniversityChange(this);
            });
        }
        
        const courseSelect = document.getElementById('course');
        if (courseSelect) {
            handleCourseChange(courseSelect);
            courseSelect.addEventListener('change', function() {
                handleCourseChange(this);
            });
        }
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
    
})();
