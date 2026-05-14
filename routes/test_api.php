<?php
// Temporary diagnostic route - add this to web.php or include this file
// Test URL: http://127.0.0.1:8000/test-api-connection

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/test-api-connection', function() {
    header('Content-Type: text/plain');
    
    echo "=== OpenRouter API Connection Test ===\n\n";
    
    // Get API key directly from env
    $apiKey = env('OPENROUTER_API_KEY');
    
    echo "1. API Key Check:\n";
    echo "   - From env(): " . ($apiKey ? "YES (len: " . strlen($apiKey) . ")" : "NO") . "\n";
    
    $configKey = config('services.openrouter.api_key');
    echo "   - From config(): " . ($configKey ? "YES (len: " . strlen($configKey) . ")" : "NO") . "\n";
    
    if (empty($apiKey)) {
        echo "\n❌ ERROR: API key is not set in .env!\n";
        echo "Add this line to your .env file:\n";
        echo "OPENROUTER_API_KEY=sk-or-v1-your-key-here\n";
        exit;
    }
    
    if ($apiKey !== $configKey) {
        echo "\n⚠️ WARNING: env() and config() return different values!\n";
        echo "   You need to run: php artisan config:clear\n";
    }
    
    echo "\n2. Testing API Connection...\n\n";
    
    $models = [
        'deepseek/deepseek-chat-v3-0324:free',
        'google/gemma-3-4b-it:free'
    ];
    
    foreach ($models as $model) {
        echo "   Testing: $model\n";
        
        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => url('/'),
                    'X-Title' => 'Test'
                ])
                ->timeout(30)
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [['role' => 'user', 'content' => 'Hi']],
                    'max_tokens' => 50
                ]);
            
            echo "   - HTTP Status: " . $response->status() . "\n";
            
            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;
                echo "   - ✅ SUCCESS: " . trim($content) . "\n";
            } else {
                $body = $response->body();
                $error = json_decode($body, true);
                echo "   - ❌ FAILED: " . ($error['error']['message'] ?? substr($body, 0, 200)) . "\n";
            }
        } catch (Exception $e) {
            echo "   - ❌ ERROR: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    echo "\n3. Recommendations:\n";
    echo "   - If config() shows different value than env():\n";
    echo "     Run: php artisan config:clear\n\n";
    echo "   - If API returns 401/403:\n";
    echo "     Your API key is invalid. Get a new one at https://openrouter.ai/keys\n\n";
    echo "   - If API returns 402/429:\n";
    echo "     You need credits on OpenRouter even for 'free' models\n\n";
    
    exit;
});
