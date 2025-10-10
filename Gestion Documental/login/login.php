<?php
// login.php
session_start();

// Simulación de base de datos (usuario y contraseña encriptada)
$usuarios = [
  'kamyla' => password_hash('miContraseñaSegura123', PASSWORD_DEFAULT),
  'admin' => password_hash('admin123', PASSWORD_DEFAULT)
];

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($usuarios[$username]) && password_verify($password, $usuarios[$username])) {
  $_SESSION['usuario'] = $username;
  echo "<script>alert('Login exitoso'); window.location.href='bienvenido.php';</script>";
} else {
  echo "<script>alert('Usuario o contraseña incorrectos'); window.history.back();</script>";
}
?>