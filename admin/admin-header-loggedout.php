<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login de Administrador</title>
    <!-- CSS principal y Dark Mode -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="icon" href="../assets/img/shoee.ico">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-left">
                <!-- LOGO (ruta con ../ para llegar a assets/ ) -->
                <a href="../index.php">
                    <img class="logo" src="../assets/img/shoee.png" alt="Logo SHOEE">
                </a>
                <!-- Enlaces al front -->
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a class="nav-link" href="../cart.php">
                    <i class="fas fa-shopping-cart"></i> Carrito
                </a>
            </div>
            <div class="nav-right">
                <a class="nav-link" href="../register.php"><i class="fas fa-user-plus"></i> Crear Cuenta</a>
                <a class="nav-link" href="../login-user.php"><i class="fas fa-user"></i> Login Usuario</a>
                <a class="nav-link" href="login.php"><i class="fas fa-user-shield"></i> Login Admin</a>

                <button id="darkModeToggle" class="nav-btn">Modo Oscuro</button>
            </div>
        </nav>
    </header>
</body>
</html>