@extends('layouts.app')

@section('title', $listing->title)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('listings.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Listings
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Images & Key Info -->
            <div class="space-y-6">
                <!-- Main Image -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="relative h-80 bg-gray-200 dark:bg-gray-700">
                        @if($listing->image)
                            <img src="{{ route('listing.image.serve', ['filename' => basename($listing->image)]) }}" 
                                 alt="{{ $listing->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-home text-6xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        @if($listing->is_available)
                            <span class="absolute top-4 left-4 px-4 py-2 bg-green-500 text-white text-sm font-bold rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Available
                            </span>
                        @else
                            <span class="absolute top-4 left-4 px-4 py-2 bg-red-500 text-white text-sm font-bold rounded-full">
                                <i class="fas fa-times-circle mr-1"></i> Occupied
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Quick Info Cards -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 text-center">
                        <i class="fas fa-bed text-2xl text-indigo-600 dark:text-indigo-400 mb-2"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Bedrooms</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-gray-50">{{ $listing->bedrooms }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 text-center">
                        <i class="fas fa-bath text-2xl text-indigo-600 dark:text-indigo-400 mb-2"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Bathrooms</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-gray-50">{{ $listing->bathrooms }}</p>
                    </div>
                </div>

                <!-- Price Card -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                    <p class="text-sm opacity-90 mb-1">Monthly Rent</p>
                    <p class="text-4xl font-bold">₱{{ number_format($listing->price, 0) }}</p>
                    <p class="text-sm opacity-90 mt-2">per month</p>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="space-y-6">
                <!-- Title & Description -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-50 mb-4">{{ $listing->title }}</h1>
                    
                    <div class="flex items-center text-gray-600 dark:text-gray-400 mb-6">
                        <i class="fas fa-map-marker-alt mr-2 text-indigo-600 dark:text-indigo-400"></i>
                        <span>{{ $listing->location }}</span>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-3">Description</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ $listing->description }}
                    </p>
                </div>

                <!-- Owner Info -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-4">Listed By</h2>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mr-4">
                            @if($listing->user)
                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ strtoupper(substr($listing->user->first_name, 0, 1)) }}
                                </span>
                            @elseif($listing->landlord)
                                <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-400"></i>
                            @else
                                <i class="fas fa-user text-indigo-600 dark:text-indigo-400"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-50">
                                @if($listing->user)
                                    {{ $listing->user->full_name }}
                                @elseif($listing->landlord)
                                    {{ $listing->landlord->first_name }} {{ $listing->landlord->last_name }} (Admin)
                                @else
                                    Unknown
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($listing->landlord)
                                    <span class="inline-flex items-center px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs rounded-full">
                                        <i class="fas fa-shield-alt mr-1"></i> Admin Listed
                                    </span>
                                @else
                                    Property Owner
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Location Map -->
                @if($listing->latitude && $listing->longitude)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-4 flex items-center">
                        <i class="fas fa-map-marked-alt mr-2 text-indigo-600 dark:text-indigo-400"></i>
                        Location on Map
                    </h2>
                    <div id="listing-map" style="height: 300px;" class="rounded-xl overflow-hidden"></div>
                    <div class="mt-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Exact location may vary. Contact owner for precise directions.</span>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-4">Interested?</h2>
                    <div class="space-y-3">
                        @if($listing->is_available)
                            @if($listing->user)
                                <a href="{{ route('messages.show', $listing->user) }}" 
                                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                                    <i class="fas fa-comment-alt mr-2"></i>
                                    Message Owner
                                </a>
                            @elseif($listing->landlord)
                                @php
                                    $adminUser = \App\Models\User::find($listing->landlord_id);
                                @endphp
                                @if($adminUser && !$adminUser->is_admin)
                                    <a href="{{ route('messages.show', $adminUser) }}" 
                                       class="w-full inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                                        <i class="fas fa-comment-alt mr-2"></i>
                                        Contact Owner
                                    </a>
                                @else
                                    <button onclick="showAdminContactModal()" 
                                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors cursor-pointer">
                                        <i class="fas fa-comment-alt mr-2"></i>
                                        Contact Admin
                                    </button>
                                @endif
                            @endif
                        @else
                            <button disabled 
                                    class="w-full inline-flex items-center justify-center px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 font-semibold rounded-xl cursor-not-allowed">
                                <i class="fas fa-ban mr-2"></i>
                                Not Available
                            </button>
                        @endif
                        
                        <button type="button" onclick="shareListing()" 
                                class="w-full inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                            <i class="fas fa-share-alt mr-2"></i>
                            <span id="share-btn-text">Share Listing</span>
                        </button>
                    </div>
                </div>

                <!-- Admin Contact Modal -->
                <div id="admin-contact-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0" id="admin-modal-content">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-50">Contact Admin</h3>
                            <button onclick="closeAdminModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Send a message to the admin about this listing.
                        </p>
                        <form id="admin-contact-form" onsubmit="submitAdminContact(event)">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Message</label>
                                <textarea id="admin-message" rows="4" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none"
                                    placeholder="Hi, I'm interested in this listing. Is it still available?"
                                    required></textarea>
                            </div>
                            <input type="hidden" id="listing-id" value="{{ $listing->id }}">
                            <input type="hidden" id="admin-id" value="{{ $listing->landlord_id }}">
                            <div class="flex gap-3">
                                <button type="button" onclick="closeAdminModal()" 
                                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize map for this listing
    @if($listing->latitude && $listing->longitude)
    var listingMap = L.map('listing-map').setView([{{ $listing->latitude }}, {{ $listing->longitude }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(listingMap);
    
    L.marker([{{ $listing->latitude }}, {{ $listing->longitude }}]).addTo(listingMap)
        .bindPopup('<b>{{ addslashes($listing->title) }}</b><br>{{ addslashes($listing->location) }}')
        .openPopup();
    @endif

    // Copy listing URL function
    function shareListing() {
        const url = window.location.href;
        const shareBtnText = document.getElementById('share-btn-text');
        const originalText = shareBtnText.textContent;
        
        // Try modern clipboard API first
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(function() {
                showShareSuccess(shareBtnText, originalText);
            }).catch(function(err) {
                console.error('Clipboard API failed:', err);
                fallbackCopy(url, shareBtnText, originalText);
            });
        } else {
            fallbackCopy(url, shareBtnText, originalText);
        }
    }
    
    function fallbackCopy(text, btnElement, originalText) {
        // Create temporary textarea for fallback
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showShareSuccess(btnElement, originalText);
        } catch (err) {
            console.error('Fallback copy failed:', err);
            alert('Unable to copy URL automatically. Please copy this link manually: ' + text);
        }
        
        document.body.removeChild(textArea);
    }
    
    function showShareSuccess(btnElement, originalText) {
        btnElement.textContent = 'Copied!';
        btnElement.parentElement.classList.add('bg-green-50', 'dark:bg-green-900', 'border-green-300', 'dark:border-green-600');
        
        setTimeout(function() {
            btnElement.textContent = originalText;
            btnElement.parentElement.classList.remove('bg-green-50', 'dark:bg-green-900', 'border-green-300', 'dark:border-green-600');
        }, 2000);
    }
    
    // Admin Contact Modal Functions
    function showAdminContactModal() {
        const modal = document.getElementById('admin-contact-modal');
        const content = document.getElementById('admin-modal-content');
        modal.classList.remove('hidden');
        
        // Animation
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeAdminModal() {
        const modal = document.getElementById('admin-contact-modal');
        const content = document.getElementById('admin-modal-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Close modal on outside click
    document.getElementById('admin-contact-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAdminModal();
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('admin-contact-modal');
            if (!modal.classList.contains('hidden')) {
                closeAdminModal();
            }
        }
    });
    
    // Submit admin contact form
    function submitAdminContact(event) {
        event.preventDefault();
        
        const message = document.getElementById('admin-message').value;
        const listingId = document.getElementById('listing-id').value;
        const adminId = document.getElementById('admin-id').value;
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalBtnContent = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
        
        // Send via AJAX to a new endpoint
        fetch('/contact-admin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                message: message,
                listing_id: listingId,
                admin_id: adminId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Sent!';
                submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                submitBtn.classList.add('bg-green-600');
                
                setTimeout(() => {
                    closeAdminModal();
                    document.getElementById('admin-message').value = '';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnContent;
                    submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    submitBtn.classList.remove('bg-green-600');
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            alert('Failed to send message. Please try again or contact support.');
        });
    }
</script>
@endpush
@endsection
