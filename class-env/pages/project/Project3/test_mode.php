<?php
// Enable errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Generate a fake room ID
$room = 'test' . substr(md5(uniqid()), 0, 4);

// Define fake players
$players = ['Alice', 'Bob', 'Charlie'];
$questions = [
    "Most likely to start a band?",
    "Most likely to get TikTok famous?",
    "Most likely to forget their own birthday?",
    "Most likely to eat something off the floor?"
];

// Create room file
$data = [
    'players' => $players,
    'question_index' => 0,
    'votes' => [],
    'voted' => [],
    'created_at' => time(),
    'game_started' => true,
    'round_started' => true,
    'host' => 'Alice',
    'questions' => $questions
];

file_put_contents("rooms/$room.json", json_encode($data));

// Display test info
echo "<h2>✅ Test Room Created</h2>";
echo "<p><strong>Room ID:</strong> $room</p>";
echo "<p><strong>Players:</strong> " . implode(', ', $players) . "</p>";
echo "<p><strong>Host:</strong> Alice</p>";

echo "<h3>🔄 You can now open the following test links in separate tabs:</h3>";
foreach ($players as $player) {
    echo "<p><a href='game.php?room=$room&name=" . urlencode($player) . "' target='_blank'>Open as $player</a></p>";
}

echo "<hr><p><strong>Note:</strong> Use these to simulate voting, syncing, and progression manually. Results will display after voting.</p>";
?>
