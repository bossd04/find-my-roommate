@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
    <div class="bg-black bg-opacity-50 min-h-screen py-8">
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
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mr-3 border border-white/30">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="bg-white/90 backdrop-blur-sm rounded-lg p-3 max-w-xs shadow-sm border border-white/20">
                            <p class="text-sm text-gray-800">Hello! I'm your roommate assistant. I can help you with:</p>
                            <ul class="text-sm text-gray-600 mt-2 space-y-1">
                                <li>• Finding compatible roommates</li>
                                <li>• Profile optimization tips</li>
                                <li>• Location-based roommate search</li>
                                <li>• Matching algorithm questions</li>
                                <li>• General roommate advice</li>
                            </ul>
                            <p class="text-sm text-gray-800 mt-2">Type <strong>"tips on improving your profile"</strong> and then select 1-4 for specific help, or ask me anything!</p>
                        </div>
                    </div>
                </div>

            <!-- Quick Actions -->
            <div class="border-t border-gray-200 p-4 bg-white">
                <div class="flex flex-wrap gap-2 mb-3">
                    <button onclick="sendQuickMessage('Find compatible roommates')" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm hover:bg-indigo-200 transition-colors">
                        Find Roommates
                    </button>
                    <button onclick="sendQuickMessage('tips on improving your profile')" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm hover:bg-purple-200 transition-colors">
                        Profile Tips
                    </button>
                    <button onclick="searchDagupan()" class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200 transition-colors">
                        🔍 Dagupan Search
                    </button>
                    <button onclick="sendQuickMessage('How does matching work?')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-colors">
                        Matching Info
                    </button>
                    <button onclick="sendQuickMessage('Roommate advice')" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm hover:bg-yellow-200 transition-colors">
                        Advice
                    </button>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="border-t border-gray-200 p-4 bg-white">
                <form id="chatForm" class="flex items-center space-x-2">
                    <input type="text" 
                           id="messageInput" 
                           placeholder="Type your message..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           autocomplete="off">
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Matching</h3>
                <p class="text-gray-600 text-sm">Our AI algorithm analyzes your preferences to find the most compatible roommates.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Profiles</h3>
                <p class="text-gray-600 text-sm">All roommate profiles are verified to ensure authenticity and safety.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Instant Matching</h3>
                <p class="text-gray-600 text-sm">Get matched with compatible roommates instantly with our real-time algorithm.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add custom CSS for typing indicator
const typingStyles = `
    <style>
        @keyframes typing-pulse {
            0%, 80%, 100% {
                opacity: 0.3;
                transform: scale(0.8);
            }
            40% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .typing-dot {
            animation: typing-pulse 1.4s infinite ease-in-out;
        }
        
        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }
    </style>
`;

document.head.insertAdjacentHTML('beforeend', typingStyles);

// Define sendQuickMessage globally before DOMContentLoaded
window.sendQuickMessage = function(message) {
    console.log('Quick message clicked:', message); // Debug log
    // Wait for DOM to be ready, then send message
    setTimeout(() => {
        const messageInput = document.getElementById('messageInput');
        const chatForm = document.getElementById('chatForm');
        if (messageInput && chatForm) {
            messageInput.value = message;
            chatForm.dispatchEvent(new Event('submit'));
        }
    }, 100);
};

document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessagesContainer = document.getElementById('chatMessagesContainer');
    
    // Form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
            messageInput.value = '';
        }
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
    
    // Add message to chat
    function addMessage(message, sender) {
        console.log('Adding message:', message, 'from:', sender); // Debug log
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start';
        
        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="ml-auto mr-2">
                    <div class="bg-indigo-600 text-white rounded-lg p-3 max-w-xs shadow-sm">
                        <p class="text-sm">${message}</p>
                    </div>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="bg-white rounded-lg p-3 max-w-xs shadow-sm">
                    <p class="text-sm text-gray-800">${message}</p>
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
        console.log('Showing typing indicator'); // Debug log
        
        // Remove any existing typing indicator first
        const existingIndicator = document.getElementById('typingIndicator');
        if (existingIndicator) {
            existingIndicator.remove();
        }
        
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.className = 'flex items-start';
        typingDiv.innerHTML = `
            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
                </div>
            </div>
        `;
        
        chatMessagesContainer.appendChild(typingDiv);
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
    }
    
    // Remove typing indicator
    function removeTypingIndicator() {
        console.log('Removing typing indicator'); // Debug log
        
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
            console.log('Typing indicator removed successfully');
        } else {
            console.log('No typing indicator found to remove');
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
        
        // Profile improvement responses
        if (lowerMessage.includes('profile') || lowerMessage.includes('improve') || lowerMessage.includes('optimize') || lowerMessage.includes('tips')) {
            return "Great question! Here are my top profile optimization tips:\n\n📸 **Photo**: Add a clear, friendly photo of yourself\n📝 **Complete Info**: Fill out all profile sections completely\n💰 **Budget**: Set realistic budget ranges (min-max)\n🏠 **Preferences**: Be specific about lifestyle, cleanliness, schedule\n🎓 **Education**: Add your university and course information\n📋 **Details**: Describe your ideal roommate clearly\n\n💡 **Pro Tip**: Complete profiles get 3x more matches! What specific area would you like help with?";
        }
        
        // Matching algorithm responses
        if (lowerMessage.includes('matching') || lowerMessage.includes('algorithm') || lowerMessage.includes('how does') || lowerMessage.includes('compatibility')) {
            return "Our AI matching algorithm is quite sophisticated! Here's how it works:\n\n🧮 **Scoring Breakdown**:\n• Lifestyle Compatibility: 25%\n• Schedule Alignment: 20%\n• Budget Compatibility: 20%\n• Cleanliness Standards: 15%\n• Age Proximity: 15%\n• University Connection: 10%\n\n🤖 **AI Analysis**: The system also considers:\n• Personality matches from preferences\n• Living habit compatibility\n• Social lifestyle alignment\n• Financial compatibility\n• Academic/social connections\n\n📊 **Compatibility Scores**:\n• 80%+ = Excellent Match\n• 60-79% = Good Match\n• 50-59% = Fair Match\n\nWant to know how to improve your compatibility score?";
        }
        
        // Advice responses
        if (lowerMessage.includes('advice') || lowerMessage.includes('tips') || lowerMessage.includes('help') || lowerMessage.includes('roommate advice')) {
            return "Here are my essential roommate success tips:\n\n🏠 **Before Moving In**:\n• Discuss expectations openly\n• Set clear house rules together\n• Agree on cleaning schedules\n• Split expenses fairly\n• Respect each other's privacy\n\n🤝 **Living Together**:\n• Communicate issues early\n• Be flexible but maintain boundaries\n• Schedule regular check-ins\n• Share responsibilities equally\n• Be considerate of noise/guests\n\n⚠️ **Red Flags to Watch**:\n• Poor communication habits\n• Financial irresponsibility\n• Disrespect for boundaries\n• Inconsistent cleanliness\n• Unreliable with commitments\n\nWhat specific roommate situation would you like advice about?";
        }
        
        // Default responses
        const defaultResponses = [
            "That's interesting! I can help you with finding compatible roommates, optimizing your profile, understanding our matching algorithm, or getting roommate advice. What would you like to know more about?",
            "I'm here to help! I can assist with: 🏠 Finding roommates, 📝 Profile optimization, 🤖 AI matching questions, or 💡 General roommate advice. What interests you most?",
            "Great to hear from you! I specialize in roommate matching success. Ask me about finding compatible roommates, improving your profile, how our AI matching works, or any roommate-related questions!",
            "Thanks for reaching out! I'm your AI roommate assistant. I can help you find perfect roommate match, optimize your profile for better results, explain our matching algorithm, or provide roommate relationship advice. What would you like to explore?",
            "Hello! I'm excited to help you find your ideal roommate! I can assist with roommate searching, profile improvement, understanding compatibility scores, or general roommate living advice. What's on your mind today?"
        ];
        
        const response = defaultResponses[Math.floor(Math.random() * defaultResponses.length)];
        console.log('Generated response:', response); // Debug log
        return response;
    }
    
    // Focus input on load
    messageInput.focus();
    
    // Debug: Log that chatbot is ready
    console.log('Chatbot initialized and ready!');
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

