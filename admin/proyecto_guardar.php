<?php
require_once '../includes/funciones.php';
require_once '../config/db.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('proyectos.php');

if (!validarCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Token CSRF inválido.');
    redirigir('proyectos.php');
}

$id          = (int)($_POST['id'] ?? 0);
$titulo      = trim($_POST['titulo']      ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$categoria   = trim($_POST['categoria']   ?? 'web');
$tags        = trim($_POST['tags']        ?? '');
$icono       = trim($_POST['icono']       ?? 'bi-folder');
$url_demo    = trim($_POST['url_demo']    ?? '');
$url_github  = trim($_POST['url_github']  ?? '');
$destacado   = isset($_POST['destacado']) ? 1 : 0;
$activo      = isset($_POST['activo'])    ? 1 : 0;

// Validar
$errores = [];
if (mb_strlen($titulo) < 3)        $errores[] = 'Título inválido.';
if (mb_strlen($descripcion) < 20)  $errores[] = 'Descripción muy corta.';
if (!in_array($categoria, ['web','python','linux','otro'])) $errores[] = 'Categoría inválida.';
if ($url_demo   && !filter_var($url_demo,   FILTER_VALIDATE_URL)) $errores[] = 'URL Demo inválida.';
if ($url_github && !filter_var($url_github, FILTER_VALIDATE_URL)) $errores[] = 'URL GitHub inválida.';

if ($errores) {
    setFlash('error', implode(' ', $errores));
    redirigir($id ? "proyecto_form.php?id=$id" : 'proyecto_form.php');
}

if ($id > 0) {
    // UPDATE
    $stmt = $conexion->prepare(
        "UPDATE proyectos
         SET titulo=?, descripcion=?, categoria=?, tags=?, icono=?,
             url_demo=?, url_github=?, destacado=?, activo=?
         WHERE id = ?"
    );
    $stmt->bind_param('sssssssiii',
        $titulo, $descripcion, $categoria, $tags, $icono,
        $url_demo, $url_github, $destacado, $activo, $id);
    $ok = $stmt->execute();
    $stmt->close();
    setFlash($ok ? 'ok' : 'error',
             $ok ? 'Proyecto actualizado correctamente.' : 'Error al actualizar.');
} else {
    // INSERT
    $stmt = $conexion->prepare(
        "INSERT INTO proyectos
         (titulo, descripcion, categoria, tags, icono, url_demo, url_github, destacado, activo)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('sssssssii',
        $titulo, $descripcion, $categoria, $tags, $icono,
        $url_demo, $url_github, $destacado, $activo);
    $ok = $stmt->execute();
    $stmt->close();
    setFlash($ok ? 'ok' : 'error',
             $ok ? 'Proyecto creado correctamente.' : 'Error al crear.');
}

redirigir('proyectos.php');
