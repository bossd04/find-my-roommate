<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Listing;
use App\Services\GeminiService;

class GeminiChatController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Detect if the user wants to generate/see an image.
     */
    private function detectImageIntent(string $lowerMessage): bool
    {
        $imageKeywords = [
            'generate an image', 'generate image', 'create an image', 'create image',
            'show me a photo', 'show a photo', 'show me a picture', 'show a picture',
            'generate a photo', 'create a photo', 'make an image', 'make a photo',
            'show me an image', 'generate a real', 'show real life', 'photograph of',
            'image of', 'photo of', 'picture of',
        ];

        foreach ($imageKeywords as $keyword) {
            if (str_contains($lowerMessage, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Build an Unsplash URL for real-life photorealistic images.
     */
    private function buildImageUrl(string $searchQuery): string
    {
        $encoded = urlencode($searchQuery);
        $seed    = abs(crc32($searchQuery)) % 1000;
        return "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80&sig={$seed}&q={$encoded}";
    }

    /**
     * Extract a meaningful image search query from the user's message.
     */
    private function extractImageQuery(string $message): string
    {
        $clean = preg_replace(
            '/(generate|create|make|show me|show|give me|display|produce|draw|render|photograph of|image of|photo of|picture of|a photo|an image|a picture|real life|realistic|real-life)\s*/i',
            '',
            $message
        );
        return empty($clean) ? 'apartment room Philippines' : trim($clean);
    }

    public function chat(Request $request)
    {
        try {
            $message      = $request->input('message') ?? '';
            $lowerMessage = strtolower($message);

            if (empty($message)) {
                return response()->json(['reply' => 'Please type something!']);
            }

            // ─── IMAGE GENERATION INTENT ─────────────────────────────────────
            if ($this->detectImageIntent($lowerMessage)) {
                $imageQuery = $this->extractImageQuery($message);
                $imageUrl   = $this->buildImageUrl($imageQuery);

                $captionPrompt = "In 1-2 short sentences, write a vivid caption describing a real-life photo of: \"{$imageQuery}\". Be descriptive.";
                $caption       = $this->gemini->chat($captionPrompt) ?? "Here's a real-life photo of \"{$imageQuery}\".";

                return response()->json([
                    'reply'     => $caption,
                    'image_url' => $imageUrl,
                    'image_alt' => $imageQuery,
                ]);
            }

            // ─── NORMAL CHAT FLOW ─────────────────────────────────────────────
            
            // Detect text intent
            $intent = 'chat';
            if (str_contains($lowerMessage, 'room') || str_contains($lowerMessage, 'rent') || str_contains($lowerMessage, 'apartment')) {
                $intent = 'search';
            } elseif (str_contains($lowerMessage, 'create') || str_contains($lowerMessage, 'write') || str_contains($lowerMessage, 'post')) {
                $intent = 'generate';
            }

            // Extract budget
            preg_match('/\d+/', $message, $matches);
            $budget = $matches ? (int)$matches[0] : null;

            // Detect Pangasinan location
            $locations = ['dagupan', 'alaminos', 'san carlos', 'urdaneta', 'lingayen', 'calasiao'];
            $detectedLocation = null;
            foreach ($locations as $loc) {
                if (str_contains($lowerMessage, $loc)) {
                    $detectedLocation = ucfirst($loc);
                    break;
                }
            }

            // Query database for listings if search intent
            $rooms = collect();
            if ($intent === 'search') {
                $rooms = Listing::active()
                    ->when($budget, fn($q) => $q->where('price', '<=', $budget))
                    ->when($detectedLocation, fn($q) => $q->where('location', 'LIKE', "%$detectedLocation%"))
                    ->limit(5)
                    ->get();
            }

            // Build prompt
            $basePrompt = "You are a friendly AI roommate assistant for Find My Roommate, a platform in Pangasinan, Philippines.";

            if ($intent === 'search' && $rooms->isNotEmpty()) {
                $prompt = "$basePrompt\n\nUser is looking for rooms. Available listings:\n" . $rooms->toJson() . "\n\nUser message: $message";
            } elseif ($intent === 'generate') {
                $prompt = "$basePrompt\n\nUser wants help writing a listing for: '$message'.";
            } else {
                $prompt = "$basePrompt\n\nUser says: $message";
            }

            $reply = $this->gemini->chat($prompt);

            if ($reply) {
                return response()->json([
                    'reply' => $reply,
                    'message' => $reply,
                    'rooms' => $rooms,
                ]);
            }

            // Fallback to local logic if AI API fails
            $chatController = app(ChatController::class);
            $fallbackReply = $chatController->generateAIResponse($message, auth()->user());

            return response()->json([
                'reply' => "💡 {$fallbackReply}",
                'message' => $fallbackReply,
                'rooms' => $rooms,
            ]);

        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'Oops! Something went wrong on the server.'], 500);
        }
    }
}
