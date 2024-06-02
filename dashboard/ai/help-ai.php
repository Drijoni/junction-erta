<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();
$apiKey = 'T5OsPOkbgFWob6CU2cbODgUrxWHpCJpTLHukkPWY';
$url = 'https://api.cohere.ai/generate';

$headers = [
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json',
];

$body = [
    'model' => 'command-nightly',
    'prompt' => 'Organize this into a to do list like a check-list and make it simple to understand: ' . $_POST['prompt'],
    'max_tokens' => 100,
    'temperature' => 0.8,
];

$response = $client->post($url, [
    'headers' => $headers,
    'json' => $body,
]);

$responseData = json_decode($response->getBody()->getContents(), true);
$generatedText = $responseData['text'];

// Remove the introductory text and split the remaining text into tasks
$tasks = array_filter(explode('- [ ]', substr($generatedText, strpos($generatedText, '- [ ]'))));

// Remove any leading/trailing spaces and join the tasks with a comma and a space
$formattedText = implode(', ', array_map('trim', $tasks));

// Output the formatted text as JSON
echo json_encode(['text' => $formattedText]);
?>