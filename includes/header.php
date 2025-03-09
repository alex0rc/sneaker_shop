<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$prefix = (basename(__DIR__) === 'admin') ? '../' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SHOEE - <?php echo (basename(__DIR__) === 'admin') ? 'Panel Admin' : 'Tienda'; ?></title>
    <!-- CSS principal y Dark Mode-->
    <link rel="stylesheet" href="<?php echo $prefix; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo $prefix; ?>assets/css/dark-mode.css">
    <link rel="icon" href="assets/img/shoee.ico">


    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-left">
                <!-- LOGO -->
                <a href="<?php echo $prefix; ?>index.php">
                    <img class="logo" src="<?php echo $prefix; ?>assets/img/shoee.png" alt="Logo SHOEE">
                </a>
                <!-- Enlace a la home (fuera o dentro de admin) -->
                <a class="nav-link" href="<?php echo $prefix; ?>index.php">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <!-- Carrito -->
                <a class="nav-link" href="<?php echo $prefix; ?>cart.php">
                    <i class="fas fa-shopping-cart"></i> Carrito
                </a>
            </div>

            <div class="nav-right">
                <?php
                // Admin logueado
                if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {
                    echo '<a class="nav-link" href="admin/dashboard.php"><i class="fas fa-toolbox"></i> Panel Admin</a>';
                    echo '<a class="nav-link" href="admin/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión (Admin)</a>';
                }
                // Usuario logueado
                elseif (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) {
                    echo '<a class="nav-link" href="'.$prefix.'mispedidos.php"><i class="fas fa-box-open"></i> Mis Pedidos</a>';
                    echo '<a class="nav-link" href="'.$prefix.'logout-user.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión (Usuario)</a>';
                }
                // Nadie logueado
                else {
                    echo '<a class="nav-link" href="'.$prefix.'register.php"><i class="fas fa-user-plus"></i> Crear Cuenta</a>';
                    echo '<a class="nav-link" href="'.$prefix.'login-user.php"><i class="fas fa-user"></i> Login Usuario</a>';
                    echo '<a class="nav-link" href="'.$prefix.'admin/login.php"><i class="fas fa-user-shield"></i> Login Admin</a>';
                }
                ?>
                <button id="darkModeToggle" class="nav-btn">Modo Oscuro</button>
            </div>
        </nav>
    </header>
</body>
</html>