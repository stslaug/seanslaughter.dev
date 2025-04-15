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

$path = "./rooms/$room.json";
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
	<link rel = "stylesheet" href = "css/style.css">
	<link rel = "stylesheet" href = "css/u_style.css">
	<title>Most Likely To - Question</title>
</head>
<body>
	<div class = "game-container">
		<!-- Add timer container -->
		<div class = "timer-container">
			<div class = "timer-animation"></div>
			<span id = "timer">15</span>
		</div>

		<h2>Question:</h2>
		<h2 class = "current-question">
                <?= preg_replace('/(most likely to)/i', '<span style="color:#ffd700;">$1</span>', htmlspecialchars($current_question)) ?>
		</h2>

		<form action = "vote.php" method = "POST" id = "voteForm">
			<input type = "hidden" name = "room" value = "<?= htmlspecialchars($room) ?>">
			<input type = "hidden" name = "name" value = "<?= htmlspecialchars($name) ?>">
			<input type = "hidden" name = "question" value = "<?= htmlspecialchars($current_question) ?>">

			<p>Vote for the player you think fits best:</p>
			<div class = "radio-options">
                      <?php foreach ($players as $player): ?>
				    <div class = "radio-option">
					    <label>
						    <input type = "radio" name = "vote" value = "<?= htmlspecialchars($player) ?>" required>
						    <span><?= htmlspecialchars($player) ?></span>
					    </label>
				    </div>
                      <?php endforeach; ?>
			</div>
			<button type = "submit">Submit Vote</button>
		</form>
	</div>

	<script>
          document.addEventListener('DOMContentLoaded', function () {
              const form = document.getElementById('voteForm');
              const timerDisplay = document.getElementById('timer');
              let timeLeft = 15;

              // Update timer display every second
              const timerInterval = setInterval(function () {
                  timeLeft--;
                  timerDisplay.textContent = timeLeft;

                  if (timeLeft <= 0) {
                      clearInterval(timerInterval);

                      // Select a random option if none selected
                      const radioButtons = form.querySelectorAll('input[type="radio"]');
                      let isAnySelected = false;

                      radioButtons.forEach(radio => {
                          if (radio.checked) {
                              isAnySelected = true;
                          }
                      });

                      if (!isAnySelected) {
                          // Select random option
                          const randomIndex = Math.floor(Math.random() * radioButtons.length);
                          radioButtons[randomIndex].checked = true;
                      }

                      // Submit the form
                      form.submit();
                  }
              }, 1000);

              // Clear timer if form is submitted manually
              form.addEventListener('submit', function () {
                  clearInterval(timerInterval);
              });
          });
	</script>
</body>
</html>
