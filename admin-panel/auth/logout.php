<?php

session_start();

// All Session Remove

session_unset();

// Session Destroy

session_destroy();

// Redirect Login Page

header("Location: login.php");
exit;

?>