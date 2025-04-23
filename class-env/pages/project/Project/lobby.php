<?php
session_start();
/**
 * File: lobby.php
 * Description: Shows who has joined the room. Host can start the game from here.
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Make sure we have the game room in the session
if (!isset($_SESSION['game_room'])) {
    header("Location: ./game-landing.php");
    exit;
}

$room = $_SESSION['game_room'];
$player_name = $_SESSION['player_name'] ?? 'Unknown Player';
$player_entry_id = $_SESSION['player_entry_id'] ?? null;

require_once __DIR__ . '/db/db_connect.php';
require_once __DIR__ . '/db/queries.php';

// Check if room exists
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$game_result = $stmt->get_result();

if ($game_result->num_rows === 0) {
    echo '<script>
            alert("This room does not exist or is no longer active.");
            window.location.href = "./game-landing.php";
          </script>';
    exit;
}

// Get game information
$game = $game_result->fetch_assoc();
$host_user_id = $game['host_user_id'];

// Determine if current player is the host
$is_host = false;
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $host_user_id) {
    $is_host = true;
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Lobby - Room <?= substr(htmlspecialchars($room), 0, 3) . "-" . substr(htmlspecialchars($room), 3) ?></title>
</head>
<body>
	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid"></div>
	</nav>
	<main class = "game-container">
		<h2 style = "color: white;">Room Code:
			<strong><?= substr(htmlspecialchars($room), 0, 3) . "-" . substr(htmlspecialchars($room), 3) ?></strong>
		</h2>
		<h3>Welcome, <?= htmlspecialchars($player_name) ?>!</h3>
		<p style = "color: white;">Waiting for players to join...</p>

		<ul id = "player-list">

		</ul>

          <?php if ($is_host): ?>
		    <form action = "./actions/start_game.php" method = "POST">
			    <input type = "hidden" name = "room" value = "<?= htmlspecialchars($room) ?>">
			    <button type = "submit">Start Game</button>
		    </form>
          <?php else: ?>
		    <p style = "color: white;">Waiting for host to start the game...</p>
          <?php endif; ?>

	</main>

	<!-- TODO Add a Leave Game Button and a Kick Player Button -->ss

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>
	<script>

          function fetch_players() {
              $.ajax({
                  url: './actions/check_players.php',
                  data: {
                      room: "<?= $room ?>"
                  },
                  success: function (data) {
                      $('#player-list').html(data);
                  }
              });
          }


          function fetch_game_status() { //Check if game is started
              console.log('Fetching game status...');
              $.ajax({
                  url: './actions/get_status.php',
                  data: {
                      room: "<?= $room ?>"
                  },
                  success: function (data) {
                      console.log('Game status:', data);
                      if (data.started) {
                          $('#game-status').html('Game Started');
                          window.location.href = "./game.php?room=<?= $room ?>";
                      } else {
                          $('#game-status').html('Waiting for players...');
                      }
                  },
                  error: function (xhr, status, error) {
                      console.error('Error fetching game status:', error);
                  },
                  complete: function () {
                      console.log('Game status fetch complete.');
                  },

              });
          }

          // Initial fetch
          fetch_players();
          fetch_game_status();

          // Set up polling intervals
          setInterval(fetch_players, 1000);
          setInterval(fetch_game_status, 1000);

	</script>

</body>
</html>