<?php
require_once "../includes/auth-check.php";
require_once "../includes/config.php";

// ELIMINAR MARCA
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    $stmtDel = $conn->prepare("DELETE FROM brands WHERE id = ?");
    $stmtDel->bind_param("i", $delId);
    $stmtDel->execute();
    header("Location: manage-brands.php");
    exit;
}

// EDITAR MARCA (cargar datos si llega ?edit_id=XX)
$editingBrand = null;
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    $stmtEdit = $conn->prepare("SELECT * FROM brands WHERE id = ?");
    $stmtEdit->bind_param("i", $editId);
    $stmtEdit->execute();
    $resEdit = $stmtEdit->get_result();
    if ($resEdit->num_rows === 1) {
        $editingBrand = $resEdit->fetch_assoc();
    }
}

// CREAR O ACTUALIZAR MARCA
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $brandName = trim($_POST["brand_name"] ?? "");

    // ACTUALIZAR
    if (!empty($_POST['brand_id'])) {
        $brandId = (int)$_POST['brand_id'];
        $stmtUpdate = $conn->prepare("UPDATE brands SET name = ? WHERE id = ?");
        $stmtUpdate->bind_param("si", $brandName, $brandId);
        $stmtUpdate->execute();
        header("Location: manage-brands.php");
        exit;
    }
    // CREAR
    else {
        if (!empty($brandName)) {
            $stmt = $conn->prepare("INSERT IGNORE INTO brands (name) VALUES (?)");
            $stmt->bind_param("s", $brandName);
            $stmt->execute();
        }
        header("Location: manage-brands.php");
        exit;
    }
}

// OBTENER TODAS LAS MARCAS
$result = $conn->query("SELECT * FROM brands ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Marcas</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
</head>
<body>
    <?php include "admin-header.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1>Gestionar Marcas</h1>

            <!-- Formulario: Editar o Crear -->
            <?php if ($editingBrand): ?>
                <h2>Editar Marca (ID: <?php echo $editingBrand['id']; ?>)</h2>
                <form method="POST">
                    <input type="hidden" name="brand_id" value="<?php echo $editingBrand['id']; ?>">
                    <label>Nombre de la Marca:</label>
                    <input type="text" name="brand_name" required
                        value="<?php echo htmlspecialchars($editingBrand['name']); ?>">
                    <button type="submit">Actualizar</button>
                </form>
            <?php else: ?>
                <h2>Crear Nueva Marca</h2>
                <form method="POST">
                    <label>Nombre de la Marca:</label>
                    <input type="text" name="brand_name" required>
                    <button type="submit">Crear</button>
                </form>
            <?php endif; ?>

            <!-- Listado de marcas existentes -->
            <h2 class="mt-20">Marcas Existentes</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
                <?php while($brand = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $brand['id']; ?></td>
                    <td><?php echo $brand['name']; ?></td>
                    <td>
                        <a class="btn-green" href="manage-brands.php?edit_id=<?php echo $brand['id']; ?>">Editar</a>
                        <a class="btn-red"
                        href="manage-brands.php?delete_id=<?php echo $brand['id']; ?>"
                        onclick="return confirm('¿Eliminar esta marca?');">
                        Borrar
                        </a>
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
