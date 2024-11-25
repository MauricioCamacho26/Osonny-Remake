<?php
require '../Mod1/conexion.php';

$id = $_GET['id']; 
$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard_restaurante.php?restaurante_id=" . $_GET['restaurante_id']);
exit;
?>
