<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['OPENROUTER_API_KEY'] ?? null;
echo "API Key: " . ($apiKey ? substr($apiKey, 0, 20) . "..." : "NOT FOUND") . "\n";

if (!$apiKey) {
    echo "ERROR: OPENROUTER_API_KEY not set in .env\n";
    exit(1);
}

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'meta-llama/llama-3.1-8b-instruct:free',
    'messages' => [
        ['role' => 'user', 'content' => 'Say "OpenRouter is working!"']
    ],
    'max_tokens' => 50
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json',
    'HTTP-Referer: http://localhost:8000'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $httpCode\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    echo "Response: " . ($data['choices'][0]['message']['content'] ?? "No content") . "\n";
    echo "\n✅ SUCCESS! OpenRouter is working.\n";
    echo "Go to http://127.0.0.1:8000/chatbot and ask any question!\n";
} else {
    echo "Error: $response\n";
}
