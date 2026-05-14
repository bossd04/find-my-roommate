<?php
/**
 * Test different OpenRouter free models
 */
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['OPENROUTER_API_KEY'] ?? null;

if (!$apiKey) {
    echo "ERROR: OPENROUTER_API_KEY not set\n";
    exit(1);
}

echo "API Key: " . substr($apiKey, 0, 25) . "...\n\n";

// Try different free models
$models = [
    'google/gemma-2-9b-it:free',
    'huggingfaceh4/zephyr-7b-beta:free',
    'openchat/openchat-3.5:free',
    'mistralai/mistral-7b-instruct:free',
    'gryphe/mythomist-7b:free',
    'undi95/remm-slerp-l2-13b:free'
];

foreach ($models as $model) {
    echo "Testing: $model\n";

    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => $model,
        'messages' => [
            ['role' => 'user', 'content' => 'Hello']
        ],
        'max_tokens' => 20
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
        'HTTP-Referer: http://localhost:8000'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);
    if ($httpCode === 200 && isset($data['choices'][0]['message']['content'])) {
        echo "  ✅ WORKING: " . trim($data['choices'][0]['message']['content']) . "\n\n";
        // Save working model to file
        file_put_contents(__DIR__ . '/openrouter_working_model.txt', $model);
        break;
    } else {
        $error = $data['error']['message'] ?? "Unknown error";
        echo "  ❌ FAILED: $error\n\n";
    }
}

echo "Working model saved to: openrouter_working_model.txt\n";
