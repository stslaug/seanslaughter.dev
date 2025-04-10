<?php
/**
 * File: create.php
 * Description: Handles game creation logic. Generates a room code and stores player and question data.
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$room = substr(md5(uniqid()), 0, 6);
$theme = $_POST['theme'] ?? 'general';
$custom_input = trim($_POST['custom_questions'] ?? '');
$mode_code = strtolower(trim($_POST['mode_code'] ?? ''));

// Load question bank
$allQuestions = json_decode(file_get_contents("questions.json"), true);

// Determine questions to use
if ($theme === 'custom' && !empty($custom_input)) {
    // Manually entered questions
    $questions = array_filter(array_map('trim', explode("\n", $custom_input)));
    $mode = 'custom';
} elseif (!empty($mode_code) && isset($allQuestions[$mode_code])) {
    // Secret mode unlocked via code
    $questions = $allQuestions[$mode_code];
    $mode = $mode_code;
} else {
    // Normal public theme
    $questions = $allQuestions[$theme] ?? $allQuestions['general'];
    $mode = $theme;
}

shuffle($questions);

$data = [
    "players" => [],
    "question_index" => 0,
    "votes" => [],
    "voted" => [],
    "created_at" => time(),
    "game_started" => false,
    "round_started" => false,
    "questions" => $questions,
    "mode" => $mode
];

file_put_contents("rooms/$room.json", json_encode($data));

header("Location: join.php?room=$room");
exit;
