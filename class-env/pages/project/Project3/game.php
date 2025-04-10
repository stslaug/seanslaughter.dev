<?php
/**
 * File: game.php
 * Description: Displays a "most likely to" question and allows players to vote.
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$room = $_GET['room'] ?? '';
$name = $_GET['name'] ?? '';

$path = "rooms/$room.json";
if (!file_exists($path)) {
    die("Room not found.");
}

$data = json_decode(file_get_contents($path), true);

// Check for expiration (optional)
if (time() - ($data['created_at'] ?? 0) > 600) {
    unlink($path);
    die("Room has expired.");
}

// Get question list and index
$questions = $data['questions'] ?? [];
$index = $data['question_index'] ?? 0;
$current_question = $questions[$index] ?? null;

if (!$current_question) {
    echo "<h2>No more questions!</h2>";
    exit;
}

// ✅ Prevent early access to round
if (!($data['round_started'] ?? false)) {
    echo "<h2>Waiting for the host to start the round...</h2>";
    echo "<script>
        setTimeout(() => {
            window.location.reload();
        }, 1000); // Reload every second to check for round start
    </script>";
    exit;
}

$players = $data['players'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<link rel = "stylesheet" href = "style_game.css">
	<link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel = "stylesheet" href = "/styles/general.css">
	<link rel = "stylesheet" href = "style.css">
	<title>Most Likely To - Question</title>
</head>
<body>
	<nav id = "navbar"></nav>

	<main class = "game-container">
		<h2>Question:</h2>
		<h2 class = "current-question">
                <?= preg_replace('/(most likely to)/i', '<span style="color:#ffd700;">$1</span>', htmlspecialchars($current_question)) ?>
		</h2>

		<form action = "vote.php" method = "POST">
			<input type = "hidden" name = "room" value = "<?= htmlspecialchars($room) ?>">
			<input type = "hidden" name = "name" value = "<?= htmlspecialchars($name) ?>">
			<input type = "hidden" name = "question" value = "<?= htmlspecialchars($current_question) ?>">

			<p>Vote for the player you think fits best:</p>
                <?php foreach ($players as $player): ?>
			    <label>
				    <input type = "radio" name = "vote" value = "<?= htmlspecialchars($player) ?>" required>
                          <?= htmlspecialchars($player) ?>
			    </label><br>
                <?php endforeach; ?>
			<br>
			<button type = "submit">Submit Vote</button>
		</form>
	</main>

	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>
</html>
