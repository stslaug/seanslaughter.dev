<?php
/**
 * File: vote.php
 * Description: Records a player's vote and checks if everyone has voted to proceed to results.
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$room = $_POST['room'] ?? '';
$name = $_POST['name'] ?? '';
$vote = $_POST['vote'] ?? '';

$path = "rooms/$room.json";
if (!file_exists($path)) {
    die("Room not found.");
}

$data = json_decode(file_get_contents($path), true);

// Record the vote
if (!isset($data['votes'][$vote])) {
    $data['votes'][$vote] = 0;
}
$data['votes'][$vote]++;

// Mark the voter
if (!in_array($name, $data['voted'])) {
    $data['voted'][] = $name;
}

// If all players have voted, mark results as ready and end the round
if (count($data['voted']) >= count($data['players'])) {
    $data['results_ready'] = true;
    $data['round_started'] = false;
}

file_put_contents($path, json_encode($data));
?>

<!DOCTYPE html>
<html>
<head>
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Waiting for Others</title>
	<link rel = "stylesheet" href = "style.css">
	<link rel = "stylesheet" href = "/styles/general.css">
</head>
<body>
	<nav id = "navbar"></nav>
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
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
	<script>
          function checkVotes() {
              fetch('actions/check_votes.php?room=<?= $room ?>')
                  .then(res => res.json())
                  .then(data => {
                      if (data.results_ready) {
                          window.location.href = "results.php?room=<?= $room ?>&name=<?= urlencode($name) ?>";
                      }
                  });
          }

          setInterval(checkVotes, 1000);
	</script>

</body>
</html>
