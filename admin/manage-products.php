<?php
// CONEXION PDO
$host = 'localhost';
$dbname = 'sneaker_shop';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// ELIMINAR PRODUCTO
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindParam(':id', $delId, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: manage-products.php");
    exit;
}

//EDITAR PRODUCTO (CARGAR DATOS)
$editingProduct = null;
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
    $stmt->execute();
    $editingProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}

// PROCESAR FORM (CREAR O EDITAR)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $_POST['name']        ?? '';
    $price       = $_POST['price']       ?? 0;
    $description = $_POST['description'] ?? '';
    $brand_id    = $_POST['brand_id']    ?? null;

    // Manejo de imagen
    $imageData = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    // ¿Edición o creación?
    if (!empty($_POST['product_id'])) {
        // EDICIÓN
        $pId = (int)$_POST['product_id'];

        if ($imageData === null) {
            // Mantener imagen anterior
            $stmt = $pdo->prepare("
                UPDATE products
                SET name = :name, price = :price, description = :desc, brand_id = :brand
                WHERE id = :id
            ");
            $stmt->bindParam(':name',  $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':desc',  $description);
            $stmt->bindParam(':brand', $brand_id, PDO::PARAM_INT);
            $stmt->bindParam(':id',    $pId, PDO::PARAM_INT);
        } else {
            // Actualizar imagen
            $stmt = $pdo->prepare("
                UPDATE products
                SET name = :name, price = :price, description = :desc, brand_id = :brand, image_data = :img
                WHERE id = :id
            ");
            $stmt->bindParam(':name',  $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':desc',  $description);
            $stmt->bindParam(':brand', $brand_id, PDO::PARAM_INT);
            $stmt->bindParam(':img',   $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':id',    $pId, PDO::PARAM_INT);
        }
        $stmt->execute();
    } else {
        // CREACIÓN
        $stmt = $pdo->prepare("
            INSERT INTO products (name, price, description, brand_id, image_data)
            VALUES (:name, :price, :desc, :brand, :img)
        ");
        $stmt->bindParam(':name',  $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':desc',  $description);
        $stmt->bindParam(':brand', $brand_id, PDO::PARAM_INT);
        $stmt->bindParam(':img',   $imageData, PDO::PARAM_LOB);
        $stmt->execute();
    }

    header("Location: manage-products.php");
    exit;
}

// LISTAR PRODUCTOS Y MARCAS
$brandsStmt = $pdo->query("SELECT id, name FROM brands ORDER BY name");
$brands = $brandsStmt->fetchAll(PDO::FETCH_ASSOC);

$productsStmt = $pdo->query("
    SELECT p.*, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.id
    ORDER BY p.id DESC
");
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Productos (PDO)</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
</head>
<body>
    <?php include "admin-header.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1>Gestionar Productos (PDO)</h1>

            <?php if ($editingProduct): ?>
                <!-- FORM EDITAR PRODUCTO -->
                <h2>Editar Producto (ID: <?php echo $editingProduct['id']; ?>)</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" value="<?php echo $editingProduct['id']; ?>">

                    <label>Nombre:</label>
                    <input type="text" name="name" required
                        value="<?php echo htmlspecialchars($editingProduct['name']); ?>">

                    <label>Precio:</label>
                    <input type="number" step="0.01" name="price" required
                        value="<?php echo htmlspecialchars($editingProduct['price']); ?>">

                    <label>Marca:</label>
                    <select name="brand_id">
                        <option value="">--Sin marca--</option>
                        <?php foreach($brands as $b): ?>
                            <option value="<?php echo $b['id']; ?>"
                                <?php if($editingProduct['brand_id'] == $b['id']) echo 'selected'; ?>>
                                <?php echo $b['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Descripción:</label>
                    <textarea name="description"><?php echo htmlspecialchars($editingProduct['description']); ?></textarea>

                    <label>Imagen (opcional):</label>
                    <input type="file" name="image" accept="image/*">
                    <p style="font-size:14px;color:gray;">
                        (Deja en blanco para mantener la imagen actual)
                    </p>

                    <button type="submit">Actualizar Producto</button>
                </form>
            <?php else: ?>
                <!-- FORM CREAR PRODUCTO -->
                <h2>Crear Nuevo Producto</h2>
                <form method="POST" enctype="multipart/form-data">
                    <label>Nombre:</label>
                    <input type="text" name="name" required>

                    <label>Precio:</label>
                    <input type="number" step="0.01" name="price" required>

                    <label>Marca:</label>
                    <select name="brand_id">
                        <option value="">--Sin marca--</option>
                        <?php foreach($brands as $b): ?>
                            <option value="<?php echo $b['id']; ?>"><?php echo $b['name']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Descripción:</label>
                    <textarea name="description"></textarea>

                    <label>Imagen (opcional):</label>
                    <input type="file" name="image" accept="image/*">

                    <button type="submit">Crear Producto</button>
                </form>
            <?php endif; ?>

            <h2 class="mt-20">Productos Existentes</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo $p['name']; ?></td>
                    <td><?php echo $p['brand_name'] ?: ''; ?></td>
                    <td><?php echo $p['price']; ?> €</td>
                    <td>
                        <a class="btn-green" href="manage-products.php?edit_id=<?php echo $p['id']; ?>">Editar</a>
                        <a class="btn-red" href="manage-products.php?delete_id=<?php echo $p['id']; ?>"onclick="return confirm('¿Eliminar este producto?');">Borrar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

<?php include "../includes/footer.php"; ?>
<script src="../assets/js/dark-mode.js"></script>
</body>
</html>
