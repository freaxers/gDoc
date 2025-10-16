<?php
session_start();
require 'conexion.php';

// Verifica que se haya enviado el formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "<p>Acceso no permitido.</p>";
  exit;
}

// Captura los datos del formulario
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Consulta el usuario en la base de datos
$sql = "SELECT id, nombre, contraseña, rol, estado FROM usuarios_sistema WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Verifica si existe el usuario
if ($result->num_rows === 1) {
  $usuario = $result->fetch_assoc();

  // Verifica si el usuario está activo
  if ($usuario['estado'] !== 'Activo') {
    echo "<script>alert('⛔ Usuario inactivo'); window.history.back();</script>";
    exit;
  }

  // Verifica la contraseña encriptada
  if (password_verify($password, $usuario['contraseña'])) {
    $_SESSION['usuario'] = $usuario['nombre'];
    $_SESSION['usuario_id'] = $usuario['id'];     // ✅ ID del usuario
    $_SESSION['rol'] = $usuario['rol'];

    header('Location: dashboard.php');
    exit;
  }
}

// Si falla, muestra mensaje
echo "<script>alert('Usuario o contraseña incorrectos'); window.history.back();</script>";
?>
