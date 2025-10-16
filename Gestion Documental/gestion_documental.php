<?php
session_start();
if (!in_array($_SESSION['usuario'], ['admin', 'kamyla'])) die("⛔ Acceso denegado.");

$documentos = [];

// Buscar todos los archivos JSON en la carpeta uploads
foreach (glob("uploads/DOC-*.json") as $jsonFile) {
    $pdfFile = str_replace(".json", ".pdf", $jsonFile);
    if (!file_exists($pdfFile)) continue;

    $datos = json_decode(file_get_contents($jsonFile), true);
    $datos['id'] = basename($pdfFile, ".pdf");
    $datos['archivo_pdf'] = $pdfFile;
    $documentos[] = $datos;
}

// Ordenar por fecha descendente
usort($documentos, fn($a, $b) => strcmp($b['fecha'], $a['fecha']));
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión Documental</title>
</head>
<body>
<h1>Documentos Radicados</h1>
<p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></p>

<?php if (empty($documentos)): ?>
  <p>No hay documentos radicados.</p>
<?php else: ?>
  <table border="1">
    <tr>
      <th>ID</th><th>Fecha</th><th>Tipo</th><th>Asunto</th><th>Remitente</th><th>Área</th><th>Sede</th><th>Medio</th><th>Observación</th><th>Estado</th><th>PDF</th>
    </tr>
    <?php foreach ($documentos as $d): ?>
    <tr>
      <td><?= $d['id'] ?></td>
      <td><?= $d['fecha'] ?></td>
      <td><?= $d['tipo'] ?></td>
      <td><?= $d['asunto'] ?></td>
      <td><?= $d['remitente'] ?></td>
      <td><?= $d['area'] ?></td>
      <td><?= $d['sede'] ?></td>
      <td><?= $d['medio'] ?></td>
      <td><?= $d['observacion'] ?></td>
      <td><?= $d['estado'] ?></td>
      <td><a href="<?= $d['archivo_pdf'] ?>" target="_blank">📄 Ver PDF</a></td>
    </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<br>
<button onclick="window.location.href='dashboard.php'">← Volver al menú</button>
</body>
</html>
