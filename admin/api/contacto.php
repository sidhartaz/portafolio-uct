<?php
/**
 * API: Procesar envío del formulario de contacto
 * Inserta el mensaje en la BD y redirige al index con flash message.
 */

require_once '../includes/funciones.php';
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('../index.php');
}

// CSRF
if (!validarCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Token de seguridad inválido. Recarga la página e intenta de nuevo.');
    redirigir('../index.php#contacto');
}

// Sanitizar
$nombre  = trim($_POST['nombre']  ?? '');
$email   = trim($_POST['email']   ?? '');
$asunto  = trim($_POST['asunto']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// Validar
$errores = [];
if ($nombre === '' || mb_strlen($nombre) < 2)      $errores[] = 'Nombre inválido.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $errores[] = 'Correo inválido.';
if ($mensaje === '' || mb_strlen($mensaje) < 10)   $errores[] = 'El mensaje debe tener al menos 10 caracteres.';

if ($errores) {
    setFlash('error', 'Revisa los campos: ' . implode(' ', $errores));
    redirigir('../index.php#contacto');
}

// Insertar con prepared statement
$stmt = $conexion->prepare(
    "INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param('ssss', $nombre, $email, $asunto, $mensaje);

$esAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';

if ($stmt->execute()) {
    $stmt->close();
    if ($esAjax) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }
    setFlash('ok', '¡Mensaje enviado correctamente! Me pondré en contacto contigo pronto.');
} else {
    error_log("Error guardando mensaje: " . $stmt->error);
    $stmt->close();
    if ($esAjax) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Error al guardar el mensaje.']);
        exit;
    }
    setFlash('error', 'Hubo un problema al enviar tu mensaje. Intenta más tarde.');
}

redirigir('../index.php#contacto');
