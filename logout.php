<?php
session_start(); // Start the session to access and destroy it

// Destroy session data
session_unset(); 
session_destroy();

// Redirect the user to the login or home page
header("Location: https://localhost/marudham_capitals/");
exit();
?>