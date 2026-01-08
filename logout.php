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
?>
<script>
    // FORCE LOGOUT ACROSS ALL TABS
    localStorage.setItem('force-logout', Date.now());

    // Redirect after broadcast
    window.location.href = "https://localhost/marudham_capitals/";
</script>