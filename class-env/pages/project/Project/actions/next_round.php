<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../db/db_connect.php';

$room = $_POST['room'] ?? '';

if (!$room) {
    die("Room code missing.");
}

// Optional: verify the user is the host
$stmt = $conn->prepare("SELECT host_user_id FROM games WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

if (!$game) {
    die("Game not found.");
}

$host_user_id = $game['host_user_id'];
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $host_user_id) {
    die("Only the host can start the next round.");
}

// Increment the current question index
$stmt = $conn->prepare("UPDATE games SET round_active = 1, current_question_index = current_question_index + 1 WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();

// Redirect to game
header("Location: ../game.php?room=" . urlencode($room));
exit;
