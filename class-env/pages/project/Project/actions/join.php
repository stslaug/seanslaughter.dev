<?php
session_start();
/**
 * File: join.php
 * Description: Allows players to join an existing room by entering their name.
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$room = $_GET['room'] ?? '';
$room = trim(str_replace('-', '', strtolower($room)));
if (!$room) { /*  I love this... THis should help players join the game again */
    die("Room code missing.");
}

require_once __DIR__ . '/../db/db_connect.php';

// Step 1: Check if room exists
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Room doesn't exist or is not active
    echo '<script>
            alert("This room does not exist or is no longer active.");
          </script>';
    header("Location: ../game-landing.php");
    exit;
}

// Handle form submission for joining the room
if (isset($_POST['name'])) {
    $nickname = $_POST['name'];
    $player_id = null;

    // If user is logged in, link to user account
    if (isset($_SESSION['user_id'])) {
        $player_id = $_SESSION['user_id'];
    }

    // Add player to game_players table
    $stmt = $conn->prepare("INSERT INTO game_players (game_id, user_id, nickname) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $room, $player_id, $nickname);

    if ($stmt->execute()) {
        // Get the player entry ID to use as a session reference
        $player_entry_id = $conn->insert_id;

        // Set session variables for the game
        $_SESSION['game_room'] = $room;
        $_SESSION['player_name'] = $nickname;
        $_SESSION['player_entry_id'] = $player_entry_id;

        // Redirect to lobby
        header("Location: ../lobby.php");
        exit;
    } else {
        $error = "Failed to join the game. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<meta content = "width=device-width, initial-scale=1, shrink-to-fit=no" name = "viewport">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">

	<title>Join Game - <?= htmlspecialchars($room) ?></title>
</head>
<body>

	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid"></div>
	</nav>

	<main class = "game-container">
		<h2>🎮 Join Game</h2>
		<p style = "color: white;">Room Code:
			[<strong> <?= substr(htmlspecialchars($room), 0, 3) . "-" . substr(htmlspecialchars($room), 3) ?></strong> ]
		</p>

          <?php if (isset($error)): ?>
		    <div class = "alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

		<form action = "join.php?room=<?= htmlspecialchars($room) ?>" method = "POST">
			<div>
				<label for = "name">Nickname</label>
				<input class = "form-control" id = "name" type = "text" name = "name" placeholder = "Enter your name" required>
			</div>
			<button type = "submit" class = "btn btn-primary mt-3">Join Room</button>
		</form>

		<p><a id = "back-btn" href = "../index.php">⬅️ Back to Start</a></p>
	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "../scripts/includes.js"></script>

</body>
</html>