<?php
session_start();
require 'conexion.php';

// Solo permite acceso a administradores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  die("⛔ Acceso denegado.");
}

$mensaje = '';
$tipo_mensaje = ''; // 'success' o 'danger'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
  $rol = $_POST['rol'];
  $estado = $_POST['estado'];

  // Verifica si el correo ya existe
  $verificar = $conn->prepare("SELECT id FROM usuarios_sistema WHERE correo = ?");
  $verificar->bind_param("s", $correo);
  $verificar->execute();
  $verificar->store_result();

  if ($verificar->num_rows > 0) {
    $mensaje = "⚠️ El correo ya está registrado.";
    $tipo_mensaje = "danger";
  } else {
    $sql = "INSERT INTO usuarios_sistema (nombre, correo, contraseña, rol, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $correo, $clave, $rol, $estado);
    if ($stmt->execute()) {
      $mensaje = "✅ Usuario registrado correctamente.";
      $tipo_mensaje = "success";
    } else {
      $mensaje = "❌ Error al registrar usuario.";
      $tipo_mensaje = "danger";
    }
  }
}

// Obtener lista de usuarios
$usuarios = $conn->query("SELECT id, nombre, correo, rol, estado FROM usuarios_sistema");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h1 class="mb-4">Registrar Nuevo Usuario</h1>

  <?php if ($mensaje): ?>
    <div class="alert alert-<?= $tipo_mensaje ?>" role="alert">
      <?= $mensaje ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="mb-4">
    <div class="mb-3">
      <label class="form-label">Nombre:</label>
      <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Correo:</label>
      <input type="email" name="correo" class="form-control" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Contraseña:</label>
      <input type="password" name="clave" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Rol:</label>
      <select name="rol" class="form-select">
        <option value="admin" <?= ($_POST['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>admin</option>
        <option value="gestor" <?= ($_POST['rol'] ?? '') === 'gestor' ? 'selected' : '' ?>>gestor</option>
        <option value="usuario" <?= ($_POST['rol'] ?? '') === 'usuario' ? 'selected' : '' ?>>usuario</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Estado:</label>
      <select name="estado" class="form-select">
        <option value="Activo" <?= ($_POST['estado'] ?? '') === 'Activo' ? 'selected' : '' ?>>Activo</option>
        <option value="Inactivo" <?= ($_POST['estado'] ?? '') === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Registrar</button>
    <button type="button" class="btn btn-secondary ms-2" onclick="window.location.href='dashboard.php'">← Volver</button>
  </form>

  <h2>Usuarios Registrados</h2>
  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr>
        <th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($u = $usuarios->fetch_assoc()): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= $u['nombre'] ?></td>
          <td><?= $u['correo'] ?></td>
          <td><?= $u['rol'] ?></td>
          <td><?= $u['estado'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
