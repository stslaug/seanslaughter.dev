<?php
session_start();

$servername = "";
$username = "";
$password = "";
$database = "";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? null;

if ($action === 'add') {
    $card_id = $conn->real_escape_string($_POST['cardId']);
    $name = $conn->real_escape_string($_POST['name']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $type_line = $conn->real_escape_string($_POST['type_line']);
    $color = $conn->real_escape_string($_POST['color']);

    $stmt = $conn->prepare("INSERT IGNORE INTO favorites (user_id, card_id, name, image_url, type_line, color) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $card_id, $name, $image_url, $type_line, $color);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add to favorites.']);
    }
    $stmt->close();

} elseif ($action === 'remove') {
    $card_id = $conn->real_escape_string($_POST['cardId']);

    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND card_id = ?");
    $stmt->bind_param("is", $user_id, $card_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to remove from favorites.']);
    }
    $stmt->close();

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action.']);
}

$conn->close();
