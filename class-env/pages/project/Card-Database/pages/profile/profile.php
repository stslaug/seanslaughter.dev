<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /class-env/pages/project/Card-Database/auth/login.php");
    exit;
}

$servername = "";
$username = "";
$password = "";
$database = "";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT COUNT(card_id) FROM favorites WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
$conn->close();
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
	<title>Profile | Card Database | Sean Slaughter</title>

	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/subnav.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "../stats/stats.css" rel = "stylesheet">

	<style>
		  /* Profile Page Styling */
		  .page
		  {
			  max-width: 1000px;
			  margin: 0 auto;
			  padding: 2rem;
			  background-color: #f7f9fc;
			  border-radius: 12px;
			  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		  }

		  h1, .intro-text
		  {
			  text-align: center;
		  }

		  .stats-grid
		  {
			  display: grid;
			  gap: 25px;
			  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			  margin-top: 3rem;
		  }

		  .stat-card
		  {
			  padding: 20px;
			  background: #ffffff;
			  border-radius: 10px;
			  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
			  transition: transform 0.2s ease-in-out;
			  overflow: hidden;
			  text-overflow: ellipsis;
			  white-space: nowrap;
		  }

		  .stat-number
		  {
			  font-size: 1.25rem;
			  overflow: hidden;
			  text-overflow: ellipsis;
			  white-space: nowrap;
		  }

		  .stat-card:hover
		  {
			  transform: translateY(-5px);
		  }

		  .stat-card h3
		  {
			  font-size: 1.1rem;
			  color: #4a5568;
			  margin-bottom: 10px;
		  }

		  body.dark-mode .page
		  {
			  background-color: rgba(255, 255, 255, 0.05);
		  }

	</style>
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

	<main class = "page" style = "max-width: 80%; min-height: 80vh; ">
		<h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
		<p class = "intro-text">This is your profile page!</p>
		<div class = "stats-grid">
			<div class = "stat-card noptr">
				<h3>Username</h3>
				<p class = "stat-number"><?php echo $_SESSION['username']; ?></p>
			</div>
			<div class = "stat-card noptr">
				<h3>Email</h3>
				<p class = "stat-number"><?php echo $_SESSION['email']; ?></p>
			</div>
			<div class = "stat-card noptr">
				<h3>Joined</h3>
				<p class = "stat-number"><?php echo date("F j, Y | g:i a", strtotime($_SESSION['creation'])); ?></p>
			</div>
			<div class = "stat-card noptr">
				<h3>Last Login</h3>
				<p class = "stat-number"><?php echo date("F j, Y | g:i a", strtotime($_SESSION['last_login'])); ?></p>
			</div>
			<div id = "cardTypes" class = "stat-card noptr">
				<h3>Login Count</h3>
				<p class = "stat-number"><?php echo $_SESSION['login_count']; ?></p>
			</div>
			<div id = "cardTypes" class = "stat-card noptr">
				<h3>Cards Favorited</h3>
				<p class = "stat-number"><?php echo $count; ?></p>
			</div>

		</div>
		<div style = " display: flex; flex-direction: column;" class = "container">
			<h2>Account Controls</h2>
			<div style = "display: flex;margin: auto; gap: 1rem; min-width: 100%;">
				<a class = "btn" href = "change-password.php">Change Password (TODO)</a>
				<a class = "btn" href = "../../auth/logout.php">Wipe Data (TODO)</a>
				<a class = "btn" href = "../../auth/logout.php">Logout</a>
			</div>
		</div>
	</main>

	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>
</html>