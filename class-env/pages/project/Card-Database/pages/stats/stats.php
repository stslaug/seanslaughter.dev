<?php
session_start();
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">
	<meta content = "width=device-width, initial-scale=1.0" name = "viewport">
	<meta content = "Sean Tyler Slaughter" name = "author">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "any" type = "image/svg+xml">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "16x16" type = "image/svg+xml">
	<title>Scryfall API Stats | Sean Slaughter</title>
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/subnav.css" rel = "stylesheet">
	<link href = "./stats.css" rel = "stylesheet">
</head>
<body class = "cardBody">
	<nav>
		<!--	Triple navigation	-->
		<nav id = "navbar"></nav>
		<nav class = "subnav">
			<h2>Magic the Gathering Card Viewer | Utilizing
				<a href = "https://scryfall.com/docs/api" target = "_blank">Scryfall API</a></h2>
			<div>
				<a class = "nav-item" href = "../../card-home.php">Cards</a>
				<a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/about/about.php">About</a>
				<a class = "nav-item" href = "#">Stats</a>
                      <?php if (isset($_SESSION['username'])): ?>
				    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/favorites/favorites.php">Favorites</a>
				    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/profile/profile.php">Profile</a>
				    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/auth/logout.php">Logout</a>
                      <?php else: ?>
				    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/auth/login.php">Login / Sign Up</a>
                      <?php endif; ?>

			</div>
		</nav>
	</nav>

	<main class = "page" style = "max-width: 80%">
		<h1>Scryfall API Statistics</h1>
		<p class = "intro-text">This page shows current statistics for the Scryfall database.</p>

		<div id = "stats-container">
			<!-- Statistics will be loaded here -->
		</div>
	</main>

	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
	<script rel = "text/javascript" src = "stats.js"></script>
</body>
</html>