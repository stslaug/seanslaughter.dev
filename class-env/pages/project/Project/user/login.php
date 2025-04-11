<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'Username', 'Password', 'Databasename');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
                header("Location: index.php");
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
	<title>Login | Pokémon Collection</title>
	<link rel = "stylesheet" href = "../style.css">
</head>
<body>
	<div class = "pokemon-page auth-page">
		<div class = "container">
			<div class = "auth-box">
				<h2>Login</h2>

                      <?php if (!empty($message)): ?>
				    <div class = "error"><?php echo $message; ?></div>
                      <?php endif; ?>

				<form method = "post">
					<div class = "form-group">
						<input type = "text" name = "username" placeholder = "Username" required>
					</div>
					<div class = "form-group">
						<input type = "password" name = "password" placeholder = "Password" required>
					</div>
					<button type = "submit">Login</button>
				</form>

				<p class = "login-register-link">
					Don't have an account? <a href = "register.php">Register here</a>
				</p>
			</div>
		</div>
	</div>
</body>
</html>
