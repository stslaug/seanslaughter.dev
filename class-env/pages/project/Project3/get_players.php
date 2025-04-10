<?php
/**
 * File: get_players.php
 * Description: Returns a list of player names in the room as a JSON array.
 * Used to dynamically update the player list in the lobby.
 *
 */
$room = $_GET['room'] ?? '';
$path = "rooms/$room.json";
if (!file_exists($path)) {
    echo json_encode(["players" => []]);
    exit;
}
$data = json_decode(file_get_contents($path), true);
echo json_encode(["players" => $data["players"]]);