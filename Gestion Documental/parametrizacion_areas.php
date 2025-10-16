<?php
session_start();
require 'conexion.php';
if ($_SESSION['rol'] !== 'admin') die("⛔ Acceso denegado.");

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $sede_id = $_POST['sede_id'];
  $sql = $conn->prepare("INSERT INTO areas (nombre, sede_id) VALUES (?, ?)");
  $sql->bind_param("si", $nombre, $sede_id);
  $sql->execute();
  $mensaje = "✅ Área registrada.";
}

$sedes = $conn->query("SELECT * FROM sedes");
$areas = $conn->query("SELECT a.*, s.nombre AS sede FROM areas a JOIN sedes s ON a.sede_id = s.id");
?>

<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Áreas</title></head>
<body>
<h1>Registrar Área</h1>
<p><?= $mensaje ?></p>

<form method="POST">
  <label>Nombre:</label><br><input type="text" name="nombre" required><br>
  <label>Sede:</label><br>
  <select name="sede_id">
    <?php while ($s = $sedes->fetch_assoc()): ?>
      <option value="<?= $s['id'] ?>"><?= $s['nombre'] ?></option>
    <?php endwhile; ?>
  </select><br><br>
  <button type="submit">Registrar</button>
</form>

<h3>Áreas Registradas</h3>
<table border="1">
<tr><th>Nombre</th><th>Sede</th></tr>
<?php while ($a = $areas->fetch_assoc()): ?>
<tr>
  <td><?= $a['nombre'] ?></td>
  <td><?= $a['sede'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<button onclick="window.location.href='parametrizacion.php'">← Volver</button>
</body>
</html>