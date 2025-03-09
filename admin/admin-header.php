<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - SHOEE</title>

    <!-- Enlaces a CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="icon" href="assets/img/shoee.ico">

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-left">
                <!-- LOGO, con ruta ../ para salir de /admin/ y buscar en /assets/img/ -->
                <a href="../index.php">
                    <img class="logo" src="../assets/img/shoee.png" alt="Logo SHOEE">
                </a>
                <!-- Enlaces a la parte front, por si el admin quiere volver a la tienda -->
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a class="nav-link" href="../cart.php">
                    <i class="fas fa-shopping-cart"></i> Carrito
                </a>
            </div>
            <div class="nav-right">
            <?php
                // Si admin está logueado
                if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {
                    // Enlaces para panel admin
                    echo '<a class="nav-link" href="dashboard.php"><i class="fas fa-toolbox"></i> Panel Admin</a>';
                    echo '<a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión (Admin)</a>';
                } else {
                    // Si no está logueado, mostrar link a login admin
                    echo '<a class="nav-link" href="login.php"><i class="fas fa-user-shield"></i> Login Admin</a>';
                }
                ?>
                <!-- Botón modo oscuro -->
                <button id="darkModeToggle" class="nav-btn">Modo Oscuro</button>
            </div>
        </nav>
    </header>
</body>
</html>
