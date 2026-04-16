<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot page.
     */
    public function index()
    {
        return view('chatbot');
    }

    /**
     * Handle chatbot API messages.
     */
    public function handleMessage(Request $request)
    {
        try {
            $message = $request->input('message');
            
            // Log the incoming message for debugging
            Log::info('Chatbot message received', ['message' => $message]);
            
            // Generate AI response
            $response = $this->generateAIResponse($message);
            
            return response()->json([
                'success' => true,
                'message' => $response
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chatbot error', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ]);
        }
    }

    /**
     * Generate AI response based on message content.
     */
    private function generateAIResponse($message)
    {
        $lowerMessage = strtolower($message);
        
        // Roommate finding responses
        if (str_contains($lowerMessage, 'find') || str_contains($lowerMessage, 'roommate') || str_contains($lowerMessage, 'match')) {
            return "I can help you find compatible roommates! Our AI algorithm analyzes your lifestyle preferences, budget, schedule, and other factors to match you with best candidates. Here's how to get started:\n\n1️⃣ Complete your profile with detailed preferences\n2️⃣ Set your budget range and lifestyle preferences\n3️⃣ Be specific about your ideal roommate qualities\n4️⃣ Browse roommate listings with AI compatibility scores\n\nWould you like tips on improving your profile to get better matches?";
        }
        
        // Location-specific responses
        if (str_contains($lowerMessage, 'dagupan') || str_contains($lowerMessage, 'near') || str_contains($lowerMessage, 'location')) {
            return "Great choice! Dagupan City is a popular location for roommates. Here's what I can help you find:\n\n🏠 **Available in Dagupan**:\n• Students from PSU and other universities\n• Young professionals working in the city\n• Various budget ranges to suit your needs\n• Different lifestyle preferences\n\n📍 **How to search**:\n1. Go to the Roommates page\n2. Set location filter to 'Dagupan City'\n3. Adjust your budget and preferences\n4. Browse compatible matches\n\n💡 **Pro Tip**: Complete your profile first to get better compatibility scores in Dagupan!";
        }
        
        // Profile improvement responses
        if (str_contains($lowerMessage, 'profile') || str_contains($lowerMessage, 'improve') || str_contains($lowerMessage, 'optimize') || str_contains($lowerMessage, 'tips')) {
            return "Great question! Here are my top profile optimization tips:\n\n📸 **Photo**: Add a clear, friendly photo of yourself\n📝 **Complete Info**: Fill out all profile sections completely\n💰 **Budget**: Set realistic budget ranges (min-max)\n🏠 **Preferences**: Be specific about lifestyle, cleanliness, schedule\n🎓 **Education**: Add your university and course information\n📋 **Details**: Describe your ideal roommate clearly\n\n💡 **Pro Tip**: Complete profiles get 3x more matches! What specific area would you like help with?";
        }
        
        // Matching algorithm responses
        if (str_contains($lowerMessage, 'matching') || str_contains($lowerMessage, 'algorithm') || str_contains($lowerMessage, 'how does') || str_contains($lowerMessage, 'compatibility')) {
            return "Our AI matching algorithm is quite sophisticated! Here's how it works:\n\n🧮 **Scoring Breakdown**:\n• Lifestyle Compatibility: 25%\n• Schedule Alignment: 20%\n• Budget Compatibility: 20%\n• Cleanliness Standards: 15%\n• Age Proximity: 15%\n• University Connection: 10%\n\n🤖 **AI Analysis**: The system also considers:\n• Personality matches from preferences\n• Living habit compatibility\n• Social lifestyle alignment\n• Financial compatibility\n• Academic/social connections\n\n📊 **Compatibility Scores**:\n• 80%+ = Excellent Match\n• 60-79% = Good Match\n• 50-59% = Fair Match\n\nWant to know how to improve your compatibility score?";
        }
        
        // Advice responses
        if (str_contains($lowerMessage, 'advice') || str_contains($lowerMessage, 'tips') || str_contains($lowerMessage, 'help') || str_contains($lowerMessage, 'roommate advice')) {
            return "Here are my essential roommate success tips:\n\n🏠 **Before Moving In**:\n• Discuss expectations openly\n• Set clear house rules together\n• Agree on cleaning schedules\n• Split expenses fairly\n• Respect each other's privacy\n\n🤝 **Living Together**:\n• Communicate issues early\n• Be flexible but maintain boundaries\n• Schedule regular check-ins\n• Share responsibilities equally\n• Be considerate of noise/guests\n\n⚠️ **Red Flags to Watch**:\n• Poor communication habits\n• Financial irresponsibility\n• Disrespect for boundaries\n• Inconsistent cleanliness\n• Unreliable with commitments\n\nWhat specific roommate situation would you like advice about?";
        }
        
        // Default responses
        $defaultResponses = [
            "That's interesting! I can help you with finding compatible roommates, optimizing your profile, understanding our matching algorithm, or getting roommate advice. What would you like to know more about?",
            "I'm here to help! I can assist with: 🏠 Finding roommates, 📝 Profile optimization, 🤖 AI matching questions, or 💡 General roommate advice. What interests you most?",
            "Great to hear from you! I specialize in roommate matching success. Ask me about finding compatible roommates, improving your profile, how our AI matching works, or any roommate-related questions!",
            "Thanks for reaching out! I'm your AI roommate assistant. I can help you find perfect roommate match, optimize your profile for better results, explain our matching algorithm, or provide roommate relationship advice. What would you like to explore?",
            "Hello! I'm excited to help you find your ideal roommate! I can assist with roommate searching, profile improvement, understanding compatibility scores, or general roommate living advice. What's on your mind today?"
        ];
        
        return $defaultResponses[array_rand($defaultResponses)];
    }
}
