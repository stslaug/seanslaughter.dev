<?php
/**
 * File: start_game.php
 * Description: Host-only action that starts the game by setting the game and round state to true.
 *
 */
$room = $_POST['room'] ?? '';
$path = "rooms/$room.json";

if (!file_exists($path)) {
    die("Room not found.");
}

$data = json_decode(file_get_contents($path), true);
$data['game_started'] = true;
$data['round_started'] = true;

file_put_contents($path, json_encode($data));
header("Location: game.php?room=$room&name=" . urlencode($data['host']));
exit;
