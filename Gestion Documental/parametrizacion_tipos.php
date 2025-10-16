<?php
session_start();
require 'conexion.php';
if ($_SESSION['rol'] !== 'admin') die("⛔ Acceso denegado.");

define('CARPETA_ARCHIVOS', 'uploads/');
$mensaje = '';

if (isset($_GET['eliminar'])) {
  $id = intval($_GET['eliminar']);
  $doc = $conn->query("SELECT archivo_pdf FROM documentos_parametrizados WHERE id = $id")->fetch_assoc();
  if ($doc && !empty($doc['archivo_pdf']) && file_exists($doc['archivo_pdf'])) unlink($doc['archivo_pdf']);
  $conn->query("DELETE FROM documentos_parametrizados WHERE id = $id");
  $mensaje = "🗑️ Documento eliminado correctamente.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $prefijo = $_POST['prefijo'];
  $numeracion = $_POST['numeracion'];
  $resolucion = $_POST['resolucion'];
  $ruta_pdf = '';

  if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === 0) {
    $nombre_archivo = basename($_FILES['archivo_pdf']['name']);
    $ruta_pdf = CARPETA_ARCHIVOS . time() . '_' . $nombre_archivo;
    move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $ruta_pdf);
  }

  $sql = $conn->prepare("INSERT INTO documentos_parametrizados (nombre, prefijo, numeracion, resolucion, archivo_pdf) VALUES (?, ?, ?, ?, ?)");
  $sql->bind_param("sssss", $nombre, $prefijo, $numeracion, $resolucion, $ruta_pdf);
  $sql->execute();
  $mensaje = "✅ Tipo de documento registrado.";
}

$tipos = $conn->query("SELECT * FROM documentos_parametrizados");
?>

<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Tipos de Documento</title></head>
<body>
<h1>Tipos de Documento</h1>
<p><?= $mensaje ?></p>

<form method="POST" enctype="multipart/form-data">
  <label>Nombre:</label><br><input type="text" name="nombre" required><br>
  <label>Prefijo:</label><br><input type="text" name="prefijo" required><br>
  <label>Numeración:</label><br><input type="text" name="numeracion" required><br>
  <label>Resolución:</label><br><input type="text" name="resolucion" required><br>
  <label>Archivo PDF (opcional):</label><br><input type="file" name="archivo_pdf" accept="application/pdf"><br><br>
  <button type="submit">Registrar</button>
</form>

<h3>Registrados</h3>
<table border="1">
<tr><th>Nombre</th><th>Prefijo</th><th>Numeración</th><th>Resolución</th><th>Documento</th><th>Acciones</th></tr>
<?php while ($doc = $tipos->fetch_assoc()): ?>
<tr>
  <td><?= $doc['nombre'] ?></td>
  <td><?= $doc['prefijo'] ?></td>
  <td><?= $doc['numeracion'] ?></td>
  <td><?= $doc['resolucion'] ?></td>
  <td>
    <?php if (!empty($doc['archivo_pdf']) && file_exists($doc['archivo_pdf'])): ?>
      <a href="<?= $doc['archivo_pdf'] ?>" target="_blank">📄 Ver PDF</a>
    <?php else: ?>No adjunto<?php endif; ?>
  </td>
  <td><a href="?eliminar=<?= $doc['id'] ?>" onclick="return confirm('¿Eliminar este documento?')">🗑️ Eliminar</a></td>
</tr>
<?php endwhile; ?>
</table>

<br><button onclick="window.location.href='parametrizacion.php'">← Volver</button>
</body>
</html>
