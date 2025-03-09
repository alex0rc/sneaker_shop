<?php
require_once "../includes/config.php"; // Conexión a BD
session_start(); // Iniciamos sesión para manejar $_SESSION

$error = "";

// Si el formulario fue enviado...
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recogemos usuario y "clave" del POST
    $username = $_POST["username"] ?? "";
    $clave    = $_POST["clave"]    ?? "";

    // Buscamos en la tabla `admins` un usuario con ese nombre
    $stmt = $conn->prepare("SELECT id, username, clave FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificamos si existe y coincide la contraseña
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // OJO: Aquí se compara texto plano. Lo ideal es password_hash() / password_verify()
        if ($row["clave"] === $clave) {
            // Login OK
            $_SESSION["admin_logged_in"] = true;
            $_SESSION["admin_id"] = $row["id"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Credenciales incorrectas.";
        }
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
</head>
<body>
    <?php include "admin-header-loggedout.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1>Login de Administrador</h1>

            <!-- Si hay error, lo mostramos -->
            <?php if ($error): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Formulario de login -->
            <form method="POST">
                <label>Usuario:</label>
                <input type="text" name="username" required />

                <label>Contraseña:</label>
                <input type="password" name="clave" required />

                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>
    </div>

    <?php include "../includes/footer.php"; ?>
    <script src="../assets/js/dark-mode.js"></script>
</body>
</html>
