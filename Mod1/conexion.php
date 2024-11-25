<?php
$host = '127.0.0.1';
$user = 'root';
$password = ''; 
$database = 'osonny_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
