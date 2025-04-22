<?php
/**
 * File: get_status.php
 * Description: Returns the current game state (e.g., game_started and round_started) for the room.
 * Used by players to sync their game screen state with the host.
 *
 */
header('Content-Type: application/json');


require_once __DIR__ . '/../db/db_connect.php';
require_once __DIR__ . '/../db/queries.php';

$room = $_GET['room'] ?? '';

$gameStatusRes = getGameStatus($room)->fetch_assoc();
if ($gameStatusRes) {
    // cast to boolean so MySQL 0/1 (or NULL) becomes true/false
    echo json_encode([
        'started' => $gameStatusRes['is_active'],
        'round_active' => $gameStatusRes['round_active']
    ]);
} else {
    // no row returned for that room
    echo json_encode([
        'started' => false,
        'round_active' => false
    ]);
}

?>

