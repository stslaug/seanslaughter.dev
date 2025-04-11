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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $message = "🎉 Registration successful! <a href='login.php'>Click here to login</a>.";
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
	<title>Register | Pokémon Collection</title>
	<link rel = "stylesheet" href = "../style.css">
</head>
<body>
	<div class = "pokemon-page auth-page">
		<div class = "container">
			<div class = "auth-box">
				<h2>Register</h2>

                      <?php if (!empty($message)): ?>
				    <div class = "<?php echo str_contains($message, 'successful') ? 'info' : 'error'; ?>">
                                <?php echo $message; ?>
				    </div>
                      <?php endif; ?>

				<form method = "post">
					<div class = "form-group">
						<input type = "text" name = "username" placeholder = "Username" required>
					</div>
					<div class = "form-group">
						<input type = "password" name = "password" placeholder = "Password" required>
					</div>
					<button type = "submit">Register</button>
				</form>

				<p class = "login-register-link">
					Already have an account? <a href = "login.php">Login here</a>
				</p>
			</div>
		</div>
	</div>
</body>
</html>
