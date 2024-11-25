<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "<div class='error'>Correo y contraseña son obligatorios.</div>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, contraseña, tipo_usuario FROM usuarios WHERE email = ?");
    if (!$stmt) {
        echo "<div class='error'>Error en el sistema. Contacte al administrador.</div>";
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo "<div class='error'>Correo o contraseña incorrectos.</div>";
        exit;
    }

    $stmt->bind_result($id, $hashed_password, $tipo_usuario);
    $stmt->fetch();

    if (!password_verify($password, $hashed_password)) {
        echo "<div class='error'>Correo o contraseña incorrectos.</div>";
        exit;
    }

    $_SESSION['user_id'] = $id;
    $_SESSION['user_type'] = $tipo_usuario;

    header("Location: ../Mod2/dashboard_{$tipo_usuario}.php");
    exit;
}
?>
