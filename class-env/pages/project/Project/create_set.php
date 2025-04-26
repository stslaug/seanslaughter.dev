<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<title>Create Question Set | Most Likely To</title>
	<meta name = "viewport" content = "width=device-width, initial-scale=1">
</head>
<body>
	<nav>
		<div id = "navbar-container"></div>
		<div id = "game-nav" class = "container-fluid"></div>
	</nav>

	<main class = "game-container container mt-5" style = "max-width: 600px;">
		<h1 class = "mb-4">Custom Question Sets</h1>

		<form style = "width: 100%;" action = "actions/save_set.php" method = "POST">
			<div class = "mb-3">
				<label for = "set_name" class = "form-label">Set Name</label>
				<input type = "text" class = "form-control" name = "set_name" id = "set_name" required>
			</div>

			<div class = "mb-3">
				<label for = "questions" class = "form-label">Enter Questions (one per line)</label>
				<textarea class = "form-control" name = "questions" id = "questions" rows = "10" required></textarea>
			</div>

			<button type = "submit" class = "btn btn-primary">Create Question Set</button>
		</form>
		<p>You can create as many question sets as you want. Sets will be displayed while logged in to your account within our game themes list at the game's landing page.</p>
	</main>

	<!-- JS scripts -->
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src = "scripts/includes.js"></script>
</body>
</html>