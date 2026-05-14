<?php
/**
 * Test script to verify chatbot responds to "What is AI?"
 */

// Make HTTP request to chatbot
$url = 'http://127.0.0.1:8000/chat';
$data = json_encode(['message' => 'What is AI?']);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-CSRF-TOKEN: test-token'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "=== Chatbot Test: 'What is AI?' ===\n\n";
echo "HTTP Status: {$httpCode}\n";

if ($error) {
    echo "Error: {$error}\n";
    exit(1);
}

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if (isset($data['reply'])) {
        echo "✅ SUCCESS! Chatbot responded:\n";
        echo "Source: " . ($data['source'] ?? 'unknown') . "\n\n";
        echo "Response preview:\n";
        echo substr($data['reply'], 0, 500) . "...\n";
    } else {
        echo "❌ Unexpected response format:\n";
        print_r($data);
    }
} else {
    echo "❌ HTTP Error {$httpCode}:\n";
    echo $response;
}
