<?php
session_start();
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "utf-8">
	<meta content = "width=device-width, initial-scale=1, shrink-to-fit=no" name = "viewport">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">
	<title>Profile | Most Likely To</title>

</head>
<body id = "game-body">
	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid">

		</div>
	</nav>

	<main class = "game-container" style = "max-width: 1000px;">

		<h1>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
		<h2>Profile</h2>
		<p>Here is your profile placeholder! Isn't this excited? There are so many possibilities!</p>
	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>

</body>