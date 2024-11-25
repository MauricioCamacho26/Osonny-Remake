<?php
require '../Mod1/conexion.php';

if (!isset($_GET['restaurante_id'])) {
    die("Error: ID del restaurante no especificado.");
}

$restaurante_id = $_GET['restaurante_id'];
$stmt = $conn->prepare("SELECT * FROM productos WHERE restaurante_id = ?");
$stmt->bind_param("i", $restaurante_id);
$stmt->execute();
$result = $stmt->get_result();
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Menú</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Gestionar Menu</h1>
    <a href="agregar_producto.php?restaurante_id=<?php echo $restaurante_id; ?>" class="btn btn-primary mb-3">Agregar Producto</a>
    <?php if (empty($productos)): ?>
        <p>No hay productos en el menu</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                        <td>MX$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td>
                            <img src="<?php echo $producto['imagen']; ?>" alt="Imagen" class="product-image">
                        </td>
                        <td>
                            <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>&restaurante_id=<?php echo $restaurante_id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
