<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db/db_connect.php';

$room = $_GET['room'] ?? '';

if (!$room) {
    die("Missing room code.");
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
$question_set_id = $game['question_set_id'];

// Get question list
$stmt = $conn->prepare("SELECT question_text FROM questions WHERE question_set_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $question_set_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = [];

while ($row = $result->fetch_assoc()) {
    $questions[] = $row['question_text'];
}

$current_question = $questions[$question_index - 1] ?? null;

if (!$current_question) {
    echo "<h2>No more questions!</h2>";
    exit;
}

$stmt = $conn->prepare("SELECT id, nickname, user_id FROM game_players WHERE game_id = ?");
$stmt->bind_param("s", $room);
$stmt->execute();
$result = $stmt->get_result();
$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = [
        'nickname' => $row['nickname'],
        'id' => $row['id'],
        'user_id' => $row['user_id']
    ];
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">

	<title>Most Likely To - Question</title>
</head>
<body>
	<nav>
		<div id = "navbar-container"></div>
		<div id = "game-nav" class = "container-fluid"></div>
	</nav>

	<div class = "game-container">
		<h2>Question:</h2>
		<h2 class = "current-question">
                <?= preg_replace('/(most likely to)/i', '<span style="color:#ffd700;">$1</span>', htmlspecialchars($current_question)) ?>
		</h2>

		<form action = "vote.php" method = "POST" id = "voteForm">
			<input type = "hidden" name = "room" value = "<?= htmlspecialchars($room) ?>">
			<input type = "hidden" name = "name" value = "<?= htmlspecialchars($_SESSION['player_name'] ?? 'anonymous') ?>">
			<input type = "hidden" name = "question" value = "<?= htmlspecialchars($current_question) ?>">

			<p>Vote for the player you think fits best:</p>
			<div class = "radio-options">
                      <?php foreach ($players as $player): ?>
				    <div class = "radio-option">
					    <label class = "radio-label">
						    <input type = "radio" class = "" name = "vote" value = "<?= htmlspecialchars($player['nickname']) ?>" required>
						    <input type = "hidden" name = "player_ids[<?= htmlspecialchars($player['nickname']) ?>]" value = "<?= htmlspecialchars($player['user_id'] ? $player['user_id'] : $player['id']) ?>">
						    <span> <?= htmlspecialchars($player['nickname']) ?></span>
					    </label>
				    </div>
                      <?php endforeach; ?>
			</div>
			<button type = "submit">Submit Vote</button>
		</form>
	</div>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>
	<script>
          document.addEventListener('DOMContentLoaded', function () {
              const form = document.getElementById('voteForm');
              const timerDisplay = document.createElement('span');
              timerDisplay.id = 'timer';
              timerDisplay.style.color = 'white';
              timerDisplay.textContent = 15;
              form.style.color = 'white';
              form.parentElement.insertBefore(timerDisplay, form);

              let timeLeft = 15;
              const timerInterval = setInterval(function () {
                  timeLeft--;
                  timerDisplay.textContent = timeLeft;
                  if (timeLeft <= 0) {
                      clearInterval(timerInterval);
                      const radios = form.querySelectorAll('input[type="radio"]');
                      const checked = Array.from(radios).some(r => r.checked);
                      if (!checked) {
                          radios[Math.floor(Math.random() * radios.length)].checked = true;
                      }
                      form.submit();
                  }
              }, 1000);
          });
	</script>
</body>
</html>
