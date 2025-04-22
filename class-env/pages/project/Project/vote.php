<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/db/db_connect.php';

$room = $_POST['room'] ?? '';
$voter_nickname = $_POST['name'] ?? '';
$vote_for_nickname = $_POST['vote'] ?? '';

if (!$room || !$voter_nickname || !$vote_for_nickname) {
    var_dump($_POST);
    exit;
    die("Missing vote data.");
}

// Get game info
$stmt = $conn->prepare("SELECT id, current_question_index FROM games WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

if (!$game) {
    var_dump($_POST);
    exit;
    die("Game not found.");
}

$question_index = $game['current_question_index'];

// Get voter ID
$stmt = $conn->prepare("SELECT id FROM game_players WHERE game_id = ? AND nickname = ?");
$stmt->bind_param("ss", $room, $voter_nickname);
$stmt->execute();
$voter_row = $stmt->get_result()->fetch_assoc();
$voter_id = $voter_row['id'] ?? null;

// Get vote_for ID
$stmt = $conn->prepare("SELECT id FROM game_players WHERE game_id = ? AND nickname = ?");
$stmt->bind_param("ss", $room, $vote_for_nickname);
$stmt->execute();
$vote_for_row = $stmt->get_result()->fetch_assoc();
$vote_for_id = $vote_for_row['id'] ?? null;

// Insert vote
if ($voter_id && $vote_for_id) {
    $stmt = $conn->prepare("INSERT INTO votes (game_id, question_index, voter_id, vote_for_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $room, $question_index, $voter_id, $vote_for_id);
    $stmt->execute();
} else {
    die("Invalid player(s).");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Waiting for Others</title>

</head>
<body>
	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid"></div>
	</nav>
	<main class = "game-container">
		<h2>✅ Your vote has been recorded!</h2>
		<p><em>Waiting for the rest of the players to vote...</em></p>
		<div class = "loader" style = "margin: 20px auto; border: 5px solid #ccc; border-top: 5px solid #ffce00; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
	</main>

	<style>
		  @keyframes spin
		  {
			  0%
			  {
				  transform: rotate(0deg);
			  }
			  100%
			  {
				  transform: rotate(360deg);
			  }
		  }
	</style>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>

	<script>
          function checkVotes() {
              fetch('actions/check_votes.php?room=<?= $room ?>')
                  .then(res => res.json())
                  .then(data => {
                      if (data.results_ready) {
                          window.location.href = "results.php?room=<?= $room ?>&name=<?= urlencode($voter_nickname) ?>";
                      }
                  });
          }

          setInterval(checkVotes, 1000);
	</script>
</body>
</html>
