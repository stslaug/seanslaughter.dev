<?php
session_start();
/**
 * File: create.php
 * Description: Handles game creation logic. Generates a room code and stores player and question data.
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../db/db_connect.php';
require_once __DIR__ . '/../db/queries.php';
/*
CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    host_user_id INT,
    question_set_id INT NOT NULL,
    current_question_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (host_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (question_set_id) REFERENCES question_sets(id) ON DELETE CASCADE
);


 Below SHOULD be all we need to create a game.
 */


$question_set_id = $_POST['theme'] ?? '0'; /* Previously Theme: 0 = general, 1 = college, 2 = office */

$game_id = substr(md5(uniqid()), 0, 6);// Random 5 byte string
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO games (id, host_user_id, question_set_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $game_id, $user_id, $question_set_id);
} else {
    $stmt = $conn->prepare("INSERT INTO games (id, question_set_id) VALUES (?, ?)");
    $stmt->bind_param("si", $game_id, $question_set_id);
}
$stmt->execute();
$room = $game_id;

header("Location: join.php?room=$game_id");
exit;
?>