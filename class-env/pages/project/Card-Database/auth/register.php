<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../card-home.php");
    exit();
}

$servername = "127.0.0.1:5522";
$username = "seanljvy_stslaug";
$password = "Darkose123!";
$database = "seanljvy_main_db";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // hash password and not use default for consistency.
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $username, $password, $email);
        if ($stmt->execute()) {
            $message = "<p>🎉 Registration successful! <a href='login.php'>Click here to login</a>.</p>";
        } else {
            $message = "❌ Username already exists. Try another.";
        }
        $stmt->close();
    } else {
        $message = "❌ Something went wrong. Please try again.";
    }
}

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
	<link href = "./card.css" rel = "stylesheet">
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/tables.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">
	<link href = "/styles/subnav.css" rel = "stylesheet">
	<title>Register | Card Database | Sean Slaughter</title>

</head>
<body>
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
				    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/auth/#">Login / Sign Up</a>
                      <?php endif; ?>

			</div>
		</nav>
	</nav>
	<main class = "page" style = "min-height: 100vh; display: flex;">
		<div class = "container-sm" style = "min-width: 400px; margin: 15vh auto auto auto; justify-content: center; align-items: center;">
			<div class = "auth-box">
				<h2>Register</h2>

                      <?php if (!empty($message)): ?>
				    <div style = "width: 100%;" class = "<?php echo str_contains($message, 'successful') ? 'container-sm' : 'error'; ?>">
					    <div>
                                      <?php echo $message; ?>
					    </div>
				    </div>
                      <?php endif; ?>

				<form method = "post">
					<div class = "input-group">
						<input class = "input" type = "text" name = "username" placeholder = "Username" required>
					</div>
					<div class = "input-group">
						<input class = "input" type = "email" name = "email" placeholder = "Email" required>
						<hr>
						<div class = "input-group">
							<input class = "input" type = "password" name = "password" placeholder = "Password" required>
						</div>
						<button class = "btn" type = "submit">Register</button>
				</form>

				<p class = "login-register-link">
					Already have an account? <a href = "login.php">Login here</a>
				</p>
			</div>
		</div>
	</main>

	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>
</html>
