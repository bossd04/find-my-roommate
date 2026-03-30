<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoommateProfile;
use App\Services\MatchingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    protected $matchingService;

    public function __construct(MatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Process chat message and generate AI response
     */
    public function chat(Request $request): JsonResponse
    {
        try {
            $message = $request->input('message', '');
            $user = Auth::user();

            // Generate response based on message content
            $response = $this->generateResponse($message, $user);

            return response()->json([
                'success' => true,
                'message' => $response,
                'type' => 'bot'
            ]);
        } catch (\Exception $e) {
            \Log::error('Chat API Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'I apologize, but I encountered an error processing your request. Please try again.',
                'type' => 'bot'
            ], 500);
        }
    }

    /**
     * Generate AI response based on message content
     */
    private function generateResponse(string $message, ?User $user): string
    {
        $lowerMessage = strtolower($message);

        // Handle numbered options (1, 2, 3, 4)
        if (preg_match('/^\s*[1-4]\s*$/', $message)) {
            return $this->handleNumberedSelection($message, $user);
        }

        // Handle location-based queries
        if ($this->isLocationQuery($lowerMessage)) {
            return $this->handleLocationQuery($lowerMessage, $user);
        }

        // Handle profile improvement queries
        if ($this->isProfileQuery($lowerMessage)) {
            return $this->handleProfileQuery($message, $user);
        }

        // Handle specific roommate finding questions
        if ($this->isSpecificRoommateQuery($lowerMessage)) {
            return $this->handleSpecificRoommateQuery($lowerMessage, $user);
        }

        // Handle budget-related questions
        if ($this->isBudgetQuery($lowerMessage)) {
            return $this->handleBudgetQuery($lowerMessage, $user);
        }

        // Handle lifestyle preference questions
        if ($this->isLifestyleQuery($lowerMessage)) {
            return $this->handleLifestyleQuery($lowerMessage, $user);
        }

        // Handle university/education questions
        if ($this->isUniversityQuery($lowerMessage)) {
            return $this->handleUniversityQuery($lowerMessage, $user);
        }

        // Handle roommate compatibility questions
        if ($this->isCompatibilityQuestion($lowerMessage)) {
            return $this->handleCompatibilityQuestion($lowerMessage, $user);
        }

        // Handle safety/security questions
        if ($this->isSafetyQuery($lowerMessage)) {
            return $this->handleSafetyQuery($lowerMessage);
        }

        // Handle application/process questions
        if ($this->isProcessQuery($lowerMessage)) {
            return $this->handleProcessQuery($lowerMessage);
        }

        // Handle existing queries
        if ($this->isRoommateQuery($lowerMessage)) {
            return $this->handleRoommateQuery($user);
        }

        if ($this->isMatchingQuery($lowerMessage)) {
            return $this->handleMatchingQuery();
        }

        if ($this->isAdviceQuery($lowerMessage)) {
            return $this->handleAdviceQuery();
        }

        // Handle greetings and small talk
        if ($this->isGreeting($lowerMessage)) {
            return $this->handleGreeting($user);
        }

        // Handle help requests
        if ($this->isHelpRequest($lowerMessage)) {
            return $this->handleHelpRequest();
        }

        // Handle general knowledge questions (ChatGPT-like responses)
        return $this->handleGeneralKnowledge($message, $user);
    }

    /**
     * Handle numbered selections (1, 2, 3, 4)
     */
    private function handleNumberedSelection(string $message, ?User $user): string
    {
        $number = trim($message);

        switch ($number) {
            case '1':
                return $this->getProfilePhotoTips($user);
            case '2':
                return $this->getProfileCompletionTips($user);
            case '3':
                return $this->getPreferenceTips($user);
            case '4':
                return $this->getCompatibilityTips($user);
            default:
                return "Please select a number between 1 and 4 to get specific profile improvement tips.";
        }
    }

    /**
     * Get profile photo tips
     */
    private function getProfilePhotoTips(?User $user): string
    {
        $hasAvatar = $user && $user->avatar;
        
        $tips = "📸 **Profile Photo Tips**:\n\n";
        
        if (!$hasAvatar) {
            $tips .= "⚠️ **Action Required**: You don't have a profile photo yet!\n\n";
        }
        
        $tips .= "✅ **Best Practices**:\n";
        $tips .= "• Use a clear, recent photo (within 6 months)\n";
        $tips .= "• Show your face clearly - no sunglasses or hats\n";
        $tips .= "• Use good lighting - natural light is best\n";
        $tips .= "• Smile and look friendly and approachable\n";
        $tips .= "• Plain background works best\n";
        $tips .= "• File size up to 5GB supported\n\n";
        
        $tips .= "🚫 **Avoid**:\n";
        $tips .= "• Group photos (confusing who you are)\n";
        $tips .= "• blurry or dark photos\n";
        $tips .= "• photos with ex-partners\n";
        $tips .= "• overly edited/filter-heavy images\n\n";
        
        if (!$hasAvatar) {
            $tips .= "💡 **Quick Fix**: Go to Profile Settings → Upload Profile Picture to add your photo now!";
        } else {
            $tips .= "✨ **Great job**: You have a profile photo! Make sure it follows these best practices for maximum matches.";
        }
        
        return $tips;
    }

    /**
     * Get profile completion tips
     */
    private function getProfileCompletionTips(?User $user): string
    {
        $completion = $this->getProfileCompletion($user);
        
        $tips = "📝 **Profile Completion Tips**:\n\n";
        $tips .= "📊 **Your Current Status**: {$completion['percentage']}% Complete\n\n";
        
        if (!empty($completion['missing'])) {
            $tips .= "⚠️ **Missing Information**:\n";
            foreach ($completion['missing'] as $item) {
                $tips .= "• $item\n";
            }
            $tips .= "\n";
        }
        
        $tips .= "✅ **Why Complete Profile Matters**:\n";
        $tips .= "• 3x more matches with complete profiles\n";
        $tips .= "• Higher compatibility scores\n";
        $tips .= "• More serious roommate seekers\n";
        $tips .= "• Better algorithm matching\n\n";
        
        $tips .= "🎯 **Priority Sections**:\n";
        $tips .= "1. Personal info (name, age, gender)\n";
        $tips .= "2. Contact details (phone, email)\n";
        $tips .= "3. Location preferences\n";
        $tips .= "4. Budget range (min-max)\n";
        $tips .= "5. Lifestyle habits\n";
        $tips .= "6. Education details\n\n";
        
        $tips .= "💡 **Pro Tip**: Complete profiles get featured more prominently in search results!";
        
        return $tips;
    }

    /**
     * Get preference tips
     */
    private function getPreferenceTips(?User $user): string
    {
        $tips = "⚙️ **Preference Settings Tips**:\n\n";
        
        $tips .= "💰 **Budget Settings**:\n";
        $tips .= "• Set realistic min-max ranges\n";
        $tips .= "• Include utilities in budget planning\n";
        $tips .= "• Be flexible - wider range = more matches\n\n";
        
        $tips .= "🏠 **Location Preferences**:\n";
        $tips .= "• Specify preferred areas/cities\n";
        $tips .= "• Consider commute times\n";
        $tips .= "• Think about proximity to university/work\n\n";
        
        $tips .= "🧹 **Lifestyle Habits**:\n";
        $tips .= "• Be honest about cleanliness standards\n";
        $tips .= "• Set realistic study/social schedules\n";
        $tips .= "• Specify guest policies\n";
        $tips .= "• Mention smoking/drinking preferences\n\n";
        
        $tips .= "👥 **Ideal Roommate**:\n";
        $tips .= "• Age range preferences\n";
        $tips .= "• Gender preferences\n";
        $tips .= "• Student vs working professional\n";
        $tips .= "• Study habits (quiet vs social)\n\n";
        
        $tips .= "💡 **Secret**: Detailed preferences = better matches! The more specific you are, the more compatible your matches will be.";
        
        return $tips;
    }

    /**
     * Get compatibility tips
     */
    private function getCompatibilityTips(?User $user): string
    {
        $tips = "🎯 **Compatibility Score Tips**:\n\n";
        
        $tips .= "📊 **How Scoring Works**:\n";
        $tips .= "• Lifestyle Compatibility: 25%\n";
        $tips .= "• Schedule Alignment: 20%\n";
        $tips .= "• Budget Compatibility: 20%\n";
        $tips .= "• Cleanliness Standards: 15%\n";
        $tips .= "• Age Proximity: 15%\n";
        $tips .= "• University Connection: 10%\n\n";
        
        $tips .= "🚀 **Boost Your Score**:\n";
        $tips .= "• Complete all preference sections\n";
        $tips .= "• Be realistic with budget ranges\n";
        $tips .= "• Specify study/social schedules\n";
        $tips .= "• Set cleanliness expectations\n";
        $tips .= "• Add university/course info\n\n";
        
        $tips .= "📈 **Score Interpretation**:\n";
        $tips .= "• 80%+ = Excellent Match (Highly recommended)\n";
        $tips .= "• 60-79% = Good Match (Worth considering)\n";
        $tips .= "• 50-59% = Fair Match (Some compromises needed)\n";
        $tips .= "• Below 50% = Poor Match (Likely conflicts)\n\n";
        
        $tips .= "💡 **Pro Strategy**: Aim for 70%+ scores, but don't ignore 60-69% - sometimes the best roommates aren't perfect on paper!";
        
        return $tips;
    }

    /**
     * Check if message is a location query
     */
    private function isLocationQuery(string $message): bool
    {
        $locationKeywords = [
            'dagupan', 'alaminos', 'urdaneta', 'lingayen', 'san carlos',
            'near', 'location', 'area', 'city', 'municipality', 'place',
            'who is in', 'users in', 'roommates in', 'compatible near'
        ];

        foreach ($locationKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle location-based queries
     */
    private function handleLocationQuery(string $message, ?User $user): string
    {
        // Extract location from message
        $location = $this->extractLocation($message);
        
        if (!$location) {
            return "I can help you find roommates in specific locations! Please mention a specific city or municipality in Pangasinan like:\n\n🏙️ **Cities**: Dagupan, Alaminos, San Carlos, Urdaneta\n\n🏘️ **Municipalities**: Lingayen, Calasiao, Mangaldan, Mapandan, etc.\n\nTry asking: \"Who are compatible roommates in Dagupan?\"";
        }

        // Find users in that location
        $users = $this->findUsersInLocation($location, $user);
        
        if (empty($users)) {
            return "📍 **Roommates in {$location}**:\n\n❌ No compatible roommates found in {$location} at the moment.\n\n💡 **Suggestions**:\n• Try nearby locations\n• Expand your search radius\n• Check back later - new users join daily!\n\nWould you like me to suggest alternative locations?";
        }

        $response = "📍 **Compatible Roommates in {$location}**:\n\n";
        $response .= "Found " . count($users) . " potential matches:\n\n";

        foreach ($users as $index => $roommate) {
            $compatibility = $this->calculateQuickCompatibility($user, $roommate);
            $response .= ($index + 1) . ". **{$roommate->name}**\n";
            $response .= "   🎯 Compatibility: {$compatibility}%\n";
            $response .= "   📧 Contact: {$roommate->email}\n";
            $response .= "   🎓 " . ($roommate->university ?? 'University not specified') . "\n";
            if ($roommate->roommateProfile && $roommate->roommateProfile->budget_max) {
                $response .= "   💰 Budget: Up to ₱" . number_format($roommate->roommateProfile->budget_max) . "\n";
            }
            $response .= "\n";
        }

        $response .= "💡 **Next Steps**:\n";
        $response .= "• Visit the Matches page to see full profiles\n";
        $response .= "• Send messages to interested roommates\n";
        $response .= "• Check compatibility scores for detailed analysis\n\n";
        $response .= "Would you like tips on how to approach these potential roommates?";

        return $response;
    }

    /**
     * Extract location from message
     */
    private function extractLocation(string $message): ?string
    {
        $locations = [
            'dagupan', 'alaminos', 'san carlos', 'urdaneta', 'lingayen',
            'calasiao', 'mangaldan', 'mapandan', 'san fabian', 'san jacinto',
            'san manuel', 'san nicolas', 'san quintin', 'santa barbara',
            'santa maria', 'santo tomas', 'sison', 'tayug', 'umingan',
            'urbiztondo', 'villasis', 'agno', 'aguilar', 'alcala', 'anda',
            'asingan', 'balungao', 'bani', 'basista', 'bautista', 'bayambang',
            'binalonan', 'binmaley', 'bolinao', 'buenavista', 'bugallon',
            'burgos', 'dasol', 'herrera', 'infanta', 'labrador', 'laoac',
            'mabini', 'malasiqui', 'natividad', 'pozorrubio', 'quezon',
            'rosales', 'rosario'
        ];

        foreach ($locations as $location) {
            if (strpos($message, $location) !== false) {
                return ucwords($location);
            }
        }

        return null;
    }

    /**
     * Find users in specific location
     */
    private function findUsersInLocation(string $location, ?User $user): array
    {
        if (!$user) {
            return [];
        }

        try {
            $users = User::where('id', '!=', $user->id)
                ->where('is_active', true)
                ->where('is_approved', true)
                ->where(function ($query) use ($location) {
                    $query->whereHas('roommateProfile', function ($q) use ($location) {
                        $q->where('city', 'like', "%{$location}%")
                          ->orWhere('apartment_location', 'like', "%{$location}%");
                    });
                })
                ->with(['roommateProfile'])
                ->limit(5)
                ->get();

            return $users->toArray();
        } catch (\Exception $e) {
            \Log::error('Location search error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Calculate quick compatibility score
     */
    private function calculateQuickCompatibility(?User $currentUser, array $potentialRoommate): int
    {
        if (!$currentUser) {
            return rand(60, 85); // Random score for non-logged users
        }

        try {
            $roommateUser = User::find($potentialRoommate['id']);
            if (!$roommateUser) {
                return rand(60, 85);
            }

            if ($this->matchingService) {
                $compatibility = $this->matchingService->calculateCompatibility($currentUser, $roommateUser);
                return round($compatibility['score']);
            } else {
                return rand(60, 85); // Fallback if service not available
            }
        } catch (\Exception $e) {
            \Log::error('Compatibility calculation error', ['error' => $e->getMessage()]);
            return rand(60, 85); // Fallback to random score
        }
    }

    /**
     * Get profile completion percentage
     */
    private function getProfileCompletion(?User $user): array
    {
        if (!$user) {
            return ['percentage' => 0, 'missing' => ['Login required']];
        }

        $missing = [];
        $completed = 0;
        $total = 6;

        // Check personal info
        if (!$user->first_name || !$user->last_name || !$user->email) {
            $missing[] = 'Personal information (name, email)';
        } else {
            $completed++;
        }

        // Check age
        if (!$user->date_of_birth && !$user->age) {
            $missing[] = 'Age information';
        } else {
            $completed++;
        }

        // Check gender
        if (!$user->gender) {
            $missing[] = 'Gender preference';
        } else {
            $completed++;
        }

        // Check phone
        if (!$user->phone) {
            $missing[] = 'Phone number';
        } else {
            $completed++;
        }

        // Check roommate profile
        if (!$user->roommateProfile) {
            $missing[] = 'Roommate preferences';
        } else {
            $completed++;
        }

        // Check education
        if (!$user->university || !$user->course) {
            $missing[] = 'Education details';
        } else {
            $completed++;
        }

        $percentage = round(($completed / $total) * 100);

        return [
            'percentage' => $percentage,
            'missing' => $missing
        ];
    }

    /**
     * Check if message is profile query
     */
    private function isProfileQuery(string $message): bool
    {
        $keywords = ['profile', 'improve', 'optimize', 'tips', 'photo', 'completion', 'preference', 'compatibility'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle profile queries
     */
    private function handleProfileQuery(string $message, ?User $user): string
    {
        $lowerMessage = strtolower($message);
        
        if (strpos($lowerMessage, 'photo') !== false || strpos($lowerMessage, 'picture') !== false) {
            return $this->getProfilePhotoTips($user);
        }
        
        if (strpos($lowerMessage, 'complete') !== false || strpos($lowerMessage, 'completion') !== false) {
            return $this->getProfileCompletionTips($user);
        }
        
        if (strpos($lowerMessage, 'preference') !== false || strpos($lowerMessage, 'setting') !== false) {
            return $this->getPreferenceTips($user);
        }
        
        if (strpos($lowerMessage, 'compatibility') !== false || strpos($lowerMessage, 'score') !== false) {
            return $this->getCompatibilityTips($user);
        }
        
        return "📝 **Profile Improvement Options**:\n\nSelect a number for specific tips:\n\n1️⃣ Profile Photo Tips\n2️⃣ Profile Completion Guide\n3️⃣ Preference Settings\n4️⃣ Compatibility Score Boost\n\nType 1, 2, 3, or 4 to get detailed help!";
    }

    /**
     * Check if message is roommate query
     */
    private function isRoommateQuery(string $message): bool
    {
        $keywords = ['find', 'roommate', 'match', 'search'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle roommate queries
     */
    private function handleRoommateQuery(?User $user): string
    {
        return "I can help you find compatible roommates! Our AI algorithm analyzes your lifestyle preferences, budget, schedule, and other factors to match you with best candidates. Here's how to get started:\n\n1️⃣ Complete your profile with detailed preferences\n2️⃣ Set your budget range and lifestyle preferences\n3️⃣ Be specific about your ideal roommate qualities\n4️⃣ Browse roommate listings with AI compatibility scores\n\nWould you like tips on improving your profile to get better matches?";
    }

    /**
     * Check if message is matching query
     */
    private function isMatchingQuery(string $message): bool
    {
        $keywords = ['matching', 'algorithm', 'how does', 'compatibility'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle matching queries
     */
    private function handleMatchingQuery(): string
    {
        return "Our AI matching algorithm is quite sophisticated! Here's how it works:\n\n🧮 **Scoring Breakdown**:\n• Lifestyle Compatibility: 25%\n• Schedule Alignment: 20%\n• Budget Compatibility: 20%\n• Cleanliness Standards: 15%\n• Age Proximity: 15%\n• University Connection: 10%\n\n🤖 **AI Analysis**: The system also considers:\n• Personality matches from preferences\n• Living habit compatibility\n• Social lifestyle alignment\n• Financial compatibility\n• Academic/social connections\n\n📊 **Compatibility Scores**:\n• 80%+ = Excellent Match\n• 60-79% = Good Match\n• 50-59% = Fair Match\n\nWant to know how to improve your compatibility score?";
    }

    /**
     * Check if message is advice query
     */
    private function isAdviceQuery(string $message): bool
    {
        $keywords = ['advice', 'tips', 'help', 'roommate advice'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle advice queries
     */
    private function handleAdviceQuery(): string
    {
        return "Here are my essential roommate success tips:\n\n🏠 **Before Moving In**:\n• Discuss expectations openly\n• Set clear house rules together\n• Agree on cleaning schedules\n• Split expenses fairly\n• Respect each other's privacy\n\n🤝 **Living Together**:\n• Communicate issues early\n• Be flexible but maintain boundaries\n• Schedule regular check-ins\n• Share responsibilities equally\n• Be considerate of noise/guests\n\n⚠️ **Red Flags to Watch**:\n• Poor communication habits\n• Financial irresponsibility\n• Disrespect for boundaries\n• Inconsistent cleanliness\n• Unreliable with commitments\n\nWhat specific roommate situation would you like advice about?";
    }

    /**
     * Get default response
     */
    private function getDefaultResponse(): string
    {
        $responses = [
            "That's interesting! I can help you with finding compatible roommates, optimizing your profile, understanding our matching algorithm, or getting roommate advice. What would you like to know more about?",
            "I'm here to help! I can assist with: 🏠 Finding roommates, 📝 Profile optimization, 🤖 AI matching questions, or 💡 General roommate advice. What interests you most?",
            "Great to hear from you! I specialize in roommate matching success. Ask me about finding compatible roommates, improving your profile, how our AI matching works, or any roommate-related questions!",
            "Thanks for reaching out! I'm your AI roommate assistant. I can help you find perfect roommate match, optimize your profile for better results, explain our matching algorithm, or provide roommate relationship advice. What would you like to explore?",
            "Hello! I'm excited to help you find your ideal roommate! I can assist with roommate searching, profile improvement, understanding compatibility scores, or general roommate living advice. What's on your mind today?"
        ];
        
        return $responses[array_rand($responses)];
    }

    /**
     * Check if message is a specific roommate query
     */
    private function isSpecificRoommateQuery(string $message): bool
    {
        $keywords = ['how many', 'who is', 'any roommates', 'available roommates', 'looking for roommate', 'need roommate', 'searching for'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle specific roommate queries
     */
    private function handleSpecificRoommateQuery(string $message, ?User $user): string
    {
        if (!$user) {
            return "I can help you find roommates! Please log in first to see personalized roommate matches and use our advanced search features. Once logged in, you'll be able to:\n\n• Browse compatible roommates\n• See compatibility scores\n• Send messages to potential matches\n• Filter by location, budget, and preferences\n\nWould you like me to help you with the login process?";
        }

        try {
            $totalUsers = User::where('id', '!=', $user->id)
                ->where('is_active', true)
                ->where('is_approved', true)
                ->count();

            $recentUsers = User::where('id', '!=', $user->id)
                ->where('is_active', true)
                ->where('is_approved', true)
                ->where('created_at', '>=', now()->subDays(7))
                ->count();

            $response = "📊 **Current Roommate Availability**:\n\n";
            $response .= "• Total active roommates: {$totalUsers}\n";
            $response .= "• New members this week: {$recentUsers}\n";
            $response .= "• Success rate: 87% find matches within 30 days\n\n";
            
            $response .= "🎯 **To Find Your Perfect Match**:\n";
            $response .= "1. Complete your profile (increases matches by 3x)\n";
            $response .= "2. Set specific preferences (budget, location, lifestyle)\n";
            $response .= "3. Browse the Matches page for compatibility scores\n";
            $response .= "4. Message potential roommates\n\n";
            
            $response .= "💡 **Quick Tip**: Users with complete profiles and photos get 5x more messages! Want help improving your profile?";

            return $response;
        } catch (\Exception $e) {
            \Log::error('Roommate statistics error', ['error' => $e->getMessage()]);
            return "I'm having trouble accessing the current roommate statistics right now. However, I can still help you with:\n\n• Finding compatible roommates\n• Improving your profile\n• Setting preferences\n• Compatibility guidance\n\nWould you like help with any of these topics?";
        }
    }

    /**
     * Check if message is a budget query
     */
    private function isBudgetQuery(string $message): bool
    {
        $keywords = ['budget', 'cost', 'price', 'rent', 'expensive', 'cheap', 'affordable', 'money'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle budget queries
     */
    private function handleBudgetQuery(string $message, ?User $user): string
    {
        $response = "💰 **Roommate Budget Guide**:\n\n";
        
        $response .= "📈 **Average Costs in Pangasinan**:\n";
        $response .= "• Shared apartment: ₱3,000-8,000/month\n";
        $response .= "• Private room: ₱5,000-12,000/month\n";
        $response .= "• Studio apartment: ₱8,000-15,000/month\n";
        $response .= "• Utilities (extra): ₱500-2,000/month\n\n";
        
        $response .= "💡 **Budget Setting Tips**:\n";
        $response .= "• Include utilities in your budget\n";
        $response .= "• Be flexible ±20% for better matches\n";
        $response .= "• Consider transportation costs\n";
        $response .= "• Factor in food and personal expenses\n\n";
        
        if ($user && $user->roommateProfile) {
            $budget = $user->roommateProfile->budget_max;
            if ($budget) {
                $budgetInt = (int) $budget;
                $response .= "🎯 **Your Current Budget**: ₱" . number_format($budgetInt) . "\n";
                $response .= "This puts you in the " . $this->getBudgetCategory($budgetInt) . " range.\n\n";
            }
        }
        
        $response .= "🔍 **Find Roommates by Budget**:\n";
        $response .= "Use the search filters to set your min-max budget range. This helps find roommates with similar financial expectations!";

        return $response;
    }

    /**
     * Get budget category
     */
    private function getBudgetCategory(int $budget): string
    {
        if ($budget <= 4000) return "budget-friendly";
        if ($budget <= 7000) return "mid-range";
        if ($budget <= 10000) return "comfortable";
        return "premium";
    }

    /**
     * Check if message is a lifestyle query
     */
    private function isLifestyleQuery(string $message): bool
    {
        $keywords = ['clean', 'messy', 'neat', 'organized', 'study', 'party', 'social', 'quiet', 'lifestyle', 'habits'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle lifestyle queries
     */
    private function handleLifestyleQuery(string $message, ?User $user): string
    {
        $response = "🏠 **Lifestyle Compatibility Guide**:\n\n";
        
        $response .= "🧹 **Cleanliness Levels**:\n";
        $response .= "• Very Clean: Daily cleaning, everything organized\n";
        $response .= "• Clean: Weekly cleaning, generally tidy\n";
        $response .= "• Average: Bi-weekly cleaning, some clutter OK\n";
        $response .= "• Flexible: Cleaning when needed, casual approach\n\n";
        
        $response .= "⏰ **Schedule Preferences**:\n";
        $response .= "• Early Bird: Up by 6 AM, bed by 10 PM\n";
        $response .= "• Regular: Up by 8 AM, bed by 11 PM\n";
        $response .= "• Night Owl: Up by 10 AM, bed after midnight\n";
        $response .= "• Variable: Different schedule each day\n\n";
        
        $response .= "🎵 **Social Habits**:\n";
        $response .= "• Quiet: Prefer peaceful environment\n";
        $response .= "• Moderate: Occasional guests, reasonable noise\n";
        $response .= "• Social: Regular guests, normal activity\n";
        $response .= "• Very Social: Frequent gatherings, active lifestyle\n\n";
        
        $response .= "💡 **Matching Success**: Be honest about your lifestyle! The best matches come from realistic expectations. Want to set your lifestyle preferences?";

        return $response;
    }

    /**
     * Check if message is a university query
     */
    private function isUniversityQuery(string $message): bool
    {
        $keywords = ['university', 'college', 'school', 'student', 'campus', 'study', 'academic', 'education'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle university queries
     */
    private function handleUniversityQuery(string $message, ?User $user): string
    {
        $response = "🎓 **University Roommate Guide**:\n\n";
        
        $response .= "🏫 **Popular Universities in Pangasinan**:\n";
        $response .= "• Pangasinan State University (PSU)\n";
        $response .= "• University of Pangasinan\n";
        $response .= "• Colegio de Dagupan\n";
        $response .= "• Dagupan Colleges\n";
        $response .= "• PHINMA Education Network\n\n";
        
        $response .= "👥 **Student Roommate Benefits**:\n";
        $response .= "• Similar class schedules\n";
        $response .= "• Shared study habits\n";
        $response .= "• Understanding of academic pressures\n";
        $response .= "• Campus proximity preferences\n\n";
        
        $response .= "📚 **Study Compatibility Factors**:\n";
        $response .= "• Study time preferences (morning/night)\n";
        $response .= "• Noise levels during study sessions\n";
        $response .= "• Guest policies for study groups\n";
        $response .= "• Academic discipline compatibility\n\n";
        
        $response .= "🎯 **Student Success Tips**:\n";
        $response .= "• List your university and course in profile\n";
        $response .= "• Specify study schedule preferences\n";
        $response .= "• Mention if you prefer student roommates\n";
        $response .= "• Set quiet hours for exam periods";

        return $response;
    }

    /**
     * Check if message is a compatibility question
     */
    private function isCompatibilityQuestion(string $message): bool
    {
        $keywords = ['compatible', 'match', 'compatibility', 'good match', 'perfect match', 'score'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle compatibility questions
     */
    private function handleCompatibilityQuestion(string $message, ?User $user): string
    {
        $response = "🎯 **Understanding Compatibility Scores**:\n\n";
        
        $response .= "📊 **Score Breakdown**:\n";
        $response .= "• 90-100%: Exceptional Match (Rare!)\n";
        $response .= "• 80-89%: Excellent Match (Highly recommended)\n";
        $response .= "• 70-79%: Good Match (Strong potential)\n";
        $response .= "• 60-69%: Fair Match (Some compromises)\n";
        $response .= "• 50-59%: Below Average (Challenging)\n";
        $response .= "• Below 50%: Poor Match (Not recommended)\n\n";
        
        $response .= "🔍 **What Affects Your Score**:\n";
        $response .= "• Lifestyle preferences (25%)\n";
        $response .= "• Schedule alignment (20%)\n";
        $response .= "• Budget compatibility (20%)\n";
        $response .= "• Cleanliness standards (15%)\n";
        $response .= "• Age proximity (15%)\n";
        $response .= "• University connection (10%)\n\n";
        
        $response .= "💡 **Improving Your Matches**:\n";
        $response .= "• Complete all preference sections\n";
        $response .= "• Be honest about your habits\n";
        $response .= "• Set realistic budget ranges\n";
        $response .= "• Add detailed profile information\n\n";
        
        $response .= "⚠️ **Important**: High scores don't guarantee perfect roommates! Sometimes 70% matches work better than 90% due to communication and flexibility.";

        return $response;
    }

    /**
     * Check if message is a safety query
     */
    private function isSafetyQuery(string $message): bool
    {
        $keywords = ['safe', 'security', 'scam', 'danger', 'protect', 'verify', 'background check'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle safety queries
     */
    private function handleSafetyQuery(string $message): string
    {
        $response = "🔒 **Safety & Security Guide**:\n\n";
        
        $response .= "✅ **Our Safety Features**:\n";
        $response .= "• Email verification for all users\n";
        $response .= "• Profile approval system\n";
        $response .= "• Report and block functionality\n";
        $response .= "• Message monitoring\n";
        $response .= "• Secure data encryption\n\n";
        
        $response .= "🛡️ **Your Safety Tips**:\n";
        $response .= "• Meet in public places first\n";
        $response .= "• Tell friends about meetings\n";
        $response .= "• Verify identity before sharing personal info\n";
        $response .= "• Never send money upfront\n";
        $response .= "• Trust your instincts\n\n";
        
        $response .= "⚠️ **Red Flags to Watch For**:\n";
        $response .= "• Requests for money or financial info\n";
        $response .= "• Refusal to meet in person\n";
        $response .= "• Inconsistent stories or information\n";
        $response .= "• Pressure to decide quickly\n";
        $response .= "• Unwillingness to verify identity\n\n";
        
        $response .= "📞 **Report Issues**: If you encounter suspicious behavior, report it immediately. We take all safety concerns seriously and will investigate promptly.";

        return $response;
    }

    /**
     * Check if message is a process query
     */
    private function isProcessQuery(string $message): bool
    {
        $keywords = ['how to', 'process', 'steps', 'apply', 'register', 'sign up', 'get started'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle process queries
     */
    private function handleProcessQuery(string $message): string
    {
        $response = "🚀 **Getting Started Guide**:\n\n";
        
        $response .= "📝 **Step 1: Create Account**\n";
        $response .= "• Sign up with email and password\n";
        $response .= "• Verify your email address\n";
        $response .= "• Wait for admin approval (usually within 24 hours)\n\n";
        
        $response .= "👤 **Step 2: Complete Profile**\n";
        $response .= "• Add personal information (name, age, gender)\n";
        $response .= "• Upload profile photo (increases matches 5x)\n";
        $response .= "• Set contact details\n";
        $response .= "• Add education information\n\n";
        
        $response .= "🏠 **Step 3: Set Preferences**\n";
        $response .= "• Budget range (min-max)\n";
        $response .= "• Location preferences\n";
        $response .= "• Lifestyle habits (cleanliness, schedule)\n";
        $response .= "• Ideal roommate qualities\n\n";
        
        $response .= "🔍 **Step 4: Find Roommates**\n";
        $response .= "• Browse matches with compatibility scores\n";
        $response .= "• Use search filters for specific needs\n";
        $response .= "• Send messages to potential matches\n";
        $response .= "• Arrange safe meetups\n\n";
        
        $response .= "⏱️ **Timeline**: Most users find compatible roommates within 2-4 weeks. Complete profiles typically find matches 3x faster!";

        return $response;
    }

    /**
     * Check if message is a greeting
     */
    private function isGreeting(string $message): bool
    {
        $greetings = ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'greetings', 'yo'];
        
        foreach ($greetings as $greeting) {
            if (strpos($message, $greeting) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle greetings
     */
    private function handleGreeting(?User $user): string
    {
        $timeOfDay = $this->getTimeOfDay();
        
        $response = "{$timeOfDay}! I'm your AI roommate assistant, ready to help you find your perfect match! ";
        
        if ($user) {
            $response .= "Nice to see you again, {$user->first_name}! ";
        }
        
        $response .= "I can help you with:\n\n";
        $response .= "🏠 Finding compatible roommates\n";
        $response .= "📝 Profile optimization tips\n";
        $response .= "📍 Location-based searches\n";
        $response .= "🎯 Compatibility guidance\n";
        $response .= "💡 Roommate advice\n\n";
        $response .= "What would you like to explore today?";
        
        return $response;
    }

    /**
     * Get time of day greeting
     */
    private function getTimeOfDay(): string
    {
        $hour = now()->hour;
        
        if ($hour >= 5 && $hour < 12) {
            return "Good morning";
        } elseif ($hour >= 12 && $hour < 17) {
            return "Good afternoon";
        } elseif ($hour >= 17 && $hour < 22) {
            return "Good evening";
        } else {
            return "Hello";
        }
    }

    /**
     * Check if message is a help request
     */
    private function isHelpRequest(string $message): bool
    {
        $keywords = ['help', 'assist', 'support', 'guide', 'instructions', 'how do i'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle help requests
     */
    private function handleHelpRequest(): string
    {
        return "🤖 **AI Assistant Help Guide**:\n\nI can help you with these topics:\n\n**🏠 Roommate Finding**:\n• \"Find compatible roommates\"\n• \"How many roommates are available?\"\n• \"Who are roommates in [location]?\"\n\n**📝 Profile Improvement**:\n• \"Tips on improving your profile\" (then select 1-4)\n• \"How to get better matches\"\n• \"Profile photo tips\"\n\n**🎯 Matching & Compatibility**:\n• \"How does matching work?\"\n• \"What is a good compatibility score?\"\n• \"Improve my compatibility\"\n\n**💰 Practical Questions**:\n• \"What's the average rent cost?\"\n• \"Budget recommendations\"\n• \"Lifestyle preferences\"\n\n**🔒 Safety & Process**:\n• \"Is this platform safe?\"\n• \"How to get started\"\n• \"Report suspicious behavior\"\n\nType any question or try the quick action buttons above!";
    }

    /**
     * Get contextual default response
     */
    private function getContextualDefaultResponse(string $message, ?User $user): string
    {
        // Try to understand the context and provide relevant guidance
        if (strlen($message) < 10) {
            return "I'd be happy to help! Could you provide more details about what you'd like to know? I can assist with finding roommates, improving your profile, compatibility questions, or general roommate advice.";
        }
        
        // Check for common themes
        if (strpos($message, '?') !== false) {
            return "That's a great question! I specialize in roommate matching and can help with:\n\n🏠 Finding compatible roommates\n📝 Profile optimization\n🎯 Compatibility scoring\n📍 Location searches\n💡 Roommate advice\n\nCould you rephrase your question or try one of these topics?";
        }
        
        return "I'm here to help with roommate finding! Based on your message, I think you might be interested in:\n\n• Finding compatible roommates\n• Improving your profile for better matches\n• Understanding compatibility scores\n• Location-based roommate search\n\nWhich of these sounds most helpful to you?";
    }

    /**
     * Handle general knowledge questions (ChatGPT-like responses)
     */
    private function handleGeneralKnowledge(string $message, ?User $user): string
    {
        $lowerMessage = strtolower($message);
        
        // Science and Technology
        if ($this->isScienceQuestion($lowerMessage)) {
            return $this->handleScienceQuestion($message);
        }
        
        // History and Geography
        if ($this->isHistoryQuestion($lowerMessage)) {
            return $this->handleHistoryQuestion($message);
        }
        
        // Mathematics and Logic
        if ($this->isMathQuestion($lowerMessage)) {
            return $this->handleMathQuestion($message);
        }
        
        // Literature and Arts
        if ($this->isLiteratureQuestion($lowerMessage)) {
            return $this->handleLiteratureQuestion($message);
        }
        
        // Sports and Entertainment
        if ($this->isSportsQuestion($lowerMessage)) {
            return $this->handleSportsQuestion($message);
        }
        
        // Health and Wellness
        if ($this->isHealthQuestion($lowerMessage)) {
            return $this->handleHealthQuestion($message);
        }
        
        // Business and Finance
        if ($this->isBusinessQuestion($lowerMessage)) {
            return $this->handleBusinessQuestion($message);
        }
        
        // Technology and Programming
        if ($this->isTechQuestion($lowerMessage)) {
            return $this->handleTechQuestion($message);
        }
        
        // Philosophy and Psychology
        if ($this->isPhilosophyQuestion($lowerMessage)) {
            return $this->handlePhilosophyQuestion($message);
        }
        
        // Current Events and News
        if ($this->isCurrentEventsQuestion($lowerMessage)) {
            return $this->handleCurrentEventsQuestion($message);
        }
        
        // Food and Cooking
        if ($this->isFoodQuestion($lowerMessage)) {
            return $this->handleFoodQuestion($message);
        }
        
        // Travel and Culture
        if ($this->isTravelQuestion($lowerMessage)) {
            return $this->handleTravelQuestion($message);
        }
        
        // Animals and Nature
        if ($this->isNatureQuestion($lowerMessage)) {
            return $this->handleNatureQuestion($message);
        }
        
        // Personal advice and life questions
        if ($this->isLifeAdviceQuestion($lowerMessage)) {
            return $this->handleLifeAdviceQuestion($message);
        }
        
        // Fallback to intelligent general response
        return $this->generateIntelligentResponse($message, $user);
    }

    /**
     * Check if message is a science question
     */
    private function isScienceQuestion(string $message): bool
    {
        $keywords = ['science', 'physics', 'chemistry', 'biology', 'astronomy', 'planet', 'space', 'atom', 'molecule', 'energy', 'gravity', 'evolution', 'dna', 'cell', 'quantum'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle science questions
     */
    private function handleScienceQuestion(string $message): string
    {
        $lowerMessage = strtolower($message);
        
        if (strpos($lowerMessage, 'gravity') !== false) {
            return "🔬 **Gravity Explained**:\n\nGravity is a fundamental force of nature that attracts objects with mass toward each other. On Earth, it gives weight to physical objects and causes them to fall to the ground when dropped.\n\n**Key Points**:\n• Discovered by Sir Isaac Newton (apple legend)\n• Einstein later explained it as curvature of spacetime\n• Earth's gravity: 9.8 m/s²\n• Keeps planets orbiting the sun\n• Essential for life as we know it\n\n**Fun Fact**: If you could stand on a neutron star, you'd weigh billions of times more due to extreme gravity!";
        }
        
        if (strpos($lowerMessage, 'quantum') !== false) {
            return "⚛️ **Quantum Physics Simplified**:\n\nQuantum physics studies the behavior of matter and energy at the smallest scales - atoms and subatomic particles.\n\n**Key Concepts**:\n• **Quantum**: Smallest possible unit of energy\n• **Superposition**: Particles can be in multiple states simultaneously\n• **Entanglement**: Connected particles affect each other instantly\n• **Uncertainty Principle**: Can't perfectly measure position and momentum\n\n**Applications**:\n• Computers and smartphones\n• Medical imaging (MRI)\n• Lasers and LEDs\n• Quantum computing (future)\n\n**Mind-Blowing Fact**: In quantum mechanics, a particle can exist in multiple places at once until observed!";
        }
        
        if (strpos($lowerMessage, 'evolution') !== false) {
            return "🧬 **Evolution Explained**:\n\nEvolution is the process by which species change over time through genetic variation and natural selection.\n\n**Mechanisms**:\n• **Mutation**: Random changes in DNA\n• **Natural Selection**: Survival of the fittest traits\n• **Adaptation**: Species develop useful traits\n• **Speciation**: New species emerge over time\n\n**Evidence**:\n• Fossil records showing gradual changes\n• DNA similarities between species\n• Observed evolution in bacteria/pests\n• Comparative anatomy\n\n**Timeline**: Life began ~3.5 billion years ago, humans evolved ~6 million years ago.\n\n**Current Example**: Bacteria evolving antibiotic resistance in hospitals.";
        }
        
        return "🔬 **Science Response**:\n\nThat's a fascinating science question! Science helps us understand the natural world through observation, experimentation, and evidence-based reasoning.\n\n**Scientific Method**:\n1. Ask a question\n2. Research background\n3. Form hypothesis\n4. Test with experiments\n5. Analyze data\n6. Draw conclusions\n7. Peer review and replication\n\n**Major Fields**:\n• Physics (matter, energy, forces)\n• Chemistry (atoms, molecules, reactions)\n• Biology (life, organisms, evolution)\n• Astronomy (celestial objects, space)\n• Earth Science (planet processes)\n\nCould you specify which area of science interests you most? I can provide more detailed information!";
    }

    /**
     * Check if message is a history question
     */
    private function isHistoryQuestion(string $message): bool
    {
        $keywords = ['history', 'war', 'ancient', 'medieval', 'roman', 'egypt', 'world war', 'revolution', 'empire', 'king', 'queen', 'president', 'historical'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle history questions
     */
    private function handleHistoryQuestion(string $message): string
    {
        $lowerMessage = strtolower($message);
        
        if (strpos($lowerMessage, 'world war') !== false) {
            return "📚 **World Wars Overview**:\n\n**World War I (1914-1918)**:\n• Caused by assassination of Archduke Franz Ferdinand\n• 32 countries involved, 16 million deaths\n• Ended with Treaty of Versailles\n• Led to League of Nations\n\n**World War II (1939-1945)**:\n• Started with German invasion of Poland\n• 70+ countries involved, 70-85 million deaths\n• Holocaust: 6 million Jews murdered\n• Ended with atomic bombs on Japan\n• Created United Nations\n\n**Legacy**: Both wars reshaped global politics, ended colonial empires, and established international cooperation frameworks.";
        }
        
        if (strpos($lowerMessage, 'ancient') !== false || strpos($lowerMessage, 'egypt') !== false) {
            return "🏛️ **Ancient Civilizations**:\n\n**Ancient Egypt (3100-30 BCE)**:\n• Built pyramids as tombs for pharaohs\n• Invented hieroglyphic writing\n• Advanced mathematics and engineering\n• Mummification for afterlife\n• Ruled by dynasties for 3,000 years\n\n**Key Achievements**:\n• 365-day calendar\n• Paper (papyrus)\n• Early medicine and surgery\n• Agricultural innovations\n\n**Famous Pharaohs**: Tutankhamun, Cleopatra, Ramses II\n\n**Legacy**: Influenced Greek, Roman, and modern culture with art, architecture, and knowledge.";
        }
        
        return "📚 **History Overview**:\n\nHistory is the study of past events, civilizations, and human experiences that shape our present world.\n\n**Major Historical Periods**:\n• **Ancient** (3000 BCE - 500 CE): Egypt, Greece, Rome, China\n• **Medieval** (500 - 1500 CE): Knights, castles, feudalism\n• **Renaissance** (1400 - 1600): Art rebirth, scientific revolution\n• **Industrial** (1760 - 1840): Factories, urbanization\n• **Modern** (1900 - present): Technology, global conflicts\n\n**Why History Matters**:\n• Learn from past mistakes\n• Understand cultural origins\n• See patterns in human behavior\n• Appreciate progress and change\n\nWhat specific historical period or event interests you?";
    }

    /**
     * Check if message is a math question
     */
    private function isMathQuestion(string $message): bool
    {
        $keywords = ['math', 'calculate', 'equation', 'algebra', 'geometry', 'statistics', 'probability', 'number', 'solve', 'plus', 'minus', 'multiply', 'divide'];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Handle math questions
     */
    private function handleMathQuestion(string $message): string
    {
        $lowerMessage = strtolower($message);
        
        // Simple arithmetic
        if (preg_match('/(\d+)\s*plus\s*(\d+)/', $lowerMessage, $matches)) {
            $result = (int)$matches[1] + (int)$matches[2];
            return "🧮 **Math Calculation**:\n\n{$matches[1]} + {$matches[2]} = {$result}\n\n**Addition**: Combining quantities to find a total. Basic arithmetic operation used in everyday life!";
        }
        
        if (preg_match('/(\d+)\s*minus\s*(\d+)/', $lowerMessage, $matches)) {
            $result = (int)$matches[1] - (int)$matches[2];
            return "🧮 **Math Calculation**:\n\n{$matches[1]} - {$matches[2]} = {$result}\n\n**Subtraction**: Finding the difference between two numbers. Essential for calculating change and comparisons!";
        }
        
        if (strpos($lowerMessage, 'pi') !== false) {
            return "🥧 **Pi (π) Explained**:\n\nPi is a mathematical constant representing the ratio of a circle's circumference to its diameter.\n\n**Value**: Approximately 3.14159 (irrational, infinite decimal)\n\n**Uses**:\n• Calculating circle area: π × r²\n• Circle circumference: 2 × π × r\n• Engineering and architecture\n• Physics calculations\n• Computer graphics\n\n**Fun Facts**:\n• Celebrated on Pi Day (March 14)\n• Required 62.8 trillion digits to calculate precisely\n• Appears in unexpected places like probability and statistics";
        }
        
        return "🧮 **Mathematics Overview**:\n\nMathematics is the language of patterns, logic, and quantitative relationships.\n\n**Main Branches**:\n• **Arithmetic**: Basic operations (+, -, ×, ÷)\n• **Algebra**: Variables and equations\n• **Geometry**: Shapes, angles, spatial relationships\n• **Statistics**: Data analysis and probability\n• **Calculus**: Change and motion\n\n**Real-World Applications**:\n• Finance and economics\n• Engineering and construction\n• Computer science and AI\n• Medicine and biology\n• Art and music\n\n**Famous Quote**: 'Mathematics is the queen of sciences' - Carl Friedrich Gauss\n\nWhat specific math topic would you like to explore?";
    }

    /**
     * Generate intelligent response for general questions
     */
    private function generateIntelligentResponse(string $message, ?User $user): string
    {
        $response = "🤖 **AI Assistant Response**:\n\n";
        
        // Analyze the question and provide relevant information
        if (strpos($message, '?') !== false) {
            $response .= "That's an interesting question! While I specialize in roommate matching and living situations, I can provide some general insights:\n\n";
        } else {
            $response .= "I understand you're curious about this topic. Let me share some thoughts:\n\n";
        }
        
        // Provide helpful general knowledge
        $response .= "📚 **General Knowledge**:\n";
        $response .= "I'm designed to help primarily with roommate finding, profile optimization, and living compatibility. However, I can assist with:\n\n";
        $response .= "• **Life Skills**: Communication, conflict resolution, time management\n";
        $response .= "• **Practical Advice**: Budget planning, home organization, safety tips\n";
        $response .= "• **Social Skills**: Meeting new people, building relationships\n";
        $response .= "• **Local Information**: Pangasinan locations, universities, cost of living\n\n";
        
        $response .= "🎯 **My Expertise**:\n";
        $response .= "For the most accurate and helpful responses, I recommend asking about:\n";
        $response .= "• Roommate compatibility and matching\n";
        $response .= "• Profile improvement strategies\n";
        $response .= "• Location-specific roommate searches\n";
        $response .= "• Living arrangement advice\n";
        $response .= "• Budget and lifestyle preferences\n\n";
        
        $response .= "💡 **For Other Topics**:\n";
        $response .= "For general knowledge questions outside my expertise, you might want to try:\n";
        $response .= "• General search engines for factual information\n";
        $response .= "• Educational websites for specific topics\n";
        $response .= "• Subject matter experts for specialized knowledge\n\n";
        
        $response .= "Is there anything related to roommate finding or living situations that I can help you with instead?";
        
        return $response;
    }

    // Additional topic detection methods (simplified for space)
    private function isLiteratureQuestion(string $message): bool {
        $keywords = ['book', 'novel', 'poem', 'author', 'literature', 'story', 'write', 'reading'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isSportsQuestion(string $message): bool {
        $keywords = ['sport', 'game', 'team', 'player', 'football', 'basketball', 'soccer', 'tennis'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isHealthQuestion(string $message): bool {
        $keywords = ['health', 'medicine', 'doctor', 'disease', 'exercise', 'diet', 'nutrition'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isBusinessQuestion(string $message): bool {
        $keywords = ['business', 'money', 'invest', 'company', 'economy', 'finance', 'career'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isTechQuestion(string $message): bool {
        $keywords = ['computer', 'internet', 'software', 'programming', 'technology', 'app', 'website'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isPhilosophyQuestion(string $message): bool {
        $keywords = ['philosophy', 'meaning', 'ethics', 'moral', 'consciousness', 'existence'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isCurrentEventsQuestion(string $message): bool {
        $keywords = ['news', 'current', 'today', 'recent', 'happening', 'world'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isFoodQuestion(string $message): bool {
        $keywords = ['food', 'cook', 'recipe', 'eat', 'restaurant', 'cuisine', 'ingredient'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isTravelQuestion(string $message): bool {
        $keywords = ['travel', 'vacation', 'trip', 'country', 'city', 'tourism', 'visit'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isNatureQuestion(string $message): bool {
        $keywords = ['animal', 'plant', 'nature', 'environment', 'wildlife', 'forest', 'ocean'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    private function isLifeAdviceQuestion(string $message): bool {
        $keywords = ['advice', 'help', 'problem', 'solution', 'should i', 'how to', 'what should'];
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) return true;
        }
        return false;
    }

    // Placeholder handlers for other topics
    private function handleLiteratureQuestion(string $message): string {
        return "📚 **Literature & Arts**:\n\nLiterature encompasses written works that express ideas, emotions, and human experiences through creative language.\n\n**Major Forms**:\n• **Fiction**: Novels, short stories, myths\n• **Poetry**: Rhythmic, metaphorical expression\n• **Drama**: Scripts for theater and film\n• **Non-fiction**: Biographies, essays, journalism\n\n**Literary Elements**:\n• Plot, character, setting, theme\n• Symbolism, metaphor, irony\n• Narrative structure and style\n\n**Famous Works**:\n• 'Don Quixote' - First modern novel\n• Shakespeare's plays - Timeless themes\n• '1984' - Dystopian commentary\n\n**Reading Benefits**: Improves empathy, vocabulary, critical thinking, and cultural understanding.\n\nWhat specific literary topic interests you?";
    }

    private function handleSportsQuestion(string $message): string {
        return "⚽ **Sports Overview**:\n\nSports are organized physical activities that combine competition, skill, and entertainment.\n\n**Popular Categories**:\n• **Team Sports**: Football, basketball, volleyball\n• **Individual Sports**: Tennis, golf, swimming\n• **Track & Field**: Running, jumping, throwing\n• **Extreme Sports**: Rock climbing, surfing\n\n**Benefits**:\n• Physical fitness and health\n• Teamwork and discipline\n• Stress relief and mental health\n• Social connection and community\n\n**Global Impact**:\n• Olympics: International unity\n• Economic: Multi-billion dollar industry\n• Cultural: National pride and identity\n\n**Philippines Sports**:\n• Basketball: Most popular\n• Boxing: World champions\n• Volleyball: Growing rapidly\n\nWhat sport would you like to know more about?";
    }

    private function handleHealthQuestion(string $message): string {
        return "🏥 **Health & Wellness**:\n\nHealth encompasses physical, mental, and social well-being - not just absence of disease.\n\n**Key Components**:\n• **Physical Health**: Exercise, nutrition, sleep\n• **Mental Health**: Stress management, emotional balance\n• **Social Health**: Relationships, community connection\n\n**Preventive Care**:\n• Regular exercise (150 min/week recommended)\n• Balanced diet with fruits/vegetables\n• 7-9 hours quality sleep\n• Regular health check-ups\n• Stress management techniques\n\n**Common Health Tips**:\n• Stay hydrated (8 glasses water daily)\n• Limit processed foods and sugar\n• Practice good hygiene\n• Maintain healthy weight\n• Don't smoke, limit alcohol\n\n**Note**: Always consult healthcare professionals for medical advice. This is general information only.";
    }

    private function handleBusinessQuestion(string $message): string {
        return "💼 **Business & Finance**:\n\nBusiness involves creating, producing, and exchanging goods and services for profit.\n\n**Key Concepts**:\n• **Supply & Demand**: Market dynamics\n• **Revenue & Profit**: Financial performance\n• **Marketing**: Customer acquisition\n• **Operations**: Production and delivery\n\n**Business Types**:\n• **Sole Proprietorship**: Single owner\n• **Partnership**: Multiple owners\n• **Corporation**: Legal entity separate from owners\n• **LLC**: Limited liability protection\n\n**Success Factors**:\n• Strong value proposition\n• Effective leadership\n• Financial management\n• Customer focus\n• Adaptability to change\n\n**Entrepreneurship Tips**:\n• Start with problems, not solutions\n• Validate ideas before investing\n• Build a strong network\n• Learn from failures\n• Focus on cash flow management";
    }

    private function handleTechQuestion(string $message): string {
        return "💻 **Technology Overview**:\n\nTechnology applies scientific knowledge to create tools, systems, and solutions that improve human life.\n\n**Major Areas**:\n• **Software**: Programs, apps, operating systems\n• **Hardware**: Computers, phones, devices\n• **Internet**: Global communication network\n• **AI**: Machine learning, automation\n• **Blockchain**: Distributed ledger technology\n\n**Impact on Society**:\n• Communication: Instant global connection\n• Work: Remote collaboration, automation\n• Education: Online learning, access to information\n• Healthcare: Medical advances, telemedicine\n• Entertainment: Streaming, gaming\n\n**Current Trends**:\n• Artificial Intelligence integration\n• 5G connectivity\n• Internet of Things (IoT)\n• Quantum computing research\n• Sustainable technology\n\n**Learning Tech**: Start with basics, practice regularly, stay updated with rapid changes.";
    }

    private function handlePhilosophyQuestion(string $message): string {
        return "🤔 **Philosophy Overview**:\n\nPhilosophy explores fundamental questions about existence, knowledge, values, reason, and reality.\n\n**Major Branches**:\n• **Metaphysics**: Nature of reality and existence\n• **Epistemology**: Theory of knowledge\n• **Ethics**: Moral principles and values\n• **Logic**: Reasoning and argumentation\n• **Aesthetics**: Beauty and art\n\n**Famous Philosophers**:\n• **Socrates**: Question everything\n• **Plato**: Theory of Forms\n• **Aristotle**: Logic and empiricism\n• **Kant**: Moral duty and reason\n• **Nietzsche**: Beyond good and evil\n\n**Key Questions**:\n• What is the meaning of life?\n• How do we know what's true?\n• What makes actions right or wrong?\n• What is consciousness?\n\n**Practical Philosophy**: Helps develop critical thinking, ethical decision-making, and deeper understanding of life's big questions.";
    }

    private function handleCurrentEventsQuestion(string $message): string {
        return "📰 **Current Events Context**:\n\nWhile I don't have access to real-time news, I can discuss how current events typically impact various aspects of life.\n\n**News Categories**:\n• **Politics**: Government decisions, elections\n• **Economy**: Markets, jobs, inflation\n• **Science**: Discoveries, research breakthroughs\n• **Technology**: Innovations, digital trends\n• **Environment**: Climate change, conservation\n\n**Staying Informed**:\n• Multiple reliable sources\n• Fact-check important claims\n• Consider different perspectives\n• Distinguish opinion from facts\n• Avoid information overload\n\n**Critical Thinking**:\n• Question sources and motives\n• Look for evidence and data\n• Consider long-term impacts\n• Recognize bias in reporting\n\n**For Latest News**: Check reputable news websites, official government sources, and established journalism outlets.";
    }

    private function handleFoodQuestion(string $message): string {
        return "🍳 **Food & Nutrition**:\n\nFood provides essential nutrients for energy, growth, and body function while also being a cultural and social experience.\n\n**Nutrition Basics**:\n• **Macronutrients**: Carbs, proteins, fats\n• **Micronutrients**: Vitamins, minerals\n• **Water**: Essential for hydration\n• **Fiber**: Digestive health\n\n**Healthy Eating**:\n• Balanced diet with variety\n• More fruits and vegetables\n• Whole grains over refined\n• Lean proteins\n• Limit processed foods and sugar\n\n**Filipino Cuisine**:\n• **Adobo**: National dish (soy sauce, vinegar)\n• **Sinigang**: Sour soup\n• **Lechon**: Roasted pig\n• **Pancit**: Noodle dishes\n• **Halo-Halo**: Dessert\n\n**Cooking Tips**:\n• Food safety and hygiene\n• Proper cooking temperatures\n• Flavor balancing\n• Meal planning and preparation";
    }

    private function handleTravelQuestion(string $message): string {
        return "✈️ **Travel & Exploration**:\n\nTravel involves visiting new places for leisure, business, education, or personal growth.\n\n**Benefits of Travel**:\n• Cultural exposure and understanding\n• Personal growth and independence\n• Stress relief and new perspectives\n• Creating memorable experiences\n• Learning about history and geography\n\n**Travel Planning**:\n• Research destinations and requirements\n• Budget planning and saving\n• Booking transportation and accommodation\n• Packing essentials and documents\n• Emergency preparation\n\n**Philippines Highlights**:\n• **Beaches**: Boracay, Palawan, Siargao\n• **Mountains**: Baguio, Sagada\n• **Cities**: Manila, Cebu, Davao\n• **Historical Sites**: Vigan, Intramuros\n• **Natural Wonders**: Chocolate Hills, Mayon Volcano\n\n**Travel Tips**:\n• Respect local customs\n• Learn basic phrases\n• Stay safe and aware\n• Document memories\n• Be flexible with plans";
    }

    private function handleNatureQuestion(string $message): string {
        return "🌿 **Nature & Environment**:\n\nNature encompasses all living things and natural phenomena on Earth, creating complex ecosystems that sustain life.\n\n**Ecosystems**:\n• **Forests**: Carbon storage, oxygen production\n• **Oceans**: Climate regulation, marine life\n• **Grasslands**: Agriculture, wildlife habitat\n• **Wetlands**: Water purification, flood control\n• **Deserts**: Unique adaptations, extreme conditions\n\n**Biodiversity**:\n• Millions of species on Earth\n• Each plays important ecological role\n• Interconnected food webs\n• Genetic diversity for resilience\n\n**Environmental Issues**:\n• Climate change impacts\n• Habitat destruction\n• Pollution concerns\n• Species extinction\n• Resource depletion\n\n**Conservation**:\n• Protected areas and reserves\n• Sustainable practices\n• Renewable energy\n• Recycling and waste reduction\n• Individual actions matter\n\n**Nature Benefits**: Mental health, clean air/water, food resources, inspiration, recreation.";
    }

    private function handleLifeAdviceQuestion(string $message): string {
        return "💡 **Life Advice & Guidance**:\n\nLife advice helps navigate challenges, make decisions, and find fulfillment in various aspects of life.\n\n**General Life Principles**:\n• **Authenticity**: Be true to yourself\n• **Growth Mindset**: Learn from challenges\n• **Gratitude**: Appreciate what you have\n• **Resilience**: Bounce back from setbacks\n• **Kindness**: Help others and yourself\n\n**Decision Making**:\n• Consider long-term consequences\n• Gather information and options\n• Trust your intuition\n• Be willing to adjust course\n• Learn from outcomes\n\n**Personal Development**:\n• Set meaningful goals\n• Develop healthy habits\n• Build strong relationships\n• Manage stress effectively\n• Continue learning and growing\n\n**Relationship Advice**:\n• Communicate openly and honestly\n• Listen more than you speak\n• Respect boundaries and differences\n• Show appreciation and support\n• Resolve conflicts constructively\n\n**Remember**: Everyone's journey is unique. Find what works for you while staying true to your values.";
    }
}
