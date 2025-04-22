<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../db/db_connect.php';

$room = $_POST['room'] ?? '';

if (!$room) {
    die("Room code missing.");
}

// Validate that the game exists
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Room not found in database.");
}


$stmt = $conn->prepare("UPDATE games SET current_question_index = 1, is_active = 1 WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();

// Redirect to the game page
header("Location: ../game.php?room=$room");
exit;
?>
