<?php


/*
 *
 * This is the login system for the card database
 *
 *  What I need to do:
 *      - Create a login form
 *         - The password should be hashed and salted
 *      - Create a database connection
 *          - Create Table
 *          - Create Connection
 *          - Post Request to Database
 *      - Ensure user login persists through pages
 *      - Create a login/logout button
 *
 *  https://codepen.io/mamislimen/pen/jOwwLvy
 *     I like this design
 *
 *      - Forgot Password Page/Button.. integrate with login page.
 *
 *      - Create a favorites button
 *          - Create a connection
 *          - Post Request to Database
 *          - Stores the cards and timestamps
 *
 *      - Create a Profile page
 *          - Displays the user's favorites
 *          - Displays the user's information
 *          - Displays the user's security question NOT ANSWER
 *
 *
 *
 */


error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../card-home.php");
    exit();
}
$servername = "";
$username = "";
$password = "";
$database = "";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $passwordInput = $_POST['password'];


    $stmt = $conn->prepare("SELECT user_id, password, email, creationTimestamp, last_login, login_count FROM users WHERE username = ?");

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $hashedPassword, $email, $creation, $lastLogin, $loginCount);

        if ($stmt->num_rows === 1) {
            $stmt->fetch();
            if (password_verify($passwordInput, $hashedPassword)) {

                $stmt = $conn->prepare("UPDATE users 
                        SET login_count = login_count + 1, 
                            last_login = CURRENT_TIMESTAMP 
                        WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['creation'] = $creation;
                $_SESSION['last_login'] = $lastLogin;
                $_SESSION['login_count'] = $loginCount + 1;

                header("Location: ../card-home.php");
                exit();
            } else {
                $message = "❌ Invalid password. Please try again.";
            }
        } else {
            $message = "❌ Username not found.";
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
	<title> Login | Card API Home | Sean Slaughter</title>

	<link href = "/styles/subnav.css" rel = "stylesheet">
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">

	<title>Login | Card Database | Sean Slaughter</title>

</head>
<body>
	<nav>
		<!--	Triple navigation	-->
		<nav id = "navbar"></nav>
		<nav class = "subnav">
			<h2>Magic the Gathering Card Viewer | Utilizing
				<a href = "https://scryfall.com/docs/api" target = "_blank">Scryfall API</a></h2>
			<div>
				<a class = "nav-item" href = "../card-home.php">Cards</a>
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
	<main class = "page" style = "min-height: 80vh; display: flex;">
		<div class = "container-sm" style = "min-width: 400px; margin: 15vh auto auto auto; justify-content: center; align-items: center;">
			<div class = "auth-box">
				<h2>Login</h2>
                      <?php if (!empty($message)): ?>
				    <div class = "error"><?php echo $message; ?></div>
                      <?php endif; ?>

				<form method = "post">
					<div class = "input-group">
						<input class = "input" type = "text" name = "username" placeholder = "Username" required>
					</div>
					<div class = "input-group">
						<input class = "input" type = "password" name = "password" placeholder = "Password" required>
					</div>
					<button class = "btn" type = "submit">Login</button>
				</form>

				<p class = "login-register-link">
					Don't have an account? <a href = "register.php">Register here</a>
				</p>
			</div>
		</div>
	</main>

	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>
</html>
