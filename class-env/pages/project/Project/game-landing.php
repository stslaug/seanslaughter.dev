<?php
session_start();
/**
 * File: index.php
 * Description: Landing page for the game. Lets users choose a theme and create a new game room.
 *
 */
require_once __DIR__ . '/db/db_connect.php';

$custom_sets = [];
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $res = $conn->prepare("SELECT id, set_name FROM question_sets WHERE user_id = ?");
    $res->bind_param("i", $uid);
    $res->execute();
    $result = $res->get_result();
    while ($row = $result->fetch_assoc()) {
        $custom_sets[] = $row;
    }
}
?>
?><!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "utf-8">
	<meta content = "width=device-width, initial-scale=1, shrink-to-fit=no" name = "viewport">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">

	<title>The Game | Most Likely To</title>

	<style>
		  .game-container
		  {
			  max-width: 450px;
		  }
	</style>
</head>
<body>
	<nav>
		<div id = "navbar-container"></div>
		<div id = "game-nav" class = "container-fluid"></div>
	</nav>

	<main class = "game-container">
		<div class = "container">
			<h1>Most Likely To</h1>
			<form action = "actions/join.php" method = "GET">
				<input class = "form-control" type = "text" name = "room" placeholder = "Enter Room Code" required>
				<button type = "submit">Join Game</button>
			</form>

                <?php if (isset($_SESSION['username'])): ?>
			    <form action = "./actions/create.php" method = "POST">
				    <div class = "form-group">
					    <label for = "theme">Choose a theme:</label>

					    <select class = "form-control" id = "theme" name = "theme" required>
						    <option value = "1">General</option>
						    <option value = "2">College</option>
						    <option value = "3">Office</option>
                                      <?php if (!empty($custom_sets)): ?>
							  <optgroup label = "Your Custom Sets">
                                                <?php foreach ($custom_sets as $set): ?>
									<option value = "<?= $set['id'] ?>"><?= htmlspecialchars($set['set_name']) ?></option>
                                                <?php endforeach; ?>
							  </optgroup>
                                      <?php endif; ?>
					    </select>
				    </div>
				    <button class = "btn btn-primary" type = "submit">Create Game</button>
			    </form>
                <?php else: ?>
			    <p>You must be logged in to create a game.</p>
			    <a href = "user/login.php">Login</a>
                <?php endif; ?>

		</div>
	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>
</body>
</html>