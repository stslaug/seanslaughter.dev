<?php
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

	<link href = "u_style.css" rel = "stylesheet">
	<link href = "style.css" rel = "stylesheet">
	<link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel = "stylesheet">

	<title>The Game | Most Likely To</title>

</head>
<body>
	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid">
			<div class = "d-flex justify-content-end py-2">
				<a class = "btn btn-primary mx-2" href = "#">The Game</a>
				<a class = "btn btn-primary mx-2" href = "./index.php">Proposal</a>
				<a class = "btn btn-primary mx-2" href = "./settings.html">Profile</a>
			</div>
		</div>
	</nav>
	<main class = "game-container">
		<div class = "container">
			<h1>Most Likely To</h1>
			<form action = "actions/join.php" method = "GET">
				<input type = "text" class = "text" name = "room" placeholder = "Enter Room Code" required>
				<button type = "submit">Join Game</button>
			</form>
			<form action = "./actions/create.php" method = "POST">
				<label for = "theme">Choose a theme:</label>
				<select name = "theme" required>
					<option value = "general">General</option>
					<option value = "college">College</option>
					<option value = "office">Office</option>
				</select>
				<div>
					<label for = "mode_code" style = "margin: 10px;"> Secret Game:</label>
					<input type = "text" placeholder = "Enter Secret Code" name = "mode_code"/>
				</div>
				<button class = "btn btn-primary" type = "submit">Create Game</button>
			</form>
		</div>
	</main>

	<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

	<!--

            Just for our navbar, and footers we will have to switch these out on each end.

            <script>
                    fetch('/navbar.html')
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('navbar-container').innerHTML = data;
                        });
            </script>


                Replace my script below with yours!
        -->
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>
</html>