<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include "includes/header.php"; ?>
        <div class="main-content">
            <div class="container">
                <h1>Carrito de Compras</h1>
                <div id="cartContainer"></div>

                <a href="checkout.php" class="checkout-link">
                    <button>Proceder al Checkout</button>
                </a>
            </div>
        </div>

    <?php include "includes/footer.php"; ?>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/dark-mode.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        renderCart();
    });
    </script>
</body>
</html>
