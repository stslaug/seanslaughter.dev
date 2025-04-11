<?php
/**
 * File: next_round.php
 * Description: Host-only action to move the game to the next question/round.
 *
 */
$room = $_POST['room'] ?? '';
$name = $_POST['name'] ?? '';

$path = "../rooms/$room.json";
if (!file_exists($path)) {
    die("Room not found.");
}

$data = json_decode(file_get_contents($path), true);

// ✅ Advance round and set round_started to true
$data['votes'] = [];
$data['voted'] = [];
$data['results_ready'] = false;
$data['round_started'] = true;
$data['question_index'] = ($data['question_index'] ?? 0) + 1;

file_put_contents($path, json_encode($data));

header("Location: ../game.php?room=$room&name=" . urlencode($name));
exit;
