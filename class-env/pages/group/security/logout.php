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
// CSRF Protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed.");
    }
}

// Destroy session and cookies
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, // Cookie expiration to 1 hour
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header("Location: login.php");
exit;
?>