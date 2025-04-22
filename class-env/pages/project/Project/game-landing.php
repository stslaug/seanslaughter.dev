<?php
session_start();
/**
 * File: index.php
 * Description: Landing page for the game. Lets users choose a theme and create a new game room.
 *
 */
?>
<!DOCTYPE html>
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

			<form action = "./actions/create.php" method = "POST">
				<div class = "form-group">
					<label for = "theme">Choose a theme:</label>

					<select class = "form-control" id = "theme" name = "theme" required>
						<option value = "1">General</option>
						<option value = "2">College</option>
						<option value = "3">Office</option>
						<!-- I think we should append player made options here. Instead of codes?-->z
					</select>
				</div>
				<button class = "btn btn-primary" type = "submit">Create Game</button>
			</form>
		</div>
	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>
</body>
</html>