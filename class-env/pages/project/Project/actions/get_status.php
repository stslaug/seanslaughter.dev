<?php
/**
 * File: get_status.php
 * Description: Returns the current game state (e.g., game_started and round_started) for the room.
 * Used by players to sync their game screen state with the host.
 *
 */
header('Content-Type: application/json');

$room = $_GET['room'] ?? '';
$path = "../rooms/$room.json";

if (!file_exists($path)) {
    echo json_encode(["started" => false, "round_started" => false]);
    exit;
}

$data = json_decode(file_get_contents($path), true);
echo json_encode([
    "started" => $data['game_started'] ?? false,
    "round_started" => $data['round_started'] ?? false
]);