<?php
/**
 * Test script to verify Gemini API connection
 * Run: php test_gemini_api.php
 */

require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? env('GEMINI_API_KEY');
$model = $_ENV['GEMINI_MODEL'] ?? 'gemini-1.5-flash';

echo "=== Gemini API Connection Test ===\n\n";

echo "1. Checking API Key...\n";
if (empty($apiKey) || $apiKey === 'your_gemini_api_key_here') {
    echo "   ❌ ERROR: API Key is not configured!\n";
    echo "   Please set GEMINI_API_KEY in your .env file\n";
    exit(1);
}
echo "   ✅ API Key found: " . substr($apiKey, 0, 10) . "...\n";

echo "\n2. Testing API Endpoint...\n";
echo "   Model: {$model}\n";

$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

$prompt = "Say 'Hello from Gemini API!' and confirm you are working.";

$payload = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 100
    ]
];

echo "\n3. Sending test request...\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "   HTTP Status: {$httpCode}\n";

if ($error) {
    echo "   ❌ cURL Error: {$error}\n";
    exit(1);
}

if ($httpCode !== 200) {
    echo "   ❌ API Error (HTTP {$httpCode}):\n";
    echo "   Response: " . substr($response, 0, 500) . "\n";
    exit(1);
}

$data = json_decode($response, true);

if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
    $text = $data['candidates'][0]['content']['parts'][0]['text'];
    echo "\n✅ SUCCESS! Gemini API is working correctly.\n";
    echo "\nResponse:\n" . $text . "\n";
} else {
    echo "   ❌ Unexpected response format:\n";
    print_r($data);
    exit(1);
}

echo "\n=== Test Complete ===\n";
