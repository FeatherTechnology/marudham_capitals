<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];

// Destroy the session file on server
session_destroy();

// Remove the session cookie from the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect the user
header("Location: https://localhost/marudham_capitals/");
exit();
?>