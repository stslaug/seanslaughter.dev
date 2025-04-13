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
	<title> about | Sean Slaughter</title>
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/subnav.css" rel = "stylesheet">
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
				<a class = "nav-item" href = "#">About</a>
				<a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/stats/stats.php">Stats</a>
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

	<main class = "page">
		<h1>About this Data Source</h1>
		<p>
			This service leverages the Scryfall API, to retrieve and display Magic: The Gathering card data.
			Scryfall is a comprehensive, community-driven database that aggregates information from various sources, including official Wizards of the Coast releases.
		<aside>This service was built with PHP, jQuery, HTML, and CSS.</aside>
		</p>
		<p>
			Key technical details: </p>
		<ul>
			<li>
				<strong>API Endpoint:</strong> Data is fetched via HTTP requests to the Scryfall API endpoints.
			</li>
			<li>
				<strong>Data Format:</strong> The API returns data in JSON format.
			</li>
			<li>
				<strong>Data Scope:</strong> Scryfall's database includes card metadata (names, mana costs, rules text, etc.), pricing information, image URIs, legality information, and more.
			</li>
			<li>
				<strong>Data Freshness:</strong> Scryfall actively updates its database with new releases and
				errata, ensuring relatively up-to-date information. This includes having an even larger, and
				more up-to-date database of cards, even compared to Magic's Creator's own!
			</li>
			<li>
				<strong>API Documentation:</strong> For detailed information on available endpoints and data structures, please refer to the official Scryfall API documentation:
				<a href = "https://scryfall.com/docs/api" target = "_blank">https://scryfall.com/docs/api</a>
			</li>
		</ul>

		<p>
			While Scryfall is a community-driven project, it strives for accuracy and completeness, often surpassing official sources in terms of data richness and accessibility. It is important to remember, that while Scryfall is very accurate, it is not an offical source of Hasbro, and that Hasbro's official database can be found here:
			<a href = "https://gatherer.wizards.com/Pages/Default.aspx" target = "_blank">Hasbro's Official Card Database</a>
		</p>

		<hr>
	</main>

	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
	<script rel = "text/javascript" src = "./about.js"></script>

</body>
</html>