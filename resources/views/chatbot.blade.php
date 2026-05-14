@extends('layouts.app')

@section('content')
@if(auth()->check())
<div class="min-h-screen bg-cover bg-center bg-fixed dark:bg-gray-900" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
    <div class="bg-black bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-80 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Map Section - NOW AT TOP -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-xl overflow-hidden border border-white/20 mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Roommate Locations
                    </h2>
                </div>
                <div class="p-4">
                    <!-- Map Search Bar -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" id="mapSearchInput" placeholder="Search location..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button onclick="searchMapLocation()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                        <button onclick="getMyLocation()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Your Location
                        </button>
                    </div>
                    <!-- Google Map Container -->
                    <div id="chatbot-map" style="height: 400px; width: 100%; border-radius: 12px; position: relative;">
                        <div id="mapLoading" class="hidden absolute inset-0 bg-white/80 flex items-center justify-center rounded-xl" style="z-index: 1000;">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DeepSeek Assistant Chat Container -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-2xl overflow-hidden border border-white/20">
                <!-- Chat Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 text-white p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 dark:bg-gray-700/50 backdrop-blur-sm rounded-full flex items-center justify-center mr-3 border border-white/30 dark:border-gray-600">
                            <svg class="w-6 h-6 text-white dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold">DeepSeek Assistant</h2>
                            <p class="text-xs text-white/80">🟢 Connected to DeepSeek AI • Ask anything!</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages - With independent scroll -->
                <div id="chatMessagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-white/5 scroll-smooth" style="max-height: 400px; min-height: 300px; scrollbar-width: thin; scrollbar-color: #888 #f1f1f1;">
                    <!-- Welcome Message -->
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 border-2 border-white/20 dark:border-gray-600">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="bg-blue-600 dark:bg-gray-700 rounded-2xl p-4 max-w-sm shadow-lg border border-blue-500 dark:border-gray-600">
                            <p class="text-sm font-medium text-white">👋 Hello! I'm your AI Roommate Assistant! I can help you with:</p>
                            <ul class="text-sm text-white/90 mt-2 space-y-1">
                                <li>• 🏠 Finding compatible roommates</li>
                                <li>• 📝 Profile optimization tips</li>
                                <li>• 📍 Location-based roommate search</li>
                                <li>• 🤖 Matching algorithm questions</li>
                                <li>• 💡 General roommate advice</li>
                            </ul>
                            <p class="text-sm text-white/90 mt-2">Type <strong class="text-yellow-300">"tips on improving your profile"</strong> to see options 1-4, or ask me anything!</p>
                        </div>
                    </div>
                </div>

            <!-- Quick Actions -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-100 dark:bg-gray-800">
                <div class="flex flex-wrap gap-2 mb-3">
                    <button data-message="Find compatible roommates" 
                            class="quick-action-btn px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-700 dark:to-blue-800 text-gray-900 dark:text-gray-100 font-semibold rounded-full text-sm hover:from-blue-600 hover:to-blue-700 dark:hover:from-blue-800 dark:hover:to-blue-900 transition-all duration-200 transform hover:scale-105 shadow-lg cursor-pointer border border-blue-400/50 dark:border-blue-600/50">
                        Find Roommates
                    </button>
                    <button data-message="tips on improving your profile" 
                            class="quick-action-btn px-4 py-2 bg-gradient-to-r from-sky-500 to-blue-500 dark:from-sky-700 dark:to-blue-700 text-gray-900 dark:text-gray-100 font-semibold rounded-full text-sm hover:from-sky-600 hover:to-blue-600 dark:hover:from-sky-800 dark:hover:to-blue-800 transition-all duration-200 transform hover:scale-105 shadow-lg cursor-pointer border border-sky-400/50 dark:border-sky-600/50">
                        Profile Tips
                    </button>
                    <button onclick="searchDagupan()" class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200 transition-colors">
                        🔍 Dagupan Search
                    </button>
                    <button onclick="sendQuickMessage('How does matching work?')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-colors">
                        Matching Info
                    </button>
                    <button data-message="Roommate advice" 
                            class="quick-action-btn px-4 py-2 bg-gradient-to-r from-blue-400 to-cyan-500 dark:from-blue-600 dark:to-cyan-600 text-gray-900 dark:text-gray-100 font-semibold rounded-full text-sm hover:from-blue-500 hover:to-cyan-600 dark:hover:from-blue-700 dark:hover:to-cyan-700 transition-all duration-200 transform hover:scale-105 shadow-lg cursor-pointer border border-blue-400/50 dark:border-blue-600/50">
                        Advice
                    </button>
                    <button data-message="Safety guidelines" 
                            class="quick-action-btn px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-500 dark:from-blue-800 dark:to-indigo-700 text-gray-900 dark:text-gray-100 font-semibold rounded-full text-sm hover:from-blue-700 hover:to-indigo-600 dark:hover:from-blue-900 dark:hover:to-indigo-800 transition-all duration-200 transform hover:scale-105 shadow-lg cursor-pointer border border-indigo-400/50 dark:border-indigo-600/50">
                        Safety Info
                    </button>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="border-t border-white/10 dark:border-gray-700 p-4 bg-gray-800/70 dark:bg-gray-800/90">
                <form id="chatForm" class="flex items-center space-x-3" onsubmit="return false;">
                    <input type="text" 
                           id="messageInput" 
                           placeholder="Type your message..." 
                           class="flex-1 px-4 py-3 border border-white/20 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white/90 dark:bg-gray-700 backdrop-blur-sm text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 shadow-inner"
                           autocomplete="off">
                    <button type="button" 
                            id="sendButton"
                            class="px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-900 dark:hover:to-blue-950 transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center cursor-pointer border border-blue-500/50 dark:border-blue-700/50">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md rounded-xl shadow-xl p-6 border border-white/20 dark:border-gray-700 hover:bg-white/20 dark:hover:bg-gray-800/70 transition-all duration-300 hover:transform hover:scale-105">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white dark:text-gray-100 mb-2">Smart Matching</h3>
                <p class="text-white/70 dark:text-gray-300 text-sm">Our AI algorithm analyzes your preferences to find most compatible roommates.</p>
            </div>

            <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md rounded-xl shadow-xl p-6 border border-white/20 dark:border-gray-700 hover:bg-white/20 dark:hover:bg-gray-800/70 transition-all duration-300 hover:transform hover:scale-105">
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white dark:text-gray-100 mb-2">Verified Profiles</h3>
                <p class="text-white/70 dark:text-gray-300 text-sm">All roommate profiles are verified to ensure authenticity and safety.</p>
            </div>

            <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md rounded-xl shadow-xl p-6 border border-white/20 dark:border-gray-700 hover:bg-white/20 dark:hover:bg-gray-800/70 transition-all duration-300 hover:transform hover:scale-105">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white dark:text-gray-100 mb-2">Instant Matching</h3>
                <p class="text-white/70 dark:text-gray-300 text-sm">Get matched with compatible roommates instantly with our real-time algorithm.</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="min-h-screen bg-cover bg-center bg-fixed dark:bg-gray-900" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
    <div class="bg-black bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-80 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-md rounded-xl shadow-xl p-8 border border-white/20 dark:border-gray-700 text-center">
                <div class="text-white dark:text-gray-100">
                    <h1 class="text-3xl font-bold mb-4">Authentication Required</h1>
                    <p class="text-xl mb-6">Please log in to access Roommate Assistant</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-900 dark:hover:to-blue-950 transition-all duration-200 transform hover:scale-105">
                        Go to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@if(auth()->check())
@push('styles')
<style>
    /* Fix Leaflet popup display */
    .leaflet-popup {
        z-index: 1000 !important;
    }
    .leaflet-popup-content-wrapper {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        width: auto !important;
    }
    .leaflet-popup-tip {
        background: white !important;
    }
    .roommate-popup .leaflet-popup-content-wrapper {
        background: transparent !important;
    }
    /* Ensure markers are clickable */
    .custom-roommate-marker {
        pointer-events: auto !important;
    }
    .marker-container {
        pointer-events: auto !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Geolocation functionality
let userLocation = null;
let userMarker = null;
let roommateMarkers = [];

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            console.log('Location detected:', userLocation);
        }, error => {
            console.error('Geolocation error:', error);
            userLocation = null;
        });
    } else {
        console.log('Geolocation not supported');
        userLocation = null;
    }
}

getLocation();

document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessagesContainer = document.getElementById('chatMessagesContainer');
    
    // Center map with smooth animation
    map.flyTo([lat, lng], zoom, {
        duration: 1.5,
        easeLinearity: 0.25
    });
    
    // Send message function - STREAMING version (ChatGPT-like)
    async function sendMessage(message) {
        console.log('Sending message:', message);
        
        // Add user message
        addMessage(message, 'user');
        
        // Show typing indicator
        showTypingIndicator();
        
        let assistantMessageDiv = null;
        let assistantTextContainer = null;
        let fullContent = '';
        let hasReceivedData = false;

        try {
            const response = await fetch('{{ route("chat.api.stream") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Network response was not ok');
            }

            // Create the bot message bubble immediately with a "Thinking" state
            assistantMessageDiv = addMessage('', 'bot');
            assistantTextContainer = assistantMessageDiv.querySelector('p');
            assistantTextContainer.innerHTML = '<span class="text-gray-400 italic">Thinking...</span>';
            
            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            
            while (true) {
                const { done, value } = await reader.read();
                if (done) break;

                // Remove typing indicator on first chunk of data
                if (!hasReceivedData) {
                    removeTypingIndicator();
                    assistantTextContainer.innerHTML = '';
                    hasReceivedData = true;
                }

                const chunk = decoder.decode(value, { stream: true });
                const lines = chunk.split('\n');

                for (const line of lines) {
                    const trimmedLine = line.trim();
                    if (trimmedLine === '' || trimmedLine === 'data: [DONE]') continue;
                    
                    if (trimmedLine.startsWith('data: ')) {
                        try {
                            const jsonStr = trimmedLine.substring(6);
                            const data = JSON.parse(jsonStr);
                            const content = data.choices[0].delta.content || '';
                            if (content) {
                                fullContent += content;
                                // Basic formatting for streaming
                                assistantTextContainer.innerHTML = fullContent
                                    .replace(/\n/g, '<br>')
                                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                                    .replace(/\*(.*?)\*/g, '<em>$1</em>');
                                    
                                chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                            }
                        } catch (e) {
                            // Partial JSON skip
                        }
                    }
                }
            }
            
            // Final scroll
            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

        } catch (error) {
            console.error('Streaming error:', error);
            removeTypingIndicator();
            
            if (assistantTextContainer && !hasReceivedData) {
                assistantTextContainer.innerHTML = '<span class="text-red-500">Sorry, I encountered an error. Trying alternative...</span>';
            }
            
            // Fallback to non-streaming API
            fetch('{{ route("chat.api.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                if (data.reply) {
                    if (assistantMessageDiv) assistantMessageDiv.remove();
                    typeMessage(data.reply, 'bot');
                } else {
                    const fallback = generateAIResponse(message);
                    if (assistantMessageDiv) assistantMessageDiv.remove();
                    typeMessage(fallback, 'bot');
                }
            })
            .catch(() => {
                const fallback = generateAIResponse(message);
                if (assistantMessageDiv) assistantMessageDiv.remove();
                typeMessage(fallback, 'bot');
            });
        }
    }
    
    // Remove highlight after 8 seconds
    setTimeout(() => {
        if (locationCircle) {
            locationCircle.setStyle({ fillOpacity: 0.1 });
        }
    }, 4000);
    
    // Remove marker after 10 seconds but keep circle
    setTimeout(() => {
        searchMarkers.forEach(marker => map.removeLayer(marker));
        searchMarkers = [];
    }, 10000);
}

// Add pulse animation style
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
`;
document.head.appendChild(style);

// Sample roommate locations around Dagupan City (Arellano St area)
var roommates = [
    { id: 1, lat: 16.0435, lng: 120.3345, name: "Student Near Arellano St", type: "Looking for roommate", budget: "₱2,500-3,500" },
    { id: 2, lat: 16.0440, lng: 120.3350, name: "Roommate - Arellano Area", type: "Has apartment", budget: "₱2,500" },
    { id: 3, lat: 16.0425, lng: 120.3330, name: "Downtown Dagupan", type: "Looking for roommate", budget: "₱2,800" },
    { id: 4, lat: 16.0438, lng: 120.3342, name: "Near Arellano St", type: "Private room", budget: "₱4,000" },
    { id: 5, lat: 16.0450, lng: 120.3360, name: "Dagupan City Center", type: "2BR House", budget: "₱8,000" }
];

// Add roommate markers
roommates.forEach(function(roommate) {
    var marker = L.marker([roommate.lat, roommate.lng], {icon: roommateIcon}).addTo(map);
    
    marker.bindPopup(
        '<div style="min-width: 180px;">' +
        '<b style="font-size: 14px; color: #1F2937;">' + roommate.name + '</b><br>' +
        '<span style="color: #6366F1; font-size: 12px; font-weight: 600;">' + roommate.type + '</span><br>' +
        '<span style="color: #4B5563; font-size: 12px;">Budget: ' + roommate.budget + '</span>' +
        '</div>'
    );
    
    roommateMarkers.push(marker);
});

// Fit bounds to show all markers
if (roommateMarkers.length > 0) {
    var group = new L.featureGroup(roommateMarkers);
    map.fitBounds(group.getBounds().pad(0.1));
}

// Center on user location function
function centerOnUserLocation() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;
            
            // Remove existing user marker
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            
            // Add user location marker
            userMarker = L.marker([userLat, userLng], {icon: userIcon}).addTo(map);
            userMarker.bindPopup('<b>Your Location</b>').openPopup();
            
            // Center map on user location
            map.setView([userLat, userLng], 15);
            
            // Update userLocation variable
            userLocation = { lat: userLat, lng: userLng };
            
        }, function(error) {
            alert('Unable to get your location. Please check your browser permissions.');
            console.error('Geolocation error:', error);
        });
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

// Try to get user location on page load
if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(function(position) {
        var userLat = position.coords.latitude;
        var userLng = position.coords.longitude;
        
        // Add user location marker
        if (!userMarker) {
            userMarker = L.marker([userLat, userLng], {icon: userIcon}).addTo(map);
            userMarker.bindPopup('<b>Your Location</b>');
        }
        
        userLocation = { lat: userLat, lng: userLng };
    }, function(error) {
        console.log('Auto-geolocation not available');
    });
}

// Simple and reliable chatbot implementation
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chatbot initializing...');
    
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    
    if (!chatMessages || !messageInput || !sendButton) {
        console.error('Required elements not found');
        return;
    }
        // Add message to chat
    function addMessage(message, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start mb-4';
        
        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="ml-auto mr-2 max-w-xs">
                    <div class="bg-blue-600 dark:bg-blue-700 text-white rounded-lg p-3 shadow-sm">
                        <p class="text-sm font-medium whitespace-pre-line">${message}</p>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}</div>
                </div>
                <div class="w-8 h-8 bg-gray-400 dark:bg-gray-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            `;
        } else {
            // Process markdown-like formatting
            let formattedMessage = message
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/•\s/g, '<br/>• ')
                .replace(/🏠|📝|🤖|💡|🛡️|🔍|📍|💰|🎯|📊|🚀|⚠️|✅|🚫|📈|💬|🔐|🔒|📱|🤝|⏰|🧹|👥|🎓|📸|📋|🔧|🌟/g, '<span class="text-lg">$&</span>')
                .replace(/\n\n/g, '<br/><br/>')
                .replace(/\n/g, '<br/>');
            
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="mr-2 max-w-xs">
                    <div class="bg-blue-600 dark:bg-gray-700 rounded-lg p-3 shadow-sm border border-blue-500 dark:border-gray-600">
                        <div class="text-sm text-white leading-relaxed font-medium">${formattedMessage}</div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}</div>
                </div>
            `;
        }
        
        chatMessagesContainer.appendChild(messageDiv);
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

        return messageDiv; // Return the created element for streaming
    }
    
    // Type out message character by character for a "realtime" feel
    function typeMessage(text, sender) {
        const messageDiv = addMessage('', sender);
        const textContainer = messageDiv.querySelector('p');
        let index = 0;
        
        // Speed up for longer messages
        const speed = text.length > 200 ? 5 : 15;
        
        function type() {
            if (index < text.length) {
                textContainer.innerHTML += text.charAt(index);
                index++;
                chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                setTimeout(type, speed);
            }
        }
        
        type();
    }
    
    // Show typing indicator
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.className = 'flex items-start mb-4';
        typingDiv.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div class="bg-blue-600 dark:bg-gray-700 rounded-lg p-3 shadow-sm border border-blue-500 dark:border-gray-600">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        `;
        
        chatMessagesContainer.appendChild(typingDiv);
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
    }
    
    // Remove typing indicator
    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    // Handle sending messages
    async function sendMessage(message) {
        if (!message.trim()) return;

        addMessage(message, 'user');
        messageInput.value = '';

        showTypingIndicator();

        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    message: message,
                    location: userLocation
                })
            });

            const data = await response.json();

            removeTypingIndicator();
            
            // Handle both 'reply' (from generateAIResponse) and 'message' (from generateResponse)
            const botMessage = data.reply || data.message || "I'm not sure how to help with that.";
            addMessage(botMessage, 'bot');
            
            // If location data is provided, show it prominently on the map
            if (data.location && data.location.lat && data.location.lng) {
                console.log('Showing location on map:', data.location);
                
                // Extract location name from the user's message
                const locationMatch = message.match(/(?:in|near|at|around|places?\s+(?:in|near|at|around))\s+([a-zA-Z\s]+)/i);
                const locationName = locationMatch ? locationMatch[1].trim() : 'Searched Location';
                
                // Show location with enhanced visual feedback
                showLocationOnMap(
                    data.location.lat, 
                    data.location.lng, 
                    locationName,
                    data.location.zoom || 15
                );
                
                // Add a visual indicator in the chat
                const mapIndicator = document.createElement('div');
                mapIndicator.className = 'flex items-start mb-2 mt-1';
                mapIndicator.innerHTML = `
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-2 flex-shrink-0 animate-pulse">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-1.414 0l-5.657-5.657a1 1 0 010-1.414l5.657-5.657a1 1 0 011.414 0l5.657 5.657a1 1 0 010 1.414l-5.657 5.657a1 1 0 01-1.414 0z" />
                        </svg>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-2 shadow-sm border border-green-200 max-w-xs">
                        <p class="text-xs text-green-700 font-medium">🗺️ Map updated! Showing ${locationName}</p>
                    </div>
                `;
                chatMessages.appendChild(mapIndicator);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                // Remove indicator after 5 seconds
                setTimeout(() => {
                    mapIndicator.remove();
                }, 5000);
            }

        } catch (error) {
            removeTypingIndicator();
            addMessage("⚠️ Error connecting to AI.", 'bot');
        }
    }
    
    // Setup quick action buttons
    const quickButtons = document.querySelectorAll('.quick-action-btn');
    quickButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = button.getAttribute('data-message');
            sendMessage(message);
        });
    });
    
    // Setup send button
    sendButton.addEventListener('click', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
        } else {
            messageInput.classList.add('animate-pulse', 'border-red-500', 'ring-2', 'ring-red-200');
            messageInput.placeholder = 'Please type a message...';
            setTimeout(() => {
                messageInput.classList.remove('animate-pulse', 'border-red-500', 'ring-2', 'ring-red-200');
                messageInput.placeholder = 'Type your message...';
            }, 2000);
        }
    });
    
    // Map Search Functionality
    const mapSearchInput = document.getElementById('mapSearchInput');
    const mapSearchBtn = document.getElementById('mapSearchBtn');
    const searchResults = document.getElementById('searchResults');
    const resultsCount = document.getElementById('resultsCount');
    
    // Location coordinates for Pangasinan areas
    const locationCoords = {
        'dagupan': { lat: 16.0430, lng: 120.3333, zoom: 14 },
        'alaminos': { lat: 16.1604, lng: 119.9887, zoom: 14 },
        'san carlos': { lat: 15.9281, lng: 120.3489, zoom: 14 },
        'urdaneta': { lat: 15.9758, lng: 120.5708, zoom: 14 },
        'lingayen': { lat: 16.0215, lng: 120.2320, zoom: 14 },
        'calasiao': { lat: 16.0121, lng: 120.3564, zoom: 14 },
        'mangaldan': { lat: 16.0700, lng: 120.4030, zoom: 14 },
        'mapandan': { lat: 16.0239, lng: 120.4546, zoom: 14 },
        'san fabian': { lat: 16.1566, lng: 120.4490, zoom: 14 },
        'san jacinto': { lat: 16.0726, lng: 120.4486, zoom: 14 },
        'binmaley': { lat: 16.0321, lng: 120.2720, zoom: 14 },
        'malasiqui': { lat: 15.9173, lng: 120.4165, zoom: 14 },
        'bayambang': { lat: 15.8109, lng: 120.4556, zoom: 14 },
        'basista': { lat: 15.8514, lng: 120.3996, zoom: 14 },
        'villasis': { lat: 15.9007, lng: 120.5833, zoom: 14 }
    };
    
    // Function to search for roommates by location
    async function searchRoommatesByLocation(location) {
        try {
            // Show loading state
            mapSearchBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            const response = await fetch(`/roommates/search?location=${encodeURIComponent(location)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (!response.ok) throw new Error('Search failed');
            
            const data = await response.json();
            
            // Clear existing markers
            if (window.roommateMarkers) {
                window.roommateMarkers.forEach(marker => map.removeLayer(marker));
            }
            window.roommateMarkers = [];
            
            // Get location coordinates
            const locationKey = location.toLowerCase().trim();
            const coords = locationCoords[locationKey] || locationCoords['dagupan'];
            
            // Center map on location
            map.setView([coords.lat, coords.lng], coords.zoom);
            
            // Add markers for each roommate
            if (data.roommates && data.roommates.length > 0) {
                data.roommates.forEach((roommate, index) => {
                    // Generate random offset for markers so they don't overlap
                    const offset = 0.002;
                    const lat = coords.lat + (Math.random() - 0.5) * offset;
                    const lng = coords.lng + (Math.random() - 0.5) * offset;
                    
                    const marker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'custom-roommate-marker',
                            html: `<div class="marker-container w-10 h-10 bg-blue-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg cursor-pointer hover:bg-blue-600 transition-colors" style="pointer-events: auto;">
                                    <img src="${roommate.avatar_url || '/images/default-avatar.png'}" class="w-7 h-7 rounded-full object-cover pointer-events-none" />
                                   </div>`,
                            iconSize: [40, 40],
                            iconAnchor: [20, 20]
                        })
                    }).addTo(map);
                    
                    // Create popup content - simplified compact card
                    const popupContent = `
                        <div class="p-3 w-[220px] bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700">
                            <!-- Header -->
                            <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">
                                <img src="${roommate.avatar_url || '/images/default-avatar.png'}" class="w-10 h-10 rounded-full object-cover border-2 border-blue-200 dark:border-blue-700" />
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-sm text-gray-900 dark:text-white truncate">${roommate.name}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">${roommate.university || 'Student'}</p>
                                </div>
                                ${roommate.looking_for_roommate ? '<span class="px-2 py-0.5 text-[10px] bg-pink-500 text-white rounded-full shrink-0">Looking</span>' : ''}
                            </div>
                            
                            <!-- Quick Info -->
                            <div class="grid grid-cols-2 gap-1.5 mb-3 text-xs">
                                ${roommate.gender ? `<div class="bg-gray-50 dark:bg-gray-700 rounded px-2 py-1"><span class="text-gray-500 dark:text-gray-400">Gender:</span> <span class="text-gray-800 dark:text-gray-200 font-medium">${roommate.gender}</span></div>` : ''}
                                ${roommate.age ? `<div class="bg-gray-50 dark:bg-gray-700 rounded px-2 py-1"><span class="text-gray-500 dark:text-gray-400">Age:</span> <span class="text-gray-800 dark:text-gray-200 font-medium">${roommate.age}</span></div>` : ''}
                                ${roommate.budget_max ? `<div class="bg-gray-50 dark:bg-gray-700 rounded px-2 py-1 col-span-2"><span class="text-gray-500 dark:text-gray-400">Budget:</span> <span class="text-gray-800 dark:text-gray-200 font-medium">PHP ${roommate.budget_min || 0} - ${roommate.budget_max}</span></div>` : ''}
                            </div>
                            
                            <!-- View Profile Button -->
                            <a href="/roommates/${roommate.id}" class="block w-full text-center bg-blue-500 dark:bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700 transition-colors text-sm font-medium">
                                View Full Profile
                            </a>
                        </div>
                    `;
                    
                    // Bind popup with options and open on click
                    marker.bindPopup(popupContent, {
                        maxWidth: 260,
                        minWidth: 220,
                        className: 'roommate-popup',
                        closeButton: true,
                        autoPan: true,
                        autoPanPadding: [50, 50]
                    });
                    
                    // Ensure popup opens on marker click
                    marker.on('click', function() {
                        this.openPopup();
                    });
                    
                    window.roommateMarkers.push(marker);
                });
                
                // Show results count
                resultsCount.textContent = `Found ${data.roommates.length} roommate${data.roommates.length !== 1 ? 's' : ''} in ${location}`;
                searchResults.classList.remove('hidden');
            } else {
                resultsCount.textContent = `No roommates found in ${location}`;
                searchResults.classList.remove('hidden');
            }
            
        } catch (error) {
            console.error('Search error:', error);
            resultsCount.textContent = 'Error searching. Please try again.';
            searchResults.classList.remove('hidden');
        } finally {
            // Reset button
            mapSearchBtn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>`;
        }
    }
    
    // Generate AI response - Smarter Local Intelligence
    function generateAIResponse(message) {
        const msg = message.toLowerCase();
        
        // Direct Conversational Matching
        if (msg.includes('hello') || msg.includes('hi') || msg.includes('hey')) {
            return "Hello! I'm your Find My Roommate AI assistant. How can I help you today?";
        }
        
        if (msg.includes('who are you') || msg.includes('what is your name')) {
            return "I am the Find My Roommate AI Assistant. I help people find perfect living arrangements and roommates in Pangasinan!";
        }

        if (msg.includes('how are you')) {
            return "I'm doing great! Ready to help you find a roommate or answer any questions you have.";
        }

        if (msg.includes('thank') || msg.includes('salamat')) {
            return "You're very welcome! Is there anything else I can assist you with?";
        }
        
        const lowerMessage = message.toLowerCase();
        
        // Roommate finding responses
        if (lowerMessage.includes('find') || lowerMessage.includes('roommate') || lowerMessage.includes('match')) {
            return "I can help you find compatible roommates! Our AI algorithm analyzes your lifestyle preferences, budget, schedule, and other factors to match you with best candidates. Here's how to get started:\n\n1️⃣ Complete your profile with detailed preferences\n2️⃣ Set your budget range and lifestyle preferences\n3️⃣ Be specific about your ideal roommate qualities\n4️⃣ Browse roommate listings with AI compatibility scores\n\nWould you like tips on improving your profile to get better matches?";
        }
    });
    
    // Setup enter key for map search
    mapSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const location = mapSearchInput.value.trim();
            if (location) {
                searchRoommatesByLocation(location);
            }
        }
    });
    
    // Setup Enter key
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (message) {
                sendMessage(message);
            }
        }
    });
    
    // Focus input
    messageInput.focus();
    
    console.log('Chatbot initialized successfully!');
});

// Leaflet Map Variables
let leafletMap = null;
let markers = [];
let currentPopup = null;

// City coordinates for Pangasinan
const cityCoordinates = {
    'dagupan': { lat: 16.0433, lng: 120.3333 },
    'san carlos': { lat: 15.9281, lng: 120.3479 },
    'urdaneta': { lat: 15.9759, lng: 120.5717 },
    'alaminos': { lat: 16.1554, lng: 119.9820 },
    'lingayen': { lat: 16.0218, lng: 120.2319 },
    'calasiao': { lat: 16.0115, lng: 120.3567 },
    'mangaldan': { lat: 16.0700, lng: 120.4036 },
    'binmaley': { lat: 16.0324, lng: 120.2695 },
    'bayambang': { lat: 15.8087, lng: 120.4593 },
    'bolinao': { lat: 16.3897, lng: 119.8943 },
    'san fabian': { lat: 16.1556, lng: 120.4494 },
    'villasis': { lat: 15.9031, lng: 120.5914 },
    'rosales': { lat: 15.8953, lng: 120.6328 },
    'malasiqui': { lat: 15.9167, lng: 120.4167 },
    'basista': { lat: 15.8537, lng: 120.4006 },
    'san jacinto': { lat: 16.0706, lng: 120.4392 },
    'mapandan': { lat: 16.0170, lng: 120.4537 },
    'mabini': { lat: 16.0685, lng: 119.9331 },
    'burgos': { lat: 16.0508, lng: 119.8656 },
    'dasol': { lat: 15.9903, lng: 119.8808 }
};

// Initialize Leaflet Map
function initLeafletMap() {
    const mapContainer = document.getElementById('chatbot-map');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }
    
    // Avoid double initialization
    if (leafletMap !== null) {
        console.log('Map already initialized');
        return;
    }
    
    try {
        console.log('Initializing Leaflet Map...');
        
        // Center on Pangasinan province
        leafletMap = L.map('chatbot-map', {
            center: [15.9, 120.3],
            zoom: 10,
            zoomControl: true
        });
        
        // Add OpenStreetMap tiles (free, no API key needed)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(leafletMap);
        
        // Force a resize to fix grey tiles issue
        setTimeout(() => {
            leafletMap.invalidateSize();
        }, 500);
        
        // Load users on map
        loadUsersOnMap();
        
        console.log('Leaflet Map initialized successfully');
    } catch (error) {
        console.error('Error initializing Leaflet Map:', error);
    }
}

// Load users on map
function loadUsersOnMap(locationFilter = '') {
    const loadingEl = document.getElementById('mapLoading');
    if (loadingEl) loadingEl.classList.remove('hidden');
    
    if (!leafletMap) {
        console.warn('Cannot load users: Map not initialized');
        return;
    }
    
    // Clear existing markers
    markers.forEach(marker => leafletMap.removeLayer(marker));
    markers = [];
    
    // Fetch users from API
    const url = locationFilter 
        ? `{{ route('chatbot.map-users') }}?location=${encodeURIComponent(locationFilter)}`
        : '{{ route('chatbot.map-users') }}';
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (loadingEl) loadingEl.classList.add('hidden');
            
            if (data.success && data.users) {
                data.users.forEach(user => {
                    addUserMarker(user);
                });
                
                // If we have users and a filter, center on the first user
                if (locationFilter && data.users.length > 0) {
                    const firstUser = data.users[0];
                    leafletMap.setView([firstUser.lat, firstUser.lng], 13);
                }
            }
        })
        .catch(error => {
            if (loadingEl) loadingEl.classList.add('hidden');
            console.error('Error loading users:', error);
        });
}

// Add user marker to map
function addUserMarker(user) {
    if (!leafletMap) return;
    
    // Create custom icon with avatar image or initials
    const avatarHtml = user.avatar 
        ? `<img src="${user.avatar}" style="width: 40px; height: 40px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); object-fit: cover;" onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\\'background: linear-gradient(135deg, #4f46e5, #7c3aed); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; font-weight: bold;\\'>${user.name.charAt(0).toUpperCase()}</div>';">`
        : `<div style="background: linear-gradient(135deg, #4f46e5, #7c3aed); width: 40px; height: 40px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; font-weight: bold;">${user.name.charAt(0).toUpperCase()}</div>`;
    
    const customIcon = L.divIcon({
        className: 'custom-user-marker',
        html: avatarHtml,
        iconSize: [40, 40],
        iconAnchor: [20, 20]
    });
    
    const marker = L.marker([user.lat, user.lng], { icon: customIcon }).addTo(leafletMap);
    
    // Create popup content
    const popupContent = `
        <div style="min-width: 220px; padding: 4px;">
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                ${user.avatar ? `<img src="${user.avatar}" style="width: 45px; height: 45px; border-radius: 50%; margin-right: 12px; object-fit: cover; border: 2px solid #4f46e5;">` : `<div style="width: 45px; height: 45px; border-radius: 50%; margin-right: 12px; background: linear-gradient(135deg, #4f46e5, #7c3aed); display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; font-weight: bold;">${user.name.charAt(0).toUpperCase()}</div>`}
                <div>
                    <h3 style="margin: 0; font-size: 15px; font-weight: bold; color: #4f46e5;">${user.name}</h3>
                    <p style="margin: 2px 0 0 0; font-size: 12px; color: #666;">${user.age || '?'} years • ${user.gender || 'N/A'}</p>
                </div>
            </div>
            <p style="margin: 4px 0; font-size: 13px;"><strong>📍</strong> ${user.location}</p>
            <p style="margin: 4px 0; font-size: 13px;"><strong>💰</strong> ${user.budget}</p>
            <p style="margin: 8px 0; font-size: 12px; color: #555; line-height: 1.4; max-height: 60px; overflow: hidden;">${user.bio ? (user.bio.substring(0, 80) + (user.bio.length > 80 ? '...' : '')) : 'No bio available'}</p>
            <a href="${user.profile_url}" target="_blank" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background: #4f46e5; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; text-align: center; width: 100%; box-sizing: border-box;">View Profile</a>
        </div>
    `;
    
    marker.bindPopup(popupContent);
    
    marker.on('click', () => {
        if (currentPopup) {
            currentPopup.closePopup();
        }
        currentPopup = marker;
    });
    
    markers.push(marker);
}

// Search map location
function searchMapLocation() {
    const searchInput = document.getElementById('mapSearchInput');
    const query = searchInput.value.trim().toLowerCase();
    
    if (!query) return;
    
    // Check if it's a known city
    let foundCoords = null;
    for (const [city, coords] of Object.entries(cityCoordinates)) {
        if (query.includes(city)) {
            foundCoords = coords;
            break;
        }
    }
    
    if (foundCoords) {
        leafletMap.setView([foundCoords.lat, foundCoords.lng], 14);
        loadUsersOnMap(query);
    } else {
        // Try to search using Nominatim (OpenStreetMap geocoding)
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Pangasinan, Philippines')}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const result = data[0];
                    leafletMap.setView([result.lat, result.lon], 14);
                    loadUsersOnMap(query);
                } else {
                    // Fallback: just filter users
                    loadUsersOnMap(query);
                    alert('Location not found on map, but showing matching roommates if any.');
                }
            })
            .catch(() => {
                loadUsersOnMap(query);
            });
    }
}

// Get my current location
function getMyLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                leafletMap.setView([lat, lng], 14);
                
                // Add a marker for user's location
                const userIcon = L.divIcon({
                    className: 'user-location-marker',
                    html: `<div style="background: #22c55e; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.4);"></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });
                
                const userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(leafletMap);
                userMarker.bindPopup('<strong>Your Location</strong>').openPopup();
                markers.push(userMarker);
                
                // Find nearby users
                findNearbyUsers({ lat, lng });
            },
            () => {
                alert('Error: The Geolocation service failed or permission was denied.');
            }
        );
    } else {
        alert('Error: Your browser doesn\'t support geolocation.');
    }
}

// Find users near a location
function findNearbyUsers(center) {
    fetch('{{ route('chatbot.map-users') }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.users) {
                // Filter users within 10km radius
                const nearbyUsers = data.users.filter(user => {
                    const distance = calculateDistance(
                        center.lat, center.lng,
                        user.lat, user.lng
                    );
                    return distance <= 10; // 10km radius
                });
                
                if (nearbyUsers.length > 0) {
                    // Show only nearby users
                    markers.forEach(marker => leafletMap.removeLayer(marker));
                    markers = [];
                    nearbyUsers.forEach(user => addUserMarker(user));
                    alert(`Found ${nearbyUsers.length} roommate(s) within 10km of your location!`);
                } else {
                    alert('No roommates found within 10km of your location. Showing all roommates instead.');
                }
            }
        });
}

// Calculate distance between two coordinates (in km)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Earth's radius in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Search for roommates in Dagupan
function searchDagupan() {
    const dagupanCoords = cityCoordinates['dagupan'];
    
    leafletMap.setView([dagupanCoords.lat, dagupanCoords.lng], 14);
    document.getElementById('mapSearchInput').value = 'Dagupan';
    loadUsersOnMap('Dagupan');
    
    // Also update chat with results
    const messageInput = document.getElementById('messageInput');
    const chatForm = document.getElementById('chatForm');
    if (messageInput && chatForm) {
        messageInput.value = 'Show me roommates in Dagupan';
        chatForm.dispatchEvent(new Event('submit'));
    }
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Leaflet to be loaded (either from layout or local)
    function tryInitMap(retries = 0) {
        if (typeof L !== 'undefined') {
            initLeafletMap();
        } else if (retries < 10) {
            console.log('Leaflet not loaded yet, retrying... (' + (retries + 1) + '/10)');
            setTimeout(() => tryInitMap(retries + 1), 500);
        } else {
            console.error('Leaflet failed to load after 5 seconds');
        }
    }
    
    tryInitMap();
    
    // Search input enter key handler
    const searchInput = document.getElementById('mapSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchMapLocation();
            }
        });
    }
});
</script>

@push('styles')
<style>
    #chatbot-map { 
        z-index: 1 !important; 
        border-radius: 12px; 
        height: 400px; 
        width: 100%; 
        background-color: #f3f4f6; /* Solid background while loading */
        border: 1px solid rgba(255,255,255,0.1);
    }
    .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
    .leaflet-popup-content { margin: 12px; }
    .custom-user-marker { background: transparent !important; border: none !important; }
    #chatMessagesContainer { overflow-y: scroll !important; }
    #chatMessagesContainer::-webkit-scrollbar { width: 8px !important; display: block !important; }
    #chatMessagesContainer::-webkit-scrollbar-track { background: #e5e7eb; border-radius: 4px; margin: 4px; }
    #chatMessagesContainer::-webkit-scrollbar-thumb { background: #6b7280; border-radius: 4px; border: 2px solid #e5e7eb; }
    #chatMessagesContainer::-webkit-scrollbar-thumb:hover { background: #4b5563; }
    #chatMessagesContainer { scrollbar-width: thin !important; scrollbar-color: #6b7280 #e5e7eb !important; }
</style>
@endpush

