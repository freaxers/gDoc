<?php
$host = 'localhost';
$usuario = 'root';
$clave = '';
$bd = 'sistema_documental';

$conn = new mysqli($host, $usuario, $clave, $bd);
if ($conn->connect_error) {
  die("❌ Error de conexión: " . $conn->connect_error);
}
?>
