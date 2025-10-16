<?php
function getModulesByRole($rol) {
  return match ($rol) {
    'admin' => [
      ['nombre' => 'Gestión de Usuarios', 'archivo' => 'gestion_usuarios.php'],
      ['nombre' => 'Parametrización', 'archivo' => 'parametrizacion.php'],
      ['nombre' => 'Radicación', 'archivo' => 'radicacion.php'],
      ['nombre' => 'Gestión Documental', 'archivo' => 'gestion_documental.php'] // ✅ agregado
    ],
    'gestor' => [
      ['nombre' => 'Radicación', 'archivo' => 'radicacion.php'],
      ['nombre' => 'Gestión Documental', 'archivo' => 'gestion_documental.php'] // ✅ agregado
    ],
    default => []
  };
}
?>
