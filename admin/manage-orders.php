<?php
require_once "../includes/auth-check.php";
require_once "../includes/config.php";

// ELIMINAR PEDIDO
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];

    // Primero borramos sus items
    $conn->query("DELETE FROM order_items WHERE order_id = $delId");

    // Luego borramos el pedido
    $stmtDel = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmtDel->bind_param("i", $delId);
    $stmtDel->execute();

    header("Location: manage-orders.php");
    exit;
}

// EDITAR PEDIDO (cargar datos)
$editingOrder = null;
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    $stmtEdit = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmtEdit->bind_param("i", $editId);
    $stmtEdit->execute();
    $resEdit = $stmtEdit->get_result();
    if ($resEdit->num_rows === 1) {
        $editingOrder = $resEdit->fetch_assoc();
    }
}

// ACTUALIZAR PEDIDO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $oId          = (int)$_POST['order_id'];
    $customerName = $_POST['customer_name'] ?? '';
    $email        = $_POST['email']         ?? '';
    $address      = $_POST['address']       ?? '';
    $totalAmount  = $_POST['total_amount']  ?? 0;

    $stmtUp = $conn->prepare("
        UPDATE orders
        SET customer_name = ?, email = ?, address = ?, total_amount = ?
        WHERE id = ?
    ");
    $stmtUp->bind_param("sssdi", $customerName, $email, $address, $totalAmount, $oId);
    $stmtUp->execute();

    header("Location: manage-orders.php");
    exit;
}

// LISTADO DE PEDIDOS
$orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Pedidos</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
</head>
<body>
    <?php include "admin-header.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1>Gestionar Pedidos</h1>

            <!-- Formulario de edición (si corresponde) -->
            <?php if ($editingOrder): ?>
                <h2>Editar Pedido (ID: <?php echo $editingOrder['id']; ?>)</h2>
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $editingOrder['id']; ?>">

                    <label>Nombre del Cliente:</label>
                    <input type="text" name="customer_name" required
                        value="<?php echo htmlspecialchars($editingOrder['customer_name']); ?>">

                    <label>Correo:</label>
                    <input type="email" name="email" required
                        value="<?php echo htmlspecialchars($editingOrder['email']); ?>">

                    <label>Dirección:</label>
                    <textarea name="address"><?php echo htmlspecialchars($editingOrder['address']); ?></textarea>

                    <label>Monto Total:</label>
                    <input type="number" step="0.01" name="total_amount"
                        value="<?php echo htmlspecialchars($editingOrder['total_amount']); ?>">

                    <button type="submit">Actualizar Pedido</button>
                </form>
            <?php endif; ?>

            <!-- Listado de todos los pedidos -->
            <h2 class="mt-20">Lista de Pedidos</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Correo</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
                <?php while($o = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo $o['customer_name']; ?></td>
                    <td><?php echo $o['email']; ?></td>
                    <td><?php echo $o['total_amount']; ?> €</td>
                    <td><?php echo $o['order_date']; ?></td>
                    <td>
                        <a class="btn-green" href="manage-orders.php?edit_id=<?php echo $o['id']; ?>">Editar</a>
                        <a class="btn-red" href="manage-orders.php?delete_id=<?php echo $o['id']; ?>" onclick="return confirm('¿Eliminar este pedido?');">Borrar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <?php include "../includes/footer.php"; ?>
    <script src="../assets/js/dark-mode.js"></script>
</body>
</html>
