<?php
require_once "../includes/auth-check.php"; // Verifica si es admin
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
</head>
<body>
    <?php include "admin-header.php"; ?>

        <div class="main-content">
            <div class="container">
                <h1>Bienvenido al Panel de Administración</h1>
                <p>Elige una opción:</p>

                <!-- Bloques o "botones" grandes para atajos -->
                <div class="admin-buttons">
                    <a class="admin-button" href="manage-products.php">Productos</a>
                    <a class="admin-button" href="manage-orders.php">Pedidos</a>
                    <a class="admin-button" href="manage-brands.php">Marcas</a>
                    <a class="admin-button" href="logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>

    <?php include "../includes/footer.php"; ?>
    <script src="../assets/js/dark-mode.js"></script>
</body>
</html>
