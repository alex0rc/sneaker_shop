<?php
require_once "includes/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $pass  = $_POST["pass"]  ?? "";

    // Buscar en la tabla 'users'
    $stmt = $conn->prepare("SELECT id, password, nombre FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verificar password con password_verify
        if (password_verify($pass, $row["password"])) {
            // Login OK
            $_SESSION["user_logged_in"] = true;
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["nombre"]; // opcional

            // Redirigir a la página principal o mispedidos
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "Credenciales incorrectas (contraseña).";
        }
    } else {
        $mensaje = "Credenciales incorrectas (email).";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login de Cliente</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="main-content">
        <div class="container">
            <h1>Iniciar Sesión (Cliente)</h1>
            <?php if ($mensaje): ?>
                <p style="color: red;"><?php echo $mensaje; ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Contraseña:</label>
                <input type="password" name="pass" required>

                <button type="submit">Acceder</button>
            </form>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
    <script src="assets/js/dark-mode.js"></script>
</body>
</html>
