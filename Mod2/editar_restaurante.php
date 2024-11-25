<?php
require '../Mod1/conexion.php';

if (!isset($_GET['id'])) {
    die("Error: ID de restaurante no especificado.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM restaurantes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$restaurante = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $horario = trim($_POST['horario']); 
    $banner = $restaurante['banner'];

    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['banner']['tmp_name'];
        $fileName = uniqid() . '.' . pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
        $destPath = 'uploads/' . $fileName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $banner = $destPath;
        }
    }

    $stmt = $conn->prepare("UPDATE restaurantes SET nombre = ?, direccion = ?, telefono = ?, horario = ?, banner = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $nombre, $direccion, $telefono, $horario, $banner, $id);
    if ($stmt->execute()) {
        header("Location: dashboard_restaurante.php");
        exit;
    } else {
        echo "<div class='error'>Error al actualizar el restaurante.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Restaurante</title>
</head>
<body>
<div class="container mt-5">
    <h1>Editar Restaurante</h1>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Restaurante</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($restaurante['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Direccion</label>
            <textarea id="direccion" name="direccion" class="form-control" rows="3" required><?php echo htmlspecialchars($restaurante['direccion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Telefono</label>
            <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($restaurante['telefono']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="horario" class="form-label">Horario</label>
            <input type="text" id="horario" name="horario" class="form-control" value="<?php echo htmlspecialchars($restaurante['horario']); ?>" placeholder="Ej: Lunes a Viernes: 9:00 AM - 9:00 PM" required>
        </div>
        <div class="mb-3">
            <label for="banner" class="form-label">Imagen del Restaurante</label>
            <input type="file" id="banner" name="banner" class="form-control">
            <?php if ($restaurante['banner']): ?>
                <img src="<?php echo $restaurante['banner']; ?>" alt="Banner" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
</body>
</html>
