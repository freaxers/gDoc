<!-- bienvenido.php -->
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: index.html');
  exit;
}

//roles 
//si acabado de modificarlo xd
$usuario = $_SESSION['usuario'];
$_SESSION['rol'] = match ($usuario) {
  'admin' => 'admin',
  'kamyla' => 'gestor',
  default => 'usuario'
};

header('location: index.pho');
exit;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido</title>
</head>
<body>
  <h1>¡Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>
  <p>Has iniciado sesión correctamente.</p>
</body>
</html>