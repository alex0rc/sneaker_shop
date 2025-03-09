<?php
require_once "includes/config.php";


$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("
    SELECT p.*, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<div class='container'><p>Producto no encontrado.</p></div>";
    exit;
}

// Imagen BLOB o por defecto
if (!empty($product['image_data'])) {
    $imageSrc = "data:image/jpeg;base64," . base64_encode($product['image_data']);
} else {
    $imageSrc = "assets/img/no-image.jpg";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1><?php echo $product['name']; ?></h1>
            <div class="product-details-box">
            <img class="product-detail-img"
                src="<?php echo $imageSrc; ?>"
                alt="<?php echo $product['name']; ?>" />

            <div class="product-info">
                <h2><?php echo $product['name']; ?></h2>
                <ul>
                    <li><strong>Marca:</strong> <?php echo $product['brand_name']; ?></li>
                    <li><strong>Precio:</strong> <?php echo $product['price']; ?> €</li>
                    <li><strong>Descripción:</strong> <?php echo $product['description']; ?></li>
                </ul>

                <label>Talla:</label>
                <select>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                </select>
                <br><br>
                <button onclick="addToCart(
                    <?php echo $product['id']; ?>,
                    '<?php echo addslashes($product['name']); ?>',
                    '<?php echo $imageSrc; ?>'
                )">Agregar al Carrito</button>
            </div>
        </div>

        </div>
    </div>

    <?php include "includes/footer.php"; ?>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/dark-mode.js"></script>
    <script>
    function addToCart(id, name, image) {
        addProductToCart(id, name, image, 1);
        alert("Producto agregado al carrito");
    }
    </script>
</body>
</html>
