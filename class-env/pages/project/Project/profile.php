<?php
session_start();

require_once __DIR__ . '/db/db_connect.php';
require_once __DIR__ . '/db/queries.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("You must be logged in to view your profile.");
}


// Get user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$stmt = $conn->prepare("SELECT COUNT(id) as total FROM games WHERE host_user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$games_hosted = $stmt->get_result()->fetch_assoc();

if (!$games_hosted) {
    die("Games Hosted not found.");
}
$stmt = $conn->prepare("SELECT COUNT(game_id) as total FROM game_players WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$games_played = $stmt->get_result()->fetch_assoc();

if (!$games_played) {
    die("Games Played not found.");
}

/* Votes made */

$stmt = $conn->prepare("SELECT COUNT(id) AS total FROM votes WHERE voter_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$votes_made = $stmt->get_result()->fetch_assoc();

if (!$votes_made) {
    die("Votes Made not found.");
}

$stmt = $conn->prepare("SELECT COUNT(id) AS total FROM votes WHERE vote_for_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$votes_received = $stmt->get_result()->fetch_assoc();

if (!$votes_received) {
    die("Votes Received not found.");
}

?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "utf-8">
	<meta content = "width=device-width, initial-scale=1, shrink-to-fit=no" name = "viewport">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">
	<title>Profile | Most Likely To</title>
	<link href = "./css/profile.css" rel = "stylesheet">

</head>
<body id = "game-body">
	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid"></div>
	</nav>

	<main class = "game-container">

		<h1>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
		<h2>Profile</h2>
		<p>Welcome to your profile page! Here you can track your stats and log out!</p>

		<section class = "profile-stats">
			<!--
                  Stats to do
                  - Total Votes gave
                  - Total Votes received
                  - Total Questions
                  - Total Games Hosted
                  - Total Games Played
                  - Favorite Nickname
                  - Last Login Time
                  - Account Creation Time
              -->

			<h3>Stats</h3>
			<ul class = "stats-list">
				<li>Total Votes Given: <strong><?= $votes_made['total'] ?></strong></li>
				<li>Total Votes Received: <strong><?= $votes_received['total'] ?></strong></li>
				<li>Total Games Hosted: <strong><?= $games_hosted['total'] ?></strong></li>
				<li>Total Games Played: <strong><?= $games_played['total'] ?></strong></li>
				</li>
				<li>Last Login Time:
					<strong class = "long-date"><?= date("F j, Y, g:i a", strtotime($user['last_login'])) ?></strong>
				</li>
				<li>Account Creation Time:
					<strong class = "long-date"><?= date("F j, Y", strtotime($user['created_at'])) ?></strong>
			</ul>
		</section>

		<section class = "profile-options">
			<h3>Options</h3>
			<ul>
				<li><a href = "user/logout.php">Logout</a></li>
				<!-- Maybe we can add question set deletion/modification here?-->
			</ul>

		</section>
	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>

</body>