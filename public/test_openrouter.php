<?php
// OpenRouter API Diagnostic Script
// Access this at http://127.0.0.1:8000/test_openrouter.php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$apiKey = $_ENV['OPENROUTER_API_KEY'] ?? null;

echo "<h1>OpenRouter API Diagnostic</h1>";
echo "<pre>";

echo "API Key Present: " . ($apiKey ? "YES (length: " . strlen($apiKey) . ")" : "NO") . "\n";
echo "API Key Preview: " . ($apiKey ? substr($apiKey, 0, 15) . "..." : "N/A") . "\n\n";

if (empty($apiKey)) {
    echo "ERROR: OPENROUTER_API_KEY is not set in .env file!\n";
    echo "\nTo fix:\n";
    echo "1. Go to https://openrouter.ai and create an account\n";
    echo "2. Get your API key from the dashboard\n";
    echo "3. Add to .env file: OPENROUTER_API_KEY=your_key_here\n";
    exit;
}

// Test API call
$models = [
    'deepseek/deepseek-chat-v3-0324:free',
    'deepseek/deepseek-r1:free',
    'google/gemini-2.5-flash-preview:free',
    'meta-llama/llama-4-maverick:free',
    'google/gemma-3-27b-it:free'
];

$testedModels = [];

foreach ($models as $model) {
    echo "Testing model: $model\n";
    
    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
        'HTTP-Referer: http://127.0.0.1:8000',
        'X-Title: Find My Roommate Test'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => $model,
        'messages' => [
            ['role' => 'user', 'content' => 'Say "API test successful"']
        ],
        'max_tokens' => 50
    ]));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "  HTTP Code: $httpCode\n";
    
    if ($error) {
        echo "  CURL Error: $error\n";
        $testedModels[$model] = 'CURL ERROR: ' . $error;
    } else {
        $data = json_decode($response, true);
        if ($httpCode === 200 && isset($data['choices'][0]['message']['content'])) {
            $content = $data['choices'][0]['message']['content'];
            echo "  SUCCESS: " . trim($content) . "\n";
            $testedModels[$model] = 'WORKING';
        } else {
            $errorMsg = $data['error']['message'] ?? $response;
            echo "  FAILED: " . substr($errorMsg, 0, 200) . "\n";
            $testedModels[$model] = 'FAILED: ' . substr($errorMsg, 0, 100);
        }
    }
    echo "\n";
}

echo "\n=== SUMMARY ===\n";
foreach ($testedModels as $model => $status) {
    echo "$model: $status\n";
}

echo "\n=== RECOMMENDATION ===\n";
$working = array_filter($testedModels, fn($s) => $s === 'WORKING');
if (count($working) > 0) {
    echo "At least one model is working. The chatbot should work now.\n";
    echo "Clear your browser cache and try again.\n";
} else {
    echo "All models failed. Common issues:\n";
    echo "1. Invalid API key - verify at https://openrouter.ai/keys\n";
    echo "2. Rate limiting - wait a few minutes and try again\n";
    echo "3. Account needs credits - OpenRouter requires credits even for 'free' models\n";
    echo "4. Network issues - check your internet connection\n";
}

echo "</pre>";
