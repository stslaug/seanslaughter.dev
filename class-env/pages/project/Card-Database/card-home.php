<?php
session_start();

?>
<script>
    const isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
</script>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">
	<meta content = "width=device-width, initial-scale=1.0" name = "viewport">
	<meta content = "Sean Tyler Slaughter" name = "author">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "any" type = "image/svg+xml">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "16x16" type = "image/svg+xml">
	<title> Card API Home | Sean Slaughter</title>
	<link href = "/styles/subnav.css" rel = "stylesheet">
	<link href = "./card.css" rel = "stylesheet">
	<link href = "./card-popup.css" rel = "stylesheet">
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/tables.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">

</head>
<body class = "cardBody">
	<nav>
		<!--	Triple navigation	-->
		<nav id = "navbar"></nav>
		<nav class = "subnav">
			<h2>Magic the Gathering Card Viewer | Utilizing
				<a href = "https://scryfall.com/docs/api" target = "_blank">Scryfall API</a></h2>
			<div>
				<a class = "nav-item" href = "#">Cards</a>
				<a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/about/about.php">About</a>
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
	<form class = "sidebar" id = "cardSearchForm">
		<!--	Gather Query Parameters		-->
		<h2>Sort and Filter</h2>
		<div class = "input-group">
			<label for = "cardName">Card Name</label>
			<input class = "input" id = "cardName" placeholder = "ex. Rhystic Study" type = "text">
		</div>

		<div class = "input-group">
			<label for = "typeLine">Type Line</label>
			<input class = "input" id = "typeLine" placeholder = "ex. 'Creature' or 'Human Wizard'" type = "text">
		</div>

		<div class = "input-group">
			<label>Colors</label>
			<div class = "checkbox-group">
				<div class = "checkbox-item">
					<input class = "input" id = "color-W" name = "colors[]" type = "checkbox" value = "W">
					<label for = "color-W">White</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "color-U" name = "colors[]" type = "checkbox" value = "U">
					<label for = "color-U">Blue</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "color-B" name = "colors[]" type = "checkbox" value = "B">
					<label for = "color-B">Black</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "color-R" name = "colors[]" type = "checkbox" value = "R">
					<label for = "color-R">Red</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "color-G" name = "colors[]" type = "checkbox" value = "G">
					<label for = "color-G">Green</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "color-C" name = "colors[]" type = "checkbox" value = "C">
					<label for = "color-C">Colorless</label>
				</div>
			</div>
			<div class = "input-group">
				<label for = "color-match-type">Color Selection Type</label>
				<select class = "input" id = "color-match-type" name = "colorsMatchType">
					<option value = ">=">Including these colors</option>
					<option value = "<=">At most these Colors</option>
					<option value = "=">Exactly these colors</option>
				</select>
			</div>
		</div>

		<div class = "input-group">
			<label for = "converted-manaCost">Converted Mana Cost (CMC)</label>
			<input class = "input" id = "converted-manaCost" min = "0" placeholder = "ex. 3, 5" type = "text">
		</div>

		<div class = "input-group">
			<label for = "format">Format / Card Legality</label>
			<select class = "input" id = "format">
				<option selected value = "commander">Commander</option>
				<option value = "standard">Standard</option>
				<option value = "future">Future Standard</option>
				<option value = "historic">Historic</option>
				<option value = "timeless">Timeless</option>
				<option value = "gladiator">Gladiator</option>
				<option value = "pioneer">Pioneer</option>
				<option value = "explorer">Explorer</option>
				<option value = "modern">Modern</option>
				<option value = "legacy">Legacy</option>
				<option value = "pauper">Pauper</option>
				<option value = "vintage">Vintage</option>
				<option value = "penny">Penny Dreadful</option>
				<option value = "oathbreaker">Oathbreaker</option>
				<option value = "brawl">Standard Brawl</option>
				<option value = "brawl_historic">Brawl</option>
				<option value = "alchemy">Alchemy</option>
				<option value = "pauper_commander">Pauper Commander</option>
				<option value = "duel">Duel Commander</option>
				<option value = "oldschool">Old School 93/94</option>
			</select>
		</div>
		<div class = "input-group" id = "commanderGroup" style = "transition:
			opacity
			0.3s ease;"
		>
			<label>Commander</label>
			<div class = "checkbox-group">
				<div class = "checkbox-item">
					<input class = "input" id = "commander-W" name = "commander[]" type = "checkbox" value = "W">
					<label for = "commander-W">White</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "commander-U" name = "commander[]" type = "checkbox" value = "U">
					<label for = "commander-U">Blue</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "commander-B" name = "commander[]" type = "checkbox" value = "B">
					<label for = "commander-B">Black</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "commander-R" name = "commander[]" type = "checkbox" value = "R">
					<label for = "commander-R">Red</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "commander-G" name = "commander[]" type = "checkbox" value = "G">
					<label for = "commander-G">Green</label>
				</div>
				<div class = "checkbox-item">
					<input class = "input" id = "commander-C" name = "commander[]" type = "checkbox" value = "C">
					<label for = "commander-C">Colorless</label>
				</div>
			</div>
		</div>
		<div class = "input-group">
			<label for = "setName">Set</label>
			<input class = "input" id = "setName" placeholder = "ex. MAR, DFT" type = "text">
		</div>
		<button class = "btn" id = "searchButton" type = "submit">Search</button>
		<p id = "totalCards" style = "text-align: center"></p>
	</form>
	</nav>
	<main>

		<div class = "page" id = "card-viewer">
			<div style = " margin: auto;text-align: center;">
				<h2>Make a search to view some cards!</h2>
				<p>Vague searches may take a moment to load!</p>
			</div>

		</div>
	</main>
	<div class = "hidden" id = "card-popup"></div>

	<footer id = "footer"></footer>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
	<script rel = "text/javascript" src = "./card.js"></script>
	<script rel = "text/javascript" src = "pages/favorites/favorites.js"></script>

</body>
</html>