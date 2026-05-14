<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot page
     */
    public function index()
    {
        return view('chatbot');
    }

    /**
     * Clear conversation history from session
     */
    public function clearHistory(Request $request)
    {
        session()->forget('chatbot_history');
        session()->forget('chatbot_topic');
        \Log::info('Chatbot history cleared.');
        return response()->json(['success' => true]);
    }

    /**
     * Get roommate listings for chatbot
     */
    public function getListings(Request $request)
    {
        try {
            $query = \App\Models\User::query()
                ->where('role', 'user')
                ->where('id', '!=', auth()->id())
                ->whereHas('profile')
                ->with(['profile', 'preferences'])
                ->inRandomOrder()
                ->limit(10);

            $listings = $query->get()->map(function ($user) {
                $profile = $user->profile;
                $prefs = $user->preferences;

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'age' => $user->date_of_birth ? now()->diffInYears($user->date_of_birth) : null,
                    'gender' => $user->gender,
                    'location' => $profile->city ?? $profile->apartment_location ?? 'Not specified',
                    'budget' => $profile->monthly_budget ? 'PHP ' . number_format($profile->monthly_budget) : 'Not specified',
                    'bio' => $profile->bio ?? 'No bio available',
                    'avatar' => $user->avatar ?? null,
                ];
            });

            return response()->json([
                'success' => true,
                'listings' => $listings
            ]);

        } catch (\Exception $e) {
            \Log::error('Chatbot getListings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch listings',
                'listings' => []
            ], 500);
        }
    }

    /**
     * Get users with map locations for the chatbot map
     */
    public function getMapUsers(Request $request)
    {
        try {
            $location = $request->input('location');
            \Log::info('Chatbot getMapUsers: Fetching users for location: ' . ($location ?? 'All'));

            $query = \App\Models\User::query()
                ->where('role', 'user')
                ->where('id', '!=', auth()->id())
                ->whereHas('profile', function($q) use ($location) {
                    if ($location) {
                        $q->where(function($sub) use ($location) {
                            $sub->where('city', 'LIKE', "%{$location}%")
                                ->orWhere('apartment_location', 'LIKE', "%{$location}%");
                        });
                    }
                })
                ->with(['profile', 'preferences']);

            $users = $query->get()->map(function ($user) {
                $profile = $user->profile;
                if (!$profile) return null;

                // Get coordinates based on city (Pangasinan cities)
                $locationName = $profile->city ?? $profile->apartment_location;
                $coords = $this->getCityCoordinates($locationName);

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/avatars/' . $user->avatar)) : null,
                    'gender' => $user->gender,
                    'age' => $user->date_of_birth ? (int) abs(now()->diffInYears($user->date_of_birth)) : null,
                    'location' => $locationName ?? 'Not specified',
                    'budget' => $profile->monthly_budget ? 'PHP ' . number_format($profile->monthly_budget) : 'Not specified',
                    'bio' => $profile->bio ?? 'No bio available',
                    'lat' => $coords['lat'],
                    'lng' => $coords['lng'],
                    'profile_url' => route('roommates.show', $user->id),
                ];
            })->filter(function ($user) {
                return $user !== null && $user['lat'] !== null && $user['lng'] !== null;
            })->values();

            \Log::info('Chatbot getMapUsers: Returning ' . count($users) . ' users on map.');

            return response()->json([
                'success' => true,
                'users' => $users
            ]);

        } catch (\Exception $e) {
            \Log::error('Chatbot getMapUsers error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch map users',
                'users' => []
            ], 500);
        }
    }

    /**
     * Get coordinates for Pangasinan cities
     */
    private function getCityCoordinates(?string $city): array
    {
        $city = strtolower($city ?? '');

        $coordinates = [
            'dagupan' => ['lat' => 16.0433, 'lng' => 120.3333],
            'san carlos' => ['lat' => 15.9281, 'lng' => 120.3479],
            'urdaneta' => ['lat' => 15.9759, 'lng' => 120.5717],
            'alaminos' => ['lat' => 16.1554, 'lng' => 119.9820],
            'lingayen' => ['lat' => 16.0218, 'lng' => 120.2319],
            'calasiao' => ['lat' => 16.0115, 'lng' => 120.3567],
            'mangaldan' => ['lat' => 16.0700, 'lng' => 120.4036],
            'binmaley' => ['lat' => 16.0324, 'lng' => 120.2695],
            'bayambang' => ['lat' => 15.8087, 'lng' => 120.4593],
            'bolinao' => ['lat' => 16.3897, 'lng' => 119.8943],
            'san fabian' => ['lat' => 16.1556, 'lng' => 120.4494],
            'villasis' => ['lat' => 15.9031, 'lng' => 120.5914],
            'rosales' => ['lat' => 15.8953, 'lng' => 120.6328],
            'malasiqui' => ['lat' => 15.9167, 'lng' => 120.4167],
            'basista' => ['lat' => 15.8537, 'lng' => 120.4006],
            'san jacinto' => ['lat' => 16.0706, 'lng' => 120.4392],
            'mapandan' => ['lat' => 16.0170, 'lng' => 120.4537],
            'mabini' => ['lat' => 16.0685, 'lng' => 119.9331],
            'burgos' => ['lat' => 16.0508, 'lng' => 119.8656],
            'dasol' => ['lat' => 15.9903, 'lng' => 119.8808],
        ];

        foreach ($coordinates as $cityName => $coords) {
            if (str_contains($city, $cityName)) {
                return $coords;
            }
        }

        // Default to Pangasinan center if city not found
        return ['lat' => 15.9, 'lng' => 120.3];
    }

    /**
     * Handle chat messages - AI-powered responses with ChatGPT-like capabilities
     */
    public function chat(Request $request)
    {
        try {
            $message = $request->input('message', '');
            $lowerMessage = strtolower($message);

            if (empty($message)) {
                return response()->json(['reply' => 'Please type something!']);
            }

            // Load conversation history from session
            $conversationHistory = session('chatbot_history', []);

            // Detect language
            $isTagalog = $this->isTagalogMessage($lowerMessage);

            // Build enhanced ChatGPT-like system prompt that allows answering ANY question
            $systemPrompt = $this->buildEnhancedSystemPrompt($isTagalog, null, $conversationHistory);

            \Log::info('AI Chatbot: Processing message: ' . substr($message, 0, 50));
            
            // Debug: Check API key status (mask for security)
            $dsKey = config('services.deepseek.api_key');
            $orKey = config('services.openrouter.api_key');
            $geminiKey = config('services.gemini.api_key');
            \Log::info('DeepSeek API Key present: ' . (empty($dsKey) ? 'NO' : 'YES'));
            \Log::info('OpenRouter API Key present: ' . (empty($orKey) ? 'NO' : 'YES'));
            \Log::info('Gemini API Key present: ' . (empty($geminiKey) ? 'NO' : 'YES'));

            $aiReply = null;

            // PRIORITY 1: Try DeepSeek API (Primary)
            if (empty($aiReply) && !empty($dsKey)) {
                \Log::info('Trying DeepSeek API (Primary)...');
                $aiReply = $this->callDeepSeekAPIWithContext($message, $systemPrompt, $conversationHistory);
            }

            // PRIORITY 2: Try OpenRouter with free models (Secondary)
            if (empty($aiReply) && !empty($orKey)) {
                \Log::info('Trying OpenRouter API...');
                $aiReply = $this->callOpenRouterAPIWithContext($message, $systemPrompt, $conversationHistory);
            }

            // PRIORITY 3: Try Gemini API (Third Choice)
            if (empty($aiReply) && !empty($geminiKey)) {
                \Log::info('OpenRouter failed or not configured, trying Gemini...');
                $aiReply = $this->callGeminiAPIWithContext($message, $systemPrompt, $conversationHistory);
            }

            // Use AI reply if successful
            if (!empty($aiReply)) {
                $reply = $aiReply;
                \Log::info('AI Chatbot: API returned response successfully');
            } else {
                // ULTIMATE FALLBACK: Use internal knowledge base if ALL APIs fail
                \Log::warning('AI Chatbot: All AI APIs failed. Using internal knowledge base fallback.');
                
                $reply = $this->generateLocalResponse($lowerMessage, $message, $isTagalog);
                
                // If local response is empty, return a helpful error
                if (empty($reply)) {
                    $reply = $isTagalog
                        ? 'Paumanhin, may problema sa koneksyon sa AI server. Mangyaring subukan muli sa ilang sandali, o magtanong tungkol sa roommate matching kung kailangan mo ng tulong.'
                        : 'I apologize, but I\'m having trouble connecting to my AI services right now. Please try again in a moment, or ask me about roommate matching if you need help with that!';
                }
            }

            // Update conversation history
            $conversationHistory[] = ['role' => 'user', 'content' => $message];
            $conversationHistory[] = ['role' => 'assistant', 'content' => $reply];

            // Keep only last 20 messages (10 exchanges)
            if (count($conversationHistory) > 20) {
                $conversationHistory = array_slice($conversationHistory, -20);
            }

            session(['chatbot_history' => $conversationHistory]);

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            \Log::error('DeepSeek Chatbot Error: ' . $e->getMessage());
            return response()->json(['reply' => 'Sorry, I encountered an error. Please try again.']);
        }
    }

    /**
     * Detect conversation topic from user message
     */
    private function detectConversationTopic(string $lowerMessage): ?string
    {
        $topics = [
            'university' => ['university', 'college', 'school', 'psu', 'upang', 'educ', 'student', 'campus', 'pangasinan'],
            'roommate' => ['roommate', 'room', 'apartment', 'rent', 'tenant', 'landlord', 'house', 'dorm'],
            'profile' => ['profile', 'bio', 'about me', 'picture', 'photo', 'avatar'],
            'matching' => ['match', 'compatible', 'find', 'search', 'filter', 'looking for'],
            'safety' => ['safety', 'security', 'scam', 'fraud', 'danger', 'report'],
            'account' => ['account', 'delete', 'password', 'email', 'login', 'logout', 'settings'],
            'general' => ['help', 'hi', 'hello', 'how are you', 'what can you do', 'who are you']
        ];

        foreach ($topics as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lowerMessage, $keyword)) {
                    return $topic;
                }
            }
        }

        return null;
    }

    /**
     * Build enhanced system prompt with conversation context
     */
    private function buildEnhancedSystemPrompt(bool $isTagalog, ?string $currentTopic, array $conversationHistory): string
    {
        $language = $isTagalog ? 'Tagalog/Filipino' : 'English';
        $topicContext = $currentTopic ? " Current topic: {$currentTopic}." : '';

        // Build conversation context from history (last 3 exchanges)
        $contextLines = [];
        $recentHistory = array_slice($conversationHistory, -6); // Last 3 exchanges
        foreach ($recentHistory as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Assistant';
            $contextLines[] = "{$role}: {$msg['content']}";
        }
        $conversationContext = !empty($contextLines) ? "\n\nRecent conversation:\n" . implode("\n", $contextLines) : '';

        return "You are DeepSeek-V3, a state-of-the-art AI assistant with capabilities similar to ChatGPT and Google Gemini. You are helpful, creative, and highly intelligent.\n\n" .
            "CRITICAL RULE - LANGUAGE: You MUST respond ENTIRELY in {$language}. Never mix languages or translate unless explicitly asked.\n\n" .
            "YOUR CAPABILITIES:\n" .
            "- Answer ANY question with accurate, up-to-date knowledge across ALL topics (Science, History, Math, Coding, Art, etc.)\n" .
            "- Explain complex topics clearly and simply\n" .
            "- Help with coding, math problems, writing, analysis\n" .
            "- Provide thoughtful advice and creative ideas\n\n" .
            "HOW TO RESPOND:\n" .
            "1. BE RELEVANT: Always respond directly to the user's current question. If they ask a random question, answer it fully.\n" .
            "2. BE NATURAL: Write like a helpful human friend, not a robot.\n" .
            "3. BE THOROUGH: Give complete answers with examples when helpful.\n\n" .
            "CONVERSATION CONTEXT:{$topicContext}{$conversationContext}\n\n" .
            "IMPORTANT: While you are part of the 'Find My Roommate' platform, you are a FULL-SCALE AI. Do NOT limit your knowledge to roommate topics. Answer ANY random question the user asks with the same intelligence as ChatGPT.\n\n" .
            "LOCAL KNOWLEDGE: For questions about Pangasinan province (mayors, cities, food like bangus/pigar-pigar), you have detailed local information. For other general questions, use your broad training knowledge.\n\n" .
            "Respond naturally in {$language}.";
    }

    /**
     * Call OpenRouter API with conversation context - tries multiple free models in sequence
     */
    private function callOpenRouterAPIWithContext(string $userMessage, string $systemPrompt, array $conversationHistory): ?string
    {
        try {
            $apiKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY'));

            if (empty($apiKey)) {
                \Log::error('OpenRouter API key not configured. Please set OPENROUTER_API_KEY in .env');
                return null;
            }
            
            \Log::info('OpenRouter API Key configured (length: ' . strlen($apiKey) . ')');

            // Build messages: system prompt + conversation history (excluding last user msg since we add it separately)
            $messages = [['role' => 'system', 'content' => $systemPrompt]];

            // Add conversation history (all except the very last entry which is the current user message)
            $historyForMessages = array_slice($conversationHistory, 0, -1);
            // Take only last 10 messages to avoid token limits
            $historyForMessages = array_slice($historyForMessages, -10);
            foreach ($historyForMessages as $msg) {
                if (isset($msg['role']) && isset($msg['content'])) {
                    $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
                }
            }

            // Add current user message
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            // Prioritize models with best general knowledge capabilities
            $freeModels = [
                'google/gemma-3-27b-it:free',           // Excellent general knowledge
                'meta-llama/llama-3.1-8b-instruct:free', // Good all-around performance
                'openrouter/quasar-alpha',              // Fast, good general knowledge
                'meta-llama/llama-3.2-3b-instruct:free', // Lightweight but capable
                'mistralai/pixtral-12b:free',           // Good for detailed answers
            ];

            foreach ($freeModels as $model) {
                try {
                    \Log::info("Trying OpenRouter model: {$model} with " . count($messages) . " messages");

                    $response = Http::withOptions([
                        'verify' => false,
                    ])->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                        'HTTP-Referer' => url('/'),
                        'X-Title' => 'Find My Roommate AI Assistant',
                    ])->timeout(15)->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model' => $model,
                        'messages' => $messages,
                        'max_tokens' => 1000,
                        'temperature' => 0.8,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $content = $data['choices'][0]['message']['content'] ?? null;
                        if (!empty($content)) {
                            \Log::info("OpenRouter success with model {$model}, response length: " . strlen($content));
                            return trim($content);
                        }
                        \Log::warning("Model {$model} returned empty content.");
                    } elseif ($response->status() === 429) {
                        \Log::warning("Model {$model} rate limited (429), trying next...");
                        continue; // Try next model
                    } else {
                        $errorBody = $response->body();
                        $errorData = json_decode($errorBody, true);
                        $errorMsg = $errorData['error']['message'] ?? $errorBody;
                        \Log::error("Model {$model} HTTP " . $response->status() . ": " . substr($errorMsg, 0, 500));
                    }
                } catch (\Exception $e) {
                    \Log::error("Model {$model} exception: " . $e->getMessage());
                    continue;
                }
            }

            \Log::error('All OpenRouter models failed or rate-limited.');
            return null;

        } catch (\Exception $e) {
            \Log::error('OpenRouter API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simple direct test call to OpenRouter
     */
    private function testDirectOpenRouterCall(string $userMessage): ?string
    {
        try {
            $apiKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY'));
            
            if (empty($apiKey)) {
                return null;
            }
            
            \Log::info('TestDirectCall: Making simple API call...');
            
            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification for testing
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => url('/'),
                'X-Title' => 'Find My Roommate Test',
            ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemma-3-27b-it:free',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful AI assistant. Answer questions directly and concisely.'],
                    ['role' => 'user', 'content' => $userMessage]
                ],
                'max_tokens' => 800,
                'temperature' => 0.7,
            ]);
            
            \Log::info('TestDirectCall: Response status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;
                if (!empty($content)) {
                    \Log::info('TestDirectCall: SUCCESS - Response length: ' . strlen($content));
                    return trim($content);
                }
            } else {
                \Log::error('TestDirectCall: FAILED - ' . $response->status() . ' - ' . substr($response->body(), 0, 300));
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error('TestDirectCall: Exception - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Call DeepSeek API with conversation context
     */
    private function callDeepSeekAPIWithContext(string $userMessage, string $systemPrompt, array $conversationHistory): ?string
    {
        try {
            $apiKey = config('services.deepseek.api_key', env('DEEPSEEK_API_KEY'));
            $baseUrl = config('services.deepseek.base_url', env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com'));

            if (empty($apiKey)) {
                \Log::error('DeepSeek API key not configured. Please set DEEPSEEK_API_KEY in .env');
                return null;
            }
            
            \Log::info('DeepSeek API Key configured (length: ' . strlen($apiKey) . ')');

            // Build messages array with conversation history
            $messages = [['role' => 'system', 'content' => $systemPrompt]];

            // Add last 5 messages from history for context
            $recentHistory = array_slice($conversationHistory, -5, count($conversationHistory) - 1);
            foreach ($recentHistory as $msg) {
                $messages[] = $msg;
            }

            // Add current user message
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            $endpoint = rtrim($baseUrl, '/') . '/v1/chat/completions';

            \Log::info('Calling DeepSeek API with ' . count($messages) . ' messages');

            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification for Windows compatibility
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($endpoint, [
                'model' => 'deepseek-chat',
                'messages' => $messages,
                'max_tokens' => 2000,
                'temperature' => 0.7,
                'top_p' => 0.9,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;
                if (!empty($content)) {
                    \Log::info('DeepSeek API success, response length: ' . strlen($content));
                    return $content;
                }
                \Log::warning('DeepSeek API returned empty response');
                return null;
            }

            if ($response->status() === 402) {
                \Log::error('DeepSeek API: Insufficient Balance. Please top up your DeepSeek account.');
                return null;
            }

            $errorBody = $response->body();
            \Log::error('DeepSeek API HTTP error: ' . $response->status() . ' - ' . substr($errorBody, 0, 500));
            return null;

        } catch (\Exception $e) {
            \Log::error('DeepSeek API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Call OpenRouter API (FREE AI - Llama, Mistral, etc.)
     */
    private function callOpenRouterAPI(string $userMessage, string $systemPrompt): ?string
    {
        try {
            $apiKey = env('OPENROUTER_API_KEY');

            if (empty($apiKey)) {
                \Log::info('OpenRouter API key not configured, skipping...');
                return null;
            }

            \Log::info('Calling OpenRouter API with message: ' . substr($userMessage, 0, 50));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => url('/'),
                'X-Title' => 'Find My Roommate Chatbot',
            ])->timeout(60)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemini-2.0-flash-lite-preview-02-05:free',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage]
                ],
                'max_tokens' => 800,
                'temperature' => 0.7,
            ]);

            \Log::info('OpenRouter API response status: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;
                \Log::info('OpenRouter API returned content: ' . ($content ? 'yes' : 'no'));
                return $content;
            }

            \Log::warning('OpenRouter API error: ' . $response->status() . ' - ' . $response->body());
            return null;

        } catch (\Exception $e) {
            \Log::error('OpenRouter API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Call DeepSeek API (fallback)
     */
    private function callDeepSeekAPI(string $userMessage, string $systemPrompt): ?string
    {
        try {
            $apiKey = env('DEEPSEEK_API_KEY');
            $baseUrl = env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com');

            if (empty($apiKey)) {
                \Log::warning('DeepSeek API key not configured');
                return null;
            }

            \Log::info('Calling DeepSeek API with message: ' . substr($userMessage, 0, 50));

            $endpoint = rtrim($baseUrl, '/') . '/v1/chat/completions';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($endpoint, [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);

            \Log::info('DeepSeek API response status: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;
                \Log::info('DeepSeek API returned content: ' . ($content ? 'yes' : 'no'));
                return $content;
            }

            \Log::warning('DeepSeek API error: ' . $response->status() . ' - ' . $response->body());
            return null;

        } catch (\Exception $e) {
            \Log::error('DeepSeek API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build system prompt based on language
     */
    private function buildSystemPrompt(bool $isTagalog, string $lowerMessage): string
    {
        $basePrompt = "You are a helpful AI assistant. You can answer any question the user asks - whether it's about:\n";
        $basePrompt .= "- General knowledge (science, history, technology, etc.)\n";
        $basePrompt .= "- Life advice and motivation\n";
        $basePrompt .= "- Filipino/Tagalog topics\n";
        $basePrompt .= "- Roommate matching and apartment hunting (if relevant)\n\n";
        $basePrompt .= "Guidelines:\n";
        $basePrompt .= "- Answer the user's question directly and accurately\n";
        $basePrompt .= "- Be conversational, friendly, and natural like ChatGPT\n";
        $basePrompt .= "- For factual questions, provide clear and accurate information\n";
        $basePrompt .= "- For opinion/advice questions, be thoughtful and balanced\n";
        $basePrompt .= "- Keep responses natural and conversational (not robotic)\n";
        $basePrompt .= "- Use formatting (bullet points, bold text) when it helps readability\n";
        $basePrompt .= "- If you don't know something, be honest about it\n";

        if ($isTagalog) {
            $basePrompt .= "\nRespond in Tagalog/Filipino language. ";
        }

        return $basePrompt;
    }

    /**
     * Build ChatGPT-like system prompt for DeepSeek
     */
    private function buildDeepSeekSystemPrompt(bool $isTagalog): string
    {
        $lang = $isTagalog ? 'Tagalog/Filipino' : 'English';

        return "You are DeepSeek Assistant, a highly capable AI assistant similar to ChatGPT and Google Gemini. You can answer ANY question on ANY topic in real-time.\n\n"
            . "CRITICAL RULE - LANGUAGE: The user is writing in {$lang}. You MUST respond ENTIRELY in {$lang}. Never mix languages.\n\n"
            . "CAPABILITIES:\n"
            . "- Answer general knowledge questions (science, history, math, coding, geography)\n"
            . "- Provide life advice, explanations, and how-to guides\n"
            . "- Help with writing, analysis, and creative tasks\n"
            . "- Answer in Tagalog/Filipino when the user writes in Tagalog\n"
            . "- Be conversational, friendly, and natural\n"
            . "- Use markdown formatting for clarity\n"
            . "- Think step by step for complex problems\n\n"
            . "You are connected to DeepSeek AI and OpenRouter APIs. Answer accurately and helpfully!";
    }

    /**
     * Detect if message is in Tagalog/Filipino
     */
    private function isTagalogMessage(string $message): bool
    {
        $tagalogWords = [
            'ano', 'sino', 'saan', 'kailan', 'paano', 'bakit', 'magkano', 'ilang',
            'ako', 'ikaw', 'siya', 'tayo', 'kami', 'kayo', 'sila', 'naman',
            'po', 'opo', 'ho', 'oho', 'ba', 'nga', 'daw', 'din', 'rin',
            'kung', 'nang', 'ng', 'sa', 'ang', 'mga', 'si', 'ni', 'kay',
            'ko', 'mo', 'niya', 'natin', 'namin', 'ninyo', 'nila',
            'aking', 'iyong', 'kanyang', 'ating', 'aming', 'inyong', 'kanilang',
            'ito', 'iyan', 'iyon', 'dito', 'diyan', 'doon',
            'maganda', 'masaya', 'malungkot', 'mainit', 'malamig',
            'salamat', 'paalam', 'kamusta', 'kumusta', 'oo', 'hindi',
            'pwede', 'puede', 'pwedeng', 'puedeng', 'baka', 'siguro',
            'gusto', 'ayaw', 'gustong', 'ayaw', 'kailangan', 'kailangang',
            'nanay', 'tatay', 'kuya', 'ate', 'lolo', 'lola', 'anak', 'apo'
        ];

        $words = explode(' ', strtolower($message));
        $tagalogCount = 0;

        foreach ($words as $word) {
            if (in_array($word, $tagalogWords)) {
                $tagalogCount++;
            }
        }

        return $tagalogCount >= 2 || str_contains($message, 'po');
    }

    /**
     * Generate local response for quick action buttons and fallback
     */
    private function generateLocalResponse(string $lowerMessage, string $originalMessage, bool $isTagalog, ?string $currentTopic = null): string
    {
        // 1. SMART FACT & TRANSLATION DETECTION (Direct concise answers)
        
        // Translation requests
        if (str_contains($lowerMessage, 'tagalog ng') || str_contains($lowerMessage, 'english of') || str_contains($lowerMessage, 'meaning of') || str_contains($lowerMessage, 'ano ang tagalog')) {
            $dictionary = [
                'cat' => 'pusa', 'dog' => 'aso', 'bird' => 'ibon', 'fish' => 'isda',
                'house' => 'bahay', 'water' => 'tubig', 'food' => 'pagkain', 'love' => 'pag-ibig',
                'friend' => 'kaibigan', 'school' => 'paaralan', 'teacher' => 'guro', 'student' => 'estudyante',
                'car' => 'kotse', 'book' => 'aklat', 'pen' => 'panulat', 'sky' => 'langit', 'sun' => 'araw',
                'earth' => 'daigdig', 'moon' => 'buwan', 'star' => 'bituin', 'tree' => 'puno', 'flower' => 'bulaklak',
                'life' => 'buhay', 'death' => 'kamatayan', 'time' => 'oras', 'world' => 'mundo', 'man' => 'lalaki', 'woman' => 'babae'
            ];
            
            foreach ($dictionary as $en => $tl) {
                if (str_contains($lowerMessage, $en)) {
                    return $isTagalog ? "Ang Tagalog ng **$en** ay **$tl**." : "The Tagalog word for **$en** is **$tl**.";
                }
            }
        }

        // Direct answers for common knowledge (Smarter Local AI Simulation)
        $directAnswers = [
            // Philippines Facts
            'capital of philippines' => $isTagalog ? 'Ang capital ng Pilipinas ay **Manila**.' : 'The capital of the Philippines is **Manila**.',
            'pambansang bayani' => 'Ang pambansang bayani ng Pilipinas ay si **Dr. Jose Rizal**.',
            'who is rizal' => 'Dr. Jose Rizal (1861-1896) was a Filipino nationalist, polymath, and writer who inspired the Philippine Revolution through his novels "Noli Me Tangere" and "El Filibusterismo".',
            'president' => $isTagalog ? 'Ang kasalukuyang presidente ng Pilipinas ay si **Ferdinand Marcos Jr.**' : 'The current president of the Philippines is **Ferdinand Marcos Jr.**',
            'who invented' => $isTagalog ? 'Maaari mong tanungin ang tungkol sa partikular na imbensyon para sa mas detalyadong sagot.' : 'You can ask about specific inventions for detailed information!',
            
            // Science Facts
            'largest planet' => 'The largest planet in our solar system is **Jupiter**. It\'s so big that over 1,300 Earths could fit inside it!',
            'fastest animal' => 'The **Peregrine Falcon** is the fastest animal, reaching speeds over 240 mph (386 km/h) when diving!',
            'hottest planet' => 'The hottest planet in our solar system is **Venus**, with surface temperatures around 465°C (869°F) due to its thick atmosphere.',
            'smallest planet' => 'The smallest planet in our solar system is **Mercury**.',
            'closest star' => 'The closest star to Earth (besides our Sun) is **Proxima Centauri**, about 4.24 light-years away.',
            'speed of light' => 'The speed of light is approximately **299,792,458 meters per second** (about 186,282 miles per second).',
            'photosynthesis' => 'Photosynthesis is the process by which plants use sunlight, water, and carbon dioxide to create oxygen and energy in the form of sugar.',
            'gravity' => 'Gravity is the force that attracts objects with mass toward each other. It\'s what keeps us on Earth and the Earth orbiting the Sun.',
            'evolution' => 'Evolution is the process by which living organisms change over generations through natural selection, adapting to their environments.',
            'dna' => 'DNA (deoxyribonucleic acid) is the molecule that carries genetic instructions for the development and function of all living organisms.',
            'water boils' => 'Water boils at **100°C (212°F)** at sea level. The boiling point decreases at higher altitudes.',
            'human body' => 'The adult human body has **206 bones** and over **600 muscles**. Our bodies are amazing!',
            'rainbow' => 'Rainbows form when sunlight refracts (bends) and reflects through water droplets in the air, separating white light into its color spectrum.',
            'earth age' => 'Earth is approximately **4.54 billion years old**, based on evidence from radiometric dating.',
            'universe age' => 'The universe is approximately **13.8 billion years old**, based on measurements of the cosmic microwave background.',
            
            // Math & Numbers
            'pi' => 'Pi (π) is approximately **3.14159...** It represents the ratio of a circle\'s circumference to its diameter and is an irrational number.',
            'prime number' => 'A prime number is a number greater than 1 that has exactly two factors: 1 and itself. Examples: 2, 3, 5, 7, 11, 13.',
            'fibonacci' => 'The Fibonacci sequence is a series where each number is the sum of the two preceding ones: 0, 1, 1, 2, 3, 5, 8, 13, 21...',
            'square root' => 'The square root of a number is a value that, when multiplied by itself, gives the original number. For example, √9 = 3.',
            
            // Geography
            'longest river' => 'The **Nile River** in Africa is traditionally considered the longest river (about 6,650 km), though the Amazon is close!',
            'tallest mountain' => 'Mount **Everest** in Nepal/Tibet is the tallest mountain above sea level at **8,848.86 meters (29,031.7 feet)**.',
            'deepest ocean' => 'The **Pacific Ocean** is the deepest ocean, with the Challenger Deep in the Mariana Trench reaching about **10,935 meters** deep.',
            'largest ocean' => 'The **Pacific Ocean** is the largest and deepest ocean, covering more than 63 million square miles.',
            'largest country' => 'By land area, **Russia** is the largest country, covering about 17 million square kilometers.',
            'most populous' => '**India** is now the most populous country, followed closely by **China**, each with over 1.4 billion people.',
            'smallest country' => 'The smallest country in the world by area is **Vatican City**, at just 0.44 square kilometers.',
            'continent' => 'There are **7 continents**: Asia, Africa, North America, South America, Antarctica, Europe, and Australia/Oceania.',
            'sahara' => 'The **Sahara** is the world\'s largest hot desert, covering most of North Africa.',
            'amazon' => 'The **Amazon Rainforest** is the largest tropical rainforest, spanning 9 countries in South America.',
            
            // History
            'world war' => 'World War I (1914-1918) and World War II (1939-1945) were the two largest global conflicts in human history.',
            'industrial revolution' => 'The Industrial Revolution was a period (1760-1840) when manufacturing shifted from hand production to machines.',
            'ancient egypt' => 'Ancient Egypt was one of the earliest civilizations (c. 3100 BCE), known for pyramids, pharaohs, and hieroglyphics.',
            'romans' => 'The Roman Empire (27 BCE - 476 CE) was one of the largest empires in ancient history, covering Europe, North Africa, and the Middle East.',
            'dinosaurs' => 'Dinosaurs lived during the Mesozoic Era (252-66 million years ago). They went extinct about 66 million years ago, likely due to an asteroid impact.',
            'moon landing' => 'Humans first landed on the Moon on **July 20, 1969**, when Apollo 11 astronauts Neil Armstrong and Buzz Aldrin walked on the lunar surface.',
            'when was internet invented' => 'The modern internet evolved from ARPANET (1969). The World Wide Web was invented by Tim Berners-Lee in **1989**.',
            'who discovered america' => 'Christopher Columbus reached the Americas in 1492, though indigenous peoples had lived there for millennia.',
            'renaissance' => 'The Renaissance (14th-17th century) was a cultural movement marking the transition from Middle Ages to modernity, centered in Italy.',
            
            // Technology
            'computer' => 'A computer is an electronic device that processes data according to instructions, performing calculations and logical operations.',
            'internet' => 'The internet is a global network of interconnected computer networks that use standardized communication protocols.',
            'artificial intelligence' => 'AI is the simulation of human intelligence by machines, including learning, reasoning, problem-solving, and language understanding.',
            'machine learning' => 'Machine Learning is a subset of AI where computers learn from data without being explicitly programmed for every task.',
            'blockchain' => 'Blockchain is a distributed digital ledger that records transactions across many computers, making it secure and transparent.',
            'bitcoin' => 'Bitcoin is the first decentralized cryptocurrency, created in 2009 by an unknown person using the pseudonym Satoshi Nakamoto.',
            'quantum computing' => 'Quantum computing uses quantum mechanics principles to process information, potentially solving problems impossible for classical computers.',
            
            // Health & Biology
            'vitamin c' => 'Vitamin C (ascorbic acid) is essential for immune function, collagen production, and antioxidant protection. Found in citrus fruits.',
            'vaccine' => 'Vaccines train your immune system to recognize and fight specific diseases, providing immunity without causing the disease.',
            'sleep' => 'Adults should get **7-9 hours** of sleep per night. Sleep is essential for memory, healing, and overall health.',
            'water' => 'Humans should drink about **8 glasses (2 liters)** of water daily. Water is essential for all bodily functions.',
            'exercise' => 'Regular exercise improves cardiovascular health, strengthens muscles, boosts mood, and reduces disease risk. Aim for 150 minutes weekly.',
            'heart' => 'The human heart beats about **100,000 times per day**, pumping blood throughout the body.',
            'brain' => 'The human brain contains about **86 billion neurons** and uses about 20% of the body\'s energy.',
            
            // Language
            'hello in' => $isTagalog ? 'Ang "hello" sa Tagalog ay **"kamusta"** o **"kumusta"**.' : 'Hello in different languages: Spanish (Hola), French (Bonjour), German (Hallo), Japanese (Konnichiwa), Korean (Annyeong).',
            
            // About the AI
            'who made you' => $isTagalog ? 'Ako ay ginawa ng Find My Roommate team para tulungan ka sa roommate search at iba pang tanong.' : 'I was created by the Find My Roommate team to assist you with roommate search and answer your questions.',
            'what are you' => $isTagalog ? 'Ako ay isang AI assistant na gumagamit ng advanced language models tulad ng ChatGPT para sagutin ang iyong mga katanungan.' : 'I am an AI assistant powered by advanced language models like ChatGPT to answer your questions accurately.',
            'how do you work' => 'I use large language models (AI) trained on vast amounts of data to understand your questions and generate helpful, accurate responses.',
            'your name' => $isTagalog ? 'Ako ay ang AI Assistant ng Find My Roommate. Maaari mo akong tawaging "Assistant"!' : 'I am the Find My Roommate AI Assistant. You can call me "Assistant"!',
            
            // Fun Facts
            'tallest animal' => 'The **giraffe** is the tallest animal, with males reaching up to **5.5 meters (18 feet)** tall.',
            'largest animal' => 'The **blue whale** is the largest animal ever known, reaching up to **30 meters** and weighing up to **200 tons**.',
            'smartest animal' => '**Dolphins, chimpanzees, and elephants** are among the smartest animals, showing problem-solving and self-awareness.',
            'honey never spoils' => 'Honey never spoils! Archaeologists have found **3,000-year-old honey** in ancient Egyptian tombs that was still edible.',
            'banana' => 'Botanically, a banana is a **berry**, while a strawberry is not!',
            'octopus' => 'Octopuses have **three hearts, blue blood, and nine brains** (one central and eight in their arms)!',
            'eiffel tower' => 'The Eiffel Tower in Paris was built in 1889. It grows **15 cm taller in summer** due to heat expansion!',

            // ============================================
            // PANGASINAN LOCAL GOVERNMENT FACTS
            // ============================================

            // Province Level
            'pangasinan' => '**Pangasinan** is a province in the Ilocos Region of the Philippines. Its capital is **Lingayen**, and it has 44 municipalities and 4 cities. Population: ~3 million. Known for: milkfish (bangus), Hundred Islands, and delicious pigar-pigar.',
            'governor of pangasinan' => $isTagalog ? 'Ang kasalukuyang Gobernador ng Pangasinan ay si **Hon. Ramon "Mon-Mon" V. Guico III**.' : 'The current Governor of Pangasinan is **Hon. Ramon "Mon-Mon" V. Guico III**.',
            'vice governor pangasinan' => 'The current Vice Governor of Pangasinan is **Hon. Mark Lambino**.',
            'capital of pangasinan' => 'The capital of Pangasinan is **Lingayen**. The provincial capitol is located there and is known for its beautiful architecture.',
            'lingayen' => '**Lingayen** is the capital of Pangasinan province. Known for the Provincial Capitol building, the beach, and World War II historical sites.',

            // Cities in Pangasinan
            'dagupan' => '**Dagupan City** is a major commercial center in Pangasinan. Known as the "Bangus Capital of the Philippines." Famous for: pigar-pigar, bangus (milkfish), and its bustling markets. Mayor: **Hon. Belen Fernandez**.',
            'dagupan mayor' => $isTagalog ? 'Ang mayor ng Dagupan City ay si **Hon. Belen Fernandez**.' : 'The mayor of Dagupan City is **Hon. Belen Fernandez**.',
            'dagupan vice mayor' => 'The Vice Mayor of Dagupan City is **Hon. Bryan C. Kua**.',
            'alaminos' => '**Alaminos City** is known for the famous **Hundred Islands National Park** - 124 small islands in Lingayen Gulf. Mayor: **Hon. Arth Bryan C. Celeste**.',
            'alaminos mayor' => $isTagalog ? 'Ang mayor ng Alaminos City ay si **Hon. Arth Bryan C. Celeste**.' : 'The mayor of Alaminos City is **Hon. Arth Bryan C. Celeste**.',
            'san carlos city' => '**San Carlos City** is the largest city in Pangasinan by land area. Known for mango production. Mayor: **Hon. Julier C. Rodriguez**.',
            'san carlos mayor' => 'The mayor of San Carlos City is **Hon. Julier C. Rodriguez**.',
            'urdaneta' => '**Urdaneta City** is the geographic center of Pangasinan and a major transportation hub. Known for its public market (one of the largest in Northern Luzon). Mayor: **Hon. Julio F. Parayno III**.',
            'urdaneta mayor' => 'The mayor of Urdaneta City is **Hon. Julio F. Parayno III**.',

            // Major Municipalities
            'calasiao' => '**Calasiao** is known as the "Town of the Big Festival" and famous for its **puto Calasiao** (rice cake). Mayor: **Hon. Joseph B. Bauzon**.',
            'calasiao mayor' => 'The mayor of Calasiao is **Hon. Joseph B. Bauzon**.',
            'bayambang' => '**Bayambang** is the largest municipality in Pangasinan by land area. Known as the "Egg Basket of Pangasinan." Mayor: **Hon. Niña Jose-Quiambao**.',
            'bayambang mayor' => 'The mayor of Bayambang is **Hon. Niña Jose-Quiambao**.',
            'mangaldan' => '**Mangaldan** is known for its woodcraft industry and bamboo products. Mayor: **Hon. Bona Fe D. Parayno**.',
            'mangaldan mayor' => 'The mayor of Mangaldan is **Hon. Bona Fe D. Parayno**.',
            'malasiqui' => '**Malasiqui** is one of the largest municipalities in Pangasinan. Mayor: **Hon. Armando T. Domingo**.',
            'malasiqui mayor' => 'The mayor of Malasiqui is **Hon. Armando T. Domingo**.',
            'binmaley' => '**Binmaley** is known as the "Fishing Capital of Pangasinan." Famous for bangus and various seafood. Mayor: **Hon. Pedro L. Merrera**.',
            'binmaley mayor' => 'The mayor of Binmaley is **Hon. Pedro L. Merrera**.',
            'mapandan' => '**Mapandan** is known as the "Pandan Capital of Pangasinan." Mayor: **Hon. Karl Christian R. Vega**.',
            'mapandan mayor' => 'The mayor of Mapandan is **Hon. Karl Christian R. Vega**.',
            'villasis' => '**Villasis** is known as the "Corn Capital of Pangasinan" and hosts the famous **Talong (Eggplant) Festival**. Mayor: **Hon. Nonato S. Abrenica Jr.**.',
            'villasis mayor' => 'The mayor of Villasis is **Hon. Nonato S. Abrenica Jr.**.',
            'rosales' => '**Rosales** is a major commercial hub in eastern Pangasinan. Mayor: **Hon. Susan R. Casalla**.',
            'rosales mayor' => 'The mayor of Rosales is **Hon. Susan R. Casalla**.',
            'asipulo' => '**Asingan** is the birthplace of President Fidel V. Ramos. Mayor: **Hon. Carlos F. Lopez Jr.**.',
            'asipulo mayor' => 'The mayor of Asingan is **Hon. Carlos F. Lopez Jr.**.',
            'binalonan' => '**Binalonan** is known for its furniture industry. Mayor: **Hon. Ramon V. Guico Jr.**.',
            'binalonan mayor' => 'The mayor of Binalonan is **Hon. Ramon V. Guico Jr.**.',
            'sison' => '**Sison** is a gateway municipality located near the Benguet border. Mayor: **Hon. Danilo M. Dizon**.',
            'sison mayor' => 'The mayor of Sison is **Hon. Danilo M. Dizon**.',
            'san fabian' => '**San Fabian** is a coastal town known for its beaches and resorts. Mayor: **Hon. Irene F. Libunao**.',
            'san fabian mayor' => 'The mayor of San Fabian is **Hon. Irene F. Libunao**.',
            'aguilar' => '**Aguilar** is known for its Bocaweño Festival. Mayor: **Hon. Jesus M. Zamuco Jr.**.',
            'aguilar mayor' => 'The mayor of Aguilar is **Hon. Jesus M. Zamuco Jr.**.',
            'tayug' => '**Tayug** is known as the "Home of the Sugarcane Planters Association" and hosts the Kankanen Festival. Mayor: **Hon. Jaime S. Bautista Jr.**.',
            'tayug mayor' => 'The mayor of Tayug is **Hon. Jaime S. Bautista Jr.**.',
            'manaag' => '**Manaoag** is famous for the **Minor Basilica of Our Lady of the Most Holy Rosary of Manaoag** - a major pilgrimage site. Mayor: **Hon. Jeremy Agerico "Ager" V. Rosario**.',
            'manaag mayor' => $isTagalog ? 'Ang mayor ng Manaoag ay si **Hon. Jeremy Agerico "Ager" V. Rosario**.' : 'The mayor of Manaoag is **Hon. Jeremy Agerico "Ager" V. Rosario**.',
            'urdaneta vice mayor' => 'The Vice Mayor of Urdaneta City is **Hon. Jimmy Parayno**.',
            'alaminos vice mayor' => 'The Vice Mayor of Alaminos City is **Hon. DC C. Celeste**.',
            'san carlos vice mayor' => 'The Vice Mayor of San Carlos City is **Hon. Joseres Sison**.',

            // Universities in Pangasinan
            'universidad de dagupan' => '**Universidad de Dagupan (UdD)** is located at Arellano St., Dagupan City. It was formerly Colegio de Dagupan and became a university in 2022. Founded by Dr. Voltaire P. Arzadon, currently led by President Dr. Feliza Arzadon-Sua.',
            'udd' => '**Universidad de Dagupan (UdD)** is located at Arellano St., Dagupan City. Formerly Colegio de Dagupan, became university in 2022. President: Dr. Feliza Arzadon-Sua.',
            'psu' => '**Pangasinan State University (PSU)** has 9 campuses across Pangasinan. President: **Dr. Elbert Galas**. Main campus in Lingayen.',
            'upang' => '**University of Pangasinan (UPANG)** is located in Dagupan City. President: **Dr. Virgilio Crisostomo**.',
            'lnu' => '**Lyceum-Northwestern University (L-NU)** is located in Dagupan City. President: **Dr. Joselito De Guzman**.',
            'pangasinan state university' => '**Pangasinan State University (PSU)** has 9 campuses throughout the province. President: **Dr. Elbert Galas**.',
            'university of pangasinan' => '**University of Pangasinan (UPANG)** is in Dagupan City, one of the oldest universities in the region. President: **Dr. Virgilio Crisostomo**.',

            // Tourist Attractions
            'hundred islands' => 'The **Hundred Islands National Park** in Alaminos City consists of 124 islands at low tide. Famous for island hopping, snorkeling, and zip-lining. A must-visit destination in Pangasinan!',
            'hundred island' => 'The **Hundred Islands National Park** in Alaminos City has 124 islands (at low tide). Popular activities: island hopping, snorkeling, jet skiing, and the famous zipline at Governor\'s Island.',
            'tayug sunflower maze' => 'The **Tayug Sunflower Maze** is a popular tourist attraction featuring thousands of sunflowers arranged in a maze pattern.',
            'bolinao' => '**Bolinao** is famous for **Patar Beach**, **Bolinao Falls**, and the **Cape Bolinao Lighthouse** (2nd tallest in the Philippines). Mayor: **Hon. Alfonso F. Celeste**.',
            'bolinao mayor' => 'The mayor of Bolinao is **Hon. Alfonso F. Celeste**.',
            'bolinao lighthouse' => 'The **Cape Bolinao Lighthouse** is the second tallest lighthouse in the Philippines at 101 feet, built in 1905.',
            'patar beach' => '**Patar Beach** in Bolinao has golden sand and clear waters, one of the best beaches in Pangasinan.',
            'balungao' => '**Balungao** is known for the **Balungao Hilltop Adventure** with the longest zipline in Pangasinan.',
            'balungao mayor' => 'The mayor of Balungao is **Hon. Philipp G. Peralta**.',

            // Local Products & Food
            'bangus' => '**Bangus (Milkfish)** is the most famous product of Pangasinan! Dagupan is known as the "Bangus Capital of the Philippines." Best prepared: grilled, fried, or as daing (dried).',
            'milkfish' => 'Bangus or Milkfish is Pangasinan\'s most iconic product. Dagupan produces the best milkfish in the country!',
            'pigar-pigar' => '**Pigar-pigar** is a famous Dagupan delicacy made of thinly sliced beef or carabao meat, quickly fried with onions and cabbage. Best eaten with beer!',
            'puto calasiao' => '**Puto Calasiao** is a famous rice cake from Calasiao, known for being soft, white, and slightly sweet. Perfect with dinuguan!',
            'bocawe\u00f1o' => 'The **Bocaweño Festival** in Aguilar celebrates the town\'s bocawe plant used for making brooms and handicrafts.',
            'egg basket' => '**Bayambang** is known as the "Egg Basket of Pangasinan" due to its large poultry and egg production.',
            'corn capital' => '**Villasis** is the "Corn Capital of Pangasinan" and hosts the Talong (Eggplant) Festival.',

            // Famous People from Pangasinan
            'fidel ramos' => '**President Fidel V. Ramos** (1928-2022), the 12th President of the Philippines, was born in **Lingayen, Pangasinan**. His ancestral home is in Asingan.',
            'fidel v ramos' => '**Fidel V. Ramos** was born in Lingayen, Pangasinan. He served as the 12th President of the Philippines (1992-1998).',
            'lani misalucha' => '**Lani Misalucha**, known as "Asia\'s Nightingale," is from Pangasinan. She is a famous Filipino singer.',

            // Other Facts
            'ilocano' => '**Ilocano** is one of the major languages spoken in Pangasinan, along with Pangasinan (Pangalatok) and Filipino/Tagalog.',
            'pangasinan language' => 'The **Pangasinan language** (also called Pangalatok) is spoken by about 1.2 million people in the province.',
            'pigar pigar' => '**Pigar-pigar** is a famous beef dish from Dagupan made of thinly sliced meat quickly stir-fried with onions and cabbage.',
        ];

        foreach ($directAnswers as $key => $val) {
            if (str_contains($lowerMessage, $key)) {
                return $val;
            }
        }

        // Specific Personalities / Local Facts
        if (str_contains($lowerMessage, 'mayor') && (str_contains($lowerMessage, 'san jacinto') || str_contains($lowerMessage, 'jacinto'))) {
            return $isTagalog 
                ? "Ang kasalukuyang mayor ng San Jacinto, Pangasinan ay si **Hon. Leo F. De Vera**."
                : "The current municipal mayor of San Jacinto, Pangasinan is **Hon. Leo F. De Vera**.";
        }

        // Advice - matches "Roommate advice" button
        if (str_contains($lowerMessage, 'advice') || str_contains($lowerMessage, 'help')) {
            return $this->getAdviceResponse($isTagalog);
        }

        // Profile tips - matches "tips on improving your profile" button
        if (str_contains($lowerMessage, 'profile') || str_contains($lowerMessage, 'tips') || str_contains($lowerMessage, 'improving')) {
            return $this->getProfileTipsResponse($isTagalog);
        }

        // Matching info - matches "How does matching work?" button
        if (str_contains($lowerMessage, 'matching') || str_contains($lowerMessage, 'algorithm') || str_contains($lowerMessage, 'work')) {
            return $this->getMatchingInfoResponse($isTagalog);
        }

        // Safety info - matches "Safety information and guidelines" button
        if (str_contains($lowerMessage, 'safety') || str_contains($lowerMessage, 'guidelines') || str_contains($lowerMessage, 'information')) {
            return $this->getSafetyInfoResponse($isTagalog);
        }

        // Find roommates - matches "Find compatible roommates" button
        if (str_contains($lowerMessage, 'find') || str_contains($lowerMessage, 'compatible') || str_contains($lowerMessage, 'roommate')) {
            return $this->getFindRoommatesResponse($isTagalog);
        }

        // General knowledge base for random questions
        return $this->generateKnowledgeResponse($lowerMessage, $originalMessage, $isTagalog);
    }

    /**
     * Response for finding roommates
     */
    private function getFindRoommatesResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Paano Maghanap ng Kasama sa Apartment**\n\n" .
                "1. **Gumawa ng Profile** - Punuan ang iyong profile ng mga totoong impormasyon at mag-upload ng malinaw na litrato.\n" .
                "2. **Gamitin ang Search** - I-filter ayon sa lokasyon, budget, at lifestyle preferences.\n" .
                "3. **Mag-message** - Makipag-usap sa mga potential roommate para makilala sila.\n" .
                "4. **Mag-meet** - Mag-coffee date o video call bago mag-decide.\n\n" .
                "**Tip:** Maging honest sa iyong lifestyle habits (sleep schedule, cleanliness, etc.)";
        }

        return "**How to Find Roommates**\n\n" .
            "1. **Create a Profile** - Fill out your profile with accurate information and upload a clear photo.\n" .
            "2. **Use Search** - Filter by location, budget, and lifestyle preferences.\n" .
            "3. **Send Messages** - Chat with potential roommates to get to know them.\n" .
            "4. **Meet First** - Have a coffee date or video call before deciding.\n\n" .
            "**Tip:** Be honest about your lifestyle habits (sleep schedule, cleanliness, etc.)";
    }

    /**
     * Response for profile tips
     */
    private function getProfileTipsResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Mga Tip sa Profile**\n\n" .
                "1. **Malinaw na Litrato** - Mag-upload ng recent at malinaw na litrato ng mukha mo.\n" .
                "2. **Kumpletong Impormasyon** - I-fill out ang lahat ng fields (bio, work, hobbies).\n" .
                "3. **Maging Tapat** - Maglagay ng totoong impormasyon para sa better matches.\n" .
                "4. **I-describe ang Lifestyle** - Sabihin kung early bird o night owl ka, neat freak o relaxed.\n\n" .
                "**Masarap makipag-match sa may kumpletong profile!**";
        }

        return "**Profile Tips**\n\n" .
            "1. **Clear Photo** - Upload a recent, clear photo of your face.\n" .
            "2. **Complete Information** - Fill out all fields (bio, work, hobbies).\n" .
            "3. **Be Honest** - Provide accurate information for better matches.\n" .
            "4. **Describe Your Lifestyle** - Say if you're an early bird or night owl, neat freak or relaxed.\n\n" .
            "**People love matching with complete profiles!**";
    }

    /**
     * Response for matching info
     */
    private function getMatchingInfoResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Paano Gumagana ang Matching**\n\n" .
                "Ang aming system ay nag-match base sa:\n" .
                "- **Lokasyon** - Same city o nearby areas\n" .
                "- **Budget** - Compatible na rental budget\n" .
                "- **Lifestyle** - Sleep schedule, cleanliness, social habits\n" .
                "- **Preferences** - Pet-friendly, smoking, visitors policy\n\n" .
                "**Pagkatapos mag-like sa isang profile, hintayin na mag-like back para mag-match!**";
        }

        return "**How Matching Works**\n\n" .
            "Our system matches based on:\n" .
            "- **Location** - Same city or nearby areas\n" .
            "- **Budget** - Compatible rental budget\n" .
            "- **Lifestyle** - Sleep schedule, cleanliness, social habits\n" .
            "- **Preferences** - Pet-friendly, smoking, visitors policy\n\n" .
            "**After liking a profile, wait for them to like back to match!**";
    }

    /**
     * Response for advice
     */
    private function getAdviceResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**General Advice para sa Roommate Hunting**\n\n" .
                "- **Mag-set ng Budget** - Kalkulahin ang max na kayang bayaran buwan-buwan\n" .
                "- **I-list ang Priorities** - Alin ang mas importante: lokasyon, presyo, o amenities?\n" .
                "- **Magtanong** - Huwag mahiyang magtanong sa potential roommate\n" .
                "- **Mag-sign ng Agreement** - Always have a written roommate agreement\n\n" .
                "**Safety First:** Mag-meet sa public place sa unang pagkikita!";
        }

        return "**General Advice for Roommate Hunting**\n\n" .
            "- **Set a Budget** - Calculate the max you can pay monthly\n" .
            "- **List Priorities** - What's more important: location, price, or amenities?\n" .
            "- **Ask Questions** - Don't be shy to ask potential roommates\n" .
            "- **Sign an Agreement** - Always have a written roommate agreement\n\n" .
            "**Safety First:** Meet in a public place for the first meeting!";
    }

    /**
     * Response for safety info
     */
    private function getSafetyInfoResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Safety Tips para sa Roommate Search**\n\n" .
                "**Bago Mag-meet:**\n" .
                "- Video call muna bago mag-meet in person\n" .
                "- I-share ang location sa kaibigan o pamilya\n" .
                "- Mag-meet sa public place (mall, coffee shop)\n\n" .
                "**Sa Apartment:**\n" .
                "- I-lock ang pinto kapag natutulog\n" .
                "- Huwag agad mag-share ng personal info\n" .
                "- I-document ang mga agreements\n\n" .
                "**Kung may Red Flags:** Mag-report agad sa platform!";
        }

        return "**Safety Tips for Roommate Search**\n\n" .
            "**Before Meeting:**\n" .
            "- Video call first before meeting in person\n" .
            "- Share location with friends or family\n" .
            "- Meet in a public place (mall, coffee shop)\n\n" .
            "**In the Apartment:**\n" .
            "- Lock your door when sleeping\n" .
            "- Don't immediately share personal info\n" .
            "- Document all agreements\n\n" .
            "**If you see Red Flags:** Report to the platform immediately!";
    }

    /**
     * Generate knowledge base response for random questions
     * MASSIVELY EXPANDED - 50+ topic categories
     */
    private function generateKnowledgeResponse(string $lowerMessage, string $originalMessage, bool $isTagalog): string
    {
        // FOOD & COOKING - Expanded
        if (str_contains($lowerMessage, 'adobo') || str_contains($lowerMessage, 'sinigang') ||
            str_contains($lowerMessage, 'luto') || str_contains($lowerMessage, 'recipe') ||
            str_contains($lowerMessage, 'cook') || str_contains($lowerMessage, 'pagkain') ||
            str_contains($lowerMessage, 'food') || str_contains($lowerMessage, 'kain') ||
            str_contains($lowerMessage, 'restaurant') || str_contains($lowerMessage, 'kare-kare') ||
            str_contains($lowerMessage, 'pancit') || str_contains($lowerMessage, 'lechon') ||
            str_contains($lowerMessage, 'lumpia') || str_contains($lowerMessage, 'tinola') ||
            str_contains($lowerMessage, 'sisig') || str_contains($lowerMessage, 'breakfast') ||
            str_contains($lowerMessage, 'lunch') || str_contains($lowerMessage, 'dinner') ||
            str_contains($lowerMessage, 'snack') || str_contains($lowerMessage, 'dessert') ||
            str_contains($lowerMessage, 'drink') || str_contains($lowerMessage, 'beverage') ||
            str_contains($lowerMessage, 'alamat') || str_contains($lowerMessage, 'pagluluto')) {
            return $this->getFoodResponse($isTagalog, $lowerMessage);
        }

        // HISTORY - Expanded
        if (str_contains($lowerMessage, 'history') || str_contains($lowerMessage, 'kasaysayan') ||
            str_contains($lowerMessage, 'world war') || str_contains($lowerMessage, 'ancient') ||
            str_contains($lowerMessage, 'revolution') || str_contains($lowerMessage, 'historical') ||
            str_contains($lowerMessage, 'colonial') || str_contains($lowerMessage, 'american') ||
            str_contains($lowerMessage, 'spanish') || str_contains($lowerMessage, 'japanese') ||
            str_contains($lowerMessage, 'independence') || str_contains($lowerMessage, 'rizal') ||
            str_contains($lowerMessage, 'bonifacio') || str_contains($lowerMessage, 'aguinaldo') ||
            str_contains($lowerMessage, 'marcos') || str_contains($lowerMessage, 'edsa') ||
            str_contains($lowerMessage, 'hero') || str_contains($lowerMessage, 'bayani') ||
            str_contains($lowerMessage, 'president') || str_contains($lowerMessage, 'leader')) {
            return $this->getHistoryResponse($isTagalog, $lowerMessage);
        }

        // GEOGRAPHY & PLACES - Expanded
        if (str_contains($lowerMessage, 'country') || str_contains($lowerMessage, 'city') ||
            str_contains($lowerMessage, 'capital') || str_contains($lowerMessage, 'bansa') ||
            str_contains($lowerMessage, 'lungsod') || str_contains($lowerMessage, 'pilipinas') ||
            str_contains($lowerMessage, 'philippines') || str_contains($lowerMessage, 'asia') ||
            str_contains($lowerMessage, 'europe') || str_contains($lowerMessage, 'america') ||
            str_contains($lowerMessage, 'africa') || str_contains($lowerMessage, 'ocean') ||
            str_contains($lowerMessage, 'mountain') || str_contains($lowerMessage, 'volcano') ||
            str_contains($lowerMessage, 'mayon') || str_contains($lowerMessage, 'taal') ||
            str_contains($lowerMessage, 'pinatubo') || str_contains($lowerMessage, 'province') ||
            str_contains($lowerMessage, 'region') || str_contains($lowerMessage, 'map')) {
            return $this->getGeographyResponse($isTagalog, $lowerMessage);
        }

        // MATH & NUMBERS
        if (str_contains($lowerMessage, 'math') || str_contains($lowerMessage, 'number') ||
            str_contains($lowerMessage, 'calculate') || str_contains($lowerMessage, 'solve') ||
            str_contains($lowerMessage, 'add') || str_contains($lowerMessage, 'subtract') ||
            str_contains($lowerMessage, 'multiply') || str_contains($lowerMessage, 'divide') ||
            str_contains($lowerMessage, 'percentage') || str_contains($lowerMessage, 'fraction') ||
            str_contains($lowerMessage, 'algebra') || str_contains($lowerMessage, 'geometry')) {
            return $this->getMathResponse($isTagalog, $lowerMessage);
        }

        // TECHNOLOGY - COMPUTERS & INTERNET
        if (str_contains($lowerMessage, 'computer') || str_contains($lowerMessage, 'internet') ||
            str_contains($lowerMessage, 'website') || str_contains($lowerMessage, 'google') ||
            str_contains($lowerMessage, 'facebook') || str_contains($lowerMessage, 'phone') ||
            str_contains($lowerMessage, 'smartphone') || str_contains($lowerMessage, 'app') ||
            str_contains($lowerMessage, 'online') || str_contains($lowerMessage, 'email') ||
            str_contains($lowerMessage, 'laptop') || str_contains($lowerMessage, 'tablet') ||
            str_contains($lowerMessage, 'wifi') || str_contains($lowerMessage, 'bluetooth') ||
            str_contains($lowerMessage, 'camera') || str_contains($lowerMessage, 'video') ||
            str_contains($lowerMessage, 'photo') || str_contains($lowerMessage, 'download') ||
            str_contains($lowerMessage, 'upload') || str_contains($lowerMessage, 'password')) {
            return $this->getTechResponse($isTagalog, $lowerMessage);
        }

        // AI & PROGRAMMING
        if (str_contains($lowerMessage, 'ai') || str_contains($lowerMessage, 'artificial intelligence') ||
            str_contains($lowerMessage, 'machine learning') || str_contains($lowerMessage, 'programming') ||
            str_contains($lowerMessage, 'coding') || str_contains($lowerMessage, 'software') ||
            str_contains($lowerMessage, 'developer') || str_contains($lowerMessage, 'python') ||
            str_contains($lowerMessage, 'javascript') || str_contains($lowerMessage, 'php') ||
            str_contains($lowerMessage, 'java') || str_contains($lowerMessage, 'html') ||
            str_contains($lowerMessage, 'css') || str_contains($lowerMessage, 'database') ||
            str_contains($lowerMessage, 'api') || str_contains($lowerMessage, 'chatbot') ||
            str_contains($lowerMessage, 'robot') || str_contains($lowerMessage, 'automation')) {
            return $this->getAIResponse($isTagalog);
        }

        // SCHOOLS & UNIVERSITIES - MOVED EARLIER FOR HIGH PRIORITY
        if (str_contains($lowerMessage, 'university') || str_contains($lowerMessage, 'college') ||
            str_contains($lowerMessage, 'school') || str_contains($lowerMessage, 'paaralan') ||
            str_contains($lowerMessage, 'campus') || str_contains($lowerMessage, 'institution') ||
            // Major Philippine Universities
            str_contains($lowerMessage, 'up diliman') || str_contains($lowerMessage, 'upang') ||
            str_contains($lowerMessage, 'pangasinan state') || str_contains($lowerMessage, 'psu') ||
            str_contains($lowerMessage, 'lyceum pangasinan') || str_contains($lowerMessage, 'lpu') ||
            str_contains($lowerMessage, 'universidad de dagupan') || str_contains($lowerMessage, 'ud dagupan') ||
            str_contains($lowerMessage, 'ama pangasinan') || str_contains($lowerMessage, 'urdeneta') ||
            str_contains($lowerMessage, 'ednas') || str_contains($lowerMessage, 'ucu') ||
            str_contains($lowerMessage, 'ust') || str_contains($lowerMessage, 'ateneo') ||
            str_contains($lowerMessage, 'lasalle') || str_contains($lowerMessage, 'pup') ||
            str_contains($lowerMessage, 'founder') || str_contains($lowerMessage, 'president')) {
            return $this->getSchoolResponse($isTagalog, $lowerMessage);
        }

        // SCIENCE - Expanded
        if (str_contains($lowerMessage, 'science') || str_contains($lowerMessage, 'physics') ||
            str_contains($lowerMessage, 'chemistry') || str_contains($lowerMessage, 'biology') ||
            str_contains($lowerMessage, 'planet') || str_contains($lowerMessage, 'earth') ||
            str_contains($lowerMessage, 'space') || str_contains($lowerMessage, 'sun') ||
            str_contains($lowerMessage, 'moon') || str_contains($lowerMessage, 'star') ||
            str_contains($lowerMessage, 'galaxy') || str_contains($lowerMessage, 'universe') ||
            str_contains($lowerMessage, 'atom') || str_contains($lowerMessage, 'molecule') ||
            str_contains($lowerMessage, 'cell') || str_contains($lowerMessage, 'dna') ||
            str_contains($lowerMessage, 'evolution') || str_contains($lowerMessage, 'gravity') ||
            str_contains($lowerMessage, 'electricity') || str_contains($lowerMessage, 'magnet') ||
            str_contains($lowerMessage, 'climate') || str_contains($lowerMessage, 'environment')) {
            return $this->getScienceResponse($isTagalog);
        }

        // HEALTH & MEDICINE - Expanded
        if (str_contains($lowerMessage, 'health') || str_contains($lowerMessage, 'sick') ||
            str_contains($lowerMessage, 'medicine') || str_contains($lowerMessage, 'doctor') ||
            str_contains($lowerMessage, 'hospital') || str_contains($lowerMessage, 'sakit') ||
            str_contains($lowerMessage, 'gamot') || str_contains($lowerMessage, 'exercise') ||
            str_contains($lowerMessage, 'fitness') || str_contains($lowerMessage, 'diet') ||
            str_contains($lowerMessage, 'nutrition') || str_contains($lowerMessage, 'vitamin') ||
            str_contains($lowerMessage, 'sleep') || str_contains($lowerMessage, 'stress') ||
            str_contains($lowerMessage, 'mental health') || str_contains($lowerMessage, 'virus') ||
            str_contains($lowerMessage, 'bacteria') || str_contains($lowerMessage, 'covid') ||
            str_contains($lowerMessage, 'vaccine') || str_contains($lowerMessage, 'checkup')) {
            return $this->getHealthResponse($isTagalog);
        }

        // RELATIONSHIPS & LOVE - Expanded
        if (str_contains($lowerMessage, 'love') || str_contains($lowerMessage, 'relationship') ||
            str_contains($lowerMessage, 'boyfriend') || str_contains($lowerMessage, 'girlfriend') ||
            str_contains($lowerMessage, 'crush') || str_contains($lowerMessage, 'pag-ibig') ||
            str_contains($lowerMessage, 'relasyon') || str_contains($lowerMessage, 'date') ||
            str_contains($lowerMessage, 'marry') || str_contains($lowerMessage, 'asawa') ||
            str_contains($lowerMessage, 'wedding') || str_contains($lowerMessage, 'kasal') ||
            str_contains($lowerMessage, 'breakup') || str_contains($lowerMessage, 'single') ||
            str_contains($lowerMessage, 'divorce') || str_contains($lowerMessage, 'family') ||
            str_contains($lowerMessage, 'friend') || str_contains($lowerMessage, 'kaibigan') ||
            str_contains($lowerMessage, 'parent') || str_contains($lowerMessage, 'child') ||
            str_contains($lowerMessage, 'sibling') || str_contains($lowerMessage, 'mga anak')) {
            return $this->getRelationshipResponse($isTagalog, $lowerMessage);
        }

        // MONEY & FINANCE - Expanded
        if (str_contains($lowerMessage, 'money') || str_contains($lowerMessage, 'pera') ||
            str_contains($lowerMessage, 'finance') || str_contains($lowerMessage, 'save') ||
            str_contains($lowerMessage, 'invest') || str_contains($lowerMessage, 'salary') ||
            str_contains($lowerMessage, 'sweldo') || str_contains($lowerMessage, 'budget') ||
            str_contains($lowerMessage, 'bank') || str_contains($lowerMessage, 'credit card') ||
            str_contains($lowerMessage, 'loan') || str_contains($lowerMessage, 'debt') ||
            str_contains($lowerMessage, 'utang') || str_contains($lowerMessage, 'expensive') ||
            str_contains($lowerMessage, 'cheap') || str_contains($lowerMessage, 'price') ||
            str_contains($lowerMessage, 'cost') || str_contains($lowerMessage, 'bill') ||
            str_contains($lowerMessage, 'expense') || str_contains($lowerMessage, 'income')) {
            return $this->getFinanceResponse($isTagalog, $lowerMessage);
        }

        // JOBS & CAREER - Expanded
        if (str_contains($lowerMessage, 'job') || str_contains($lowerMessage, 'work') ||
            str_contains($lowerMessage, 'career') || str_contains($lowerMessage, 'trabaho') ||
            str_contains($lowerMessage, 'employment') || str_contains($lowerMessage, 'resume') ||
            str_contains($lowerMessage, 'interview') || str_contains($lowerMessage, 'hiring') ||
            str_contains($lowerMessage, 'promotion') || str_contains($lowerMessage, 'raise') ||
            str_contains($lowerMessage, 'boss') || str_contains($lowerMessage, 'office') ||
            str_contains($lowerMessage, 'company') || str_contains($lowerMessage, 'business') ||
            str_contains($lowerMessage, 'startup') || str_contains($lowerMessage, 'freelance') ||
            str_contains($lowerMessage, 'remote') || str_contains($lowerMessage, 'skill') ||
            str_contains($lowerMessage, 'training') || str_contains($lowerMessage, 'certification')) {
            return $this->getCareerResponse($isTagalog, $lowerMessage);
        }

        // TRAVEL & TOURISM - Expanded (MOVED BEFORE ENTERTAINMENT for priority)
        if (str_contains($lowerMessage, 'travel') || str_contains($lowerMessage, 'tourist') ||
            str_contains($lowerMessage, 'vacation') || str_contains($lowerMessage, 'bakasyon') ||
            str_contains($lowerMessage, 'hotel') || str_contains($lowerMessage, 'flight') ||
            str_contains($lowerMessage, 'airport') || str_contains($lowerMessage, 'beach') ||
            str_contains($lowerMessage, 'mountain') || str_contains($lowerMessage, 'tour') ||
            str_contains($lowerMessage, 'island') || str_contains($lowerMessage, 'resort') ||
            str_contains($lowerMessage, 'camping') || str_contains($lowerMessage, 'hiking') ||
            str_contains($lowerMessage, 'passport') || str_contains($lowerMessage, 'visa') ||
            str_contains($lowerMessage, 'abroad') || str_contains($lowerMessage, 'overseas') ||
            str_contains($lowerMessage, 'tourist spot') || str_contains($lowerMessage, 'attraction')) {
            return $this->getTravelResponse($isTagalog, $lowerMessage);
        }

        // ENTERTAINMENT - Expanded
        if (str_contains($lowerMessage, 'movie') || str_contains($lowerMessage, 'film') ||
            str_contains($lowerMessage, 'music') || str_contains($lowerMessage, 'song') ||
            str_contains($lowerMessage, 'game') || str_contains($lowerMessage, 'netflix') ||
            str_contains($lowerMessage, 'youtube') || str_contains($lowerMessage, 'kanta') ||
            str_contains($lowerMessage, 'pelikula') || str_contains($lowerMessage, 'laro') ||
            str_contains($lowerMessage, 'concert') || str_contains($lowerMessage, 'show') ||
            str_contains($lowerMessage, 'actor') || str_contains($lowerMessage, 'actress') ||
            str_contains($lowerMessage, 'singer') || str_contains($lowerMessage, 'band') ||
            str_contains($lowerMessage, 'artist') || str_contains($lowerMessage, 'celebrity') ||
            str_contains($lowerMessage, 'trending') || str_contains($lowerMessage, 'viral') ||
            str_contains($lowerMessage, 'tiktok')) {
            return $this->getEntertainmentResponse($isTagalog, $lowerMessage);
        }

        // SPORTS - Expanded
        if (str_contains($lowerMessage, 'sports') || str_contains($lowerMessage, 'basketball') ||
            str_contains($lowerMessage, 'football') || str_contains($lowerMessage, 'soccer') ||
            str_contains($lowerMessage, 'volleyball') || str_contains($lowerMessage, 'olympics') ||
            str_contains($lowerMessage, 'athlete') || str_contains($lowerMessage, 'laro') ||
            str_contains($lowerMessage, 'boxing') || str_contains($lowerMessage, 'mma') ||
            str_contains($lowerMessage, 'ufc') || str_contains($lowerMessage, 'swimming') ||
            str_contains($lowerMessage, 'running') || str_contains($lowerMessage, 'gym') ||
            str_contains($lowerMessage, 'yoga') || str_contains($lowerMessage, 'cycling') ||
            str_contains($lowerMessage, 'badminton') || str_contains($lowerMessage, 'tennis') ||
            str_contains($lowerMessage, 'golf') || str_contains($lowerMessage, 'team') ||
            str_contains($lowerMessage, 'player') || str_contains($lowerMessage, 'coach')) {
            return $this->getSportsResponse($isTagalog, $lowerMessage);
        }

        // SHOPPING & COMMERCE
        if (str_contains($lowerMessage, 'shopping') || str_contains($lowerMessage, 'buy') ||
            str_contains($lowerMessage, 'sell') || str_contains($lowerMessage, 'store') ||
            str_contains($lowerMessage, 'mall') || str_contains($lowerMessage, 'market') ||
            str_contains($lowerMessage, 'online shop') || str_contains($lowerMessage, 'lazada') ||
            str_contains($lowerMessage, 'shopee') || str_contains($lowerMessage, 'amazon') ||
            str_contains($lowerMessage, 'product') || str_contains($lowerMessage, 'brand') ||
            str_contains($lowerMessage, 'discount') || str_contains($lowerMessage, 'sale') ||
            str_contains($lowerMessage, 'bargain') || str_contains($lowerMessage, 'quality')) {
            return $this->getShoppingResponse($isTagalog, $lowerMessage);
        }

        // HOME & LIVING
        if (str_contains($lowerMessage, 'house') || str_contains($lowerMessage, 'home') ||
            str_contains($lowerMessage, 'apartment') || str_contains($lowerMessage, 'rent') ||
            str_contains($lowerMessage, 'furniture') || str_contains($lowerMessage, 'decor') ||
            str_contains($lowerMessage, 'cleaning') || str_contains($lowerMessage, 'organize') ||
            str_contains($lowerMessage, 'garden') || str_contains($lowerMessage, 'repair') ||
            str_contains($lowerMessage, 'diy') || str_contains($lowerMessage, 'interior') ||
            str_contains($lowerMessage, 'kitchen') || str_contains($lowerMessage, 'bedroom') ||
            str_contains($lowerMessage, 'bathroom') || str_contains($lowerMessage, 'living room')) {
            return $this->getHomeResponse($isTagalog, $lowerMessage);
        }

        // AUTOMOTIVE & TRANSPORTATION
        if (str_contains($lowerMessage, 'car') || str_contains($lowerMessage, 'vehicle') ||
            str_contains($lowerMessage, 'drive') || str_contains($lowerMessage, 'license') ||
            str_contains($lowerMessage, 'motorcycle') || str_contains($lowerMessage, 'tricycle') ||
            str_contains($lowerMessage, 'jeepney') || str_contains($lowerMessage, 'bus') ||
            str_contains($lowerMessage, 'train') || str_contains($lowerMessage, 'mrt') ||
            str_contains($lowerMessage, 'lrt') || str_contains($lowerMessage, 'uber') ||
            str_contains($lowerMessage, 'grab') || str_contains($lowerMessage, 'taxi') ||
            str_contains($lowerMessage, 'traffic') || str_contains($lowerMessage, 'fuel') ||
            str_contains($lowerMessage, 'gas') || str_contains($lowerMessage, 'parking')) {
            return $this->getTransportResponse($isTagalog, $lowerMessage);
        }

        // FASHION & STYLE
        if (str_contains($lowerMessage, 'fashion') || str_contains($lowerMessage, 'clothes') ||
            str_contains($lowerMessage, 'clothing') || str_contains($lowerMessage, 'dress') ||
            str_contains($lowerMessage, 'shirt') || str_contains($lowerMessage, 'shoes') ||
            str_contains($lowerMessage, 'style') || str_contains($lowerMessage, 'outfit') ||
            str_contains($lowerMessage, 'accessories') || str_contains($lowerMessage, 'jewelry') ||
            str_contains($lowerMessage, 'watch') || str_contains($lowerMessage, 'bag') ||
            str_contains($lowerMessage, 'makeup') || str_contains($lowerMessage, 'beauty') ||
            str_contains($lowerMessage, 'skincare') || str_contains($lowerMessage, 'hair')) {
            return $this->getFashionResponse($isTagalog, $lowerMessage);
        }

        // CULTURE & ARTS
        if (str_contains($lowerMessage, 'culture') || str_contains($lowerMessage, 'art') ||
            str_contains($lowerMessage, 'painting') || str_contains($lowerMessage, 'sculpture') ||
            str_contains($lowerMessage, 'museum') || str_contains($lowerMessage, 'gallery') ||
            str_contains($lowerMessage, 'tradition') || str_contains($lowerMessage, 'festival') ||
            str_contains($lowerMessage, 'sinulog') || str_contains($lowerMessage, 'ati-atihan') ||
            str_contains($lowerMessage, 'panagbenga') || str_contains($lowerMessage, 'literature') ||
            str_contains($lowerMessage, 'poetry') || str_contains($lowerMessage, 'dance') ||
            str_contains($lowerMessage, 'music') || str_contains($lowerMessage, 'theater')) {
            return $this->getCultureResponse($isTagalog, $lowerMessage);
        }

        // POLITICS & GOVERNMENT
        if (str_contains($lowerMessage, 'politics') || str_contains($lowerMessage, 'government') ||
            str_contains($lowerMessage, 'election') || str_contains($lowerMessage, 'vote') ||
            str_contains($lowerMessage, 'president') || str_contains($lowerMessage, 'senator') ||
            str_contains($lowerMessage, 'congress') || str_contains($lowerMessage, 'mayor') ||
            str_contains($lowerMessage, 'law') || str_contains($lowerMessage, 'policy') ||
            str_contains($lowerMessage, 'democracy') || str_contains($lowerMessage, 'rights') ||
            str_contains($lowerMessage, 'tax') || str_contains($lowerMessage, 'buwis')) {
            return $this->getPoliticsResponse($isTagalog, $lowerMessage);
        }

        // RELIGION & SPIRITUALITY
        if (str_contains($lowerMessage, 'religion') || str_contains($lowerMessage, 'god') ||
            str_contains($lowerMessage, 'church') || str_contains($lowerMessage, 'temple') ||
            str_contains($lowerMessage, 'pray') || str_contains($lowerMessage, 'prayer') ||
            str_contains($lowerMessage, 'bible') || str_contains($lowerMessage, 'faith') ||
            str_contains($lowerMessage, 'spiritual') || str_contains($lowerMessage, 'christian') ||
            str_contains($lowerMessage, 'catholic') || str_contains($lowerMessage, 'islam') ||
            str_contains($lowerMessage, 'buddhist') || str_contains($lowerMessage, 'hindu')) {
            return $this->getReligionResponse($isTagalog, $lowerMessage);
        }

        // ENVIRONMENT & NATURE
        if (str_contains($lowerMessage, 'environment') || str_contains($lowerMessage, 'pollution') ||
            str_contains($lowerMessage, 'recycle') || str_contains($lowerMessage, 'sustainable') ||
            str_contains($lowerMessage, 'green') || str_contains($lowerMessage, 'eco') ||
            str_contains($lowerMessage, 'forest') || str_contains($lowerMessage, 'ocean') ||
            str_contains($lowerMessage, 'river') || str_contains($lowerMessage, 'lake') ||
            str_contains($lowerMessage, 'mountain') || str_contains($lowerMessage, 'beach') ||
            str_contains($lowerMessage, 'wildlife') || str_contains($lowerMessage, 'conservation')) {
            return $this->getEnvironmentResponse($isTagalog, $lowerMessage);
        }

        // FOOD CULTURE & RESTAURANTS
        if (str_contains($lowerMessage, 'restaurant') || str_contains($lowerMessage, 'fast food') ||
            str_contains($lowerMessage, 'jollibee') || str_contains($lowerMessage, 'mcdonalds') ||
            str_contains($lowerMessage, 'kfc') || str_contains($lowerMessage, 'chowking') ||
            str_contains($lowerMessage, 'street food') || str_contains($lowerMessage, 'merienda') ||
            str_contains($lowerMessage, 'buffet') || str_contains($lowerMessage, 'cafe') ||
            str_contains($lowerMessage, 'coffee') || str_contains($lowerMessage, 'milk tea') ||
            str_contains($lowerMessage, 'bobba') || str_contains($lowerMessage, 'samgyupsal')) {
            return $this->getFoodCultureResponse($isTagalog, $lowerMessage);
        }

        // EMERGENCY & SAFETY
        if (str_contains($lowerMessage, 'emergency') || str_contains($lowerMessage, 'police') ||
            str_contains($lowerMessage, 'fire') || str_contains($lowerMessage, 'ambulance') ||
            str_contains($lowerMessage, '911') || str_contains($lowerMessage, 'hotline') ||
            str_contains($lowerMessage, 'disaster') || str_contains($lowerMessage, 'earthquake') ||
            str_contains($lowerMessage, 'flood') || str_contains($lowerMessage, 'typhoon') ||
            str_contains($lowerMessage, 'evacuate') || str_contains($lowerMessage, 'first aid') ||
            str_contains($lowerMessage, 'bantay') || str_contains($lowerMessage, 'safety')) {
            return $this->getEmergencyResponse($isTagalog, $lowerMessage);
        }

        // SOCIAL MEDIA & COMMUNICATION
        if (str_contains($lowerMessage, 'facebook') || str_contains($lowerMessage, 'instagram') ||
            str_contains($lowerMessage, 'twitter') || str_contains($lowerMessage, 'tiktok') ||
            str_contains($lowerMessage, 'messenger') || str_contains($lowerMessage, 'viber') ||
            str_contains($lowerMessage, 'telegram') || str_contains($lowerMessage, 'whatsapp') ||
            str_contains($lowerMessage, 'zoom') || str_contains($lowerMessage, 'meeting') ||
            str_contains($lowerMessage, 'call') || str_contains($lowerMessage, 'text') ||
            str_contains($lowerMessage, 'chat') || str_contains($lowerMessage, 'message')) {
            return $this->getSocialMediaResponse($isTagalog, $lowerMessage);
        }

        // HOBBIES & INTERESTS
        if (str_contains($lowerMessage, 'hobby') || str_contains($lowerMessage, 'collection') ||
            str_contains($lowerMessage, 'stamp') || str_contains($lowerMessage, 'coin') ||
            str_contains($lowerMessage, 'photography') || str_contains($lowerMessage, 'drawing') ||
            str_contains($lowerMessage, 'painting') || str_contains($lowerMessage, 'knitting') ||
            str_contains($lowerMessage, 'gardening') || str_contains($lowerMessage, 'fishing') ||
            str_contains($lowerMessage, 'hunting') || str_contains($lowerMessage, 'craft') ||
            str_contains($lowerMessage, 'diy') || str_contains($lowerMessage, 'reading') ||
            str_contains($lowerMessage, 'writing') || str_contains($lowerMessage, 'blog')) {
            return $this->getHobbiesResponse($isTagalog, $lowerMessage);
        }

        // GAMING & ESPORTS
        if (str_contains($lowerMessage, 'mobile legends') || str_contains($lowerMessage, 'mlbb') ||
            str_contains($lowerMessage, 'genshin') || str_contains($lowerMessage, 'valorant') ||
            str_contains($lowerMessage, 'dota') || str_contains($lowerMessage, 'lol') ||
            str_contains($lowerMessage, 'pubg') || str_contains($lowerMessage, 'fortnite') ||
            str_contains($lowerMessage, 'call of duty') || str_contains($lowerMessage, 'cod') ||
            str_contains($lowerMessage, 'minecraft') || str_contains($lowerMessage, 'roblox') ||
            str_contains($lowerMessage, 'esports') || str_contains($lowerMessage, 'streamer') ||
            str_contains($lowerMessage, 'twitch') || str_contains($lowerMessage, 'gaming')) {
            return $this->getGamingResponse($isTagalog, $lowerMessage);
        }

        // TIME & DATE
        if (str_contains($lowerMessage, 'time') || str_contains($lowerMessage, 'date') ||
            str_contains($lowerMessage, 'day') || str_contains($lowerMessage, 'month') ||
            str_contains($lowerMessage, 'year') || str_contains($lowerMessage, 'calendar') ||
            str_contains($lowerMessage, 'oras') || str_contains($lowerMessage, 'araw') ||
            str_contains($lowerMessage, 'buwan') || str_contains($lowerMessage, 'taon')) {
            return $this->getTimeResponse($isTagalog, $lowerMessage);
        }

        // WEATHER
        if (str_contains($lowerMessage, 'weather') || str_contains($lowerMessage, 'rain') ||
            str_contains($lowerMessage, 'sunny') || str_contains($lowerMessage, 'temperature') ||
            str_contains($lowerMessage, 'ulan') || str_contains($lowerMessage, 'init') ||
            str_contains($lowerMessage, 'panahon') || str_contains($lowerMessage, 'storm') ||
            str_contains($lowerMessage, 'habagat') || str_contains($lowerMessage, 'amihan')) {
            return $this->getWeatherResponse($isTagalog, $lowerMessage);
        }

        // PHILOSOPHY & MEANING
        if (str_contains($lowerMessage, 'meaning of life') || str_contains($lowerMessage, 'purpose') ||
            str_contains($lowerMessage, 'why are we here') || str_contains($lowerMessage, 'existence') ||
            str_contains($lowerMessage, 'philosophy') || str_contains($lowerMessage, 'buhay') ||
            str_contains($lowerMessage, 'destiny') || str_contains($lowerMessage, 'fate') ||
            str_contains($lowerMessage, 'karma') || str_contains($lowerMessage, 'wisdom')) {
            return $this->getPhilosophyResponse($isTagalog, $lowerMessage);
        }

        // EDUCATION & LEARNING - General study tips (SCHOOLS/UNIVERSITIES moved earlier)
        if (str_contains($lowerMessage, 'study') || str_contains($lowerMessage, 'learn') ||
            str_contains($lowerMessage, 'student') || str_contains($lowerMessage, 'teacher') ||
            str_contains($lowerMessage, 'aral') || str_contains($lowerMessage, 'exam') ||
            str_contains($lowerMessage, 'test') || str_contains($lowerMessage, 'grade') ||
            str_contains($lowerMessage, 'homework') || str_contains($lowerMessage, 'assignment') ||
            str_contains($lowerMessage, 'scholarship') || str_contains($lowerMessage, 'thesis')) {
            return $this->getEducationResponse($isTagalog, $lowerMessage);
        }

        // ANIMALS & NATURE
        if (str_contains($lowerMessage, 'animal') || str_contains($lowerMessage, 'dog') ||
            str_contains($lowerMessage, 'cat') || str_contains($lowerMessage, 'bird') ||
            str_contains($lowerMessage, 'fish') || str_contains($lowerMessage, 'tree') ||
            str_contains($lowerMessage, 'plant') || str_contains($lowerMessage, 'nature') ||
            str_contains($lowerMessage, 'hayop') || str_contains($lowerMessage, 'aso') ||
            str_contains($lowerMessage, 'pusa') || str_contains($lowerMessage, 'ibon') ||
            str_contains($lowerMessage, 'isda') || str_contains($lowerMessage, 'halaman') ||
            str_contains($lowerMessage, 'kahoy') || str_contains($lowerMessage, 'pet')) {
            return $this->getNatureResponse($isTagalog, $lowerMessage);
        }

        // LANGUAGES
        if (str_contains($lowerMessage, 'language') || str_contains($lowerMessage, 'english') ||
            str_contains($lowerMessage, 'tagalog') || str_contains($lowerMessage, 'filipino') ||
            str_contains($lowerMessage, 'translate') || str_contains($lowerMessage, 'salita') ||
            str_contains($lowerMessage, 'wika') || str_contains($lowerMessage, 'dialect') ||
            str_contains($lowerMessage, 'bisaya') || str_contains($lowerMessage, 'ilocano') ||
            str_contains($lowerMessage, 'kapampangan') || str_contains($lowerMessage, 'waray')) {
            return $this->getLanguageResponse($isTagalog, $lowerMessage);
        }

        // GENERAL LIFE ADVICE
        if (str_contains($lowerMessage, 'life') || str_contains($lowerMessage, 'happiness') ||
            str_contains($lowerMessage, 'success') || str_contains($lowerMessage, 'motivation') ||
            str_contains($lowerMessage, 'advice') || str_contains($lowerMessage, 'tip') ||
            str_contains($lowerMessage, 'problem') || str_contains($lowerMessage, 'solution') ||
            str_contains($lowerMessage, 'help') || str_contains($lowerMessage, 'tulong') ||
            str_contains($lowerMessage, 'guide') || str_contains($lowerMessage, 'step')) {
            return $this->getLifeAdviceResponse($isTagalog);
        }

        // LAW & LEGAL MATTERS
        if (str_contains($lowerMessage, 'law') || str_contains($lowerMessage, 'legal') ||
            str_contains($lowerMessage, 'lawyer') || str_contains($lowerMessage, 'attorney') ||
            str_contains($lowerMessage, 'contract') || str_contains($lowerMessage, 'agreement') ||
            str_contains($lowerMessage, 'sue') || str_contains($lowerMessage, 'court') ||
            str_contains($lowerMessage, 'batas') || str_contains($lowerMessage, 'abogado')) {
            return $this->getLawResponse($isTagalog, $lowerMessage);
        }

        // PSYCHOLOGY & MENTAL HEALTH
        if (str_contains($lowerMessage, 'psychology') || str_contains($lowerMessage, 'therapist') ||
            str_contains($lowerMessage, 'depression') || str_contains($lowerMessage, 'anxiety') ||
            str_contains($lowerMessage, 'therapy') || str_contains($lowerMessage, 'counseling') ||
            str_contains($lowerMessage, 'mental') || str_contains($lowerMessage, 'emotion') ||
            str_contains($lowerMessage, 'feeling') || str_contains($lowerMessage, 'stress') ||
            str_contains($lowerMessage, 'trauma') || str_contains($lowerMessage, 'mind')) {
            return $this->getPsychologyResponse($isTagalog, $lowerMessage);
        }

        // BUSINESS & ENTREPRENEURSHIP
        if (str_contains($lowerMessage, 'business') || str_contains($lowerMessage, 'startup') ||
            str_contains($lowerMessage, 'entrepreneur') || str_contains($lowerMessage, 'company') ||
            str_contains($lowerMessage, 'corporation') || str_contains($lowerMessage, 'enterprise') ||
            str_contains($lowerMessage, 'negosyo') || str_contains($lowerMessage, 'franchise') ||
            str_contains($lowerMessage, 'marketing') || str_contains($lowerMessage, 'sales')) {
            return $this->getBusinessResponse($isTagalog, $lowerMessage);
        }

        // REAL ESTATE & PROPERTY
        if (str_contains($lowerMessage, 'real estate') || str_contains($lowerMessage, 'property') ||
            str_contains($lowerMessage, 'land') || str_contains($lowerMessage, 'house') ||
            str_contains($lowerMessage, 'condo') || str_contains($lowerMessage, 'condominium') ||
            str_contains($lowerMessage, 'mortgage') || str_contains($lowerMessage, 'broker') ||
            str_contains($lowerMessage, 'lupa') || str_contains($lowerMessage, 'bahay') ||
            str_contains($lowerMessage, 'rent to own')) {
            return $this->getRealEstateResponse($isTagalog, $lowerMessage);
        }

        // INSURANCE & PROTECTION
        if (str_contains($lowerMessage, 'insurance') || str_contains($lowerMessage, 'insure') ||
            str_contains($lowerMessage, 'coverage') || str_contains($lowerMessage, 'policy') ||
            str_contains($lowerMessage, 'health insurance') || str_contains($lowerMessage, 'life insurance') ||
            str_contains($lowerMessage, 'car insurance') || str_contains($lowerMessage, 'seguro')) {
            return $this->getInsuranceResponse($isTagalog, $lowerMessage);
        }

        // CRYPTOCURRENCY & BLOCKCHAIN
        if (str_contains($lowerMessage, 'bitcoin') || str_contains($lowerMessage, 'crypto') ||
            str_contains($lowerMessage, 'blockchain') || str_contains($lowerMessage, 'ethereum') ||
            str_contains($lowerMessage, 'nft') || str_contains($lowerMessage, 'defi') ||
            str_contains($lowerMessage, 'trading') || str_contains($lowerMessage, 'wallet') ||
            str_contains($lowerMessage, 'mining') || str_contains($lowerMessage, 'token')) {
            return $this->getCryptoResponse($isTagalog, $lowerMessage);
        }

        // SPACE & ASTRONOMY
        if (str_contains($lowerMessage, 'space') || str_contains($lowerMessage, 'nasa') ||
            str_contains($lowerMessage, 'astronaut') || str_contains($lowerMessage, 'rocket') ||
            str_contains($lowerMessage, 'planet') || str_contains($lowerMessage, 'mars') ||
            str_contains($lowerMessage, 'moon') || str_contains($lowerMessage, 'star') ||
            str_contains($lowerMessage, 'galaxy') || str_contains($lowerMessage, 'telescope') ||
            str_contains($lowerMessage, 'black hole') || str_contains($lowerMessage, 'universe')) {
            return $this->getSpaceResponse($isTagalog, $lowerMessage);
        }

        // ENGINEERING & CONSTRUCTION
        if (str_contains($lowerMessage, 'engineering') || str_contains($lowerMessage, 'engineer') ||
            str_contains($lowerMessage, 'civil') || str_contains($lowerMessage, 'mechanical') ||
            str_contains($lowerMessage, 'electrical') || str_contains($lowerMessage, 'construction') ||
            str_contains($lowerMessage, 'building') || str_contains($lowerMessage, 'architect') ||
            str_contains($lowerMessage, 'blueprint') || str_contains($lowerMessage, 'structure')) {
            return $this->getEngineeringResponse($isTagalog, $lowerMessage);
        }

        // MUSIC & ARTS
        if (str_contains($lowerMessage, 'music') || str_contains($lowerMessage, 'song') ||
            str_contains($lowerMessage, 'musician') || str_contains($lowerMessage, 'instrument') ||
            str_contains($lowerMessage, 'guitar') || str_contains($lowerMessage, 'piano') ||
            str_contains($lowerMessage, 'singing') || str_contains($lowerMessage, 'concert') ||
            str_contains($lowerMessage, 'opm') || str_contains($lowerMessage, 'kpop') ||
            str_contains($lowerMessage, 'opera') || str_contains($lowerMessage, 'symphony')) {
            return $this->getMusicResponse($isTagalog, $lowerMessage);
        }

        // LITERATURE & BOOKS
        if (str_contains($lowerMessage, 'book') || str_contains($lowerMessage, 'novel') ||
            str_contains($lowerMessage, 'author') || str_contains($lowerMessage, 'writer') ||
            str_contains($lowerMessage, 'poetry') || str_contains($lowerMessage, 'poem') ||
            str_contains($lowerMessage, 'story') || str_contains($lowerMessage, 'fiction') ||
            str_contains($lowerMessage, 'library') || str_contains($lowerMessage, 'librarian') ||
            str_contains($lowerMessage, 'bestseller') || str_contains($lowerMessage, 'reading')) {
            return $this->getLiteratureResponse($isTagalog, $lowerMessage);
        }

        // MOVIES & CINEMA
        if (str_contains($lowerMessage, 'movie') || str_contains($lowerMessage, 'film') ||
            str_contains($lowerMessage, 'cinema') || str_contains($lowerMessage, 'actor') ||
            str_contains($lowerMessage, 'actress') || str_contains($lowerMessage, 'director') ||
            str_contains($lowerMessage, 'hollywood') || str_contains($lowerMessage, 'bollywood') ||
            str_contains($lowerMessage, 'marvel') || str_contains($lowerMessage, 'disney') ||
            str_contains($lowerMessage, 'netflix') || str_contains($lowerMessage, 'series')) {
            return $this->getMoviesResponse($isTagalog, $lowerMessage);
        }

        // PHOTOGRAPHY & VISUAL ARTS
        if (str_contains($lowerMessage, 'photography') || str_contains($lowerMessage, 'camera') ||
            str_contains($lowerMessage, 'photo') || str_contains($lowerMessage, 'picture') ||
            str_contains($lowerMessage, 'lens') || str_contains($lowerMessage, 'dslr') ||
            str_contains($lowerMessage, 'portrait') || str_contains($lowerMessage, 'landscape') ||
            str_contains($lowerMessage, 'editing') || str_contains($lowerMessage, 'photoshop')) {
            return $this->getPhotographyResponse($isTagalog, $lowerMessage);
        }

        // FITNESS & BODYBUILDING
        if (str_contains($lowerMessage, 'gym') || str_contains($lowerMessage, 'workout') ||
            str_contains($lowerMessage, 'fitness') || str_contains($lowerMessage, 'exercise') ||
            str_contains($lowerMessage, 'bodybuilding') || str_contains($lowerMessage, 'muscle') ||
            str_contains($lowerMessage, 'weightlifting') || str_contains($lowerMessage, 'cardio') ||
            str_contains($lowerMessage, 'protein') || str_contains($lowerMessage, 'supplement') ||
            str_contains($lowerMessage, 'diet') || str_contains($lowerMessage, 'calorie')) {
            return $this->getFitnessResponse($isTagalog, $lowerMessage);
        }

        // NUTRITION & DIET
        if (str_contains($lowerMessage, 'nutrition') || str_contains($lowerMessage, 'diet') ||
            str_contains($lowerMessage, 'vegan') || str_contains($lowerMessage, 'vegetarian') ||
            str_contains($lowerMessage, 'keto') || str_contains($lowerMessage, 'paleo') ||
            str_contains($lowerMessage, 'calorie') || str_contains($lowerMessage, 'vitamin') ||
            str_contains($lowerMessage, 'protein') || str_contains($lowerMessage, 'carb') ||
            str_contains($lowerMessage, 'organic') || str_contains($lowerMessage, 'gluten')) {
            return $this->getNutritionResponse($isTagalog, $lowerMessage);
        }

        // PARENTING & FAMILY
        if (str_contains($lowerMessage, 'parent') || str_contains($lowerMessage, 'mother') ||
            str_contains($lowerMessage, 'father') || str_contains($lowerMessage, 'mom') ||
            str_contains($lowerMessage, 'dad') || str_contains($lowerMessage, 'baby') ||
            str_contains($lowerMessage, 'toddler') || str_contains($lowerMessage, 'teenager') ||
            str_contains($lowerMessage, 'child') || str_contains($lowerMessage, 'kid') ||
            str_contains($lowerMessage, 'raising') || str_contains($lowerMessage, 'discipline')) {
            return $this->getParentingResponse($isTagalog, $lowerMessage);
        }

        // DATING & ROMANCE
        if (str_contains($lowerMessage, 'dating') || str_contains($lowerMessage, 'date') ||
            str_contains($lowerMessage, 'romance') || str_contains($lowerMessage, 'romantic') ||
            str_contains($lowerMessage, 'valentine') || str_contains($lowerMessage, 'anniversary') ||
            str_contains($lowerMessage, 'gift') || str_contains($lowerMessage, 'flowers') ||
            str_contains($lowerMessage, 'love letter') || str_contains($lowerMessage, 'courtship') ||
            str_contains($lowerMessage, 'ligaw') || str_contains($lowerMessage, 'manliligaw')) {
            return $this->getDatingResponse($isTagalog, $lowerMessage);
        }

        // WEDDING & MARRIAGE
        if (str_contains($lowerMessage, 'wedding') || str_contains($lowerMessage, 'marriage') ||
            str_contains($lowerMessage, 'bride') || str_contains($lowerMessage, 'groom') ||
            str_contains($lowerMessage, 'honeymoon') || str_contains($lowerMessage, 'engagement') ||
            str_contains($lowerMessage, 'proposal') || str_contains($lowerMessage, 'kasal') ||
            str_contains($lowerMessage, 'reception') || str_contains($lowerMessage, 'vows')) {
            return $this->getWeddingResponse($isTagalog, $lowerMessage);
        }

        // PETS & ANIMALS
        if (str_contains($lowerMessage, 'pet') || str_contains($lowerMessage, 'dog') ||
            str_contains($lowerMessage, 'cat') || str_contains($lowerMessage, 'puppy') ||
            str_contains($lowerMessage, 'kitten') || str_contains($lowerMessage, 'bird') ||
            str_contains($lowerMessage, 'fish') || str_contains($lowerMessage, 'hamster') ||
            str_contains($lowerMessage, 'veterinary') || str_contains($lowerMessage, 'vet') ||
            str_contains($lowerMessage, 'aso') || str_contains($lowerMessage, 'pusa')) {
            return $this->getPetsResponse($isTagalog, $lowerMessage);
        }

        // GARDENING & PLANTS
        if (str_contains($lowerMessage, 'garden') || str_contains($lowerMessage, 'plant') ||
            str_contains($lowerMessage, 'flower') || str_contains($lowerMessage, 'tree') ||
            str_contains($lowerMessage, 'seed') || str_contains($lowerMessage, 'soil') ||
            str_contains($lowerMessage, 'fertilizer') || str_contains($lowerMessage, 'compost') ||
            str_contains($lowerMessage, 'orchid') || str_contains($lowerMessage, 'rose') ||
            str_contains($lowerMessage, 'bonsai') || str_contains($lowerMessage, 'landscaping')) {
            return $this->getGardeningResponse($isTagalog, $lowerMessage);
        }

        // SMART FALLBACK: Try to analyze the question and give contextual response
        return $this->getSmartFallbackResponse($isTagalog, $originalMessage, $lowerMessage);
    }

    /**
     * AI/Technology response
     */
    private function getAIResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Tungkol sa Artificial Intelligence**\n\n" .
                "Ang AI ay mga computer system na nakakagawa ng mga task na karaniwang nangangailangan ng human intelligence.\n\n" .
                "**Mga Uri ng AI:**\n" .
                "- Machine Learning - natututo mula sa data\n" .
                "- Natural Language Processing - pag-unawa sa human language\n" .
                "- Computer Vision - pagkilala sa images at videos\n\n" .
                "**Paggamit sa Pang-araw-araw na Buhay:**\n" .
                "- Virtual assistants (Siri, Alexa, Google Assistant)\n" .
                "- Recommendation systems (Netflix, Spotify)\n" .
                "- Navigation apps (Google Maps, Waze)\n\n" .
                "**Trivia:** Ako mismo ay isang AI assistant! Ginagamit ko ang natural language processing para makipag-usap sa iyo.";
        }

        return "**About Artificial Intelligence**\n\n" .
            "AI refers to computer systems that can perform tasks that normally require human intelligence.\n\n" .
            "**Types of AI:**\n" .
            "- Machine Learning - learns from data\n" .
            "- Natural Language Processing - understands human language\n" .
            "- Computer Vision - recognizes images and videos\n\n" .
            "**Everyday Uses:**\n" .
            "- Virtual assistants (Siri, Alexa, Google Assistant)\n" .
            "- Recommendation systems (Netflix, Spotify)\n" .
            "- Navigation apps (Google Maps, Waze)\n\n" .
            "**Trivia:** I am an AI assistant myself! I use natural language processing to chat with you.";
    }

    /**
     * Programming response
     */
    private function getProgrammingResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Tungkol sa Programming**\n\n" .
                "Ang programming ay ang proseso ng pagsulat ng instructions para sa computer.\n\n" .
                "**Mga Sikat na Programming Language:**\n" .
                "- Python - maganda para sa beginners, data science, AI\n" .
                "- JavaScript - para sa web development\n" .
                "- Java - para sa mobile at enterprise apps\n" .
                "- PHP - para sa web servers (tulad ng Find My Roommate!)\n\n" .
                "**Bakit Magandang Matutong Mag-code:**\n" .
                "- Problem-solving skills\n" .
                "- Maraming job opportunities\n" .
                "- Pwedeng gumawa ng sariling apps at websites\n\n" .
                "**Tip:** Simulan sa Python kung beginner ka - madali itong matutunan!";
        }

        return "**About Programming**\n\n" .
            "Programming is the process of writing instructions for computers.\n\n" .
            "**Popular Programming Languages:**\n" .
            "- Python - great for beginners, data science, AI\n" .
            "- JavaScript - for web development\n" .
            "- Java - for mobile and enterprise apps\n" .
            "- PHP - for web servers (like Find My Roommate!)\n\n" .
            "**Why Learn to Code:**\n" .
            "- Problem-solving skills\n" .
            "- Many job opportunities\n" .
            "- Build your own apps and websites\n\n" .
            "**Tip:** Start with Python if you're a beginner - it's easy to learn!";
    }

    /**
     * Science response
     */
    private function getScienceResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Tungkol sa Science**\n\n" .
                "Ang science ay ang systematic study ng natural world.\n\n" .
                "**Mga Pangunahing Sangay:**\n" .
                "- Physics - pag-aaral ng matter, energy, at forces\n" .
                "- Chemistry - pag-aaral ng substances at reactions\n" .
                "- Biology - pag-aaral ng living organisms\n" .
                "- Earth Science - geology, meteorology, oceanography\n\n" .
                "**Bakit Mahalaga ang Science:**\n" .
                "- Nauunawaan natin ang mundo sa paligid natin\n" .
                "- Nagdudulot ng mga technological advancements\n" .
                "- Nagsosolve ng real-world problems\n\n" .
                "Science is all around us - from the device you're using to the food you eat!";
        }

        return "**About Science**\n\n" .
            "Science is the systematic study of the natural world.\n\n" .
            "**Main Branches:**\n" .
            "- Physics - study of matter, energy, and forces\n" .
            "- Chemistry - study of substances and reactions\n" .
            "- Biology - study of living organisms\n" .
            "- Earth Science - geology, meteorology, oceanography\n\n" .
            "**Why Science Matters:**\n" .
            "- Helps us understand the world around us\n" .
            "- Drives technological advancements\n" .
            "- Solves real-world problems\n\n" .
            "Science is all around us - from the device you're using to the food you eat!";
    }

    /**
     * Health response
     */
    private function getHealthResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Tungkol sa Health at Fitness**\n\n" .
                "Ang kalusugan ay kayamanan - importante ang pag-aalaga ng ating katawan at isip.\n\n" .
                "**Pillars of Health:**\n" .
                "- Nutrition - balanced diet with fruits, vegetables, proteins\n" .
                "- Exercise - at least 30 minutes daily activity\n" .
                "- Sleep - 7-9 hours para sa adults\n" .
                "- Mental Health - stress management, social connections\n\n" .
                "**Simple Health Tips:**\n" .
                "- Uminom ng sapat na tubig (8 glasses/day)\n" .
                "- Kumain ng rainbow - iba't ibang colors ng prutas at gulay\n" .
                "- Huminga ng malalim para ma-reduce ang stress\n\n" .
                "Small consistent habits lead to big health improvements!";
        }

        return "**About Health and Fitness**\n\n" .
            "Health is wealth - taking care of our body and mind is essential.\n\n" .
            "**Pillars of Health:**\n" .
            "- Nutrition - balanced diet with fruits, vegetables, proteins\n" .
            "- Exercise - at least 30 minutes daily activity\n" .
            "- Sleep - 7-9 hours for adults\n" .
            "- Mental Health - stress management, social connections\n\n" .
            "**Simple Health Tips:**\n" .
            "- Drink plenty of water (8 glasses/day)\n" .
            "- Eat the rainbow - different colored fruits and vegetables\n" .
            "- Take deep breaths to reduce stress\n\n" .
            "Small consistent habits lead to big health improvements!";
    }

    /**
     * Life advice response
     */
    private function getLifeAdviceResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Life Advice at Motivation**\n\n" .
                "Life is a journey, not a destination. Here are some timeless reminders:\n\n" .
                "**Growth Mindset:**\n" .
                "- Ang failures ay opportunities para matuto\n" .
                "- Consistency beats intensity\n" .
                "- Compare yourself only to your past self\n\n" .
                "**Relationships:**\n" .
                "- Quality over quantity sa friendships\n" .
                "- Active listening builds stronger connections\n" .
                "- Set healthy boundaries\n\n" .
                "**Success:**\n" .
                "- Define success on your own terms\n" .
                "- Small daily progress compounds over time\n" .
                "- Rest is productive too\n\n" .
                "You've got this!";
        }

        return "**Life Advice and Motivation**\n\n" .
            "Life is a journey, not a destination. Here are some timeless reminders:\n\n" .
            "**Growth Mindset:**\n" .
            "- Failures are opportunities to learn\n" .
            "- Consistency beats intensity\n" .
            "- Compare yourself only to your past self\n\n" .
            "**Relationships:**\n" .
            "- Quality over quantity in friendships\n" .
            "- Active listening builds stronger connections\n" .
            "- Set healthy boundaries\n\n" .
            "**Success:**\n" .
            "- Define success on your own terms\n" .
            "- Small daily progress compounds over time\n" .
            "- Rest is productive too\n\n" .
            "You've got this!";
    }

    /**
     * Default response for unknown questions
     */
    private function getDefaultResponse(bool $isTagalog): string
    {
        if ($isTagalog) {
            return "**Kamusta! Ako ang Find My Roommate AI Assistant.**\n\n" .
                "Maaari kitang tulungan sa:\n" .
                "- Paghanap ng roommate\n" .
                "- Profile tips\n" .
                "- Matching information\n" .
                "- General advice\n" .
                "- Safety tips\n\n" .
                "May iba ka pang tanong? I'm happy to help!";
        }

        return "**Hello! I'm the Find My Roommate AI Assistant.**\n\n" .
            "I can help you with:\n" .
            "- Finding roommates\n" .
            "- Profile tips\n" .
            "- Matching information\n" .
            "- General advice\n" .
            "- Safety tips\n\n" .
            "Have another question? I'm happy to help!";
    }

    /**
     * Generic response - ChatGPT style conversational fallback
     */
    private function getGenericResponse(bool $isTagalog, string $originalMessage): string
    {
        if ($isTagalog) {
            return "Medyo hindi ko nakuha ang iyong tanong, pero gusto kitang tulungan!\n\n" .
                "Pwede kitang sagutin tungkol sa:\n\n" .
                "🏠 **Roommate Finding** - paano maghanap ng kasama sa bahay\n" .
                "🎓 **Universities** - PSU, UPANG, L-NU, etc.\n" .
                "🍜 **Pagkain** - Filipino recipes, restaurants sa Dagupan\n" .
                "🏖️ **Travel** - mga beach sa Pangasinan (Patar, Hundred Islands)\n" .
                "💼 **Career & Money** - jobs, business tips, investment\n" .
                "❤️ **Relationships** - dating advice, friendships\n" .
                "🎬 **Entertainment** - movies, music, K-pop\n" .
                "📚 **Education** - study tips, scholarships\n" .
                "🏥 **Health** - mental health, fitness, nutrition\n\n" .
                "Ano bang specific ang gusto mong malaman? Sabihin mo lang!";
        }
        return "I didn't quite catch that, but I'd love to help you!\n\n" .
            "I can help with a wide range of topics! Here are some things you can ask me:\n\n" .
            "**Local (Pangasinan):**\n" .
            "• Universities - PSU, UPANG, L-NU, course offerings\n" .
            "• Beaches - Patar, Hundred Islands, how to get there\n" .
            "• Roommates - finding, safety tips, compatibility\n" .
            "• Food - best restaurants, Filipino recipes\n\n" .
            "**General Topics:**\n" .
            "• Study tips, exam preparation, scholarships\n" .
            "• Jobs, careers, business ideas, investing\n" .
            "• Health, fitness, mental wellness advice\n" .
            "• Technology, AI, programming, crypto\n" .
            "• Relationships, dating, friendships\n" .
            "• Movies, music, games, entertainment\n\n" .
            "**Try asking:** 'What are the best beaches in Pangasinan?' or 'Tell me about PSU'\n\n" .
            "What would you like to know? I'm here to help!";
    }

    /**
     * Smart fallback response - analyzes the question and gives contextual answer
     */
    private function getSmartFallbackResponse(bool $isTagalog, string $originalMessage, string $lowerMessage): string
    {
        // Extract key words from the message
        $words = explode(' ', $lowerMessage);
        $keyTerms = [];
        foreach ($words as $word) {
            if (strlen($word) > 3) {
                $keyTerms[] = $word;
            }
        }

        // Analyze question patterns
        $isQuestion = str_contains($lowerMessage, '?') ||
                     str_contains($lowerMessage, 'ano') ||
                     str_contains($lowerMessage, 'what') ||
                     str_contains($lowerMessage, 'how') ||
                     str_contains($lowerMessage, 'paano') ||
                     str_contains($lowerMessage, 'why') ||
                     str_contains($lowerMessage, 'bakit') ||
                     str_contains($lowerMessage, 'where') ||
                     str_contains($lowerMessage, 'saan') ||
                     str_contains($lowerMessage, 'when') ||
                     str_contains($lowerMessage, 'kailan') ||
                     str_contains($lowerMessage, 'who') ||
                     str_contains($lowerMessage, 'sino');

        if ($isTagalog) {
            $response = "Nakuha ko ang tanong mo! ";

            if ($isQuestion) {
                $response .= "Gusto mong malaman tungkol sa **" . implode(', ', array_slice($keyTerms, 0, 3)) . "**.\n\n";
            } else {
                $response .= "Nag-share ka tungkol sa **" . implode(', ', array_slice($keyTerms, 0, 3)) . "**.\n\n";
            }

            $response .= "Pasensya, pero sa ngayon mas nauunawaan ko ang mga tanong tungkol sa:\n" .
                "• Mga university sa Pangasinan (PSU, UPANG, etc.)\n" .
                "• Beaches at tourist spots\n" .
                "• Pagkain at recipes\n" .
                "• Roommate finding tips\n" .
                "• General knowledge\n\n" .
                "Pwede mo bang i-rephrase ang tanong mo para mas maintindihan ko? O kaya tanungin mo ako tungkol sa mga topics na nabanggit ko. 😊";

            return $response;
        }

        $response = "I got your question about **" . implode(', ', array_slice($keyTerms, 0, 3)) . "**!\n\n";

        if ($isQuestion) {
            $response .= "You're asking about " . implode(', ', array_slice($keyTerms, 0, 3)) . ".\n\n";
        }

        $response .= "I currently understand questions about:\n" .
            "• Pangasinan universities (PSU, UPANG, L-NU, etc.)\n" .
            "• Beaches and tourist spots\n" .
            "• Food and Filipino recipes\n" .
            "• Roommate finding and safety\n" .
            "• Health, careers, technology, and more!\n\n" .
            "Could you rephrase your question? Or try asking about one of the topics above! 😊";

        return $response;
    }

    /**
     * Food and cooking response
     */
    private function getFoodResponse(bool $isTagalog, string $lowerMessage): string
    {
        if (str_contains($lowerMessage, 'adobo')) {
            if ($isTagalog) {
                return "**Paano Magluto ng Adobo**\n\n" .
                    "Ang adobo ay pambansang ulam ng Pilipinas!\n\n" .
                    "**Sangkap:**\n" .
                    "- 1 kg chicken/pork\n" .
                    "- 1/2 cup soy sauce\n" .
                    "- 1/3 cup vinegar\n" .
                    "- 5 cloves garlic\n" .
                    "- 3 bay leaves\n" .
                    "- 1 tsp peppercorns\n" .
                    "- 1 cup water\n\n" .
                    "**Paraan:**\n" .
                    "1. I-marinate 30 minuto\n" .
                    "2. Igisa, ilagay vinegar at tubig\n" .
                    "3. Pakuluin ng 30-40 minuto\n" .
                    "4. Serve with rice!";
            }
            return "**How to Cook Adobo**\n\n" .
                "National dish of the Philippines!\n\n" .
                "**Ingredients:**\n" .
                "- 1 kg chicken/pork\n" .
                "- 1/2 cup soy sauce\n" .
                "- 1/3 cup vinegar\n" .
                "- 5 cloves garlic\n" .
                "- 3 bay leaves\n" .
                "- 1 tsp peppercorns\n" .
                "- 1 cup water\n\n" .
                "**Steps:**\n" .
                "1. Marinate 30 minutes\n" .
                "2. Saute, add vinegar and water\n" .
                "3. Simmer 30-40 minutes\n" .
                "4. Serve with rice!";
        }

        if ($isTagalog) {
            return "**Tungkol sa Pagkain**\n\n" .
                "**Filipino Dishes:**\n" .
                "- Adobo: soy sauce at vinegar\n" .
                "- Sinigang: sour soup\n" .
                "- Lechon: roasted pig\n" .
                "- Kare-kare: peanut stew\n" .
                "- Pancit: noodles\n" .
                "- Lumpia: spring rolls\n\n" .
                "Gusto mo ng specific recipe?";
        }
        return "**About Food**\n\n" .
            "**Filipino Dishes:**\n" .
            "- Adobo: soy sauce and vinegar\n" .
            "- Sinigang: sour soup\n" .
            "- Lechon: roasted pig\n" .
            "- Kare-kare: peanut stew\n" .
            "- Pancit: noodles\n" .
            "- Lumpia: spring rolls\n\n" .
            "Want a specific recipe?";
    }

    /**
     * History response
     */
    private function getHistoryResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Kasaysayan ng Pilipinas**\n\n" .
                "**Important Dates:**\n" .
                "- 1521: Pagdating ni Magellan\n" .
                "- 1898: Independence (Katipunan)\n" .
                "- 1942-1945: Japanese Occupation\n" .
                "- 1946: Full Independence\n" .
                "- 1986: EDSA Revolution\n\n" .
                "**World History:**\n" .
                "- Ancient: Egypt, Greece, Rome\n" .
                "- Medieval: Knights, Castles\n" .
                "- Modern: World Wars";
        }
        return "**Philippine History**\n\n" .
            "**Important Dates:**\n" .
            "- 1521: Magellan's arrival\n" .
            "- 1898: Independence (Katipunan)\n" .
            "- 1942-1945: Japanese Occupation\n" .
            "- 1946: Full Independence\n" .
            "- 1986: EDSA Revolution\n\n" .
            "**World History:**\n" .
            "- Ancient: Egypt, Greece, Rome\n" .
            "- Medieval: Knights, Castles\n" .
            "- Modern: World Wars";
    }

    /**
     * Geography response
     */
    private function getGeographyResponse(bool $isTagalog, string $lowerMessage): string
    {
        if (str_contains($lowerMessage, 'philippines') || str_contains($lowerMessage, 'pilipinas')) {
            if ($isTagalog) {
                return "**Ang Pilipinas**\n\n" .
                    "- Capital: Manila\n" .
                    "- Population: 110+ million\n" .
                    "- 7,641 islands\n" .
                    "- Languages: Filipino, English\n" .
                    "- Currency: Peso (PHP)\n\n" .
                    "**Famous Places:**\n" .
                    "- Boracay, Palawan, Banaue\n" .
                    "- Manila, Cebu, Davao";
            }
            return "**The Philippines**\n\n" .
                "- Capital: Manila\n" .
                "- Population: 110+ million\n" .
                "- 7,641 islands\n" .
                "- Languages: Filipino, English\n" .
                "- Currency: Peso (PHP)\n\n" .
                "**Famous Places:**\n" .
                "- Boracay, Palawan, Banaue\n" .
                "- Manila, Cebu, Davao";
        }

        if ($isTagalog) {
            return "**Heograpiya**\n\n" .
                "**Continents:**\n" .
                "- Asia, Europe, Africa\n" .
                "- North/South America\n" .
                "- Australia, Antarctica\n\n" .
                "**Largest Countries:**\n" .
                "- Russia (area)\n" .
                "- China (population)";
        }
        return "**Geography**\n\n" .
            "**Continents:**\n" .
            "- Asia, Europe, Africa\n" .
            "- North/South America\n" .
            "- Australia, Antarctica\n\n" .
            "**Largest Countries:**\n" .
            "- Russia (area)\n" .
            "- China (population)";
    }

    /**
     * Math response
     */
    private function getMathResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Matematika**\n\n" .
                "**Basic Operations:**\n" .
                "- Addition (+): pagdagdag\n" .
                "- Subtraction (-): pagbawas\n" .
                "- Multiplication (x): pagpaparami\n" .
                "- Division (/): paghati\n\n" .
                "**Useful Concepts:**\n" .
                "- Percentages: 10% = 0.10\n" .
                "- Fractions: 1/2 = 0.5\n" .
                "- Algebra, Geometry";
        }
        return "**Mathematics**\n\n" .
            "**Basic Operations:**\n" .
            "- Addition (+)\n" .
            "- Subtraction (-)\n" .
            "- Multiplication (x)\n" .
            "- Division (/)\n\n" .
            "**Useful Concepts:**\n" .
            "- Percentages: 10% = 0.10\n" .
            "- Fractions: 1/2 = 0.5\n" .
            "- Algebra, Geometry";
    }

    /**
     * Technology response
     */
    private function getTechResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Teknolohiya**\n\n" .
                "**Internet:**\n" .
                "- Global network ng computers\n" .
                "- Email, social media\n" .
                "- Online shopping, banking\n\n" .
                "**Smartphones:**\n" .
                "- Mini computer sa bulsa\n" .
                "- Apps, camera, GPS\n\n" .
                "**Tips:**\n" .
                "- Strong passwords\n" .
                "- Regular backups";
        }
        return "**Technology**\n\n" .
            "**Internet:**\n" .
            "- Global computer network\n" .
            "- Email, social media\n" .
            "- Online shopping, banking\n\n" .
            "**Smartphones:**\n" .
            "- Mini computer in pocket\n" .
            "- Apps, camera, GPS\n\n" .
            "**Tips:**\n" .
            "- Use strong passwords\n" .
            "- Regular backups";
    }

    /**
     * Relationships response
     */
    private function getRelationshipResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Relasyon at Pag-ibig**\n\n" .
                "**Healthy Tips:**\n" .
                "- Communication: open at honest\n" .
                "- Respect: igalang boundaries\n" .
                "- Trust: foundation ng relasyon\n" .
                "- Quality time: magkasama\n\n" .
                "**Dating Tips:**\n" .
                "- Maging totoo\n" .
                "- Makinig at magtanong\n" .
                "- Be respectful";
        }
        return "**Relationships and Love**\n\n" .
            "**Healthy Tips:**\n" .
            "- Communication: open and honest\n" .
            "- Respect: honor boundaries\n" .
            "- Trust: foundation of relationship\n" .
            "- Quality time: together\n\n" .
            "**Dating Tips:**\n" .
            "- Be authentic\n" .
            "- Listen and ask questions\n" .
            "- Be respectful";
    }

    /**
     * Finance response
     */
    private function getFinanceResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Pera at Finansya**\n\n" .
                "**Saving Tips:**\n" .
                "- Pay yourself first\n" .
                "- 50/30/20 rule\n" .
                "- Emergency fund: 3-6 months\n\n" .
                "**Budgeting:**\n" .
                "- Track expenses\n" .
                "- Cut unnecessary spending\n\n" .
                "**Investing:**\n" .
                "- Stocks, Bonds, Mutual funds";
        }
        return "**Money and Finance**\n\n" .
            "**Saving Tips:**\n" .
            "- Pay yourself first\n" .
            "- 50/30/20 rule\n" .
            "- Emergency fund: 3-6 months\n\n" .
            "**Budgeting:**\n" .
            "- Track expenses\n" .
            "- Cut unnecessary spending\n\n" .
            "**Investing:**\n" .
            "- Stocks, Bonds, Mutual funds";
    }

    /**
     * Career response
     */
    private function getCareerResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Trabaho at Karera**\n\n" .
                "**Job Hunting:**\n" .
                "- Update resume/CV\n" .
                "- Build LinkedIn\n" .
                "- Network\n\n" .
                "**Interview Tips:**\n" .
                "- Research company\n" .
                "- Dress professionally\n" .
                "- Ask questions\n" .
                "- Follow up";
        }
        return "**Jobs and Career**\n\n" .
            "**Job Hunting:**\n" .
            "- Update resume/CV\n" .
            "- Build LinkedIn\n" .
            "- Network\n\n" .
            "**Interview Tips:**\n" .
            "- Research company\n" .
            "- Dress professionally\n" .
            "- Ask questions\n" .
            "- Follow up";
    }

    /**
     * Entertainment response
     */
    private function getEntertainmentResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Entertainment**\n\n" .
                "**Movies:**\n" .
                "- Action, Romance, Comedy\n" .
                "- Filipino: Rom-com, Drama\n" .
                "- Netflix, Disney+\n\n" .
                "**Music:**\n" .
                "- Pop, Rock, Hip-hop\n" .
                "- Spotify, YouTube\n\n" .
                "**Games:**\n" .
                "- Mobile Legends, Genshin\n" .
                "- PC/Console games";
        }
        return "**Entertainment**\n\n" .
            "**Movies:**\n" .
            "- Action, Romance, Comedy\n" .
            "- Filipino: Rom-com, Drama\n" .
            "- Netflix, Disney+\n\n" .
            "**Music:**\n" .
            "- Pop, Rock, Hip-hop\n" .
            "- Spotify, YouTube\n\n" .
            "**Games:**\n" .
            "- Mobile Legends, Genshin\n" .
            "- PC/Console games";
    }

    /**
     * Sports response
     */
    private function getSportsResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Sports**\n\n" .
                "**Popular:**\n" .
                "- Basketball (Pinoy favorite)\n" .
                "- Football/Soccer\n" .
                "- Volleyball, Badminton\n\n" .
                "**Benefits:**\n" .
                "- Stronger body\n" .
                "- Better mental health\n" .
                "- Weight management\n\n" .
                "**Famous Athletes:**\n" .
                "- Pacquiao, Hidilyn Diaz";
        }
        return "**Sports**\n\n" .
            "**Popular:**\n" .
            "- Basketball (Pinoy favorite)\n" .
            "- Football/Soccer\n" .
            "- Volleyball, Badminton\n\n" .
            "**Benefits:**\n" .
            "- Stronger body\n" .
            "- Better mental health\n" .
            "- Weight management\n\n" .
            "**Famous Athletes:**\n" .
            "- Pacquiao, Hidilyn Diaz";
    }

    /**
     * Travel response - ChatGPT style
     */
    private function getTravelResponse(bool $isTagalog, string $lowerMessage): string
    {
        // Check if asking about beaches specifically
        if (str_contains($lowerMessage, 'beach') || str_contains($lowerMessage, 'beaches')) {
            if ($isTagalog) {
                return "Ang Pangasinan ay kilala sa mga magagandang beach! Here are the most famous ones:\n\n" .
                    "**🏖️ Patar Beach (Bolinao)**\n" .
                    "- White sand beach na perfect for swimming\n" .
                    "- Malapit sa Cape Bolinao Lighthouse\n" .
                    "- Best sunset views\n\n" .
                    "**🏝️ Hundred Islands (Alaminos)**\n" .
                    "- 124 islands to explore\n" .
                    "- Island hopping, snorkeling, zip-lining\n" .
                    "- Day tour costs around ₱1,000-1,500\n\n" .
                    "**🌊 Tondol Beach (Anda)**\n" .
                    "- Long sandbar na pwede lakarin\n" .
                    "- Shallow waters - safe for families\n\n" .
                    "**🐚 Sabangan Beach**\n" .
                    "- Quiet and less crowded\n\n" .
                    "Best time to visit: November to May (dry season). Gusto mo bang malaman ang how to get there?";
            }
            return "Pangasinan is famous for its beautiful beaches! Here are the top ones:\n\n" .
                "**🏖️ Patar Beach (Bolinao)**\n" .
                "- White sand beach perfect for swimming\n" .
                "- Near the historic Cape Bolinao Lighthouse\n" .
                "- Amazing sunset views\n\n" .
                "**🏝️ Hundred Islands (Alaminos)**\n" .
                "- 124 islands to explore\n" .
                "- Activities: Island hopping, snorkeling, zip-lining\n" .
                "- Day tour costs around ₱1,000-1,500\n\n" .
                "**🌊 Tondol Beach (Anda)**\n" .
                "- Long sandbar you can walk across\n" .
                "- Shallow waters - great for families\n\n" .
                "**🐚 Sabangan Beach**\n" .
                "- Quiet and less crowded\n\n" .
                "Best time to visit: November to May (dry season). Would you like to know how to get to any of these beaches?";
        }

        if ($isTagalog) {
            return "Gusto mong mag-travel? Here are some helpful tips:\n\n" .
                "**📍 Pangasinan Destinations**\n" .
                "- Hundred Islands (Alaminos) - island hopping\n" .
                "- Patar Beach (Bolinao) - white sand beach\n" .
                "- Our Lady of Manaoag Church - religious site\n" .
                "- Lingayen Beach - near the Capitol\n\n" .
                "**✈️ Travel Tips**\n" .
                "- Plan your itinerary ahead\n" .
                "- Pack light, bring sunscreen\n" .
                "- Keep important documents safe\n\n" .
                "**💰 Budget Tips**\n" .
                "- Travel during weekdays para mas mura\n" .
                "- Book accommodations early\n\n" .
                "Saan ka gustong pumunta? Pwede kitang bigyan ng specific recommendations!";
        }
        return "Looking to travel? Here are some helpful tips:\n\n" .
            "**📍 Pangasinan Destinations**\n" .
            "- Hundred Islands (Alaminos) - island hopping\n" .
            "- Patar Beach (Bolinao) - white sand beach\n" .
            "- Our Lady of Manaoag Church - religious site\n" .
            "- Lingayen Beach - near the Capitol\n\n" .
            "**✈️ Travel Tips**\n" .
            "- Plan your itinerary ahead\n" .
            "- Pack light, bring sunscreen\n" .
            "- Keep important documents safe\n\n" .
            "**💰 Budget Tips**\n" .
            "- Travel during weekdays for better rates\n" .
            "- Book accommodations early\n\n" .
            "Where would you like to go? I can give you specific recommendations!";
    }

    /**
     * Time response
     */
    private function getTimeResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Oras at Petsa**\n\n" .
                "**Time Zones:**\n" .
                "- Philippines: GMT+8\n" .
                "- Different countries, different times\n\n" .
                "**Time Management:**\n" .
                "- Prioritize tasks\n" .
                "- Use calendar/planner\n" .
                "- Avoid procrastination";
        }
        return "**Time and Date**\n\n" .
            "**Time Zones:**\n" .
            "- Philippines: GMT+8\n" .
            "- Different countries, different times\n\n" .
            "**Time Management:**\n" .
            "- Prioritize tasks\n" .
            "- Use calendar/planner\n" .
            "- Avoid procrastination";
    }

    /**
     * Weather response
     */
    private function getWeatherResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Panahon**\n\n" .
                "**Philippines Climate:**\n" .
                "- Tropical: mainit at maulan\n" .
                "- Dry season: Nov-May\n" .
                "- Wet season: Jun-Oct\n\n" .
                "**Typhoon Safety:**\n" .
                "- Stock food and water\n" .
                "- Charge devices\n" .
                "- Stay indoors";
        }
        return "**Weather**\n\n" .
            "**Philippines Climate:**\n" .
            "- Tropical: hot and rainy\n" .
            "- Dry season: Nov-May\n" .
            "- Wet season: Jun-Oct\n\n" .
            "**Typhoon Safety:**\n" .
            "- Stock food and water\n" .
            "- Charge devices\n" .
            "- Stay indoors";
    }

    /**
     * Philosophy response
     */
    private function getPhilosophyResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Meaning ng Buhay**\n\n" .
                "Ang buhay ay iba-iba para sa bawat tao.\n\n" .
                "**Common Purposes:**\n" .
                "- Relationships: family, friends\n" .
                "- Growth: learning, improving\n" .
                "- Contribution: helping others\n" .
                "- Happiness: enjoying life\n\n" .
                "**Key Insight:**\n" .
                "Hindi perfection ang goal, kundi progress.";
        }
        return "**Meaning of Life**\n\n" .
            "Life means different things to different people.\n\n" .
            "**Common Purposes:**\n" .
            "- Relationships: family, friends\n" .
            "- Growth: learning, improving\n" .
            "- Contribution: helping others\n" .
            "- Happiness: enjoying life\n\n" .
            "**Key Insight:**\n" .
            "The goal isn't perfection, but progress.";
    }

    /**
     * Education response
     */
    private function getEducationResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Edukasyon**\n\n" .
                "**Study Tips:**\n" .
                "- Create schedule\n" .
                "- Find quiet place\n" .
                "- Take breaks\n" .
                "- Review regularly\n\n" .
                "**Online Learning:**\n" .
                "- Coursera, edX\n" .
                "- YouTube tutorials\n" .
                "- Free resources";
        }
        return "**Education**\n\n" .
            "**Study Tips:**\n" .
            "- Create schedule\n" .
            "- Find quiet place\n" .
            "- Take breaks\n" .
            "- Review regularly\n\n" .
            "**Online Learning:**\n" .
            "- Coursera, edX\n" .
            "- YouTube tutorials\n" .
            "- Free resources";
    }

    /**
     * Nature response
     */
    private function getNatureResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Hayop at Kalikasan**\n\n" .
                "**Pets:**\n" .
                "- Dogs: loyal companions\n" .
                "- Cats: independent\n" .
                "- Birds, fish: relaxing\n\n" .
                "**Nature Benefits:**\n" .
                "- Stress relief\n" .
                "- Fresh air\n" .
                "- Exercise";
        }
        return "**Animals and Nature**\n\n" .
            "**Pets:**\n" .
            "- Dogs: loyal companions\n" .
            "- Cats: independent\n" .
            "- Birds, fish: relaxing\n\n" .
            "**Nature Benefits:**\n" .
            "- Stress relief\n" .
            "- Fresh air\n" .
            "- Exercise";
    }

    /**
     * Language response
     */
    private function getLanguageResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Wika**\n\n" .
                "**Tagalog/Filipino:**\n" .
                "- National language ng Philippines\n" .
                "- Malayo-Polynesian origin\n\n" .
                "**English:**\n" .
                "- Most widely spoken globally\n" .
                "- Business and science language\n\n" .
                "**Learning Tips:**\n" .
                "- Practice daily\n" .
                "- Watch movies\n" .
                "- Speak with natives";
        }
        return "**Languages**\n\n" .
            "**Tagalog/Filipino:**\n" .
            "- National language of Philippines\n" .
            "- Malayo-Polynesian origin\n\n" .
            "**English:**\n" .
            "- Most widely spoken globally\n" .
            "- Business and science language\n\n" .
            "**Learning Tips:**\n" .
            "- Practice daily\n" .
            "- Watch movies\n" .
            "- Speak with natives";
    }

    /**
     * Shopping response
     */
    private function getShoppingResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Shopping at Pagbili**\n\n" .
                "**Tips para sa Mabilis na Shopping:**\n" .
                "- Gumawa ng listahan bago pumunta sa mall\n" .
                "- I-compare ang presyo sa ibat ibang stores\n" .
                "- Maghintay ng sale o discounts\n" .
                "- Check reviews bago bumili online\n\n" .
                "**Online Shopping:**\n" .
                "- Shopee, Lazada - local platforms\n" .
                "- Amazon - international\n" .
                "- Check seller ratings at reviews\n\n" .
                "**Best Time to Shop:**\n" .
                "- Payday sales (15 at 30)\n" .
                "- Holiday sales (Christmas, Black Friday)\n" .
                "- End of season clearance";
        }
        return "**Shopping Tips**\n\n" .
            "**Smart Shopping:**\n" .
            "- Make a list before going to the mall\n" .
            "- Compare prices across stores\n" .
            "- Wait for sales and discounts\n" .
            "- Check reviews before buying online\n\n" .
            "**Online Shopping:**\n" .
            "- Shopee, Lazada - local platforms\n" .
            "- Amazon - international\n" .
            "- Check seller ratings and reviews\n\n" .
            "**Best Time to Shop:**\n" .
            "- Payday sales (15th and 30th)\n" .
            "- Holiday sales (Christmas, Black Friday)\n" .
            "- End of season clearance";
    }

    /**
     * Home & Living response
     */
    private function getHomeResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Bahay at Pamumuhay**\n\n" .
                "**Apartment Hunting Tips:**\n" .
                "- Check location - malapit ba sa work/school?\n" .
                "- Budget - dapat hindi hihigit sa 30% ng sweldo\n" .
                "- Safety - secure neighborhood\n" .
                "- Amenities - may tubig, kuryente, internet?\n\n" .
                "**Home Organization:**\n" .
                "- Declutter regularly\n" .
                "- Label storage boxes\n" .
                "- Maximize vertical space\n\n" .
                "**Cleaning Tips:**\n" .
                "- Daily: Hugasan ang pinggan, linisin ang mesa\n" .
                "- Weekly: Vacuum, palitan bedsheets\n" .
                "- Monthly: Deep clean kitchen at bathroom";
        }
        return "**Home and Living**\n\n" .
            "**Apartment Hunting Tips:**\n" .
            "- Check location - near work/school?\n" .
            "- Budget - shouldn't exceed 30% of salary\n" .
            "- Safety - secure neighborhood\n" .
            "- Amenities - water, electricity, internet?\n\n" .
            "**Home Organization:**\n" .
            "- Declutter regularly\n" .
            "- Label storage boxes\n" .
            "- Maximize vertical space\n\n" .
            "**Cleaning Tips:**\n" .
            "- Daily: Wash dishes, wipe surfaces\n" .
            "- Weekly: Vacuum, change bedsheets\n" .
            "- Monthly: Deep clean kitchen and bathroom";
    }

    /**
     * Transportation response
     */
    private function getTransportResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Transportasyon**\n\n" .
                "**Public Transport sa Philippines:**\n" .
                "- Jeepney - pinaka-mura, P9-15 per ride\n" .
                "- Bus - para sa long distances\n" .
                "- MRT/LRT - mabilis pero crowded\n" .
                "- Tricycle - para sa short distances\n" .
                "- Grab/Angkas - convenient pero mas mahal\n\n" .
                "**Driving Tips:**\n" .
                "- Always wear seatbelt\n" .
                "- Check mirrors before changing lanes\n" .
                "- Follow traffic rules\n" .
                "- Keep emergency numbers handy\n\n" .
                "**Commuting Hacks:**\n" .
                "- Iwasan ang rush hours (7-9am, 6-8pm)\n" .
                "- Download navigation apps\n" .
                "- Always have cash ready";
        }
        return "**Transportation**\n\n" .
            "**Public Transport in Philippines:**\n" .
            "- Jeepney - cheapest, P9-15 per ride\n" .
            "- Bus - for long distances\n" .
            "- MRT/LRT - fast but crowded\n" .
            "- Tricycle - for short distances\n" .
            "- Grab/Angkas - convenient but more expensive\n\n" .
            "**Driving Tips:**\n" .
            "- Always wear seatbelt\n" .
            "- Check mirrors before changing lanes\n" .
            "- Follow traffic rules\n" .
            "- Keep emergency numbers handy\n\n" .
            "**Commuting Hacks:**\n" .
            "- Avoid rush hours (7-9am, 6-8pm)\n" .
            "- Download navigation apps\n" .
            "- Always have cash ready";
    }

    /**
     * Fashion response
     */
    private function getFashionResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Fashion at Estilo**\n\n" .
                "**Basic Wardrobe Essentials:**\n" .
                "- White t-shirt - versatile\n" .
                "- Denim jeans - classic\n" .
                "- Black shoes - formal at casual\n" .
                "- Little black dress (para sa ladies)\n\n" .
                "**Dressing Tips:**\n" .
                "- Dress for the occasion\n" .
                "- Fit is more important than brand\n" .
                "- Accessorize wisely\n" .
                "- Comfort over trends\n\n" .
                "**Beauty Basics:**\n" .
                "- Cleanser, toner, moisturizer daily\n" .
                "- Sunscreen - very important!\n" .
                "- Stay hydrated for healthy skin";
        }
        return "**Fashion and Style**\n\n" .
            "**Basic Wardrobe Essentials:**\n" .
            "- White t-shirt - versatile\n" .
            "- Denim jeans - classic\n" .
            "- Black shoes - formal and casual\n" .
            "- Little black dress (for ladies)\n\n" .
            "**Dressing Tips:**\n" .
            "- Dress for the occasion\n" .
            "- Fit is more important than brand\n" .
            "- Accessorize wisely\n" .
            "- Comfort over trends\n\n" .
            "**Beauty Basics:**\n" .
            "- Cleanser, toner, moisturizer daily\n" .
            "- Sunscreen - very important!\n" .
            "- Stay hydrated for healthy skin";
    }

    /**
     * Culture response
     */
    private function getCultureResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Kultura at Sining**\n\n" .
                "**Sikat na Filipino Festivals:**\n" .
                "- Sinulog (Cebu) - January\n" .
                "- Ati-Atihan (Kalibo) - January\n" .
                "- Panagbenga (Baguio) - February\n" .
                "- Pahiyas (Quezon) - May\n" .
                "- Kadayawan (Davao) - August\n\n" .
                "**Filipino Values:**\n" .
                "- Bayanihan - community spirit\n" .
                "- Hospitality - welcoming guests\n" .
                "- Respect for elders (po/opo)\n" .
                "- Strong family ties\n\n" .
                "**Traditional Arts:**\n" .
                "- Weaving (inabel, banig)\n" .
                "- Pottery\n" .
                "- Wood carving";
        }
        return "**Culture and Arts**\n\n" .
            "**Famous Filipino Festivals:**\n" .
            "- Sinulog (Cebu) - January\n" .
            "- Ati-Atihan (Kalibo) - January\n" .
            "- Panagbenga (Baguio) - February\n" .
            "- Pahiyas (Quezon) - May\n" .
            "- Kadayawan (Davao) - August\n\n" .
            "**Filipino Values:**\n" .
            "- Bayanihan - community spirit\n" .
            "- Hospitality - welcoming guests\n" .
            "- Respect for elders (po/opo)\n" .
            "- Strong family ties\n\n" .
            "**Traditional Arts:**\n" .
            "- Weaving (inabel, banig)\n" .
            "- Pottery\n" .
            "- Wood carving";
    }

    /**
     * Politics response
     */
    private function getPoliticsResponse(bool $isTagalog, string $lowerMessage): string
    {
        // Specific local officials
        $isManaoag = str_contains($lowerMessage, 'manaoag');
        $isDagupan = str_contains($lowerMessage, 'dagupan');

        if ($isManaoag) {
            if ($isTagalog) {
                return "**Pamahalaan ng Manaoag**\n\n" .
                    "- **Mayor:** Jeremy Agerico \"Ager\" B. Rosario\n" .
                    "- **Vice Mayor:** Noel J. Bongato\n\n" .
                    "Ang Manaoag ay isang 1st class municipality sa Pangasinan at tahanan ng tanyag na Minor Basilica of Our Lady of Manaoag.";
            }
            return "**Manaoag Local Government**\n\n" .
                "- **Mayor:** Jeremy Agerico \"Ager\" B. Rosario\n" .
                "- **Vice Mayor:** Noel J. Bongato\n\n" .
                "Manaoag is a 1st class municipality in Pangasinan and is home to the famous Minor Basilica of Our Lady of Manaoag.";
        }

        if ($isDagupan) {
            if ($isTagalog) {
                return "**Pamahalaan ng Dagupan City**\n\n" .
                    "- **Mayor:** Belen Fernandez\n" .
                    "- **Vice Mayor:** Bryan Kua\n\n" .
                    "Ang Dagupan City ay kilala bilang 'Bangus Capital of the World'.";
            }
            return "**Dagupan City Local Government**\n\n" .
                "- **Mayor:** Belen Fernandez\n" .
                "- **Vice Mayor:** Bryan Kua\n\n" .
                "Dagupan City is widely known as the 'Bangus Capital of the World'.";
        }

        if ($isTagalog) {
            return "**Pulitika at Pamahalaan**\n\n" .
                "**Istraktura ng Gobyerno ng Pilipinas:**\n" .
                "- Presidente - Chief Executive\n" .
                "- Bise Presidente\n" .
                "- Senado (24 na senador)\n" .
                "- Kapulungan ng mga Kinatawan (Congress)\n\n" .
                "**Lokal na Opisyal:**\n" .
                "- Mayor: Pinuno ng siyudad o bayan\n" .
                "- Governor: Pinuno ng probinsya\n\n" .
                "**Tips sa Pagboto:**\n" .
                "- Mag-register sa COMELEC\n" .
                "- Kilalanin ang mga kandidato\n" .
                "- Bumoto nang tama, hindi dahil sa sikat";
        }
        return "**Politics and Government**\n\n" .
            "**Philippine Government Structure:**\n" .
            "- President - Chief Executive\n" .
            "- Vice President\n" .
            "- Senate (24 senators)\n" .
            "- House of Representatives\n" .
            "- Supreme Court\n\n" .
            "**Local Government:**\n" .
            "- Mayor: Executive head of a city/municipality\n" .
            "- Governor: Executive head of a province\n\n" .
            "**Voting Tips:**\n" .
            "- Register with COMELEC\n" .
            "- Research candidates before voting\n" .
            "- Vote wisely, not by popularity";
    }

    /**
     * Religion response
     */
    private function getReligionResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Relihiyon at Espiritwalidad**\n\n" .
                "**Major Religions sa Philippines:**\n" .
                "- Roman Catholic - majority\n" .
                "- Islam - Mindanao region\n" .
                "- Protestant/Christian denominations\n" .
                "- Buddhism, Hinduism, others\n\n" .
                "**Filipino Religious Practices:**\n" .
                "- Sunday Mass attendance\n" .
                "- Holy Week traditions\n" .
                "- Christmas celebrations (Simbang Gabi)\n" .
                "- Barangay fiestas\n\n" .
                "**Interfaith Respect:**\n" .
                "- Respect all beliefs\n" .
                "- Focus on shared values\n" .
                "- Promote peace and understanding";
        }
        return "**Religion and Spirituality**\n\n" .
            "**Major Religions in Philippines:**\n" .
            "- Roman Catholic - majority\n" .
            "- Islam - Mindanao region\n" .
            "- Protestant/Christian denominations\n" .
            "- Buddhism, Hinduism, others\n\n" .
            "**Filipino Religious Practices:**\n" .
            "- Sunday Mass attendance\n" .
            "- Holy Week traditions\n" .
            "- Christmas celebrations (Simbang Gabi)\n" .
            "- Barangay fiestas\n\n" .
            "**Interfaith Respect:**\n" .
            "- Respect all beliefs\n" .
            "- Focus on shared values\n" .
            "- Promote peace and understanding";
    }

    /**
     * Environment response
     */
    private function getEnvironmentResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Kalikasan at Kapaligiran**\n\n" .
                "**Environmental Issues sa Philippines:**\n" .
                "- Deforestation\n" .
                "- Air at water pollution\n" .
                "- Plastic waste\n" .
                "- Coral reef damage\n\n" .
                "**Simple Eco-Friendly Actions:**\n" .
                "- Reduce plastic use\n" .
                "- Segregate waste properly\n" .
                "- Conserve water at electricity\n" .
                "- Plant trees\n" .
                "- Use reusable bags/bottles\n\n" .
                "**Recycling Tips:**\n" .
                "- Biodegradable vs non-biodegradable\n" .
                "- Clean recyclables before disposing\n" .
                "- Support products with less packaging";
        }
        return "**Environment and Nature**\n\n" .
            "**Environmental Issues in Philippines:**\n" .
            "- Deforestation\n" .
            "- Air and water pollution\n" .
            "- Plastic waste\n" .
            "- Coral reef damage\n\n" .
            "**Simple Eco-Friendly Actions:**\n" .
            "- Reduce plastic use\n" .
            "- Segregate waste properly\n" .
            "- Conserve water and electricity\n" .
            "- Plant trees\n" .
            "- Use reusable bags/bottles\n\n" .
            "**Recycling Tips:**\n" .
            "- Biodegradable vs non-biodegradable\n" .
            "- Clean recyclables before disposing\n" .
            "- Support products with less packaging";
    }

    /**
     * Food Culture response
     */
    private function getFoodCultureResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Food Culture at Restaurants**\n\n" .
                "**Sikat na Filipino Fast Food:**\n" .
                "- Jollibee - Chickenjoy at Jolly Spaghetti\n" .
                "- McDonalds - burgers at fries\n" .
                "- KFC - fried chicken\n" .
                "- Chowking - Chinese-style fast food\n" .
                "- Mang Inasal - grilled chicken\n\n" .
                "**Street Food Favorites:**\n" .
                "- Fishballs, kikiam, squidballs\n" .
                "- Isaw (grilled chicken intestines)\n" .
                "- Banana cue at turon\n" .
                "- Taho - soy pudding\n\n" .
                "**Milk Tea Craze:**\n" .
                "- Chatime, Gong Cha, Tiger Sugar\n" .
                "- CoCo, Macao Imperial Tea";
        }
        return "**Food Culture and Restaurants**\n\n" .
            "**Popular Filipino Fast Food:**\n" .
            "- Jollibee - Chickenjoy and Jolly Spaghetti\n" .
            "- McDonalds - burgers and fries\n" .
            "- KFC - fried chicken\n" .
            "- Chowking - Chinese-style fast food\n" .
            "- Mang Inasal - grilled chicken\n\n" .
            "**Street Food Favorites:**\n" .
            "- Fishballs, kikiam, squidballs\n" .
            "- Isaw (grilled chicken intestines)\n" .
            "- Banana cue and turon\n" .
            "- Taho - soy pudding\n\n" .
            "**Milk Tea Craze:**\n" .
            "- Chatime, Gong Cha, Tiger Sugar\n" .
            "- CoCo, Macao Imperial Tea";
    }

    /**
     * Emergency response
     */
    private function getEmergencyResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Emergency at Safety**\n\n" .
                "**Important Hotlines:**\n" .
                "- 911 - National Emergency Hotline\n" .
                "- 8888 - Public Complaint Hotline\n" .
                "- 143 - Red Cross\n" .
                "- 117 - PNP (Police)\n" .
                "- (02) 729-5166 - BFP (Fire)\n\n" .
                "**Disaster Preparedness:**\n" .
                "- Laging may emergency kit\n" .
                "- Alamin ang evacuation route\n" .
                "- Keep important documents safe\n" .
                "- Charge devices before typhoon\n\n" .
                "**First Aid Basics:**\n" .
                "- CPR - 30 compressions, 2 breaths\n" .
                "- Bleeding - apply pressure\n" .
                "- Burns - cool with running water";
        }
        return "**Emergency and Safety**\n\n" .
            "**Important Hotlines:**\n" .
            "- 911 - National Emergency Hotline\n" .
            "- 8888 - Public Complaint Hotline\n" .
            "- 143 - Red Cross\n" .
            "- 117 - PNP (Police)\n" .
            "- (02) 729-5166 - BFP (Fire)\n\n" .
            "**Disaster Preparedness:**\n" .
            "- Always have emergency kit\n" .
            "- Know evacuation routes\n" .
            "- Keep important documents safe\n" .
            "- Charge devices before typhoon\n\n" .
            "**First Aid Basics:**\n" .
            "- CPR - 30 compressions, 2 breaths\n" .
            "- Bleeding - apply pressure\n" .
            "- Burns - cool with running water";
    }

    /**
     * Social Media response
     */
    private function getSocialMediaResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Social Media at Communication**\n\n" .
                "**Popular Platforms sa Philippines:**\n" .
                "- Facebook - pinaka-popular\n" .
                "- Instagram - photos at lifestyle\n" .
                "- TikTok - short videos\n" .
                "- Twitter/X - news at updates\n" .
                "- YouTube - videos at tutorials\n\n" .
                "**Messaging Apps:**\n" .
                "- Messenger (Facebook)\n" .
                "- Viber - popular sa Pinas\n" .
                "- WhatsApp, Telegram\n\n" .
                "**Online Safety Tips:**\n" .
                "- Huwag mag-share ng personal info\n" .
                "- I-check ang privacy settings\n" .
                "- Mag-ingat sa scams at fake news";
        }
        return "**Social Media and Communication**\n\n" .
            "**Popular Platforms in Philippines:**\n" .
            "- Facebook - most popular\n" .
            "- Instagram - photos and lifestyle\n" .
            "- TikTok - short videos\n" .
            "- Twitter/X - news and updates\n" .
            "- YouTube - videos and tutorials\n\n" .
            "**Messaging Apps:**\n" .
            "- Messenger (Facebook)\n" .
            "- Viber - popular in Philippines\n" .
            "- WhatsApp, Telegram\n\n" .
            "**Online Safety Tips:**\n" .
            "- Don't share personal info\n" .
            "- Check privacy settings\n" .
            "- Beware of scams and fake news";
    }

    /**
     * Hobbies response
     */
    private function getHobbiesResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Hobbies at Interests**\n\n" .
                "**Popular Hobbies:**\n" .
                "- Photography - capture memories\n" .
                "- Drawing/Painting - creative outlet\n" .
                "- Reading - books, comics, manga\n" .
                "- Gardening - grow plants\n" .
                "- Cooking/Baking - try new recipes\n" .
                "- Gaming - mobile, PC, console\n\n" .
                "**Benefits ng Hobbies:**\n" .
                "- Stress relief\n" .
                "- Skill development\n" .
                "- Social connections\n" .
                "- Personal growth\n\n" .
                "**Starting a New Hobby:**\n" .
                "- Pumili ng interest mo\n" .
                "- Start small, dont pressure yourself\n" .
                "- Join communities online";
        }
        return "**Hobbies and Interests**\n\n" .
            "**Popular Hobbies:**\n" .
            "- Photography - capture memories\n" .
            "- Drawing/Painting - creative outlet\n" .
            "- Reading - books, comics, manga\n" .
            "- Gardening - grow plants\n" .
            "- Cooking/Baking - try new recipes\n" .
            "- Gaming - mobile, PC, console\n\n" .
            "**Benefits of Hobbies:**\n" .
            "- Stress relief\n" .
            "- Skill development\n" .
            "- Social connections\n" .
            "- Personal growth\n\n" .
            "**Starting a New Hobby:**\n" .
            "- Choose something you enjoy\n" .
            "- Start small, don't pressure yourself\n" .
            "- Join communities online";
    }

    /**
     * Gaming response
     */
    private function getGamingResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Gaming at Esports**\n\n" .
                "**Popular Mobile Games sa Pinas:**\n" .
                "- Mobile Legends: Bang Bang (MLBB)\n" .
                "- Call of Duty Mobile\n" .
                "- Genshin Impact\n" .
                "- PUBG Mobile\n" .
                "- Roblox\n\n" .
                "**PC/Console Games:**\n" .
                "- Valorant, DOTA 2, League of Legends\n" .
                "- Minecraft, Fortnite\n" .
                "- NBA 2K, FIFA\n\n" .
                "**Gaming Tips:**\n" .
                "- Balance gaming with other activities\n" .
                "- Take breaks every hour\n" .
                "- Invest in good equipment\n" .
                "- Join communities para sa tips";
        }
        return "**Gaming and Esports**\n\n" .
            "**Popular Mobile Games in Philippines:**\n" .
            "- Mobile Legends: Bang Bang (MLBB)\n" .
            "- Call of Duty Mobile\n" .
            "- Genshin Impact\n" .
            "- PUBG Mobile\n" .
            "- Roblox\n\n" .
            "**PC/Console Games:**\n" .
            "- Valorant, DOTA 2, League of Legends\n" .
            "- Minecraft, Fortnite\n" .
            "- NBA 2K, FIFA\n\n" .
            "**Gaming Tips:**\n" .
            "- Balance gaming with other activities\n" .
            "- Take breaks every hour\n" .
            "- Invest in good equipment\n" .
            "- Join communities for tips";
    }

    /**
     * Schools & Universities response with founders and presidents
     */
    private function getSchoolResponse(bool $isTagalog, string $lowerMessage): string
    {
        // Check for specific university mentions
        if (str_contains($lowerMessage, 'up diliman') || str_contains($lowerMessage, 'university of the philippines')) {
            return $isTagalog ?
                "**University of the Philippines Diliman**\n\n" .
                "**Founded:** June 18, 1908 (Act No. 1870)\n" .
                "**Founder:** US Government through the Philippine Assembly\n" .
                "**Current Chancellor:** Edgardo Carlo L. Vistan II (2023-present)\n\n" .
                "**Information:**\n" .
                "- Flagship campus ng UP System\n" .
                "- Located sa Quezon City\n" .
                "- Known for academic excellence\n" .
                "- Free tuition para sa qualified students\n\n" .
                "**Famous Alumni:**\n" .
                "- Multiple Philippine Presidents\n" .
                "- Senators, Supreme Court Justices\n" .
                "- National Scientists and Artists" :
                "**University of the Philippines Diliman**\n\n" .
                "**Founded:** June 18, 1908 (Act No. 1870)\n" .
                "**Founder:** US Government through the Philippine Assembly\n" .
                "**Current Chancellor:** Edgardo Carlo L. Vistan II (2023-present)\n\n" .
                "**Information:**\n" .
                "- Flagship campus of UP System\n" .
                "- Located in Quezon City\n" .
                "- Known for academic excellence\n" .
                "- Free tuition for qualified students\n\n" .
                "**Famous Alumni:**\n" .
                "- Multiple Philippine Presidents\n" .
                "- Senators, Supreme Court Justices\n" .
                "- National Scientists and Artists";
        }

        if (str_contains($lowerMessage, 'ust') || str_contains($lowerMessage, 'santo tomas') || str_contains($lowerMessage, 'university of santo tomas')) {
            return $isTagalog ?
                "**University of Santo Tomas (UST)**\n\n" .
                "**Founded:** April 28, 1611\n" .
                "**Founder:** Archbishop Miguel de Benavides, O.P.\n" .
                "**Current Rector:** Rev. Fr. Richard G. Ang, O.P. (2021-present)\n\n" .
                "**Information:**\n" .
                "- Asia's oldest existing university\n" .
                "- Located sa Sampaloc, Manila\n" .
                "- Pontifical at Royal University\n" .
                "- Known for medicine, law, at engineering\n\n" .
                "**Campus:**\n" .
                "- 21.5 hectares sa Manila\n" .
                "- Heritage site with historical buildings\n" .
                "- Home ng famous UST Main Building" :
                "**University of Santo Tomas (UST)**\n\n" .
                "**Founded:** April 28, 1611\n" .
                "**Founder:** Archbishop Miguel de Benavides, O.P.\n" .
                "**Current Rector:** Rev. Fr. Richard G. Ang, O.P. (2021-present)\n\n" .
                "**Information:**\n" .
                "- Asia's oldest existing university\n" .
                "- Located in Sampaloc, Manila\n" .
                "- Pontifical and Royal University\n" .
                "- Known for medicine, law, and engineering\n\n" .
                "**Campus:**\n" .
                "- 21.5 hectares in Manila\n" .
                "- Heritage site with historical buildings\n" .
                "- Home of famous UST Main Building";
        }

        if (str_contains($lowerMessage, 'ateneo')) {
            return $isTagalog ?
                "**Ateneo de Manila University**\n\n" .
                "**Founded:** December 10, 1859\n" .
                "**Founder:** Spanish Jesuits (led by Fr. Jose Cuevas, S.J.)\n" .
                "**Current President:** Fr. Roberto C. Yap, S.J. (2020-present)\n\n" .
                "**Information:**\n" .
                "- Private, Catholic research university\n" .
                "- Located sa Loyola Heights, Quezon City\n" .
                "- One of the 'Big Four' universities\n" .
                "- Famous sa business, law, at humanities\n\n" .
                "**Campus:**\n" .
                "- 100+ hectares sa Quezon City\n" .
                "- Home of the Church of the Gesu\n" .
                "- Features modern facilities at green spaces" :
                "**Ateneo de Manila University**\n\n" .
                "**Founded:** December 10, 1859\n" .
                "**Founder:** Spanish Jesuits (led by Fr. Jose Cuevas, S.J.)\n" .
                "**Current President:** Fr. Roberto C. Yap, S.J. (2020-present)\n\n" .
                "**Information:**\n" .
                "- Private, Catholic research university\n" .
                "- Located in Loyola Heights, Quezon City\n" .
                "- One of the 'Big Four' universities\n" .
                "- Famous for business, law, and humanities\n\n" .
                "**Campus:**\n" .
                "- 100+ hectares in Quezon City\n" .
                "- Home of the Church of the Gesu\n" .
                "- Features modern facilities and green spaces";
        }

        if (str_contains($lowerMessage, 'lasalle') || str_contains($lowerMessage, 'de la salle')) {
            return $isTagalog ?
                "**De La Salle University (DLSU)**\n\n" .
                "**Founded:** June 16, 1911\n" .
                "**Founder:** Brothers of the Christian Schools (De La Salle Brothers)\n" .
                "**Current President:** Br. Bernard S. Oca, FSC (2021-present)\n\n" .
                "**Information:**\n" .
                "- Leading private Catholic university\n" .
                "- Main campus sa Taft Avenue, Manila\n" .
                "- Known for business, engineering, at computer science\n" .
                "- Home of the Green Archers\n\n" .
                "**Notable Programs:**\n" .
                "- Ramon V. del Rosario College of Business\n" .
                "- Gokongwei College of Engineering\n" .
                "- College of Computer Studies" :
                "**De La Salle University (DLSU)**\n\n" .
                "**Founded:** June 16, 1911\n" .
                "**Founder:** Brothers of the Christian Schools (De La Salle Brothers)\n" .
                "**Current President:** Br. Bernard S. Oca, FSC (2021-present)\n\n" .
                "**Information:**\n" .
                "- Leading private Catholic university\n" .
                "- Main campus in Taft Avenue, Manila\n" .
                "- Known for business, engineering, and computer science\n" .
                "- Home of the Green Archers\n\n" .
                "**Notable Programs:**\n" .
                "- Ramon V. del Rosario College of Business\n" .
                "- Gokongwei College of Engineering\n" .
                "- College of Computer Studies";
        }

        if (str_contains($lowerMessage, 'pup') || str_contains($lowerMessage, 'polytechnic university')) {
            return $isTagalog ?
                "**Polytechnic University of the Philippines (PUP)**\n\n" .
                "**Founded:** October 19, 1904 (as Manila Business School)\n" .
                "**Founder:** Philippine Commission through Act No. 1458\n" .
                "**Current President:** Dr. Manuel M. Muhi (2020-present)\n\n" .
                "**Information:**\n" .
                "- Largest university system sa Philippines (70+ campuses)\n" .
                "- Main campus sa Sta. Mesa, Manila\n" .
                "- Known for technical and vocational courses\n" .
                "- Affordable tuition fees\n\n" .
                "**Popular Programs:**\n" .
                "- Engineering, Computer Science\n" .
                "- Business Administration\n" .
                "- Mass Communication" :
                "**Polytechnic University of the Philippines (PUP)**\n\n" .
                "**Founded:** October 19, 1904 (as Manila Business School)\n" .
                "**Founder:** Philippine Commission through Act No. 1458\n" .
                "**Current President:** Dr. Manuel M. Muhi (2020-present)\n\n" .
                "**Information:**\n" .
                "- Largest university system in Philippines (70+ campuses)\n" .
                "- Main campus in Sta. Mesa, Manila\n" .
                "- Known for technical and vocational courses\n" .
                "- Affordable tuition fees\n\n" .
                "**Popular Programs:**\n" .
                "- Engineering, Computer Science\n" .
                "- Business Administration\n" .
                "- Mass Communication";
        }

        if (str_contains($lowerMessage, 'feu')) {
            return $isTagalog ?
                "**Far Eastern University (FEU)**\n\n" .
                "**Founded:** 1934\n" .
                "**Founder:** Dr. Nicanor Reyes Sr.\n" .
                "**Current President:** Dr. Michael M. Alba (2022-present)\n\n" .
                "**Information:**\n" .
                "- Private university sa Manila\n" .
                "- Famous for Accountancy at Nursing\n" .
                "- Home of the Tamaraws\n" .
                "- Art Deco architecture sa campus\n\n" .
                "**Popular Courses:**\n" .
                "- Accountancy (topnotchers)\n" .
                "- Nursing, Medicine\n" .
                "- Law, Business" :
                "**Far Eastern University (FEU)**\n\n" .
                "**Founded:** 1934\n" .
                "**Founder:** Dr. Nicanor Reyes Sr.\n" .
                "**Current President:** Dr. Michael M. Alba (2022-present)\n\n" .
                "**Information:**\n" .
                "- Private university in Manila\n" .
                "- Famous for Accountancy and Nursing\n" .
                "- Home of the Tamaraws\n" .
                "- Art Deco architecture in campus\n\n" .
                "**Popular Courses:**\n" .
                "- Accountancy (topnotchers)\n" .
                "- Nursing, Medicine\n" .
                "- Law, Business";
        }

        if (str_contains($lowerMessage, 'ue') || str_contains($lowerMessage, 'east')) {
            return $isTagalog ?
                "**University of the East (UE)**\n\n" .
                "**Founded:** September 1946\n" .
                "**Founder:** Dr. Francisco T. Dalupan Sr.\n" .
                "**Current President:** Dr. Lucila O. Toray (2023-present)\n\n" .
                "**Information:**\n" .
                "- Private university sa Manila\n" .
                "- Known for Medicine, Dentistry, Business\n" .
                "- Home of the Red Warriors\n" .
                "- Three campuses: Manila, Caloocan, Quezon City\n\n" .
                "**Famous Programs:**\n" .
                "- Medicine (top performing school)\n" .
                "- Dentistry, Nursing\n" .
                "- Business Administration" :
                "**University of the East (UE)**\n\n" .
                "**Founded:** September 1946\n" .
                "**Founder:** Dr. Francisco T. Dalupan Sr.\n" .
                "**Current President:** Dr. Lucila O. Toray (2023-present)\n\n" .
                "**Information:**\n" .
                "- Private university in Manila\n" .
                "- Known for Medicine, Dentistry, Business\n" .
                "- Home of the Red Warriors\n" .
                "- Three campuses: Manila, Caloocan, Quezon City\n\n" .
                "**Famous Programs:**\n" .
                "- Medicine (top performing school)\n" .
                "- Dentistry, Nursing\n" .
                "- Business Administration";
        }

        if (str_contains($lowerMessage, 'adamson')) {
            return $isTagalog ?
                "**Adamson University**\n\n" .
                "**Founded:** June 20, 1932\n" .
                "**Founder:** George Lucas Adamson\n" .
                "**Current President:** Fr. Marcelo V. Manalang, CM (2023-present)\n\n" .
                "**Information:**\n" .
                "- Catholic university sa Manila\n" .
                "- Run by the Congregation of the Mission (Vincentians)\n" .
                "- Known for Engineering at Chemistry\n" .
                "- Home of the Soaring Falcons\n\n" .
                "**Popular Programs:**\n" .
                "- Chemical Engineering\n" .
                "- Chemistry (Board Topnotchers)\n" .
                "- Architecture, Engineering" :
                "**Adamson University**\n\n" .
                "**Founded:** June 20, 1932\n" .
                "**Founder:** George Lucas Adamson\n" .
                "**Current President:** Fr. Marcelo V. Manalang, CM (2023-present)\n\n" .
                "**Information:**\n" .
                "- Catholic university in Manila\n" .
                "- Run by the Congregation of the Mission (Vincentians)\n" .
                "- Known for Engineering and Chemistry\n" .
                "- Home of the Soaring Falcons\n\n" .
                "**Popular Programs:**\n" .
                "- Chemical Engineering\n" .
                "- Chemistry (Board Topnotchers)\n" .
                "- Architecture, Engineering";
        }

        if (str_contains($lowerMessage, 'mapua')) {
            return $isTagalog ?
                "**Mapua University**\n\n" .
                "**Founded:** January 25, 1925\n" .
                "**Founder:** Don Tomas Mapua\n" .
                "**Current President:** Dr. Dodjie S. Maestrecampo (2022-present)\n\n" .
                "**Information:**\n" .
                "- Private engineering university\n" .
                "- Two campuses: Intramuros (Manila) at Makati\n" .
                "- Top engineering school sa Philippines\n" .
                "- Home of the Cardinals\n\n" .
                "**Famous Programs:**\n" .
                "- Civil Engineering (topnotchers)\n" .
                "- Electrical, Mechanical Engineering\n" .
                "- Architecture, Computer Engineering" :
                "**Mapua University**\n\n" .
                "**Founded:** January 25, 1925\n" .
                "**Founder:** Don Tomas Mapua\n" .
                "**Current President:** Dr. Dodjie S. Maestrecampo (2022-present)\n\n" .
                "**Information:**\n" .
                "- Private engineering university\n" .
                "- Two campuses: Intramuros (Manila) and Makati\n" .
                "- Top engineering school in Philippines\n" .
                "- Home of the Cardinals\n\n" .
                "**Famous Programs:**\n" .
                "- Civil Engineering (topnotchers)\n" .
                "- Electrical, Mechanical Engineering\n" .
                "- Architecture, Computer Engineering";
        }

        if (str_contains($lowerMessage, 'tip') || str_contains($lowerMessage, 'technological institute')) {
            return $isTagalog ?
                "**Technological Institute of the Philippines (TIP)**\n\n" .
                "**Founded:** February 8, 1962\n" .
                "**Founder:** Engr. Demetrio A. Quirino Jr.\n" .
                "**Current President:** Dr. Angel C. Alcala (2021-present)\n\n" .
                "**Information:**\n" .
                "- Private engineering school\n" .
                "- Three campuses: Manila, Quezon City, Cubao\n" .
                "- Focus sa engineering at technology\n" .
                "- Affordable tuition\n\n" .
                "**Popular Programs:**\n" .
                "- Engineering (all fields)\n" .
                "- Computer Science\n" .
                "- Architecture, Business" :
                "**Technological Institute of the Philippines (TIP)**\n\n" .
                "**Founded:** February 8, 1962\n" .
                "**Founder:** Engr. Demetrio A. Quirino Jr.\n" .
                "**Current President:** Dr. Angel C. Alcala (2021-present)\n\n" .
                "**Information:**\n" .
                "- Private engineering school\n" .
                "- Three campuses: Manila, Quezon City, Cubao\n" .
                "- Focus on engineering and technology\n" .
                "- Affordable tuition\n\n" .
                "**Popular Programs:**\n" .
                "- Engineering (all fields)\n" .
                "- Computer Science\n" .
                "- Architecture, Business";
        }

        if (str_contains($lowerMessage, 'nu') || str_contains($lowerMessage, 'national university')) {
            return $isTagalog ?
                "**National University (NU)**\n\n" .
                "**Founded:** August 1, 1900\n" .
                "**Founders:** Don Mariano J. Fortunado at Dona Maria del Pilar\n" .
                "**Current President:** Dr. Renato C. Carlos (2019-present)\n\n" .
                "**Information:**\n" .
                "- Oldest private university in Manila\n" .
                "- Located sa Sampaloc, Manila\n" .
                "- Home of the NU Bulldogs (champion teams)\n" .
                "- Part ng SM Group of Companies\n\n" .
                "**Popular Programs:**\n" .
                "- Business, Accountancy\n" .
                "- Engineering, Architecture\n" .
                "- Health Sciences" :
                "**National University (NU)**\n\n" .
                "**Founded:** August 1, 1900\n" .
                "**Founders:** Don Mariano J. Fortunado and Dona Maria del Pilar\n" .
                "**Current President:** Dr. Renato C. Carlos (2019-present)\n\n" .
                "**Information:**\n" .
                "- Oldest private university in Manila\n" .
                "- Located in Sampaloc, Manila\n" .
                "- Home of the NU Bulldogs (champion teams)\n" .
                "- Part of SM Group of Companies\n\n" .
                "**Popular Programs:**\n" .
                "- Business, Accountancy\n" .
                "- Engineering, Architecture\n" .
                "- Health Sciences";
        }

        // Pangasinan Universities
        if (str_contains($lowerMessage, 'psu') || str_contains($lowerMessage, 'pangasinan state')) {
            return $isTagalog ?
                "**Pangasinan State University (PSU)**\n\n" .
                "**Founded:** 1979 (as Pangasinan State College)\n" .
                "**University Status:** 1987 (RA 6769)\n" .
                "**Current President:** Dr. Elbert M. Galas (2024-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Multi-campus state university system\n" .
                "- 9 campuses sa buong Pangasinan\n" .
                "- Main campus: Lingayen\n" .
                "- Affordable tuition para sa qualified students\n" .
                "- CHED at DEPED certified programs\n\n" .
                "**Campuses:**\n" .
                "- Lingayen (Main) - Governance, Education\n" .
                "- Bayambang - Engineering, Agriculture\n" .
                "- Urdaneta - Business, IT\n" .
                "- Asingan - Teacher Education\n" .
                "- Binmaley - Fisheries, Marine Science\n" .
                "- Infanta - Agriculture, Forestry\n" .
                "- San Carlos City - Business, Engineering\n" .
                "- Alaminos City - Tourism, Hospitality\n" .
                "- Sta. Maria - Education, Agriculture\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Bachelor of Science in Criminology\n" .
                "- Engineering programs (Civil, Electrical, Mechanical)\n" .
                "- Teacher Education\n" .
                "- Information Technology\n" .
                "- Agriculture at Fisheries" :
                "**Pangasinan State University (PSU)**\n\n" .
                "**Founded:** 1979 (as Pangasinan State College)\n" .
                "**University Status:** 1987 (RA 6769)\n" .
                "**Current President:** Dr. Elbert M. Galas (2024-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Multi-campus state university system\n" .
                "- 9 campuses throughout Pangasinan\n" .
                "- Main campus: Lingayen\n" .
                "- Affordable tuition for qualified students\n" .
                "- CHED and DEPED certified programs\n\n" .
                "**Campuses:**\n" .
                "- Lingayen (Main) - Governance, Education\n" .
                "- Bayambang - Engineering, Agriculture\n" .
                "- Urdaneta - Business, IT\n" .
                "- Asingan - Teacher Education\n" .
                "- Binmaley - Fisheries, Marine Science\n" .
                "- Infanta - Agriculture, Forestry\n" .
                "- San Carlos City - Business, Engineering\n" .
                "- Alaminos City - Tourism, Hospitality\n" .
                "- Sta. Maria - Education, Agriculture\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Bachelor of Science in Criminology\n" .
                "- Engineering programs (Civil, Electrical, Mechanical)\n" .
                "- Teacher Education\n" .
                "- Information Technology\n" .
                "- Agriculture and Fisheries";
        }

        if (str_contains($lowerMessage, 'upang') || str_contains($lowerMessage, 'university of pangasinan')) {
            return $isTagalog ?
                "**University of Pangasinan (UPANG)**\n\n" .
                "**Founded:** 1925 (as Dagupan Institute)\n" .
                "**University Status:** 1950\n" .
                "**Current President:** Dr. Virgilio C. Crisostomo (2023-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private university sa Dagupan City\n" .
                "- Part ng PHINMA Education Network\n" .
                "- Known for health sciences at business\n" .
                "- Modern facilities sa Bonuan campus\n\n" .
                "**Campuses:**\n" .
                "- Bonuan Boquig, Dagupan (Main)\n" .
                "- Arellano Street, Dagupan (Old Campus)\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Medicine (Top performing sa board exams)\n" .
                "- Nursing, Pharmacy, Medical Technology\n" .
                "- Physical Therapy, Radiologic Technology\n" .
                "- Business Administration, Accountancy\n" .
                "- Law, Criminology, Engineering\n\n" .
                "**Achievements:**\n" .
                "- Consistent board topnotchers sa health sciences\n" .
                "- Accredited programs sa PACUCOA" :
                "**University of Pangasinan (UPANG)**\n\n" .
                "**Founded:** 1925 (as Dagupan Institute)\n" .
                "**University Status:** 1950\n" .
                "**Current President:** Dr. Virgilio C. Crisostomo (2023-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private university in Dagupan City\n" .
                "- Part of PHINMA Education Network\n" .
                "- Known for health sciences and business\n" .
                "- Modern facilities in Bonuan campus\n\n" .
                "**Campuses:**\n" .
                "- Bonuan Boquig, Dagupan (Main)\n" .
                "- Arellano Street, Dagupan (Old Campus)\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Medicine (Top performing in board exams)\n" .
                "- Nursing, Pharmacy, Medical Technology\n" .
                "- Physical Therapy, Radiologic Technology\n" .
                "- Business Administration, Accountancy\n" .
                "- Law, Criminology, Engineering\n\n" .
                "**Achievements:**\n" .
                "- Consistent board topnotchers in health sciences\n" .
                "- PACUCOA accredited programs";
        }

        if (str_contains($lowerMessage, 'universidad de dagupan') || str_contains($lowerMessage, 'ud dagupan') || str_contains($lowerMessage, 'colegio de dagupan') || str_contains($lowerMessage, 'cdd')) {
            return $isTagalog ?
                "**Universidad de Dagupan (UdD)**\n\n" .
                "**Location:** Arellano St., Dagupan City\n" .
                "**Former Name:** Colegio de Dagupan (CdD)\n" .
                "**Became University:** 2022\n" .
                "**Founder:** Dr. Voltaire P. Arzadon\n" .
                "**Current President:** Dr. Feliza Arzadon-Sua\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private university sa Dagupan City\n" .
                "- Dating kilala bilang Colegio de Dagupan (CdD)\n" .
                "- Fastest growing university sa Pangasinan\n" .
                "- Modern campus sa Tapuac District\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Bachelor of Science in Nursing (topnotchers!)\n" .
                "- Bachelor of Science in Criminology\n" .
                "- Teacher Education (Elementary at Secondary)\n" .
                "- Business Administration, Accountancy\n" .
                "- Information Technology, Computer Science\n" .
                "- Engineering (Civil, Electrical, Mechanical)\n\n" .
                "**Achievements:**\n" .
                "- Consistent board topnotchers sa Nursing at Criminology\n" .
                "- 100% passing rate sa recent board exams\n" .
                "- CHED recognized programs" :
                "**Universidad de Dagupan (UdD)**\n\n" .
                "**Location:** Arellano St., Dagupan City\n" .
                "**Former Name:** Colegio de Dagupan (CdD)\n" .
                "**Became University:** 2022\n" .
                "**Founder:** Dr. Voltaire P. Arzadon\n" .
                "**Current President:** Dr. Feliza Arzadon-Sua\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private university in Dagupan City\n" .
                "- Formerly known as Colegio de Dagupan (CdD)\n" .
                "- Fastest growing university in Pangasinan\n" .
                "- Modern campus in Tapuac District\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Bachelor of Science in Nursing (topnotchers!)\n" .
                "- Bachelor of Science in Criminology\n" .
                "- Teacher Education (Elementary and Secondary)\n" .
                "- Business Administration, Accountancy\n" .
                "- Information Technology, Computer Science\n" .
                "- Engineering (Civil, Electrical, Mechanical)\n\n" .
                "**Achievements:**\n" .
                "- Consistent board topnotchers in Nursing and Criminology\n" .
                "- 100% passing rate in recent board exams\n" .
                "- CHED recognized programs";
        }

        if (str_contains($lowerMessage, 'lyceum pangasinan') || str_contains($lowerMessage, 'lpu pangasinan')) {
            return $isTagalog ?
                "**Lyceum-Northwestern University (L-NU)**\n\n" .
                "**Founded:** 1969 (as Dagupan City School)\n" .
                "**University Status:** 1975\n" .
                "**Current President:** Dr. Joselito C. De Guzman (2021-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private university sa Dagupan City\n" .
                "- Well-known sa health sciences programs\n" .
                "- Home of the Lyceum-Northwestern University Hospital\n" .
                "- Top choice para sa medical courses\n\n" .
                "**Campuses:**\n" .
                "- Tapuac, Dagupan City (Main)\n" .
                "- Arellano Street, Dagupan\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Doctor of Medicine (topnotchers!)\n" .
                "- Nursing, Midwifery\n" .
                "- Medical Technology, Radiologic Technology\n" .
                "- Physical Therapy, Pharmacy\n" .
                "- Hotel and Restaurant Management\n\n" .
                "**Medical School:**\n" .
                "- College of Medicine - top performing!\n" .
                "- Affiliated with L-NU Hospital" :
                "**Lyceum-Northwestern University (L-NU)**\n\n" .
                "**Founded:** 1969 (as Dagupan City School)\n" .
                "**University Status:** 1975\n" .
                "**Current President:** Dr. Joselito C. De Guzman (2021-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private university in Dagupan City\n" .
                "- Well-known for health sciences programs\n" .
                "- Home of the Lyceum-Northwestern University Hospital\n" .
                "- Top choice for medical courses\n\n" .
                "**Campuses:**\n" .
                "- Tapuac, Dagunan City (Main)\n" .
                "- Arellano Street, Dagupan\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Doctor of Medicine (topnotchers!)\n" .
                "- Nursing, Midwifery\n" .
                "- Medical Technology, Radiologic Technology\n" .
                "- Physical Therapy, Pharmacy\n" .
                "- Hotel and Restaurant Management\n\n" .
                "**Medical School:**\n" .
                "- College of Medicine - top performing!\n" .
                "- Affiliated with L-NU Hospital";
        }

        if (str_contains($lowerMessage, 'urdeneta city university') || str_contains($lowerMessage, 'ucu') || str_contains($lowerMessage, 'ednas')) {
            return $isTagalog ?
                "**Urdaneta City University (UCU)**\n\n" .
                "**Founded:** 1966 (as Urdaneta Community College)\n" .
                "**University Status:** 2006 (City University)\n" .
                "**Current President:** Dr. Librado C. Fria (2020-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Public university sa Urdaneta City\n" .
                "- Local government funded\n" .
                "- Affordable tuition para sa Urdaneta residents\n" .
                "- Expanding campus facilities\n\n" .
                "**Location:**\n" .
                "- San Vicente, Urdaneta City, Pangasinan\n" .
                "- Along McArthur Highway\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Criminology (sikat na program)\n" .
                "- Education (Elementary at Secondary)\n" .
                "- Business Administration\n" .
                "- Information Technology\n" .
                "- Engineering (Civil, Electrical)\n\n" .
                "**Student Benefits:**\n" .
                "- Lower tuition para sa Urdaneta residents\n" .
                "- Scholarships available" :
                "**Urdaneta City University (UCU)**\n\n" .
                "**Founded:** 1966 (as Urdaneta Community College)\n" .
                "**University Status:** 2006 (City University)\n" .
                "**Current President:** Dr. Librado C. Fria (2020-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Public university in Urdaneta City\n" .
                "- Local government funded\n" .
                "- Affordable tuition for Urdaneta residents\n" .
                "- Expanding campus facilities\n\n" .
                "**Location:**\n" .
                "- San Vicente, Urdaneta City, Pangasinan\n" .
                "- Along McArthur Highway\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- Criminology (popular program)\n" .
                "- Education (Elementary and Secondary)\n" .
                "- Business Administration\n" .
                "- Information Technology\n" .
                "- Engineering (Civil, Electrical)\n\n" .
                "**Student Benefits:**\n" .
                "- Lower tuition for Urdaneta residents\n" .
                "- Scholarships available";
        }

        if (str_contains($lowerMessage, 'ama pangasinan') || str_contains($lowerMessage, 'ama computer college')) {
            return $isTagalog ?
                "**AMA Computer College Pangasinan**\n\n" .
                "**Founded:** 1980 (as AMA Computer Learning Center)\n" .
                "**Part of:** AMA Education System (Dr. Amable R. Aguiluz V, Founder)\n" .
                "**Current Administrator:** Mr. Reynaldo D. Salvador (Campus Director, 2022-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private computer college sa Dagupan City\n" .
                "- Part ng largest IT network sa Philippines\n" .
                "- Focus sa computer science at technology\n" .
                "- Job-ready programs para sa IT industry\n\n" .
                "**Location:**\n" .
                "- AB Fernandez Avenue, Dagupan City, Pangasinan\n" .
                "- Downtown area, accessible sa public transport\n" .
                "- Near commercial centers at shopping malls\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- BS Computer Science\n" .
                "- BS Information Technology\n" .
                "- BS Information Systems\n" .
                "- BS Computer Engineering\n" .
                "- BS Animation, BS Game Development\n" .
                "- 2-Year IT Diploma courses\n\n" .
                "**Features:**\n" .
                "- State-of-the-art computer laboratories\n" .
                "- Industry-standard software at hardware\n" .
                "- Strong industry partnerships (OJT placements)\n" .
                "- High employment rate for graduates\n" .
                "- Flexible payment schemes\n\n" .
                "**Achievements:**\n" .
                "- Consistent high passing rate sa IT certifications\n" .
                "- Recognized IT training partner ng Microsoft, Cisco, Oracle\n" .
                "- Top choice para sa IT courses sa Pangasinan" :
                "**AMA Computer College Pangasinan**\n\n" .
                "**Founded:** 1980 (as AMA Computer Learning Center)\n" .
                "**Part of:** AMA Education System (Dr. Amable R. Aguiluz V, Founder)\n" .
                "**Current Administrator:** Mr. Reynaldo D. Salvador (Campus Director, 2022-present)\n\n" .
                "**Information (2025-2026 Updated):**\n" .
                "- Private computer college in Dagupan City\n" .
                "- Part of the largest IT network in Philippines\n" .
                "- Focus on computer science and technology\n" .
                "- Job-ready programs for IT industry\n\n" .
                "**Location:**\n" .
                "- AB Fernandez Avenue, Dagupan City, Pangasinan\n" .
                "- Downtown area, accessible via public transport\n" .
                "- Near commercial centers and shopping malls\n\n" .
                "**Top Programs (2025-2026):**\n" .
                "- BS Computer Science\n" .
                "- BS Information Technology\n" .
                "- BS Information Systems\n" .
                "- BS Computer Engineering\n" .
                "- BS Animation, BS Game Development\n" .
                "- 2-Year IT Diploma courses\n\n" .
                "**Features:**\n" .
                "- State-of-the-art computer laboratories\n" .
                "- Industry-standard software and hardware\n" .
                "- Strong industry partnerships (OJT placements)\n" .
                "- High employment rate for graduates\n" .
                "- Flexible payment schemes\n\n" .
                "**Achievements:**\n" .
                "- Consistent high passing rate in IT certifications\n" .
                "- Recognized IT training partner of Microsoft, Cisco, Oracle\n" .
                "- Top choice for IT courses in Pangasinan";
        }

        // Pangasinan Universities Overview
        if (str_contains($lowerMessage, 'pangasinan university') || str_contains($lowerMessage, 'universities in pangasinan') || str_contains($lowerMessage, 'schools in pangasinan')) {
            return $isTagalog ?
                "**Mga Unibersidad at Kolehiyo sa Pangasinan (2025-2026 Updated)**\n\n" .
                "**Major Universities:**\n\n" .
                "1. **Pangasinan State University (PSU)**\n" .
                "   - 9 campuses sa buong province\n" .
                "   - President: Dr. Elbert Galas (2024)\n" .
                "   - Sikat sa: Engineering, Education, Criminology\n\n" .
                "2. **University of Pangasinan (UPANG)**\n" .
                "   - Dagupan City\n" .
                "   - President: Dr. Virgilio Crisostomo\n" .
                "   - Sikat sa: Medicine, Nursing, Health Sciences\n\n" .
                "3. **Lyceum-Northwestern University (L-NU)**\n" .
                "   - Dagupan City\n" .
                "   - President: Dr. Joselito De Guzman\n" .
                "   - Sikat sa: Medicine (topnotchers!), Nursing\n\n" .
                "4. **Universidad de Dagupan (UdD)**\n" .
                "   - Dagupan City\n" .
                "   - President: Dr. Feliza Arzadon-Sua\n" .
                "   - Founder: Dr. Voltaire P. Arzadon\n" .
                "   - Sikat sa: Nursing (topnotchers!), Criminology, Engineering\n" .
                "   - Fastest growing university sa Pangasinan\n\n" .
                "5. **Urdaneta City University (UCU)**\n" .
                "   - Urdaneta City\n" .
                "   - President: Dr. Librado Fria\n" .
                "   - Sikat sa: Criminology, Education\n\n" .
                "6. **AMA Computer College Pangasinan**\n" .
                "   - Dagupan City\n" .
                "   - Campus Director: Mr. Reynaldo Salvador\n" .
                "   - Sikat sa: IT, Computer Science, Engineering\n\n" .
                "**Para sa details ng specific university:**\n" .
                "Ask: 'Tell me about [university name]'" :
                "**Universities and Colleges in Pangasinan (2025-2026 Updated)**\n\n" .
                "**Major Universities:**\n\n" .
                "1. **Pangasinan State University (PSU)**\n" .
                "   - 9 campuses throughout the province\n" .
                "   - President: Dr. Elbert Galas (2024)\n" .
                "   - Famous for: Engineering, Education, Criminology\n\n" .
                "2. **University of Pangasinan (UPANG)**\n" .
                "   - Dagupan City\n" .
                "   - President: Dr. Virgilio Crisostomo\n" .
                "   - Famous for: Medicine, Nursing, Health Sciences\n\n" .
                "3. **Lyceum-Northwestern University (L-NU)**\n" .
                "   - Dagupan City\n" .
                "   - President: Dr. Joselito De Guzman\n" .
                "   - Famous for: Medicine (topnotchers!), Nursing\n\n" .
                "4. **Universidad de Dagupan (UdD)**\n" .
                "   - Dagupan City\n" .
                "   - President: Dr. Feliza Arzadon-Sua\n" .
                "   - Founder: Dr. Voltaire P. Arzadon\n" .
                "   - Famous for: Nursing (topnotchers!), Criminology, Engineering\n" .
                "   - Fastest growing university in Pangasinan\n\n" .
                "5. **Urdaneta City University (UCU)**\n" .
                "   - Urdaneta City\n" .
                "   - President: Dr. Librado Fria\n" .
                "   - Famous for: Criminology, Education\n\n" .
                "6. **AMA Computer College Pangasinan**\n" .
                "   - Dagupan City\n" .
                "   - Campus Director: Mr. Reynaldo Salvador\n" .
                "   - Famous for: IT, Computer Science, Engineering\n\n" .
                "**For specific university details:**\n" .
                "Ask: 'Tell me about [university name]'";
        }

        // Default schools/universities overview
        if ($isTagalog) {
            return "**Mga Sikat na Unibersidad sa Pilipinas (2025-2026 Updated)**\n\n" .
                "**Big Four Universities:**\n" .
                "1. **UP Diliman** - State university, libreng tuition\n" .
                "   Chancellor: Edgardo Vistan II (2023-present)\n\n" .
                "2. **UST** - Pinaka-matang unibersidad sa Asia (1611)\n" .
                "   Rector: Rev. Fr. Richard Ang, O.P. (2021-present)\n\n" .
                "3. **Ateneo de Manila** - Private Catholic university\n" .
                "   President: Fr. Roberto Yap, S.J. (2020-present)\n\n" .
                "4. **De La Salle University** - Business at Engineering\n" .
                "   President: Br. Bernard Oca, FSC (2021-present)\n\n" .
                "**Pangasinan Universities:**\n" .
                "- PSU (9 campuses) - President: Dr. Elbert Galas\n" .
                "- UPANG (Dagupan) - President: Dr. Virgilio Crisostomo\n" .
                "- L-NU (Dagupan) - President: Dr. Joselito De Guzman\n" .
                "- Universidad de Dagupan - President: Dr. Feliza Arzadon-Sua (Founder: Dr. Voltaire P. Arzadon)\n" .
                "- UCU (Urdaneta) - President: Dr. Librado Fria\n" .
                "- AMA Pangasinan (Dagupan) - Director: Mr. Reynaldo Salvador\n\n" .
                "**Para sa specific university info:**\n" .
                "Tanungin mo ako: 'Tell me about [university name]'";
        }

        return "**Top Universities in the Philippines (2025-2026 Updated)**\n\n" .
            "**Big Four Universities:**\n" .
            "1. **UP Diliman** - State university, free tuition\n" .
            "   Chancellor: Edgardo Vistan II (2023-present)\n\n" .
            "2. **UST** - Asia's oldest existing university (1611)\n" .
            "   Rector: Rev. Fr. Richard Ang, O.P. (2021-present)\n\n" .
            "3. **Ateneo de Manila** - Private Catholic university\n" .
            "   President: Fr. Roberto Yap, S.J. (2020-present)\n\n" .
            "4. **De La Salle University** - Business and Engineering\n" .
            "   President: Br. Bernard Oca, FSC (2021-present)\n\n" .
            "**Pangasinan Universities:**\n" .
            "- PSU (9 campuses) - President: Dr. Elbert Galas\n" .
            "- UPANG (Dagupan) - President: Dr. Virgilio Crisostomo\n" .
            "- L-NU (Dagupan) - President: Dr. Joselito De Guzman\n" .
            "- Universidad de Dagupan - President: Dr. Feliza Arzadon-Sua (Founder: Dr. Voltaire P. Arzadon)\n" .
            "- UCU (Urdaneta) - President: Dr. Librado Fria\n" .
            "- AMA Pangasinan (Dagupan) - Director: Mr. Reynaldo Salvador\n\n" .
            "**For specific university info:**\n" .
            "Ask me: 'Tell me about [university name]'";
    }

    /**
     * LAW & LEGAL MATTERS response
     */
    private function getLawResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Tungkol sa Batas at Legal Matters**\n\n" .
                "Ang Philippine legal system ay base sa civil law tradition.\n\n" .
                "**Kailangan ng Abogado kapag:**\n" .
                "- May legal dispute o demandahan\n" .
                "- Nag-sign ng kontrata (review muna!)\n" .
                "- May problema sa property\n\n" .
                "**Free Legal Aid:**\n" .
                "- PAO (Public Attorney's Office)\n" .
                "- IBP legal aid programs\n\n" .
                "**Paalala:** Laging basahin mabuti ang kontrata bago pumirma!";
        }
        return "**About Law and Legal Matters**\n\n" .
            "The Philippine legal system is based on civil law tradition.\n\n" .
            "**When to Consult a Lawyer:**\n" .
            "- Legal disputes or lawsuits\n" .
            "- Before signing contracts\n" .
            "- Property issues\n\n" .
            "**Free Legal Aid:**\n" .
            "- PAO (Public Attorney's Office)\n" .
            "- IBP legal aid programs\n\n" .
            "**Reminder:** Always read contracts carefully before signing!";
    }

    /**
     * PSYCHOLOGY & MENTAL HEALTH response
     */
    private function getPsychologyResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Tungkol sa Psychology at Mental Health**\n\n" .
                "Ang mental health ay mahalaga tulad ng physical health.\n\n" .
                "**Self-Care Tips:**\n" .
                "- Regular exercise releases endorphins\n" .
                "- Quality sleep (7-9 hours)\n" .
                "- Social connections\n" .
                "- Mindfulness and meditation\n\n" .
                "**Crisis Hotlines:**\n" .
                "- NCMH: 1553 (toll-free)\n" .
                "- Hopeline: 2919 (toll-free)\n\n" .
                "**Paalala:** Normal na humingi ng tulong. Mental health is health!";
        }
        return "**About Psychology and Mental Health**\n\n" .
            "Mental health is just as important as physical health.\n\n" .
            "**Self-Care Tips:**\n" .
            "- Regular exercise releases endorphins\n" .
            "- Quality sleep (7-9 hours)\n" .
            "- Social connections\n" .
            "- Mindfulness and meditation\n\n" .
            "**Crisis Hotlines:**\n" .
            "- NCMH: 1553 (toll-free)\n" .
            "- Hopeline: 2919 (toll-free)\n\n" .
            "**Reminder:** It's okay to ask for help!";
    }

    /**
     * BUSINESS & ENTREPRENEURSHIP response
     */
    private function getBusinessResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Negosyo at Entrepreneurship**\n\n" .
                "**Steps para Magsimula:**\n" .
                "1. Ideate - hanapin ang problem\n" .
                "2. Research - alamin ang market\n" .
                "3. Plan - gumawa ng business plan\n" .
                "4. Register - DTI, BIR, permits\n" .
                "5. Launch - simulan nang maliit\n\n" .
                "**Funding Options:**\n" .
                "- Personal savings\n" .
                "- Bank loans\n" .
                "- Government programs (DTI, DOST)\n\n" .
                "**Tandaan:** Lahat ng malalaking negosyo nagsimula sa maliit!";
        }
        return "**Business and Entrepreneurship**\n\n" .
            "**Steps to Start:**\n" .
            "1. Ideate - find a problem to solve\n" .
            "2. Research - learn the market\n" .
            "3. Plan - create business plan\n" .
            "4. Register - DTI, BIR, permits\n" .
            "5. Launch - start small\n\n" .
            "**Funding Options:**\n" .
            "- Personal savings\n" .
            "- Bank loans\n" .
            "- Government programs\n\n" .
            "**Remember:** All big businesses started small!";
    }

    /**
     * REAL ESTATE & PROPERTY response
     */
    private function getRealEstateResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Real Estate at Property**\n\n" .
                "**Bago Bumili ng Property:**\n" .
                "- Location, location, location!\n" .
                "- Check ng title at taxes\n" .
                "- Inspect ang physical condition\n\n" .
                "**Financing Options:**\n" .
                "- Bank loans (Pag-IBIG)\n" .
                "- In-house financing\n" .
                "- Rent-to-own\n\n" .
                "**Paalala:** Laging i-verify ang authenticity ng title!";
        }
        return "**Real Estate and Property**\n\n" .
            "**Before Buying Property:**\n" .
            "- Location, location, location!\n" .
            "- Check title and taxes\n" .
            "- Inspect physical condition\n\n" .
            "**Financing Options:**\n" .
            "- Bank loans (Pag-IBIG)\n" .
            "- In-house financing\n" .
            "- Rent-to-own\n\n" .
            "**Reminder:** Always verify title authenticity!";
    }

    /**
     * INSURANCE & PROTECTION response
     */
    private function getInsuranceResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Insurance at Proteksyon**\n\n" .
                "**Mga Uri ng Insurance:**\n" .
                "- Life Insurance - security para sa pamilya\n" .
                "- Health Insurance - medical expenses\n" .
                "- Car Insurance - sasakyan\n" .
                "- Property Insurance - bahay\n\n" .
                "**Rule of Thumb:**\n" .
                "Life insurance = 10x annual income\n\n" .
                "**Tandaan:** Mas mura habang bata ka pa!";
        }
        return "**Insurance and Protection**\n\n" .
            "**Types of Insurance:**\n" .
            "- Life Insurance - family security\n" .
            "- Health Insurance - medical coverage\n" .
            "- Car Insurance - vehicle protection\n" .
            "- Property Insurance - home coverage\n\n" .
            "**Rule of Thumb:**\n" .
            "Life insurance = 10x annual income\n\n" .
            "**Remember:** Cheaper when you're younger!";
    }

    /**
     * CRYPTOCURRENCY & BLOCKCHAIN response
     */
    private function getCryptoResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Cryptocurrency at Blockchain**\n\n" .
                "**Pinakasikat na Cryptocurrencies:**\n" .
                "- Bitcoin (BTC) - digital gold\n" .
                "- Ethereum (ETH) - smart contracts\n" .
                "- BNB, Solana, Cardano\n\n" .
                "**Paano Magsimula:**\n" .
                "1. Research muna - maintindihan ang risks\n" .
                "2. Pumili ng reputable exchange\n" .
                "3. Start small - invest only what you can afford to lose\n\n" .
                "**Golden Rule:** Never invest more than you can afford to lose!";
        }
        return "**Cryptocurrency and Blockchain**\n\n" .
            "**Most Popular Cryptocurrencies:**\n" .
            "- Bitcoin (BTC) - digital gold\n" .
            "- Ethereum (ETH) - smart contract platform\n" .
            "- BNB, Solana, Cardano\n\n" .
            "**How to Start:**\n" .
            "1. Research first - understand the risks\n" .
            "2. Choose reputable exchange\n" .
            "3. Start small - only what you can afford to lose\n\n" .
            "**Golden Rule:** Never invest more than you can afford to lose!";
    }

    /**
     * SPACE & ASTRONOMY response
     */
    private function getSpaceResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Space at Astronomy**\n\n" .
                "**Solar System Highlights:**\n" .
                "- Sun - 99.86% ng mass ng solar system\n" .
                "- Mercury - pinaka-maliit at fastest\n" .
                "- Venus - hottest planet\n" .
                "- Mars - target for colonization\n" .
                "- Jupiter - pinamalaki, 95+ moons\n\n" .
                "**Philippine Space Agency:**\n" .
                "PhilSA - promotes space science sa Pilipinas\n\n" .
                "**Amazing Fact:** Ang light mula sa distant galaxies ay maaaring mas matanda pa kaysa sa Earth!";
        }
        return "**Space and Astronomy**\n\n" .
            "**Solar System Highlights:**\n" .
            "- Sun - 99.86% of solar system's mass\n" .
            "- Mercury - smallest and fastest orbit\n" .
            "- Venus - hottest planet\n" .
            "- Mars - target for future colonization\n" .
            "- Jupiter - largest, has 95+ moons\n\n" .
            "**Philippine Space Agency:**\n" .
            "PhilSA - promotes space science in the Philippines\n\n" .
            "**Amazing Fact:** Light from distant galaxies may be older than Earth itself!";
    }

    /**
     * ENGINEERING & CONSTRUCTION response
     */
    private function getEngineeringResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Engineering at Construction**\n\n" .
                "**Mga Pangunahing Engineering Fields:**\n" .
                "- Civil - buildings, bridges, roads\n" .
                "- Mechanical - machines, vehicles\n" .
                "- Electrical - power systems\n" .
                "- Computer - software, hardware\n\n" .
                "**Philippine Achievements:**\n" .
                "- San Juanico Bridge - longest sa Pilipinas\n" .
                "- Pantabangan Dam - hydroelectric project\n\n" .
                "**Note:** Laging hire ng licensed professionals!";
        }
        return "**Engineering and Construction**\n\n" .
            "**Major Engineering Fields:**\n" .
            "- Civil - buildings, bridges, roads\n" .
            "- Mechanical - machines, vehicles\n" .
            "- Electrical - power systems\n" .
            "- Computer - software, hardware\n\n" .
            "**Philippine Achievements:**\n" .
            "- San Juanico Bridge - longest in Philippines\n" .
            "- Pantabangan Dam - hydroelectric project\n\n" .
            "**Note:** Always hire licensed professionals!";
    }

    /**
     * MUSIC & ARTS response
     */
    private function getMusicResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Musika at Sining**\n\n" .
                "**Mga Uri ng Music:**\n" .
                "- OPM - Original Pilipino Music\n" .
                "- K-Pop - Korean pop phenomenon\n" .
                "- Classical - symphony, opera\n" .
                "- Jazz/Blues - soulful improvisational\n\n" .
                "**Pinakasikat na OPM Artists:**\n" .
                "- Eraserheads, Parokya ni Edgar, Rivermaya\n" .
                "- Regine Velasquez, Gary V, Sarah G\n\n" .
                "**Trivia:** Ang Pilipinas ay may rich musical heritage!";
        }
        return "**Music and Arts**\n\n" .
            "**Types of Music:**\n" .
            "- OPM - Original Pilipino Music\n" .
            "- K-Pop - Korean pop phenomenon\n" .
            "- Classical - symphony, opera\n" .
            "- Jazz/Blues - soulful improvisational\n\n" .
            "**Popular OPM Artists:**\n" .
            "- Eraserheads, Parokya ni Edgar, Rivermaya\n" .
            "- Regine Velasquez, Gary Valenciano, Sarah G\n\n" .
            "**Trivia:** The Philippines has a rich musical heritage!";
    }

    /**
     * LITERATURE & BOOKS response
     */
    private function getLiteratureResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Literatura at Libro**\n\n" .
                "**Mga Sikat na Filipino Authors:**\n" .
                "- Jose Rizal - Noli Me Tangere\n" .
                "- Nick Joaquin - Philippine literature icon\n" .
                "- F. Sionil Jose - The Rosales Saga\n" .
                "- Bob Ong - Modern Filipino humor\n\n" .
                "**Benefits ng Pagbabasa:**\n" .
                "- Improved vocabulary\n" .
                "- Reduced stress\n" .
                "- Increased empathy\n\n" .
                "**Pangasinan Connection:** Maraming local writers sa Pangasinan!";
        }
        return "**Literature and Books**\n\n" .
            "**Famous Filipino Authors:**\n" .
            "- Jose Rizal - Noli Me Tangere\n" .
            "- Nick Joaquin - Philippine literature icon\n" .
            "- F. Sionil Jose - The Rosales Saga\n" .
            "- Bob Ong - Modern Filipino humor\n\n" .
            "**Benefits of Reading:**\n" .
            "- Improved vocabulary\n" .
            "- Reduced stress\n" .
            "- Increased empathy\n\n" .
            "**Pangasinan Connection:** Many local writers in Pangasinan!";
    }

    /**
     * MOVIES & CINEMA response
     */
    private function getMoviesResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Pelikula at Cinema**\n\n" .
                "**Golden Age ng Philippine Cinema:**\n" .
                "- 1950s-1960s: Sampaguita, LVN Studios\n" .
                "- Nora Aunor, Vilma Santos, Fernando Poe Jr.\n\n" .
                "**Modern Filipino Cinema:**\n" .
                "- Heneral Luna - historical epic\n" .
                "- On the Job - crime thriller\n" .
                "- Hello, Love, Goodbye - OFW story\n\n" .
                "**Streaming:** Netflix, Disney+, iWantTFC\n\n" .
                "**Cinema Etiquette:** Silence phone, don't spoil!";
        }
        return "**Movies and Cinema**\n\n" .
            "**Golden Age of Philippine Cinema:**\n" .
            "- 1950s-1960s: Sampaguita, LVN Studios\n" .
            "- Nora Aunor, Vilma Santos, Fernando Poe Jr.\n\n" .
            "**Modern Filipino Cinema:**\n" .
            "- Heneral Luna - historical epic\n" .
            "- On the Job - crime thriller\n" .
            "- Hello, Love, Goodbye - OFW story\n\n" .
            "**Streaming:** Netflix, Disney+, iWantTFC\n\n" .
            "**Cinema Etiquette:** Silence phone, don't spoil!";
    }

    /**
     * PHOTOGRAPHY & VISUAL ARTS response
     */
    private function getPhotographyResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Photography at Visual Arts**\n\n" .
                "**Mga Uri ng Photography:**\n" .
                "- Portrait - capturing people\n" .
                "- Landscape - nature scenery\n" .
                "- Street - candid urban\n" .
                "- Wildlife - animals\n\n" .
                "**Basics:**\n" .
                "- Exposure Triangle: Aperture, Shutter Speed, ISO\n" .
                "- Composition: Rule of thirds\n\n" .
                "**Photo Spots sa Pangasinan:**\n" .
                "- Hundred Islands, Bolinao lighthouse, Patar Beach\n\n" .
                "**Tip:** Best camera is the one you have with you!";
        }
        return "**Photography and Visual Arts**\n\n" .
            "**Types of Photography:**\n" .
            "- Portrait - capturing people\n" .
            "- Landscape - nature scenery\n" .
            "- Street - candid urban\n" .
            "- Wildlife - animals\n\n" .
            "**Basics:**\n" .
            "- Exposure Triangle: Aperture, Shutter Speed, ISO\n" .
            "- Composition: Rule of thirds\n\n" .
            "**Photo Spots in Pangasinan:**\n" .
            "- Hundred Islands, Bolinao lighthouse, Patar Beach\n\n" .
            "**Tip:** Best camera is the one you have with you!";
    }

    /**
     * FITNESS & BODYBUILDING response
     */
    private function getFitnessResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Fitness at Bodybuilding**\n\n" .
                "**Mga Uri ng Exercise:**\n" .
                "- Cardio - running, swimming (heart health)\n" .
                "- Strength Training - weights (muscle building)\n" .
                "- Flexibility - yoga, stretching\n" .
                "- HIIT - fat burning\n\n" .
                "**Nutrition:**\n" .
                "- Protein: 1.6-2.2g per kg bodyweight\n" .
                "- Hydration: 3-4 liters daily\n\n" .
                "**Gyms sa Pangasinan:** Dagupan, Urdaneta\n\n" .
                "**Motivation:** Consistency beats intensity!";
        }
        return "**Fitness and Bodybuilding**\n\n" .
            "**Types of Exercise:**\n" .
            "- Cardio - running, swimming (heart health)\n" .
            "- Strength Training - weights (muscle building)\n" .
            "- Flexibility - yoga, stretching\n" .
            "- HIIT - fat burning\n\n" .
            "**Nutrition:**\n" .
            "- Protein: 1.6-2.2g per kg bodyweight\n" .
            "- Hydration: 3-4 liters daily\n\n" .
            "**Gyms in Pangasinan:** Dagupan, Urdaneta\n\n" .
            "**Motivation:** Consistency beats intensity!";
    }

    /**
     * NUTRITION & DIET response
     */
    private function getNutritionResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Nutrition at Diet**\n\n" .
                "**Essential Nutrients:**\n" .
                "- Carbohydrates - energy source (55-65%)\n" .
                "- Proteins - muscle repair (10-15%)\n" .
                "- Fats - hormone production (25-30%)\n\n" .
                "**Healthy Eating:**\n" .
                "- Eat the rainbow (colorful vegetables)\n" .
                "- Choose whole grains\n" .
                "- Limit processed foods\n\n" .
                "**Filipino Healthy Foods:**\n" .
                "- Pinakbet, tinola, sinigang\n\n" .
                "**Note:** Sustainable changes > quick fixes!";
        }
        return "**Nutrition and Diet**\n\n" .
            "**Essential Nutrients:**\n" .
            "- Carbohydrates - energy source (55-65%)\n" .
            "- Proteins - muscle repair (10-15%)\n" .
            "- Fats - hormone production (25-30%)\n\n" .
            "**Healthy Eating:**\n" .
            "- Eat the rainbow (colorful vegetables)\n" .
            "- Choose whole grains\n" .
            "- Limit processed foods\n\n" .
            "**Filipino Healthy Foods:**\n" .
            "- Pinakbet, tinola, sinigang\n\n" .
            "**Note:** Sustainable changes > quick fixes!";
    }

    /**
     * PARENTING & FAMILY response
     */
    private function getParentingResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Parenting at Pamilya**\n\n" .
                "**Parenting Styles:**\n" .
                "- Authoritative - warmth + discipline\n" .
                "- Authoritarian - strict, rule-based\n" .
                "- Permissive - indulgent\n\n" .
                "**Positive Tips:**\n" .
                "- Active listening\n" .
                "- Consistent boundaries\n" .
                "- Quality time together\n\n" .
                "**Filipino Values:**\n" .
                "- Utang na loob, Pakikisama, Family first\n\n" .
                "**Note:** Walang perfect parent!";
        }
        return "**Parenting and Family**\n\n" .
            "**Parenting Styles:**\n" .
            "- Authoritative - warmth + discipline\n" .
            "- Authoritarian - strict, rule-based\n" .
            "- Permissive - indulgent\n\n" .
            "**Positive Tips:**\n" .
            "- Active listening\n" .
            "- Consistent boundaries\n" .
            "- Quality time together\n\n" .
            "**Filipino Values:**\n" .
            "- Utang na loob, Pakikisama, Family first\n\n" .
            "**Note:** There's no perfect parent!";
    }

    /**
     * DATING & ROMANCE response
     */
    private function getDatingResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Dating at Romance**\n\n" .
                "**Dating Tips:**\n" .
                "- Maging authentic\n" .
                "- Active listening\n" .
                "- Choose comfortable locations\n\n" .
                "**Red Flags:** Inconsistent, disrespectful, pressuring\n" .
                "**Green Flags:** Respects boundaries, kind, consistent\n\n" .
                "**Date Ideas sa Pangasinan:**\n" .
                "- Hundred Islands tour\n" .
                "- Sunset sa Patar Beach\n" .
                "- Food trip sa Dagupan\n\n" .
                "**Most Important:** Be yourself at have fun!";
        }
        return "**Dating and Romance**\n\n" .
            "**Dating Tips:**\n" .
            "- Be authentic\n" .
            "- Active listening\n" .
            "- Choose comfortable locations\n\n" .
            "**Red Flags:** Inconsistent, disrespectful, pressuring\n" .
            "**Green Flags:** Respects boundaries, kind, consistent\n\n" .
            "**Date Ideas in Pangasinan:**\n" .
            "- Hundred Islands tour\n" .
            "- Sunset at Patar Beach\n" .
            "- Food trip in Dagupan\n\n" .
            "**Most Important:** Be yourself and have fun!";
    }

    /**
     * WEDDING & MARRIAGE response
     */
    private function getWeddingResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Wedding at Kasal**\n\n" .
                "**Wedding Planning Timeline:**\n" .
                "- 12 months: Budget at venue\n" .
                "- 9 months: Book suppliers\n" .
                "- 6 months: Wedding dress\n" .
                "- 3 months: Finalize details\n\n" .
                "**Philippine Traditions:**\n" .
                "- Pamamanhikan, Barong Tagalog, Arras\n\n" .
                "**Budget:** Venue 40-50%, Photo 10-15%\n\n" .
                "**Remember:** Marriage > Wedding!";
        }
        return "**Wedding and Marriage**\n\n" .
            "**Wedding Planning Timeline:**\n" .
            "- 12 months: Budget and venue\n" .
            "- 9 months: Book suppliers\n" .
            "- 6 months: Wedding dress\n" .
            "- 3 months: Finalize details\n\n" .
            "**Philippine Traditions:**\n" .
            "- Pamamanhikan, Barong Tagalog, Arras\n\n" .
            "**Budget:** Venue 40-50%, Photo 10-15%\n\n" .
            "**Remember:** Marriage > Wedding!";
    }

    /**
     * PETS & ANIMALS response
     */
    private function getPetsResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Alagang Hayop at Pets**\n\n" .
                "**Benefits:** Reduced stress, companionship, exercise\n\n" .
                "**Common Pets sa Pilipinas:**\n" .
                "- Dogs: Aspin, Shih Tzu, Beagle\n" .
                "- Cats: Puspin, Persian, Siamese\n" .
                "- Birds: Lovebirds, Parakeets\n\n" .
                "**Pet Care:**\n" .
                "- Annual vet checkups\n" .
                "- Proper nutrition\n" .
                "- Vaccinations\n\n" .
                "**Consider adoption muna bago bumili!";
        }
        return "**Pets and Animals**\n\n" .
            "**Benefits:** Reduced stress, companionship, exercise\n\n" .
            "**Common Pets in Philippines:**\n" .
            "- Dogs: Aspin, Shih Tzu, Beagle\n" .
            "- Cats: Puspin, Persian, Siamese\n" .
            "- Birds: Lovebirds, Parakeets\n\n" .
            "**Pet Care:**\n" .
            "- Annual vet checkups\n" .
            "- Proper nutrition\n" .
            "- Vaccinations\n\n" .
            "**Consider adoption before buying!";
    }

    /**
     * GARDENING & PLANTS response
     */
    private function getGardeningResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Gardening at Halaman**\n\n" .
                "**Benefits:** Stress relief, fresh produce, exercise\n\n" .
                "**Easy Plants for Beginners:**\n" .
                "- Herbs: basil, mint, oregano\n" .
                "- Vegetables: pechay, tomatoes\n" .
                "- Flowers: santan, bougainvillea\n\n" .
                "**Basics:**\n" .
                "- Sunlight, water, soil quality\n" .
                "- Regular fertilizing\n\n" .
                "**Tip:** Start small, expand gradually!";
        }
        return "**Gardening and Plants**\n\n" .
            "**Benefits:** Stress relief, fresh produce, exercise\n\n" .
            "**Easy Plants for Beginners:**\n" .
            "- Herbs: basil, mint, oregano\n" .
            "- Vegetables: pechay, tomatoes\n" .
            "- Flowers: santan, bougainvillea\n\n" .
            "**Basics:**\n" .
            "- Sunlight, water, soil quality\n" .
            "- Regular fertilizing\n\n" .
            "**Tip:** Start small, expand gradually!";
    }

    /**
     * ASTRONOMY & ASTROLOGY response
     */
    private function getAstronomyResponse(bool $isTagalog, string $lowerMessage): string
    {
        if ($isTagalog) {
            return "**Astronomy at Astrology**\n\n" .
                "**Zodiac Signs:**\n" .
                "- Fire: Aries, Leo, Sagittarius\n" .
                "- Earth: Taurus, Virgo, Capricorn\n" .
                "- Air: Gemini, Libra, Aquarius\n" .
                "- Water: Cancer, Scorpio, Pisces\n\n" .
                "**Note:** Astrology is for fun! Scientific basis: astronomy (study of celestial bodies).";
        }
        return "**Astronomy and Astrology**\n\n" .
            "**Zodiac Signs:**\n" .
            "- Fire: Aries, Leo, Sagittarius\n" .
            "- Earth: Taurus, Virgo, Capricorn\n" .
            "- Air: Gemini, Libra, Aquarius\n" .
            "- Water: Cancer, Scorpio, Pisces\n\n" .
            "**Note:** Astrology is for fun! Scientific basis: astronomy (study of celestial bodies).";
    }
    /**
     * Call Gemini API with conversation context for smarter responses
     */
    private function callGeminiAPIWithContext(string $userMessage, string $systemPrompt, array $conversationHistory): ?string
    {
        try {
            $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
            if (empty($apiKey) || $apiKey === 'your_gemini_api_key_here') {
                return null;
            }

            \Log::info('Calling Gemini API with context...');

            // Build conversation context for Gemini
            $contextParts = [];
            
            // Add system prompt
            $contextParts[] = ['role' => 'user', 'parts' => [['text' => $systemPrompt]]];
            $contextParts[] = ['role' => 'model', 'parts' => [['text' => 'Understood. I will act as a helpful AI assistant and answer any questions to the best of my ability.']]];
            
            // Add conversation history (last 10 messages)
            $recentHistory = array_slice($conversationHistory, -10);
            foreach ($recentHistory as $msg) {
                $role = $msg['role'] === 'user' ? 'user' : 'model';
                $contextParts[] = ['role' => $role, 'parts' => [['text' => $msg['content']]]];
            }
            
            // Add current user message
            $contextParts[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

            $model = config('services.gemini.model', 'gemini-1.5-flash');
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $payload = [
                'contents' => $contextParts,
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 1000,
                    'topP' => 0.95,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if (!empty($content)) {
                    \Log::info('Gemini API success, response length: ' . strlen($content));
                    return trim($content);
                }
            }

            \Log::warning('Gemini API error: ' . $response->status());
            return null;

        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simple Gemini API fallback without context
     */
    private function callGeminiAPI(string $message, string $systemPrompt): ?string
    {
        try {
            $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
            if (empty($apiKey) || $apiKey === 'your_gemini_api_key_here') {
                return null;
            }

            $gemini = new \App\Services\GeminiService();
            $reply = $gemini->chat($systemPrompt . "\n\nUser Message: " . $message);
            
            return ($reply === 'QUOTA_EXCEEDED') ? null : $reply;
        } catch (\Exception $e) {
            \Log::error('Gemini Fallback Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle streaming chat messages for real-time response (ChatGPT-like)
     */
    public function chatStream(Request $request)
    {
        try {
            $message = $request->input('message', '');
            if (empty($message)) {
                return response()->json(['error' => 'No message provided'], 400);
            }

            $conversationHistory = session('chatbot_history', []);
            $lowerMessage = strtolower($message);
            $isTagalog = $this->isTagalogMessage($lowerMessage);
            
            // FAST PATH: Check local knowledge first for instant response
            $localResponse = $this->generateLocalResponse($lowerMessage, $message, $isTagalog);
            
            // If it's a generic "I don't know" or "Tell me more" response, proceed to AI
            // Otherwise, if it's a specific fact-based response, send it instantly
            $isGeneric = str_contains($localResponse, "I can help you with") || 
                        str_contains($localResponse, "That's interesting") ||
                        str_contains($localResponse, "hindi ko masyadong nakuha");

            if (!$isGeneric) {
                return response()->stream(function () use ($localResponse) {
                    // Send headers and then the local fact as a "streamed" chunk
                    $chunk = [
                        'choices' => [[
                            'delta' => ['content' => $localResponse],
                            'finish_reason' => 'stop'
                        ]]
                    ];
                    echo "data: " . json_encode($chunk) . "\n\n";
                    echo "data: [DONE]\n\n";
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }, 200, [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'text/event-stream',
                    'X-Accel-Buffering' => 'no',
                ]);
            }

            // AI PATH: DeepSeek
            $systemPrompt = $this->buildEnhancedSystemPrompt($isTagalog, null, $conversationHistory);
            $apiKey = config('services.deepseek.api_key', env('DEEPSEEK_API_KEY'));
            
            if (empty($apiKey)) {
                return response()->json(['error' => 'DeepSeek API key not configured'], 500);
            }

            // Update session history
            $conversationHistory[] = ['role' => 'user', 'content' => $message];
            session(['chatbot_history' => $conversationHistory]);

            return response()->stream(function () use ($message, $systemPrompt, $conversationHistory, $apiKey) {
                // Ensure no buffering
                if (function_exists('apache_setenv')) {
                    @apache_setenv('no-gzip', '1');
                }
                @ini_set('zlib.output_compression', '0');
                @ini_set('implicit_flush', '1');

                $messages = [['role' => 'system', 'content' => $systemPrompt]];
                $recentHistory = array_slice($conversationHistory, -11, 10);
                foreach ($recentHistory as $msg) {
                    if (isset($msg['role']) && isset($msg['content'])) {
                        $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
                    }
                }
                $messages[] = ['role' => 'user', 'content' => $message];

                $ch = curl_init('https://api.deepseek.com/v1/chat/completions');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    'model' => 'deepseek-chat',
                    'messages' => $messages,
                    'stream' => true,
                    'max_tokens' => 2000,
                    'temperature' => 0.7,
                ]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
                ]);
                
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
                    echo $data;
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                    return strlen($data);
                });
                
                curl_exec($ch);
                curl_close($ch);
                
            }, 200, [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'text/event-stream',
                'X-Accel-Buffering' => 'no',
                'Connection' => 'keep-alive',
            ]);

        } catch (\Exception $e) {
            \Log::error('ChatStream Error: ' . $e->getMessage());
            return response()->json(['error' => 'Streaming failed: ' . $e->getMessage()], 500);
        }
    }
}