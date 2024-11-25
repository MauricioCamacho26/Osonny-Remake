<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $tipo_usuario = $_POST['tipo_usuario'] ?? '';

    if (empty($nombre) || empty($email) || empty($password) || empty($tipo_usuario)) {
        echo "<div class='error'>Todos los campos son obligatorios.</div>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error'>Correo no válido.</div>";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<div class='error'>El correo ya está registrado.</div>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña, tipo_usuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $password_hash, $tipo_usuario);

    if ($stmt->execute()) {
        echo "<div class='success'>Registro exitoso. ¡Bienvenido!</div>";
        header("Location: login.html");
        exit;
    } else {
        echo "<div class='error'>Error al registrar el usuario. Intentalo de nuevo.</div>";
    }

    $stmt->close();
}
?>
