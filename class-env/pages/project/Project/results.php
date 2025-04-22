<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db/db_connect.php';

$room = $_GET['room'] ?? '';
$name = $_GET['name'] ?? '';

if (!$room || !$name) {
    die("Missing room or name.");
}

// Get game info
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

if (!$game) {
    die("Game not found.");
}

$question_index = $game['current_question_index'];
$host_user_id = $game['host_user_id'];

// Get player info
$stmt = $conn->prepare("SELECT id FROM game_players WHERE game_id = ? AND nickname = ?");
$stmt->bind_param("ss", $room, $name);
$stmt->execute();
$player_row = $stmt->get_result()->fetch_assoc();
$current_player_id = $player_row['id'] ?? null;

// Check if this player is the host
$is_host = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $host_user_id;

// Count votes
$stmt = $conn->prepare("
    SELECT gp.nickname, COUNT(*) as vote_count
    FROM votes v
    JOIN game_players gp ON v.vote_for_id = gp.id
    WHERE v.game_id = ? AND v.question_index = ?
    GROUP BY v.vote_for_id
    ORDER BY vote_count DESC
");
$stmt->bind_param("si", $room, $question_index);
$stmt->execute();
$vote_results = $stmt->get_result();

$votes = [];
while ($row = $vote_results->fetch_assoc()) {
    $votes[$row['nickname']] = $row['vote_count'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Round Results | Most Likely To</title>
</head>
<body>
	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid"></div>
	</nav>
	<main class = "game-container">
		<h2>🎉 Results for This Round</h2>

          <?php if (!empty($votes)): ?>
		    <ul>
                    <?php foreach ($votes as $player => $count): ?>
				  <li><strong><?= htmlspecialchars($player) ?></strong>: <?= $count ?> vote(s)</li>
                    <?php endforeach; ?>
		    </ul>
          <?php else: ?>
		    <p><em>No votes recorded.</em></p>
          <?php endif; ?>

          <?php
          // Count total questions
          $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM questions WHERE question_set_id = ?");
          $stmt->bind_param("i", $game['question_set_id']);
          $stmt->execute();
          $total_question_count = $stmt->get_result()->fetch_assoc()['total'];

          $more_questions = $question_index < $total_question_count;
          ?>

          <?php if ($more_questions): ?><?php if ($is_host): ?>
		    <form action = "./actions/next_round.php" method = "POST">
		    <input type = "hidden" name = "room" value = "<?= htmlspecialchars($room) ?>">
		    <button type = "submit">➡️ Next Question</button>
		    </form><?php else: ?><p><em>Waiting for the host to start the next round...</em></p>
		    <script>
                    function checkRoundStart() {
                        fetch('get_status.php?room=<?= $room ?>')
                            .then(res => res.json())
                            .then(data => {
                                if (data.round_started) {
                                    window.location.href = "game.php?room=<?= $room ?>&name=<?= urlencode($name) ?>";
                                }
                            });
                    }

                    setInterval(checkRoundStart, 1000);
		    </script><?php endif; ?><?php else: ?><h3>🏁 Game Over! Thanks for playing 🎉</h3>
		    <form action = "index.php" method = "GET">
			    <button type = "submit">🔁 Back to Start</button>
		    </form>
          <?php endif; ?>
	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>
</body>
</html>
