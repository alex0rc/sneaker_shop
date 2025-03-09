<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "includes/config.php";

// Verificar si está logueado como usuario
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: login-user.php");
    exit;
}

// Seleccionar pedidos de este user_id
$userId = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1>Mis Pedidos</h1>

            <?php if ($orders->num_rows === 0): ?>
                <p>No tienes pedidos aún.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                    </tr>
                    <?php while($o = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $o['id']; ?></td>
                        <td><?php echo $o['order_date']; ?></td>
                        <td><?php echo $o['total_amount']; ?> €</td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
    <script src="assets/js/dark-mode.js"></script>
</body>
</html>
