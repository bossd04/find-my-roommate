@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 bg-blue-600 text-white font-bold">
            Chat Assistant
        </div>
        
        <div id="chat-box" class="h-96 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="text-center text-gray-500 text-xs py-2">Conversation started</div>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 flex gap-2">
            <input id="msg" 
                   type="text"
                   placeholder="Type message..." 
                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 outline-none">
            <button onclick="send()" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Send
            </button>
        </div>
    </div>
</div>

<script>
function send() {
    const msgInput = document.getElementById('msg');
    const msg = msgInput.value;
    if (!msg.trim()) return;

    // Show user message immediately
    const chatBox = document.getElementById('chat-box');
    const userMessageId = 'msg-' + Date.now();
    chatBox.innerHTML += `
        <div class="flex justify-end">
            <div class="bg-blue-600 text-white px-4 py-2 rounded-lg max-w-[80%]" id="${userMessageId}">
                ${msg}
            </div>
        </div>
    `;
    msgInput.value = '';
    chatBox.scrollTop = chatBox.scrollHeight;

    // Show typing indicator
    const typingId = 'typing-' + Date.now();
    chatBox.innerHTML += `
        <div class="flex justify-start" id="${typingId}">
            <div class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg max-w-[80%]">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                </div>
            </div>
        </div>
    `;
    chatBox.scrollTop = chatBox.scrollHeight;

    fetch('/chat/test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            conversation_id: 1,
            message: msg
        })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        return res.json();
    })
    .then(data => {
        // Remove typing indicator
        const typingElement = document.getElementById(typingId);
        if (typingElement) {
            typingElement.remove();
        }
        
        // Add AI response
        chatBox.innerHTML += `
            <div class="flex justify-start">
                <div class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg max-w-[80%]">
                    <strong>AI:</strong> ${data.reply || 'Sorry, I could not process your message.'}
                </div>
            </div>
        `;
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(err => {
        console.error('Error:', err);
        
        // Remove typing indicator
        const typingElement = document.getElementById(typingId);
        if (typingElement) {
            typingElement.remove();
        }
        
        // Show error message
        chatBox.innerHTML += `
            <div class="flex justify-start">
                <div class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-4 py-2 rounded-lg max-w-[80%] text-sm">
                    ⚠️ Failed to send message. Please try again.
                </div>
            </div>
        `;
        chatBox.scrollTop = chatBox.scrollHeight;
        
        // Keep the user message visible but mark as failed
        const userMsgElement = document.getElementById(userMessageId);
        if (userMsgElement) {
            userMsgElement.classList.add('opacity-75');
            userMsgElement.title = 'Message failed to send';
        }
    });
}

// Add enter key support
document.getElementById('msg')?.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') send();
});
</script>
@endsection
