<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) die("⛔ Acceso denegado.");

$id = $_GET['id'] ?? null;
$accion = $_GET['accion'] ?? null;

$permitidas = ['aceptar', 'rechazar', 'trasladar', 'tramitar'];
if (!$id || !in_array($accion, $permitidas)) {
  header("Location: gestion_documental.php?msg=⚠️ Acción inválida");
  exit;
}

// Obtener estado actual
$consulta = $conn->prepare("SELECT estado FROM documentos_radicados WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$consulta->bind_result($estado_actual);
$consulta->fetch();
$consulta->close();

// Reglas de negocio
$transiciones = [
  'Pendiente' => ['aceptar', 'rechazar', 'trasladar'],
  'Aceptado' => ['tramitar']
];

if (!in_array($accion, $transiciones[$estado_actual] ?? [])) {
  header("Location: gestion_documental.php?msg=⛔ Acción no permitida desde estado '$estado_actual'");
  exit;
}

// Aplicar cambio
$nuevo_estado = match ($accion) {
  'aceptar' => 'Aceptado',
  'rechazar' => 'Rechazado',
  'trasladar' => 'Trasladado',
  'tramitar' => 'Tramitado'
};

$update = $conn->prepare("UPDATE documentos_radicados SET estado = ? WHERE id = ?");
$update->bind_param("si", $nuevo_estado, $id);
$update->execute();

header("Location: gestion_documental.php?msg=✅ Documento actualizado a '$nuevo_estado'");
exit;
