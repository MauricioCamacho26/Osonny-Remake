<?php
require '../Mod1/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurante_id = $_POST['restaurante_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = uniqid() . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $destPath = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpPath, $destPath);
        $imagen = $destPath;
    }

    $stmt = $conn->prepare("INSERT INTO productos (restaurante_id, nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $restaurante_id, $nombre, $descripcion, $precio, $imagen);
    $stmt->execute();
    header("Location: dashboard_restaurante.php?restaurante_id=" . $restaurante_id);
    exit;
    
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="cssM2/agregar_producto.css">
</head>
<body>
<div class="container">
    <div class="form-container">
        <a href="javascript:history.back()" class="btn btn-link">← Regresar</a>
        <h1 class="text-center mb-4">Agregar Producto</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio (MX$)</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Producto</label>
                <input type="file" name="imagen" id="imagen" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Agregar Producto</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
