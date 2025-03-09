<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION["user_logged_in"]);
unset($_SESSION["user_id"]);
unset($_SESSION["user_name"]);

// Redirigir a la página principal
header("Location: index.php");
exit;
