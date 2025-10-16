<?php
session_start();
if ($_SESSION['rol'] !== 'admin') die("⛔ Acceso denegado.");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Parametrización</title>
</head>
<body>
  <h1>Panel de Parametrización</h1>
  <ul>
    <li><a href="parametrizacion_tipos.php">Tipos de Documento</a></li>
    <li><a href="parametrizacion_sedes.php">Sedes</a></li>
    <li><a href="parametrizacion_areas.php">Áreas</a></li>
    <li><a href="gestion_usuarios.php">gestion usuarios</a></li>
  </ul>
  <br>
  <button onclick="window.location.href='dashboard.php'">← Volver al menú</button>
</body>
</html>
