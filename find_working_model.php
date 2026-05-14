<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.gemini.api_key');
$url = "https://generativelanguage.googleapis.com/v1/models?key={$apiKey}";

echo "Discovering models for API Key: " . substr($apiKey, 0, 10) . "...\n";

$response = Http::get($url);
if ($response->failed()) {
    echo "FAILED to list models: " . $response->body() . "\n";
    exit;
}

$models = $response->json()['models'];
echo "Total models found: " . count($models) . "\n";
foreach ($models as $m) {
    echo "Found: " . $m['name'] . "\n";
}
echo "-------------------\n";
$messages = [
    ['role' => 'user', 'parts' => [['text' => 'Hi']]]
];

foreach ($models as $m) {
    if (!in_array('generateContent', $m['supportedGenerationMethods'])) continue;
    
    $modelName = str_replace('models/', '', $m['name']);
    echo "Testing $modelName... ";
    
    // Try v1 first
    $testUrl = "https://generativelanguage.googleapis.com/v1/models/{$modelName}:generateContent?key={$apiKey}";
    $testResponse = Http::timeout(30)->post($testUrl, ['contents' => $messages]);
    
    if ($testResponse->successful()) {
        echo "V1 SUCCESS!\n";
        continue;
    } else {
        echo "V1 FAIL (" . $testResponse->status() . ")";
    }
    
    // Try v1beta
    $testUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";
    $testResponse = Http::timeout(30)->post($testUrl, ['contents' => $messages]);
    
    if ($testResponse->successful()) {
        echo " V1BETA SUCCESS!\n";
    } else {
        echo " V1BETA FAIL (" . $testResponse->status() . ")\n";
    }
}
