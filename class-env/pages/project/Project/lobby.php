<?php
/**
 * File: lobby.php
 * Description: Shows who has joined the room. Host can start the game from here.
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$room = $_POST['room'] ?? '';
$name = trim($_POST['name'] ?? '');

$path = "./rooms/$room.json";
if (!file_exists($path)) {
    die("Room not found.");
}

$data = json_decode(file_get_contents($path), true);

// Set the host as the first player to join
if (!isset($data['host'])) {
    $data['host'] = $name;
}

if (!in_array($name, $data['players'])) {
    $data['players'][] = $name;
    file_put_contents($path, json_encode($data));
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<link rel = "stylesheet" href = "style.css">
	<link rel = "stylesheet" href = "u_style.css">
	<title>Lobby - Room <?= htmlspecialchars($room) ?></title>
</head>
<body>
	<nav id = "navbar"></nav>

	<main class = "game-container">
		<h2 style = "color: white;">Room Code: <strong><?= htmlspecialchars($room) ?></strong></h2>
		<h3>Welcome, <?= htmlspecialchars($name) ?>!</h3>
		<p style = "color: white;">Waiting for players to join...</p>

		<ul id = "player-list"></ul>

          <?php if ($data['host'] === $name): ?>
		    <form action = "./actions/start_game.php" method = "POST">
			    <input type = "hidden" name = "room" value = "<?= htmlspecialchars($room) ?>">
			    <button type = "submit">Start Game</button>
		    </form>
          <?php endif; ?>

	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
	<script>
          function fetchPlayers() {
              fetch('actions/get_players.php?room=<?= $room ?>')
                  .then(res => res.json())
                  .then(data => {
                      const list = document.getElementById("player-list");
                      list.innerHTML = "";
                      data.players.forEach(player => {
                          const li = document.createElement("li");
                          li.textContent = player;
                          list.appendChild(li);
                      });
                  });
          }

          function checkGameStart() {
              fetch('actions/get_status.php?room=<?= $room ?>')
                  .then(res => res.json())
                  .then(data => {
                      if (data.started) {
                          window.location.href = "game.php?room=<?= $room ?>&name=<?= urlencode($name) ?>";
                      }
                  });
          }

          setInterval(fetchPlayers, 1000);
          setInterval(checkGameStart, 1000);
          fetchPlayers();
	</script>
</body>
</html>
