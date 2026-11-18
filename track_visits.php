<?php
// track_visits.php — Lightweight, file-based visit tracker for the project page
$statsFile = __DIR__ . '/visits.json';

if (!file_exists($statsFile)) {
    file_put_contents($statsFile, json_encode(['page_visits' => 0, 'demo_runs' => 0], JSON_PRETTY_PRINT));
}

$jsonContent = @file_get_contents($statsFile);
$data = $jsonContent ? json_decode($jsonContent, true) : null;
if (!is_array($data)) {
    $data = ['page_visits' => 0, 'demo_runs' => 0];
}

$data['page_visits'] = isset($data['page_visits']) ? $data['page_visits'] + 1 : 1;
@file_put_contents($statsFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
