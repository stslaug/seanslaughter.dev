<?php
// Include any necessary configuration or database connection files
require_once '../db/db_connect.php';
require_once '../db/queries.php';

// Get the room ID from the AJAX request
$room = $_GET['room'] ?? '';

// Validate room ID
if (empty($room)) {
    echo '<li>Invalid room ID</li>';
    exit;
}

// Call your existing function to get players in this room
$players = getGamePlayers($room);

// Debug output to help troubleshoot
// echo "<!-- DEBUG: Room ID: " . htmlspecialchars($room) . " -->";
// echo "<!-- DEBUG: Player count: " . count($players) . " -->";

// Check if we have players
if (empty($players)) {
    echo '<li>No players in this room</li>';
} else {
    // Generate the HTML for the player list
    foreach ($players as $player) {
        echo '<li>' . htmlspecialchars($player['nickname']) . '</li>';
    }
}
?>