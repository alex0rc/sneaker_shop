<?php
require_once "includes/config.php";

$search      = $_GET['search']     ?? '';
$brandFilter = $_GET['brand_id']   ?? '';
$sortOption  = $_GET['sort']       ?? '';

// Construimos la consulta base
$sql = "
    SELECT p.*, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE 1=1
";

// Filtro de búsqueda
if (!empty($search)) {
    $searchEsc = $conn->real_escape_string($search);
    $sql .= " AND p.name LIKE '%{$searchEsc}%'";
}

// Filtro por marca
if (!empty($brandFilter)) {
    $sql .= " AND p.brand_id = " . (int)$brandFilter;
}

// Manejo del ORDER BY según sort
$orderByClause = "p.id DESC"; // por defecto
switch ($sortOption) {
    case 'price_asc':
        $orderByClause = "p.price ASC";
        break;
    case 'price_desc':
        $orderByClause = "p.price DESC";
        break;
    case 'name_asc':
        $orderByClause = "p.name ASC";
        break;
    case 'name_desc':
        $orderByClause = "p.name DESC";
        break;
    default:
        $orderByClause = "p.id DESC";
        break;
}

$sql .= " ORDER BY $orderByClause";
$result = $conn->query($sql);

// Obtener marcas para <select>
$brandsResult = $conn->query("SELECT * FROM brands ORDER BY name");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SHOEE LIBRARY - Inicio</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="main-content">
    <!-- Banner de Bienvenida -->
        <div class="banner">
            <h2>¡Bienvenido a SHOEE!</h2>
            <p>Encuentra tu calzado ideal con nuestros últimos modelos y marcas.</p>
        </div>

        <div class="container">
            <h1>Tienda de Calzado</h1>

            <!-- FORM para búsqueda -->
            <form method="GET" style="margin-bottom:20px;">
                <label>Buscar producto:</label>
                <input type="text" name="search" placeholder="Ej. Nike..."
                    value="<?php echo htmlspecialchars($search); ?>" />

                <label>Marca:</label>
                <select name="brand_id">
                    <option value="">Todas las marcas</option>
                    <?php while($b = $brandsResult->fetch_assoc()): ?>
                        <option value="<?php echo $b['id']; ?>"
                            <?php if($brandFilter == $b['id']) echo 'selected'; ?>>
                            <?php echo $b['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Filtrar</button>
            </form>

            <!-- FORM aparte para ordenar (separado) -->
            <form method="GET" style="margin-bottom:30px;">
                <!-- Conservamos la búsqueda y marca si existen -->
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>" />
                <input type="hidden" name="brand_id" value="<?php echo htmlspecialchars($brandFilter); ?>" />

                <label>Ordenar por:</label>
                <select name="sort">
                    <option value=""           <?php if($sortOption === '')          echo 'selected'; ?>>Por defecto</option>
                    <option value="price_asc"  <?php if($sortOption === 'price_asc')  echo 'selected'; ?>>Precio (menor a mayor)</option>
                    <option value="price_desc" <?php if($sortOption === 'price_desc') echo 'selected'; ?>>Precio (mayor a menor)</option>
                    <option value="name_asc"   <?php if($sortOption === 'name_asc')   echo 'selected'; ?>>Nombre (A-Z)</option>
                    <option value="name_desc"  <?php if($sortOption === 'name_desc')  echo 'selected'; ?>>Nombre (Z-A)</option>
                </select>
                <button type="submit">Ordenar</button>
            </form>

            <!-- Grid de Productos -->
            <div class="product-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <?php
                    // Imagen
                    if (!empty($row['image_data'])) {
                        $base64 = base64_encode($row['image_data']);
                        echo "<img src='data:image/jpeg;base64,{$base64}' alt='{$row['name']}' />";
                    } else {
                        echo "<img src='assets/img/no-image.jpg' alt='Sin Imagen' />";
                    }
                    ?>
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['brand_name']; ?></p>
                    <p><strong><?php echo $row['price']; ?>€</strong></p>
                    <a href="product.php?id=<?php echo $row['id']; ?>">Ver Detalles</a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
    <script src="assets/js/dark-mode.js"></script>
</body>
</html>
