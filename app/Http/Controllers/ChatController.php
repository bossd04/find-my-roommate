<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\GeminiService;

class ChatController extends Controller
{
    protected $ai;

    public function __construct(GeminiService $ai)
    {
        $this->ai = $ai;
    }

    public function send(Request $request)
    {
        try {
            \Log::info('ChatController::send called', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            $request->validate([
                'conversation_id' => 'required|exists:conversations,id',
                'message' => 'required|string'
            ]);

            $conversation = Conversation::findOrFail($request->conversation_id);
            \Log::info('Conversation found', ['conversation_id' => $conversation->id]);

            // Save user message
            Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'user',
                'content' => $request->message
            ]);
            \Log::info('User message saved');

        // Get last messages (memory)
        $history = Message::where('conversation_id', $conversation->id)
            ->latest()
            ->take(10)
            ->get()
            ->reverse();

        // System prompt - AI Assistant that can answer general questions AND help with roommates
        // Note: For Gemini, we start with a user turn or model turn.
        $messages = [
            [
                "role" => "user",
                "parts" => [
                    ["text" => "You are Gemini AI, a helpful and friendly AI assistant. You can answer any general knowledge questions, have conversations on any topic, and also provide expert advice about finding roommates, student housing, and living arrangements. Be conversational, informative, and engaging. If someone asks about the app features, be helpful about those too."]
                ]
            ],
            [
                "role" => "model",
                "parts" => [
                    ["text" => "Hello! I'm Gemini AI, your helpful assistant. I can chat about anything - general knowledge, answer your questions, help with homework, discuss topics, or assist you with finding roommates and housing. What would you like to talk about?"]
                ]
            ]
        ];

        foreach ($history as $msg) {
            $messages[] = [
                // Map 'assistant' role to Gemini's 'model' role
                "role" => ($msg->role === 'assistant' ? 'model' : 'user'),
                "parts" => [
                    ["text" => $msg->content]
                ]
            ];
        }

        // AI response
        \Log::info('Calling AI service', ['message_count' => count($messages)]);
        $reply = $this->ai->generate($messages);
        \Log::info('AI response received', ['reply' => $reply]);

        if (!$reply) {
            \Log::error('AI service returned null');
            return response()->json([
                'reply' => '⚠️ AI is temporarily unavailable. Please try again.'
            ]);
        }

        // Save AI response
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $reply
        ]);
        \Log::info('AI response saved');

        \Log::info('Returning JSON response', ['reply_length' => strlen($reply)]);
        return response()->json([
            'reply' => $reply
        ]);
        } catch (\Exception $e) {
            \Log::error('ChatController error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'reply' => '⚠️ An error occurred: ' . $e->getMessage()
            ]);
        }
    }
}
