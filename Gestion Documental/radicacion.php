<?php
session_start();
if (!in_array($_SESSION['usuario'], ['admin', 'kamyla'])) die("⛔ Acceso denegado.");

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo = $_FILES['archivo'];
    if ($archivo['type'] !== 'application/pdf' || $archivo['size'] > 3 * 1024 * 1024) {
        $mensaje = "⚠️ Solo PDF menores a 3MB.";
    } else {
        $indice = uniqid('DOC-');
        if (!is_dir('uploads')) mkdir('uploads');

        $ruta_pdf = "uploads/$indice.pdf";
        move_uploaded_file($archivo['tmp_name'], $ruta_pdf);

        // Guardar metadatos en JSON
        $datos = [
            'fecha'       => $_POST['fecha'],
            'tipo'        => $_POST['tipo'] ?? 'Otro',
            'asunto'      => $_POST['asunto'],
            'remitente'   => $_POST['remitente'],
            'area'        => $_POST['area'],
            'sede'        => $_POST['sede'] ?? 'Virtual',
            'medio'       => $_POST['medio'],
            'observacion' => $_POST['observacion'],
            'estado'      => 'Pendiente'
        ];

        file_put_contents("uploads/$indice.json", json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $mensaje = "✅ Documento guardado con índice: $indice";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Radicación</title></head>
<body>
<h1>Radicación de Documentos</h1>
<p><?= $mensaje ?></p>

<form method="POST" enctype="multipart/form-data">
  <label>Fecha:</label><br><input type="date" name="fecha" required><br>

  <label>Tipo de Documento:</label><br>
  <select name="tipo" required>
    <option value="">-- Seleccione un tipo --</option>
    <option value="Oficio">Oficio</option>
    <option value="Memorando">Memorando</option>
    <option value="Resolución">Resolución</option>
    <option value="Solicitud">Solicitud</option>
    <option value="Informe">Informe</option>
    <option value="Otro">Otro</option>
  </select><br>

  <label>Asunto:</label><br><input type="text" name="asunto" required><br>
  <label>Remitente:</label><br><input type="text" name="remitente" required><br>

  <label>Área:</label><br>
  <select name="area" required>
    <option value="">-- Seleccione un área --</option>
    <option value="Jurídica">Jurídica</option>
    <option value="Administrativa">Administrativa</option>
    <option value="Financiera">Financiera</option>
    <option value="Comercial">Comercial</option>
    <option value="Técnica">Técnica</option>
  </select><br>

  <label>Sede:</label><br>
  <select name="sede" required>
    <option value="">-- Seleccione una sede --</option>
    <option value="Bogotá">Bogotá</option>
    <option value="Medellín">Medellín</option>
    <option value="Cali">Cali</option>
    <option value="Barranquilla">Barranquilla</option>
    <option value="Virtual">Virtual</option>
  </select><br>

  <label>Medio:</label><br><input type="text" name="medio" required><br>
  <label>Observación:</label><br><textarea name="observacion"></textarea><br>
  <label>Archivo PDF:</label><br><input type="file" name="archivo" accept="application/pdf" required><br><br>
  <button type="submit">Guardar</button>
</form>

<br>
<button onclick="window.location.href='dashboard.php'">← Volver al menú</button>
</body>
</html>
