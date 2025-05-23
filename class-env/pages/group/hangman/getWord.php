<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// SECURITY: Make sure 'words.txt' is stored outside public_html
// Check if running on local environment or server
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '162.213.255.53') {
    // Local development path
    $wordsFile = 'words.txt';
} else {
    // Server path
    $wordsFile = '/home/seanljvy/TextFile/words.txt';
}

// Now $wordsFile will have the appropriate path based on environment

// To make sure it's the correct Content-Type for JSON responses
header('Content-Type: application/json');

// Check if the file exists
if (file_exists($wordsFile)) {
    $words = file($wordsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!empty($words)) {
        // Randomly select a word from the file
        $selectedWord = trim($words[array_rand($words)]);
        // Send the selected word back to the client
        echo json_encode(['word' => $selectedWord]);
    } else {
        echo json_encode(['error' => 'No words found.']);
    }
} else {
    echo json_encode(['error' => 'File not found.']);
}
?>