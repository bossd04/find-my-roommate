<!-- Profile Form Updated: {{ date('Y-m-d H:i:s') }} - Version 3.0 Clean -->
<script>
    // Define profileFormUpdate function first for Alpine.js
    // Cache-busted: {{ time() }}
    function profileFormUpdate() {
        return {
            notifications: [],
            showNotification(message, type = 'success') {
                this.notifications.push({ id: Date.now(), message, type });
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== this.notifications[0].id);
                }, 3000);
            },
            async submitForm(formId, endpoint, section) {
                const form = document.getElementById(formId);
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type=submit]');
                const originalText = submitBtn.innerHTML;
                
                try {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                    
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showNotification(data.message, 'success');
                        const errorElements = form.querySelectorAll('.text-red-500');
                        errorElements.forEach(el => el.remove());
                        
                        // Update completion percentage if provided
                        if (data.completion_percentage !== undefined) {
                            console.log(`📊 Updating completion percentage to: ${data.completion_percentage}%`);
                            
                            // Update progress bar
                            const progressBar = document.querySelector('.bg-gradient-to-r.from-blue-500');
                            const progressText = document.querySelector('.flex.justify-between.text-sm span:last-child');
                            
                            if (progressBar && progressText) {
                                progressBar.style.width = data.completion_percentage + '%';
                                progressText.textContent = data.completion_percentage + '%';
                            }
                            
                            // Show dashboard button if 100% complete
                            if (data.completion_percentage >= 100) {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        }
                    } else {
                        this.showNotification(data.message || 'Error saving information', 'error');
                    }
                } catch (error) {
                    this.showNotification('Network error. Please try again.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }
        };
    } // End of profileFormUpdate function
</script>

<div x-data="profileFormUpdate()" class="space-y-6">
    
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

    <!-- Profile Completion Section -->
    @if($completionPercentage >= 100)
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-8 mb-8 shadow-lg">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 4l4 4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-green-800">🎉 Profile Complete!</h3>
        </div>
        <p class="text-green-700 mb-4 text-center">Your profile is 100% complete. You now have full access to all roommate finding features!</p>
        
        <div class="flex gap-4 justify-center">
            <button onclick="window.location.href='/dashboard'" 
                    class="inline-flex items-center px-8 py-4 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                <svg class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7 7 7 7M5 10v7a7 7 0 0014 0H3a7 7 0 01-7-7v-4a7 7 0 0114 0h4a7 7 0 017 7v4" />
                </svg>
                🚀 Go to Dashboard
            </button>
        </div>
    </div>
    @endif

</div>
