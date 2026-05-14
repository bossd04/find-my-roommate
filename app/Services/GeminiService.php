<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? '';
        $this->model = config('services.gemini.model', 'gemini-1.5-flash');
    }

    /**
     * Simple single prompt chat
     */
    public function chat(string $prompt): ?string
    {
        $contents = [
            [
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ]
        ];

        return $this->generate($contents);
    }

    /**
     * Generate content with conversation history
     */
    public function generate(array $contents): ?string
    {
        try {
            // Check if API key is configured
            if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
                Log::error('Gemini API Key is not configured. Please set GEMINI_API_KEY in .env file');
                return null;
            }

            $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

            Log::info('Calling Gemini API with model: ' . $this->model);

            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 2048,
                    'topP' => 0.9,
                    'topK' => 40
                ]
            ]);

            if ($response->failed()) {
                $status = $response->status();
                $body = $response->body();

                // Check for quota exceeded error
                if (str_contains($body, 'Quota exceeded') || str_contains($body, 'rate limit') || $status === 429) {
                    Log::error('Gemini API Quota Exceeded. Free tier limit reached.');
                    return 'QUOTA_EXCEEDED';
                }

                Log::error("Gemini API Error (Status: {$status}): " . $body);
                return null;
            }

            $data = $response->json();
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $data['candidates'][0]['content']['parts'][0]['text'];
                Log::info('Gemini API success, response length: ' . strlen($text));
                return $text;
            }

            // Check for blocked content
            if (isset($data['promptFeedback']['blockReason'])) {
                Log::warning('Gemini API blocked content: ' . $data['promptFeedback']['blockReason']);
                return null;
            }

            Log::warning('Gemini API Response unexpected format: ' . json_encode($data));
            return null;

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return null;
        }
    }
}
