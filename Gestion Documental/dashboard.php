<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: login.html');
  exit;
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'] ?? 'usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { color: #333; }
    ul { list-style-type: none; padding-left: 0; }
    li { margin-bottom: 8px; }
    a { text-decoration: none; color: #0077cc; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <h1>Bienvenido, <?= htmlspecialchars($usuario) ?></h1>

  <h2>Módulos disponibles:</h2>
  <ul>
    <?php if ($rol === 'admin'): ?>
      <li><a href="parametrizacion.php">Parametrización</a></li>
    <?php endif; ?>
    <li><a href="radicacion.php">Radicar Documento</a></li>
    <li><a href="gestion_documental.php">Gestion de documentos</a></li>
  </ul>

  <p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>
