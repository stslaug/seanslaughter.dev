<?php
session_set_cookie_params([
    'lifetime' => 0, // Cookies Expire when browser is closed
    'path' => '/', // Cookie available on all pages of site
    'domain' => '', // Cookie only valid on current Domain
    'secure' => true, //Cookie only sent over HTTPS
    'httponly' => true, // Reduces XSS attacks by preventing JS access to Cookies
    'samesite' => 'Lax' // Prevent CSRF attacks by only allowing same sit requests
]);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
	<link rel = "stylesheet" href = "styles.css">
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">
</head>
<body>
	<nav id = "navbar"></nav>
	<main>
		<div class = "containersec">
			<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
			<button class = "btn" onclick = "window.location.href='logout.php'">Logout</button>
		</div>

	</main>
	<footer id = "footer"></footer>

	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>

</body>
</html>