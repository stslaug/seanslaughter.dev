<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ./../index.php");
    exit();
}

require_once __DIR__ . '/../db/db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $passwordInput = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $hashedPassword);

        if ($stmt->num_rows === 1) {
            $stmt->fetch();
            if (password_verify($passwordInput, $hashedPassword)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                header("Location: ../index.php");
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

	<title>Login | Most Likely</title>

	<link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel = "stylesheet">
	<link href = "../css/style.css" rel = "stylesheet">
	<link href = "../css/u_style.css" rel = "stylesheet">
	<link href = "./auth-style.css" rel = "stylesheet">

</head>
<body>
	<nav>
		<div id = "navbar-container"></div>

		<div id = "game-nav" class = "container-fluid">
			<div class = "d-flex justify-content-end py-2">
				<a class = "btn btn-primary mx-2" href = "../game-landing.php">The Game</a>
				<a class = "btn btn-primary mx-2" href = "../index.php">Proposal</a>
                      <?php if (isset($_SESSION['username'])): ?>
				    <a class = "btn btn-primary mx-2" href = "../settings.html">Profile</a>
				    <a class = "btn btn-primary mx-2" href = "logout.php">Logout</a>
                      <?php else: ?>
				    <a class = "btn btn-primary mx-2" href = "../user/register.php">Login / Sign Up</a>
                      <?php endif; ?>
			</div>
		</div>
	</nav>

	<main class = "game-container container-fluid">
		<div class = "auth-box">
			<h2>Login</h2>

                <?php if (!empty($message)): ?>
			    <div class = "error"><?php echo $message; ?></div>
                <?php endif; ?>

			<form method = "post">
				<div>
					<input class = "form-control" type = "text" name = "username" placeholder = "Username" required>
				</div>
				<div>
					<input class = "form-control" type = "password" name = "password" placeholder = "Password" required>
				</div>
				<button class = "btn btn-primary" type = "submit">Login</button>
			</form>

			<p class = "login-register-link">
				Don't have an account? <a href = "register.php">Register here</a>
			</p>
		</div>
	</main>

	<footer id = "footer"></footer>
	<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>
</html>
