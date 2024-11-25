<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: ../Mod1/login.html");
    exit;
}

require '../Mod1/conexion.php';

$id_restaurante = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_restaurante) {
    die("ID de restaurante no válido.");
}

$sql_restaurante = "SELECT * FROM restaurantes WHERE id = ?";
$stmt_restaurante = $conn->prepare($sql_restaurante);
$stmt_restaurante->bind_param("i", $id_restaurante);
$stmt_restaurante->execute();
$result_restaurante = $stmt_restaurante->get_result();

if ($result_restaurante->num_rows > 0) {
    $restaurante = $result_restaurante->fetch_assoc();
} else {
    die("Restaurante no encontrado.");
}

$sql_menu = "SELECT * FROM menus WHERE id_restaurante = ?";
$stmt_menu = $conn->prepare($sql_menu);
$stmt_menu->bind_param("i", $id_restaurante);
$stmt_menu->execute();
$result_menu = $stmt_menu->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurante['nombre']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="cssM2/restaurant.css">
</head>
<body>
    <header class="restaurant-header" style="background-image: url('<?php echo htmlspecialchars($restaurante['banner']); ?>');">
        <div class="restaurant-info bg-dark text-white p-3 rounded">
            <a href="javascript:history.back()" class="btn btn-light mb-3">← Regresar</a>
            <h1><?php echo htmlspecialchars($restaurante['nombre']); ?></h1>
            <p>⭐ <?php echo htmlspecialchars($restaurante['calificacion'] ?? '0'); ?> (<?php echo htmlspecialchars($restaurante['reseñas'] ?? '0'); ?> reseñas)</p>
            <p>Categoría: <?php echo htmlspecialchars($restaurante['categoria'] ?? 'No especificada'); ?></p>
            <p>Horario: <?php echo htmlspecialchars($restaurante['horario'] ?? 'No especificado'); ?></p>
        </div>
    </header>

    <section class="menu container mt-5">
        <h2 class="text-center text-danger">Menú</h2>
        <?php if ($result_menu->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($menu = $result_menu->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($menu['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($menu['nombre_plato']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($menu['nombre_plato']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($menu['descripcion']); ?></p>
                            <p class="price text-success">Precio: MX$<?php echo number_format($menu['precio'], 2); ?></p>
                            <p>Disponible: <?php echo htmlspecialchars($menu['horario_disponible'] ?? 'Todo el día'); ?></p>
                            <form method="post" action="cart.php">
                                <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                                <input type="hidden" name="menu_name" value="<?php echo htmlspecialchars($menu['nombre_plato']); ?>">
                                <input type="hidden" name="menu_price" value="<?php echo $menu['precio']; ?>">
                                <input type="hidden" name="menu_image" value="<?php echo htmlspecialchars($menu['imagen']); ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Agregar al carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                No hay productos disponibles en este momento.
            </div>
        <?php endif; ?>
    </section>
</body>
</html>
