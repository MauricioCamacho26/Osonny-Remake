<?php
require '../Mod1/conexion.php';

$id = $_GET['id']; 
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $producto['imagen'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = uniqid() . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $destPath = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpPath, $destPath);
        $imagen = $destPath;
    }

    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $id);
    $stmt->execute();
    header("Location: dashboard_restaurante.php?restaurante_id=" . $producto['restaurante_id']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="cssM2/editar_producto.css">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h1 class="text-center mb-4">Editar Producto</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio (MX$)</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Producto</label>
                <input type="file" name="imagen" id="imagen" class="form-control">
                <?php if ($producto['imagen']): ?>
                    <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del Producto" class="preview-image">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
