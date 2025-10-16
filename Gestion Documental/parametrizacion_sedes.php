<?php
session_start();
require 'conexion.php';
if ($_SESSION['rol'] !== 'admin') die("⛔ Acceso denegado.");

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $ciudad = $_POST['ciudad'];
  $estado = $_POST['estado'];
  $sql = $conn->prepare("INSERT INTO sedes (nombre, ciudad, estado) VALUES (?, ?, ?)");
  $sql->bind_param("sss", $nombre, $ciudad, $estado);
  $sql->execute();
  $mensaje = "✅ Sede registrada.";
}

$sedes = $conn->query("SELECT * FROM sedes");
?>

<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Sedes</title></head>
<body>
<h1>Registrar Sede</h1>
<p><?= $mensaje ?></p>

<form method="POST">
  <label>Nombre:</label><br><input type="text" name="nombre" required><br>
  <label>Ciudad:</label><br><input type="text" name="ciudad" required><br>
  <label>Estado:</label><br>
  <select name="estado">
    <option value="Activo">Activo</option>
    <option value="Inactivo">Inactivo</option>
  </select><br><br>
  <button type="submit">Registrar</button>
</form>

<h3>Sedes Registradas</h3>
<table border="1">
<tr><th>Nombre</th><th>Ciudad</th><th>Estado</th></tr>
<?php while ($s = $sedes->fetch_assoc()): ?>
<tr>
  <td><?= $s['nombre'] ?></td>
  <td><?= $s['ciudad'] ?></td>
  <td><?= $s['estado'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<br><button onclick="window.location.href='parametrizacion.php'">← Volver</button>
</body>
</html>
