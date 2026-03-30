@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
    <div class="bg-black bg-opacity-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-xl p-6 mb-8 border border-white/20">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-white mb-2">Roommate Assistant</h1>
                    <p class="text-white/80">Get help with finding your perfect roommate and managing your profile</p>
                </div>
            </div>

            <!-- Chat Container -->
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
                            <h2 class="text-lg font-semibold">AI Assistant</h2>
                            <p class="text-xs text-white/80">Online • Ready to help</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-4 bg-white/5">
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
                    <button onclick="sendQuickMessage('can you give me a compatible near in dagupan?')" class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200 transition-colors">
                        Dagupan Search
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
    const chatMessages = document.getElementById('chatMessages');
    
    // Form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
            messageInput.value = '';
        }
    });
    
    // Send message function
    function sendMessage(message) {
        console.log('Sending message:', message); // Debug log
        
        // Add user message
        addMessage(message, 'user');
        
        // Show typing indicator
        showTypingIndicator();
        
        // Simulate AI response with API call
        setTimeout(() => {
            removeTypingIndicator();
            
            // Call API for response
            fetch('{{ route("chat.api.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addMessage(data.message, 'bot');
                } else {
                    // Fallback to local response
                    const fallbackResponse = generateAIResponse(message);
                    addMessage(fallbackResponse, 'bot');
                }
            })
            .catch(error => {
                console.error('Chat API error:', error);
                // Fallback to local response
                const fallbackResponse = generateAIResponse(message);
                addMessage(fallbackResponse, 'bot');
            });
        }, 1000 + Math.random() * 1000);
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
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
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
        
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
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
    
    // Generate AI response
    function generateAIResponse(message) {
        console.log('Generating response for:', message); // Debug log
        
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
</script>
@endpush
