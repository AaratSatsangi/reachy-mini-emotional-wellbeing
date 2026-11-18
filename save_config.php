<?php
// save_config.php — Updates external model API endpoint URL for live demonstrations
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['api_base_url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request: Missing api_base_url']);
    exit;
}

$apiUrl = trim($data['api_base_url']);
$apiUrl = rtrim($apiUrl, '/');

if (empty($apiUrl)) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request: API Base URL cannot be empty']);
    exit;
}

$configFile = __DIR__ . '/config.json';
$configData = ['api_base_url' => $apiUrl];

if (file_put_contents($configFile, json_encode($configData, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'api_base_url' => $apiUrl]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error: Failed to write to config.json']);
}
