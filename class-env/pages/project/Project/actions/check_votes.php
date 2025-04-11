<?php
/**
 * File: check_votes.php
 * Description: Called via AJAX to check if all players have voted and results are ready.
 * Returns a JSON response used to trigger the transition to results.php.
 *
 */
header('Content-Type: application/json');

$room = $_GET['room'] ?? '';
$path = "../rooms/$room.json";

if (!file_exists($path)) {
    echo json_encode(["results_ready" => false]);
    exit;
}

$data = json_decode(file_get_contents($path), true);
echo json_encode([
    "results_ready" => $data['results_ready'] ?? false
]);
