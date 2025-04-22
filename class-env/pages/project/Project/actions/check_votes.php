<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../db/db_connect.php';

$room = $_GET['room'] ?? '';

if (!$room) {
    echo json_encode(["results_ready" => false]);
    exit;
}

// Get current question index
$stmt = $conn->prepare("SELECT current_question_index FROM games WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    echo json_encode(["results_ready" => false]);
    exit;
}

$question_index = $game['current_question_index'];

// Get player count
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM game_players WHERE game_id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$player_result = $stmt->get_result()->fetch_assoc();
$total_players = $player_result['total'];

// Count votes for this round
$stmt = $conn->prepare("SELECT COUNT(*) AS votes FROM votes WHERE game_id = ? AND question_index = ?");
$stmt->bind_param("si", $room, $question_index);
$stmt->execute();
$vote_result = $stmt->get_result()->fetch_assoc();
$total_votes = $vote_result['votes'];

// Check if all players have voted
$results_ready = ($total_votes >= $total_players);

echo json_encode(["results_ready" => $results_ready]);
?>
