<?php
session_start();
require 'conexion.php';
if ($_SESSION['rol'] !== 'admin') die("⛔ Acceso denegado.");

$mensaje = '';

// REGISTRO DE USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_usuario'])) {
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $rol = $_POST['rol'];
  $estado = $_POST['estado'];
  $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

  // Validar correo duplicado
  $verificar = $conn->prepare("SELECT id FROM usuarios_sistema WHERE correo = ?");
  $verificar->bind_param("s", $correo);
  $verificar->execute();
  $verificar->store_result();

  if ($verificar->num_rows > 0) {
    $mensaje = "⚠️ El correo ya está registrado.";
  } else {
    $sql = $conn->prepare("INSERT INTO usuarios_sistema (nombre, correo, contraseña, rol, estado) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("sssss", $nombre, $correo, $clave, $rol, $estado);
    $sql->execute();
    $mensaje = "✅ Usuario creado.";
  }
}

// CAMBIO DE ESTADO
if (isset($_GET['cambiar_estado'])) {
  $id = $_GET['id'];
  $nuevo_estado = $_GET['estado'];
  $sql = $conn->prepare("UPDATE usuarios_sistema SET estado = ? WHERE id = ?");
  $sql->bind_param("si", $nuevo_estado, $id);
  $sql->execute();
  $mensaje = "🔄 Estado actualizado.";
}

// FILTRO
$filtro = $_GET['filtro'] ?? '';
$consulta = "SELECT * FROM usuarios_sistema";
if ($filtro) {
  $consulta .= " WHERE rol = '$filtro' OR estado = '$filtro'";
}
$result = $conn->query($consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
</head>
<body>
<h1>Gestión de Usuarios</h1>
<p><?= $mensaje ?></p>

<!-- FORMULARIO DE REGISTRO -->
<form method="POST">
  <input type="hidden" name="crear_usuario" value="1">
  <input type="text" name="nombre" placeholder="Nombre" required><br>
  <input type="email" name="correo" placeholder="Correo" required><br>
  <input type="password" name="clave" placeholder="Contraseña" required><br>
  <select name="rol">
    <option value="admin">admin</option>
    <option value="gestor">gestor</option>
    <option value="usuario">usuario</option>
  </select><br>
  <select name="estado">
    <option value="Activo">Activo</option>
    <option value="Inactivo">Inactivo</option>
  </select><br>
  <button type="submit">Crear Usuario</button>
</form>

<hr>

<!-- FILTRO -->
<h2>Filtrar Usuarios</h2>
<form method="GET">
  <select name="filtro">
    <option value="">-- Ver todos --</option>
    <option value="admin">admin</option>
    <option value="gestor">gestor</option>
    <option value="usuario">usuario</option>
    <option value="Activo">Activo</option>
    <option value="Inactivo">Inactivo</option>
  </select>
  <button type="submit">Filtrar</button>
</form>

<hr>

<!-- LISTADO DE USUARIOS -->
<h2>Usuarios registrados</h2>
<table border="1">
<tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
<?php while ($u = $result->fetch_assoc()): ?>
<tr>
  <td><?= $u['id'] ?></td>
  <td><?= $u['nombre'] ?></td>
  <td><?= $u['correo'] ?></td>
  <td><?= $u['rol'] ?></td>
  <td><?= $u['estado'] ?></td>
  <td>
    <?php if ($u['estado'] === 'Activo'): ?>
      <a href="?cambiar_estado=1&id=<?= $u['id'] ?>&estado=Inactivo"
         onclick="return confirm('¿Estás seguro de que quieres inactivar este usuario?')">Inactivar</a>
    <?php else: ?>
      <a href="?cambiar_estado=1&id=<?= $u['id'] ?>&estado=Activo"
         onclick="return confirm('¿Estás seguro de que quieres activar este usuario?')">Activar</a>
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
</table>

<br>
<button onclick="window.location.href='dashboard.php'">← Volver al menú</button>
</body>
</html>
