<?php
require_once "includes/config.php"; // $conn a la BD
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = $_POST["nombre"]   ?? "";
    $apellido = $_POST["apellido"] ?? "";
    $email    = $_POST["email"]    ?? "";
    $telefono = $_POST["telefono"] ?? "";
    $direccion = $_POST["direccion"] ?? "";
    $ciudad   = $_POST["ciudad"]   ?? "";
    $cp       = $_POST["cp"]       ?? "";
    $pais     = $_POST["pais"]     ?? "";
    $pass1    = $_POST["pass1"]    ?? "";
    $pass2    = $_POST["pass2"]    ?? "";

    // Validar contraseñas
    if ($pass1 !== $pass2) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        // Generar hash de contraseña
        $hash = password_hash($pass1, PASSWORD_DEFAULT);

        // Insertar en 'users'
        $stmt = $conn->prepare("
            INSERT INTO users
            (nombre, apellido, email, telefono, direccion, ciudad, cp, pais, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssssss",
            $nombre, $apellido, $email, $telefono, $direccion, $ciudad, $cp, $pais, $hash
        );

        if ($stmt->execute()) {
            $mensaje = "¡Cuenta creada! Ya puedes iniciar sesión.";
        } else {
            $mensaje = "Error al crear cuenta (¿email ya existente?).";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
<?php include "includes/header.php"; ?>

<div class="main-content">
<div class="container">
    <h1>Crear Cuenta</h1>
    <?php if ($mensaje): ?>
        <p style="color:red;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Apellido:</label>
        <input type="text" name="apellido">

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Teléfono:</label>
        <input type="text" name="telefono">

        <label>Dirección:</label>
        <input type="text" name="direccion">

        <label>Ciudad:</label>
        <input type="text" name="ciudad">

        <label>Código Postal:</label>
        <input type="text" name="cp">

        <label>País:</label>
        <input type="text" name="pais">

        <label>Contraseña:</label>
        <input type="password" name="pass1" required>

        <label>Repetir Contraseña:</label>
        <input type="password" name="pass2" required>

        <button type="submit">Registrar</button>
    </form>
</div>
</div>

<?php include "includes/footer.php"; ?>
<script src="assets/js/dark-mode.js"></script>
</body>
</html>
