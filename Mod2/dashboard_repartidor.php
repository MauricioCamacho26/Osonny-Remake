<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'repartidor') {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Repartidor</title>
    <link rel="stylesheet" href="cssM2/dashboardsM1.css">

</head>
<body>
    <div class="navbar">
        <a href="logout.php" class="logout-button">Cerrar Sesion</a>
    </div>
    <div class="container">
        <h1>Bienvenido al Dashboard de Repartidor</h1>
        <p>Aqui puedes ver las entregas asignadas.</p>
    </div>
</body>
</html>
