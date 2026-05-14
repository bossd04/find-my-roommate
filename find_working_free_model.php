<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['OPENROUTER_API_KEY'] ?? null;
if (!$apiKey) {
    echo "ERROR: OPENROUTER_API_KEY not set\n";
    exit(1);
}

$ch = curl_init('https://openrouter.ai/api/v1/models');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$freeModels = [];

foreach ($data['data'] as $model) {
    if (str_contains($model['id'], ':free')) {
        $freeModels[] = $model['id'];
    }
}

echo "Found " . count($freeModels) . " free models:\n";
print_r(array_slice($freeModels, 0, 10));

foreach (array_slice($freeModels, 0, 5) as $modelId) {
    echo "Testing $modelId...\n";
    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => $modelId,
        'messages' => [['role' => 'user', 'content' => 'hi']]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ]);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code === 200) {
        echo "✅ WORKING: $modelId\n";
        file_put_contents('working_free_model.txt', $modelId);
        break;
    } else {
        echo "❌ FAILED: $modelId (HTTP $code) - $res\n";
    }
}
