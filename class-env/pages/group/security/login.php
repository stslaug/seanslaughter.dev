<?php
session_set_cookie_params([
    'lifetime' => 0, // Cookies expire when browser closes
    'path' => '/', // Cookie available on all pages of site
    'domain' => '', // Cookie only valid on current domain
    'secure' => true, // Only sent over HTTPS
    'httponly' => true, // Prevents JS access to cookies
    'samesite' => 'Lax' // Prevents CSRF attacks
]);

session_start();

// Hardcoded credentials
const USERNAME = 'admin';
$hashed_password = password_hash('password123', PASSWORD_DEFAULT);

// CSRF token is set before form submission
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Login attempts and lockout time
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

$lockout_duration = 5; // Lockout for 5 seconds

// Check if user is locked out
if ($_SESSION['login_attempts'] >= 5) {
    $time_since_lockout = time() - $_SESSION['lockout_time'];
    $time_remaining = $lockout_duration - $time_since_lockout;

    if ($time_remaining > 0) {
        echo "<p style='color:red;'>Too many login attempts. Try again in <span id='countdown'>$time_remaining</span> seconds.</p>";
        echo "<script>
                let timeLeft = $time_remaining;
                function updateCountdown() {
                    if (document.getElementById('countdown')) {
                        document.getElementById('countdown').innerText = timeLeft;
                    }
                    if (timeLeft > 0) {
                        timeLeft--;
                        setTimeout(updateCountdown, 1000);
                    }
                }
                updateCountdown();
              </script>";
        exit;
    } else {
        // Reset login attempts after timeout
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = 0;
    }
}

// CSRF Protection & Login Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed.");
    }

    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    if ($username === USERNAME && password_verify($password, $hashed_password)) {
        session_regenerate_id(true);
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['login_attempts'] = 0;
        header("Location: dashboard.php");
    } else {
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] === 5) {
            $_SESSION['lockout_time'] = time(); // Start lockout timer
        }
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel = "stylesheet" href = "styles.css">
	<link rel = "stylesheet" href = "/styles/general.css">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">
</head>
<body>
	<nav id = "navbar"></nav>
	<main>
		<div class = "containersec">
			<h2>Login</h2>
			<form method = "post" action = "">
				<div class = "input-group">
					<label for = "username">Username:</label>
					<input class = "input" type = "text" name = "username" required>
				</div>
				<div class = "input-group">
					<label for = "password">Password:</label>
					<input class = "input" type = "password" name = "password" required>
				</div>
				<input type = "hidden" name = "csrf_token" value = "<?php echo $_SESSION['csrf_token']; ?>">
				<button class = "btn" type = "submit" style = "width: 100%;">Login</button>
			</form>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
			<h3> Username = admin</h3>
			<h3> Password = password123 </h3>
		</div>
	</main>
	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>

</body>
</html>
