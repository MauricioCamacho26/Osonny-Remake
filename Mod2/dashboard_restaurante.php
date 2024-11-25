<?php
session_start();
require_once '../Mod1/conexion.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: Usuario no autenticado Inicia sesion.");
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, nombre, direccion, telefono, banner FROM restaurantes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$restaurantes = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_restaurant'])) {
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $banner = null;

    if (empty($nombre) || empty($direccion) || empty($telefono)) {
        echo "<div class='error'>Todos los campos son obligatorios.</div>";
    } else {
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['banner']['tmp_name'];
            $fileName = uniqid() . '.' . pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
            $uploadDir = __DIR__ . '/uploads/'; 
            $destPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $banner = 'uploads/' . $fileName; 
            } else {
                echo "<div class='error'>Error al subir la imagen.</div>";
            }
        }

        $stmt = $conn->prepare("INSERT INTO restaurantes (user_id, nombre, direccion, telefono, banner) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $nombre, $direccion, $telefono, $banner);

        if ($stmt->execute()) {
            header("Location: dashboard_restaurante.php");
            exit;
        } else {
            echo "<div class='error'>Error al registrar el restaurante. Intenta de nuevo.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Restaurante</title>
    <link rel="stylesheet" href="cssM2/dashRest.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Dashboard Restaurante</h1>

    <?php if (empty($restaurantes)): ?>
        <h2 class="mt-4">Registrar un Restaurante</h2>
        <form method="post" action="dashboard_restaurante.php" enctype="multipart/form-data" class="mt-3">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Restaurante</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Direccion</label>
                <textarea id="direccion" name="direccion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="banner" class="form-label">Imagen del Restaurante (Opcional)</label>
                <input type="file" id="banner" name="banner" class="form-control">
            </div>
            <button type="submit" name="add_restaurant" class="btn btn-primary">Registrar Restaurante</button>
        </form>
    <?php else: ?>
        <h2 class="mt-4">Tus Restaurantes</h2>
        <ul class="list-group mt-3">
            <?php foreach ($restaurantes as $restaurante): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo htmlspecialchars($restaurante['nombre']); ?></strong><br>
                        <span><?php echo htmlspecialchars($restaurante['direccion']); ?></span><br>
                        <span><?php echo htmlspecialchars($restaurante['telefono']); ?></span>
                    </div>
                    <div class="ms-3">
                        <img src="<?php echo $restaurante['banner'] ?: 'uploads/default-banner.jpg'; ?>" alt="Banner" width="50">
                    </div>
                    <div class="ms-auto">
                        <a href="gestionar_menu.php?restaurante_id=<?php echo $restaurante['id']; ?>" class="btn btn-sm btn-secondary">Gestionar Menu</a>
                        <a href="editar_restaurante.php?id=<?php echo $restaurante['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="eliminar_restaurante.php?id=<?php echo $restaurante['id']; ?>" class="btn btn-sm btn-danger">Eliminar</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</body>
</html>
