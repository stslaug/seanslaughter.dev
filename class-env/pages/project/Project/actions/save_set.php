<?php
session_start();
require_once __DIR__ . '/../db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to create a question set.");
}

$user_id = $_SESSION['user_id'];
$set_name = $_POST['set_name'];
$questions_raw = trim($_POST['questions']);
$questions = explode("\n", $questions_raw);

// Insert new question set
$stmt = $conn->prepare("INSERT INTO question_sets (user_id, set_name) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $set_name);
$stmt->execute();
$set_id = $conn->insert_id;

// Insert each question
$stmt = $conn->prepare("INSERT INTO questions (question_text, question_set_id) VALUES (?, ?)");
foreach ($questions as $q) {
    $cleaned = trim($q);
    if (!empty($cleaned)) {
        $stmt->bind_param("si", $cleaned, $set_id);
        $stmt->execute();
    }
}

header("Location: ../game-landing.php");
exit;
?>
