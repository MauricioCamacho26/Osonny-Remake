<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: ../Mod1/login.html");
    exit;
}

require '../Mod1/conexion.php';

$sql = "SELECT * FROM restaurantes";
$result = $conn->query($sql);

if (!$result) {
    die("<p>Error al obtener los restaurantes: " . $conn->error . "</p>");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cliente</title>
    <link rel="stylesheet" href="cssM2/dashboard_cliente.css">
</head>
<body>
    <header class="top-bar">
        <div class="location">
            <img src="img/location-icon.png" alt="Ubicacion">
            <span>Cooperativa, 522</span>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Buscar en el menú...">
        </div>
    </header>

    <section class="restaurants">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <a href="restaurant.php?id=<?php echo $row['id']; ?>" class="restaurant-card">
                    <img src="<?php echo $row['banner']; ?>" alt="<?php echo $row['nombre']; ?>">
                    <div class="info">
                        <h3><?php echo $row['nombre']; ?></h3>
                        <p>Abre: <?php echo $row['horario']; ?></p>
                        <p class="price">⭐ <?php echo $row['calificacion']; ?> (<?php echo $row['reseñas']; ?> reseñas)</p>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay restaurantes disponibles en este momento.</p>
        <?php endif; ?>
    </section>
</body>
</html>
