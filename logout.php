<?php
// Start the session
session_start();

// Destroy all session data
session_destroy();

// Redirect to the login page
header("Location: login.html");
exit; // Make sure no code is executed after the redirect
?>
