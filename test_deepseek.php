<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$deepseekKey = env('DEEPSEEK_API_KEY');
$deepseekUrl  = env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com');

echo "DeepSeek Key: " . substr($deepseekKey, 0, 10) . "...\n";
echo "DeepSeek URL: $deepseekUrl\n\n";

// Test with conversation context
$messages = [
    ['role' => 'system', 'content' => 'You are a helpful AI assistant like ChatGPT. Answer any question directly and accurately.'],
    ['role' => 'user',   'content' => 'My favorite fruit is mango.'],
    ['role' => 'assistant', 'content' => 'That\'s great! Mango is a delicious tropical fruit.'],
    ['role' => 'user',   'content' => 'What is my favorite fruit?'],
];

echo "--- Test 1: Context awareness ---\n";
$r = Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . $deepseekKey,
    'Content-Type'  => 'application/json',
    'Accept'        => 'application/json',
])->timeout(30)->post(rtrim($deepseekUrl,'/').'/v1/chat/completions', [
    'model'       => 'deepseek-chat',
    'messages'    => $messages,
    'max_tokens'  => 200,
    'temperature' => 0.7,
]);
echo "Status: " . $r->status() . "\n";
if ($r->successful()) {
    $d = $r->json();
    echo "Reply: " . ($d['choices'][0]['message']['content'] ?? 'NO CONTENT') . "\n\n";
} else {
    echo "Error: " . $r->body() . "\n\n";
}

// Test Tagalog
$messages2 = [
    ['role' => 'system', 'content' => 'You are a helpful AI assistant. Respond in Tagalog/Filipino when the user writes in Tagalog.'],
    ['role' => 'user',   'content' => 'Ano ang pinakamagandang lugar sa Pilipinas?'],
];

echo "--- Test 2: Tagalog ---\n";
$r2 = Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . $deepseekKey,
    'Content-Type'  => 'application/json',
    'Accept'        => 'application/json',
])->timeout(30)->post(rtrim($deepseekUrl,'/').'/v1/chat/completions', [
    'model'       => 'deepseek-chat',
    'messages'    => $messages2,
    'max_tokens'  => 300,
    'temperature' => 0.7,
]);
echo "Status: " . $r2->status() . "\n";
if ($r2->successful()) {
    $d2 = $r2->json();
    echo "Reply: " . ($d2['choices'][0]['message']['content'] ?? 'NO CONTENT') . "\n\n";
} else {
    echo "Error: " . $r2->body() . "\n\n";
}

echo "Done!\n";
