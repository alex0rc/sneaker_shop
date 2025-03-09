<?php
require_once "includes/config.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1>Checkout</h1>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name    = $_POST['customer_name'];
                $email   = $_POST['email'];
                $address = $_POST['address'];
                $cart    = json_decode($_POST['cart'], true);

                // Calcular total
                $total = 0;
                foreach ($cart as $item) {
                    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
                    $stmt->bind_param("i", $item['id']);
                    $stmt->execute();
                    $row  = $stmt->get_result()->fetch_assoc();
                    $price = $row['price'] ?? 0;
                    $total += $price * $item['quantity'];
                }

                // Insertar en orders (si user logueado => user_id, si no => NULL)
                $userId = isset($_SESSION["user_logged_in"]) ? $_SESSION["user_id"] : null;
                $stmt = $conn->prepare("
                    INSERT INTO orders (customer_name, email, address, total_amount, user_id)
                    VALUES (?, ?, ?, ?, ?)
                ");
                // user_id puede ser null => "i" con 0 o null => pasarlo como "is"
                $stmt->bind_param("sssdi", $name, $email, $address, $total, $userId);
                $stmt->execute();
                $orderId = $stmt->insert_id;

                // Insertar items
                foreach ($cart as $item) {
                    $stmtItem = $conn->prepare("
                        INSERT INTO order_items (order_id, product_id, quantity)
                        VALUES (?, ?, ?)
                    ");
                    $stmtItem->bind_param("iii", $orderId, $item['id'], $item['quantity']);
                    $stmtItem->execute();
                }

                echo "<p>¡Gracias por tu compra! Tu pedido se registró con ID: $orderId</p>";
                echo "<script>localStorage.removeItem('cart');</script>";
                exit;
            }
            ?>

            <form method="POST" onsubmit="return sendOrder();">
                <label>Nombre:</label>
                <input type="text" name="customer_name" required />

                <label>Correo:</label>
                <input type="email" name="email" required />

                <label>Dirección:</label>
                <textarea name="address" rows="3" required></textarea>

                <input type="hidden" id="cartData" name="cart" value="">

                <button type="submit">Finalizar Compra</button>
            </form>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/dark-mode.js"></script>
    <script>
    function sendOrder() {
        const cart = getCart();
        if (cart.length === 0) {
            alert("El carrito está vacío.");
            return false;
        }
        document.getElementById('cartData').value = JSON.stringify(cart);
        return true;
    }
    </script>
</body>
</html>
