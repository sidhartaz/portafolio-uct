<?php
require_once '../includes/funciones.php';
require_once '../config/db.php';

// Si ya está logueado, ir directo al dashboard
if (estaLogueado()) {
    redirigir('../admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validación CSRF
    if (!validarCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Token de seguridad inválido. Recarga la página.';
    } else {
        $usuario  = trim($_POST['usuario']  ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($usuario === '' || $password === '') {
            $error = 'Usuario y contraseña son obligatorios.';
        } else {
            // Consulta preparada → previene SQL injection
            $stmt = $conexion->prepare(
                "SELECT id, usuario, `password`, nombre
                 FROM usuarios WHERE usuario = ? LIMIT 1"
            );
            $stmt->bind_param('s', $usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila      = $resultado->fetch_assoc();
            $stmt->close();

            if ($fila && password_verify($password, $fila['password'])) {
                // Login OK → regenerar id de sesión (anti session fixation)
                session_regenerate_id(true);
                $_SESSION['admin_id']     = $fila['id'];
                $_SESSION['admin_user']   = $fila['usuario'];
                $_SESSION['admin_nombre'] = $fila['nombre'];

                redirigir('../admin/dashboard.php');
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Portafolio Carlos Sepúlveda</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Estilos propios del login -->
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="login-container">

    <a href="../index.php" class="back-link">
        <i class="bi bi-arrow-left"></i> Volver al portafolio
    </a>

    <div class="login-header">
        <div class="login-icon-wrap">
            <i class="bi bi-shield-lock"></i>
        </div>
        <div class="login-badge">
            <span class="bracket">&lt;</span> ACCESO ADMIN <span class="bracket">/&gt;</span>
        </div>
        <h1 class="login-title">Iniciar sesión</h1>
        <p class="login-subtitle">Accede con tus credenciales</p>
    </div>

    <?php if ($error): ?>
        <div class="alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= e($error) ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

        <div class="form-group">
            <label for="usuario" class="form-label">USUARIO</label>
            <div class="input-wrap">
                <input type="text" name="usuario" id="usuario"
                       class="form-input"
                       placeholder="tu usuario"
                       value="<?= e($_POST['usuario'] ?? '') ?>"
                       required autofocus autocomplete="username">
                <i class="bi bi-person input-icon"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">CONTRASEÑA</label>
            <div class="input-wrap">
                <input type="password" name="password" id="password"
                       class="form-input form-input-pass"
                       placeholder="••••••••"
                       required autocomplete="current-password">
                <i class="bi bi-lock input-icon"></i>
                <button type="button" class="toggle-pass" id="togglePass" aria-label="Mostrar contraseña">
                    <i class="bi bi-eye" id="togglePassIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-login">
            Ingresar al panel <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <div class="login-footer">
        <span class="bracket">&lt;</span>CS<span class="bracket">/&gt;</span> · Portafolio UCT 2026
    </div>

</div>

<script>
    // Toggle mostrar/ocultar contraseña
    const toggleBtn  = document.getElementById('togglePass');
    const toggleIcon = document.getElementById('togglePassIcon');
    const passInput  = document.getElementById('password');

    toggleBtn.addEventListener('click', () => {
        if (passInput.type === 'password') {
            passInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
            toggleBtn.setAttribute('aria-label', 'Ocultar contraseña');
        } else {
            passInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
            toggleBtn.setAttribute('aria-label', 'Mostrar contraseña');
        }
    });
</script>

</body>
</html>