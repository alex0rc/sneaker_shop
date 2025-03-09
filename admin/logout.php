<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy(); // Destruimos toda la sesión
header("Location: login.php"); // Redirigimos al login
exit;
?>
