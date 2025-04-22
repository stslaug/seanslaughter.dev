<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once __DIR__ . '/../db/db_connect.php';
// Database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['password'] !== $_POST['password2']) {
        $message = "❌ Passwords do not match. Please try again.";
        goto end;
    }

    $username = strtolower($_POST['username']);
    if ($username !== trim($username)) {
        $message = "❌ Username must not include spaces. Please try again.";
        goto end;
    }


    $security_question = $_POST['security-question'];
    if ($security_question === "0") {
        $message = "❌ Please select a security question. Please try again.";
        goto end;
    }


    $security_answer = $_POST['security-answer'];
    $security_answer = password_hash(trim($security_answer), PASSWORD_DEFAULT);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password, security_question, security_answer) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement.");
        }
        $stmt->bind_param("ssss", $username, $password, $security_question, $security_answer);
        $stmt->execute();
        $message = "🎉 Registration successful! <a href='login.php'>Click here to login</a>.";
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Username Error handling
            $message = "❌ Username already exists. Try another.";
        } else {
            $message = "❌ Something went wrong. Please try again. Error: " . $e->getMessage();
        }
    } catch (Exception $e) {
        $message = "❌ Something went wrong. Please try again. Error: " . $e->getMessage();
    }
}
end:

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

	<link href = "./auth-style.css" rel = "stylesheet">

	<style>
		  .fa-solid.fa-eye-slash, .fa-solid.fa-eye
		  {
			  color: white;
		  }
	</style>

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
				    <a class = "btn btn-primary mx-2" href = "../user/login.php">Login / Sign Up</a>
                      <?php endif; ?>
			</div>
		</div>
	</nav>

	<main class = "game-container container-fluid">
		<div class = "auth-box">
			<h2>Register</h2>

                <?php if (!empty($message)): ?>
			    <div style = "color: white;" class = "error"><?php echo $message; ?></div>
                <?php endif; ?>

			<form class = "container" style = "min-width: 450px;" method = "post">
				<div class = "form-group">

					<div>
						<input class = "form-control" type = "text" name = "username" placeholder = "Username" required>
					</div>
					<div class = "pass-div">
						<input class = "form-control" id = "password" type = "password" name = "password" placeholder = "Password" autocomplete = "on" required>
						<i class = "fa-solid fa-eye" id = "togglePassword"></i>
					</div>
					<div class = "pass-div">
						<input class = "form-control" id = "password2" type = "password" name = "password2" placeholder = "Confirm Password" required>
						<i class = "fa-solid fa-eye" id = "togglePassword2"></i>
					</div>

					<div>
						<label for = "security-question">Security Question</label>
						<select class = "form-control" id = "security-question" name = "security-question" required>
							<option selected value = "0">Please select a question</option>
							<option value = "1">What is your first pet's name?</option>
							<option value = "2">What city were you born in?</option>
							<option value = "3">What is your favorite color?</option>
						</select>
						<input class = "form-control" type = "text" name = "security-answer" placeholder = "Answer" required>
					</div>
				</div>

				<button class = "btn btn-primary" type = "submit">Register</button>
			</form>

			<p class = "login-register-link">
				Already have an account? <a href = "login.php">Login here</a>
			</p>
		</div>
	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>

	<script>
          /*
          Password visibility toggle
         */
          let $icon = $(document.getElementById('togglePassword'));
          let $icon2 = $(document.getElementById('togglePassword2'));
          let $password = $(document.getElementById('password'));
          let $password2 = $(document.getElementById('password2'));

          /* Event fired when <i> is clicked */
          $icon.on('click', function () {
              if ($password.attr('type') === 'password') {
                  $password.attr('type', 'text');
                  $icon.addClass("fa-eye-slash");
                  $icon.removeClass("fa-eye");
              } else {
                  $password.attr('type', 'password');
                  $icon.addClass("fa-eye");
                  $icon.removeClass("fa-eye-slash");
              }
          });

          $icon2.on('click', function () {
              if ($password2.attr('type') === 'password') {
                  $password2.attr('type', 'text');
                  $icon2.addClass("fa-eye-slash");
                  $icon2.removeClass("fa-eye");
              } else {
                  $password2.attr('type', 'password');
                  $icon2.addClass("fa-eye");
                  $icon2.removeClass("fa-eye-slash");
              }
          });
	</script>
</body>
</html>
