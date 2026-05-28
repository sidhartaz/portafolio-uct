<?php
require_once 'config/db.php';
require_once 'includes/funciones.php';

// Biografía (singleton)
$bio = $conexion->query("SELECT * FROM biografia LIMIT 1")->fetch_assoc();

// Proyectos activos
$proyectos = $conexion->query(
    "SELECT * FROM proyectos WHERE activo = 1 ORDER BY destacado DESC, id DESC"
)->fetch_all(MYSQLI_ASSOC);

// Habilidades por categoría
$habilidades_lenguaje = $conexion->query(
    "SELECT * FROM habilidades
     WHERE activo = 1 AND categoria = 'lenguaje'
     ORDER BY orden ASC"
)->fetch_all(MYSQLI_ASSOC);

$habilidades_herramienta = $conexion->query(
    "SELECT * FROM habilidades
     WHERE activo = 1 AND categoria = 'herramienta'
     ORDER BY orden ASC"
)->fetch_all(MYSQLI_ASSOC);

// Flash de contacto (si vuelve del envío)
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">

<head>
  <!-- ================================================
       META & SEO
  ================================================ -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Portafolio profesional de <?= e($bio['nombre_completo']) ?>, <?= e($bio['titulo']) ?>." />
  <title><?= e($bio['nombre_completo']) ?> | <?= e($bio['titulo']) ?></title>

  <!-- ================================================
       FUENTES GOOGLE
  ================================================ -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />

  <!-- ================================================
       BOOTSTRAP 5 CSS
  ================================================ -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- ================================================
       BOOTSTRAP ICONS
  ================================================ -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <!-- ================================================
       ESTILOS PERSONALIZADOS
  ================================================ -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/animations.css" />
  <link rel="stylesheet" href="css/responsive.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
</head>

<body>

  <!-- ================================================
       LOADER INICIAL
  ================================================ -->
  <div id="loader" class="loader-wrapper" aria-hidden="true">
    <div class="loader-inner">
      <span class="loader-bracket">[</span>
      <span class="loader-text" id="loaderText">Iniciando</span>
      <span class="loader-cursor">_</span>
      <span class="loader-bracket">]</span>
    </div>
    <div class="loader-bar-track"><div class="loader-bar"></div></div>
  </div>

  <!-- ================================================
       BOTÓN MODO OSCURO / CLARO
  ================================================ -->
  <button id="themeToggle" class="theme-toggle" aria-label="Cambiar tema">
    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
  </button>

  <!-- ================================================
       NAVBAR
  ================================================ -->
<nav class="navbar navbar-expand-lg fixed-top" id="mainNav" aria-label="Navegación principal">
  <div class="container">

    <!-- Logo / Nombre -->
    <a class="navbar-brand" href="#inicio">
      <span class="brand-bracket">&lt;</span>
      CS
      <span class="brand-bracket">/&gt;</span>
    </a>

    <!-- Botón hamburguesa móvil -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav"
      aria-expanded="false" aria-label="Abrir menú">
      <span class="toggler-icon"></span>
      <span class="toggler-icon"></span>
      <span class="toggler-icon"></span>
    </button>

    <!-- Links de navegación -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="#sobre-mi">Biografía</a></li>
        <li class="nav-item"><a class="nav-link" href="#habilidades">Habilidades</a></li>
        <li class="nav-item"><a class="nav-link" href="#tecnologias">Tecnologías</a></li>
        <li class="nav-item"><a class="nav-link" href="#proyectos">Proyectos</a></li>
      </ul>

      <!-- Botón Login alineado a la derecha -->
      <a href="pages/login.php" class="btn-login">
        <i class="bi bi-person-lock"></i> Iniciar Sesión
      </a>
    </div>

  </div>
</nav>
<!-- ================================================
     SECCIÓN 1: HERO
================================================ -->
<section id="inicio" class="hero-section">

  <div class="container hero-content">
    <div class="row align-items-center min-vh-100">

      <!-- Columna texto -->
      <div class="col-lg-7 hero-text" data-animate="fade-up">
        <p class="hero-label">
          <span class="label-dot"></span> Disponible para proyectos
        </p>

        <h1 class="hero-name">
          <?php
            $partes = explode(' ', $bio['nombre_completo'], 2);
            echo e($partes[0]);
          ?><br />
          <span class="hero-name-accent"><?= e($partes[1] ?? '') ?>.</span>
        </h1>

        <p class="hero-subtitle">
          Soy <span id="typingText" class="typing-text"></span><span class="typing-cursor">|</span>
        </p>

        <p class="hero-description">
          Estudiante de Informática apasionado por construir
          soluciones tecnológicas. Me enfoco en el desarrollo web,
          la automatización y el software de código abierto.
        </p>

        <div class="hero-actions">
          <a href="#proyectos" class="btn-primary-custom">
            Ver Proyectos <i class="bi bi-arrow-right-short"></i>
          </a>
          <a href="#contacto" class="btn-ghost-custom">
            Contáctame
          </a>
        </div>

        <div class="hero-stats">
          <div class="stat-item">
            <span class="stat-number" data-target="<?= count($proyectos) ?>">0</span>
            <span class="stat-label">Proyectos</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number" data-target="<?= count($habilidades_lenguaje) + count($habilidades_herramienta) ?>">0</span>
            <span class="stat-label">Tecnologías</span>
          </div>
          <div class="stat-divider"></div>

        </div>
      </div>

      <!-- Columna avatar -->
      <div class="col-lg-5 hero-avatar-col" data-animate="fade-left">
        <div class="avatar-wrapper">
          <div class="avatar-img-wrapper">
            <img src="assets/img/226392851.jpeg" alt="Carlos Sepúlveda">
          </div>
          <div class="avatar-badge">
            <i class="bi bi-code-slash"></i> Full Stack Dev
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="scroll-indicator" aria-hidden="true">
    <div class="scroll-line"></div>
    <span>scroll</span>
  </div>

</section>

  <!-- ================================================
       SECCIÓN 2: SOBRE MÍ
  ================================================ -->
  <section id="sobre-mi" class="section-padding">
    <div class="container">

      <div class="section-header" data-animate="fade-up">
        <span class="section-number">01</span>
        <h2 class="section-title">Biografía</h2>
        <div class="section-line"></div>
      </div>

      <div class="row g-4 align-items-start mt-4">

        <div class="col-lg-6" data-animate="fade-right">
          <div class="about-bio-card">
            <div class="bio-tag">// bio.txt</div>
            <p class="bio-text">
              Hola, soy <?= e($bio['nombre_completo']) ?>.
            </p>
            <p class="bio-text">
              <?= nl2br(e($bio['descripcion'])) ?>
            </p>
            <p class="bio-text">
              Mi objetivo es crear herramientas digitales que tengan impacto real
              en las personas, combinando buenas prácticas con un
              diseño centrado en el usuario.
            </p>
            <div class="terminal-snippet">
              <div class="terminal-header">
                <span class="t-dot red"></span>
                <span class="t-dot yellow"></span>
                <span class="t-dot green"></span>
                <span class="t-title">about.js</span>
              </div>
              <pre class="terminal-code"><code><span class="c-keyword">const</span> <span class="c-var">Carlos</span> = {
  <span class="c-prop">edad</span>: <span class="c-num">26</span>,
  <span class="c-prop">ciudad</span>: <span class="c-str">"Temuco, Chile"</span>,
  <span class="c-prop">universidad</span>: <span class="c-str">"UCT"</span>,
  <span class="c-prop">idiomas</span>: [<span class="c-str">"Español"</span>, <span class="c-str">"Inglés B2"</span>],
  <span class="c-prop">hobbies</span>: [<span class="c-str">"DEBIAN"</span>, <span class="c-str">""</span>, <span class="c-str">"Anime"</span>],
  <span class="c-prop">disponible</span>: <span class="c-bool">true</span>
};</code></pre>
            </div>
          </div>
        </div>

        <div class="col-lg-6" data-animate="fade-left">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="info-card">
                <div class="info-card-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <h5>Estudios</h5>
                <p>Informática<br /><small>UCT</small></p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-card">
                <div class="info-card-icon accent-2"><i class="bi bi-lightning-charge-fill"></i></div>
                <h5>Enfoque</h5>
                <p>Web Dev &amp; DevOps<br /><small>Backend + Frontend</small></p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-card">
                <div class="info-card-icon accent-3"><i class="bi bi-cpu-fill"></i></div>
                <h5>Intereses</h5>
                <p>IA, Linux, Cloud<br /><small>Open Source</small></p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-card">
                <div class="info-card-icon accent-4"><i class="bi bi-globe2"></i></div>
                <h5>Idiomas</h5>
                <p>Español nativo<br /><small>Inglés B2 (TOEIC)</small></p>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

  <!-- ================================================
       SECCIÓN 3: HABILIDADES
  ================================================ -->
  <section id="habilidades" class="section-padding section-alt">
    <div class="container">

      <div class="section-header" data-animate="fade-up">
        <span class="section-number">02</span>
        <h2 class="section-title">Habilidades</h2>
        <div class="section-line"></div>
      </div>

      <div class="row g-4 mt-4">

        <div class="col-lg-6" data-animate="fade-right">
          <h4 class="skills-subtitle">Lenguajes &amp; Tecnologías</h4>

          <?php foreach ($habilidades_lenguaje as $h): ?>
          <div class="skill-bar-item">
            <div class="skill-bar-header">
              <span><i class="bi <?= e($h['icono']) ?>"></i> <?= e($h['nombre']) ?></span>
              <span class="skill-percent"><?= (int)$h['porcentaje'] ?>%</span>
            </div>
            <div class="skill-track">
              <div class="skill-fill" data-width="<?= (int)$h['porcentaje'] ?>"></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="col-lg-6" data-animate="fade-left">
          <h4 class="skills-subtitle">Herramientas &amp; Entorno</h4>
          <div class="row g-3">
            <div class="col-6 col-sm-4"><div class="tool-card"><i class="bi bi-ubuntu tool-icon"></i><span>Ubuntu</span></div></div>
            <div class="col-6 col-sm-4"><div class="tool-card"><i class="bi bi-github tool-icon"></i><span>GitHub</span></div></div>
            <div class="col-6 col-sm-4"><div class="tool-card"><i class="bi bi-file-code tool-icon"></i><span>VS Code</span></div></div>
            <div class="col-6 col-sm-4"><div class="tool-card"><i class="bi bi-box-seam tool-icon"></i><span>Docker</span></div></div>
            <div class="col-6 col-sm-4"><div class="tool-card"><i class="bi bi-diagram-3 tool-icon"></i><span>MySQL</span></div></div>
            <div class="col-6 col-sm-4">
              <div class="tool-card">
               <i class="devicon-debian-plain tool-icon" style="color:#A81D33;"></i>
              <span>Debian</span>
            </div>
</div>
          </div>

          <div class="soft-skills mt-4">
            <h4 class="skills-subtitle mb-3">Habilidades Blandas</h4>
            <div class="d-flex flex-wrap gap-2">
              <span class="soft-badge">Trabajo en equipo</span>
              <span class="soft-badge">Comunicación</span>
              <span class="soft-badge">Resolución de problemas</span>
              <span class="soft-badge">Aprendizaje continuo</span>
              <span class="soft-badge">Gestión del tiempo</span>
              <span class="soft-badge">Metodologías Ágiles</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================================================
     SECCIÓN: TECNOLOGÍAS DOMINADAS
================================================ -->
<section id="tecnologias" class="section-padding">
  <div class="container">

    <div class="section-header" data-animate="fade-up">
      <span class="section-number">03</span>
      <h2 class="section-title">Tecnologías Dominadas</h2>
      <div class="section-line"></div>
    </div>

    <div class="row g-4 mt-4">

      <?php
        // Partir habilidades en dos columnas según porcentaje (frontend/backend)
        // Más simple: dividir por la mitad respetando el orden
        $todas = array_merge($habilidades_lenguaje, $habilidades_herramienta);
        $mitad = (int) ceil(count($todas) / 2);
        $col1 = array_slice($todas, 0, $mitad);
        $col2 = array_slice($todas, $mitad);
      ?>

      <div class="col-lg-6" data-animate="fade-right">
        <h4 class="skills-subtitle">Stack Principal</h4>
        <?php foreach ($col1 as $t): ?>
        <div class="tech-item">
          <div class="tech-header">
            <div class="tech-info">
              <i class="bi <?= e($t['icono']) ?>"></i>
              <span><?= e($t['nombre']) ?></span>
            </div>
            <div class="tech-level nivel-<?= e($t['nivel']) ?>"><?= ucfirst(e($t['nivel'])) ?></div>
          </div>
          <div class="skill-track">
            <div class="skill-fill" data-width="<?= (int)$t['porcentaje'] ?>"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="col-lg-6" data-animate="fade-left">
        <h4 class="skills-subtitle">Stack Complementario</h4>
        <?php foreach ($col2 as $t): ?>
        <div class="tech-item">
          <div class="tech-header">
            <div class="tech-info">
              <i class="bi <?= e($t['icono']) ?>"></i>
              <span><?= e($t['nombre']) ?></span>
            </div>
            <div class="tech-level nivel-<?= e($t['nivel']) ?>"><?= ucfirst(e($t['nivel'])) ?></div>
          </div>
          <div class="skill-track">
            <div class="skill-fill" data-width="<?= (int)$t['porcentaje'] ?>"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="col-12 mt-2" data-animate="fade-up">
        <div class="tech-legend">
          <span><span class="legend-dot nivel-basico-dot"></span> Básico</span>
          <span><span class="legend-dot nivel-intermedio-dot"></span> Intermedio</span>
          <span><span class="legend-dot nivel-avanzado-dot"></span> Avanzado</span>
        </div>
      </div>

    </div>
  </div>
</section>

  <!-- ================================================
       SECCIÓN 4: PROYECTOS
  ================================================ -->
  <section id="proyectos" class="section-padding">
    <div class="container">

      <div class="section-header" data-animate="fade-up">
        <span class="section-number">04</span>
        <h2 class="section-title">Proyectos</h2>
        <div class="section-line"></div>
      </div>

      <div class="project-filters mt-4 mb-5" data-animate="fade-up">
        <button class="filter-btn active" data-filter="all">Todos</button>
        <button class="filter-btn" data-filter="web">Web</button>
        <button class="filter-btn" data-filter="python">Python</button>
        <button class="filter-btn" data-filter="linux">Linux</button>
      </div>

      <div class="row g-4" id="projectsGrid">

        <?php foreach ($proyectos as $i => $p):
          $bgClass = 'bg-project-' . (($i % 6) + 1);
          $tags = array_filter(array_map('trim', explode(',', $p['tags'] ?? '')));
        ?>
        <div class="col-md-6 col-lg-4 project-item"
             data-category="<?= e($p['categoria']) ?>" data-animate="fade-up">
          <div class="project-card <?= $p['destacado'] ? 'featured-project' : '' ?>">
            <?php if ($p['destacado']): ?>
              <div class="featured-badge"><i class="bi bi-star-fill"></i> Destacado</div>
            <?php endif; ?>
            <div class="project-thumb">
              <div class="project-thumb-bg <?= $bgClass ?>">
                <i class="bi <?= e($p['icono']) ?> project-icon"></i>
              </div>
              <div class="project-overlay">
                <a href="<?= e($p['url_github'] ?: '#') ?>" class="overlay-link"
                   aria-label="Ver código" target="_blank" rel="noopener">
                  <i class="bi bi-github"></i>
                </a>
              </div>
            </div>
            <div class="project-info">
              <div class="project-tags">
                <?php foreach ($tags as $tag): ?>
                  <span class="p-tag"><?= e($tag) ?></span>
                <?php endforeach; ?>
              </div>
              <h5 class="project-title"><?= e($p['titulo']) ?></h5>
              <p class="project-desc"><?= e($p['descripcion']) ?></p>
              <div class="project-actions">
                <a href="<?= e($p['url_github'] ?: '#') ?>" class="btn-project-gh"
                   target="_blank" rel="noopener">
                  <i class="bi bi-github"></i> GitHub
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

      </div>
    </div>
  </section>



  <!-- ================================================
       SECCIÓN 6: CONTACTO
  ================================================ -->
  <section id="contacto" class="section-padding">
    <div class="container">

      <div class="section-header" data-animate="fade-up">
        <span class="section-number">05</span>
        <h2 class="section-title">Contacto</h2>
        <div class="section-line"></div>
      </div>

      <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> mt-3" role="alert">
          <i class="bi bi-<?= $flash['tipo'] === 'ok' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
          <?= e($flash['mensaje']) ?>
        </div>
      <?php endif; ?>

      <div class="row g-5 mt-2 align-items-start">

        <div class="col-lg-5" data-animate="fade-right">
          <h3 class="contact-headline">¿Tienes un proyecto en mente?</h3>
          <p class="contact-subtext">Estoy disponible para prácticas, proyectos freelance y colaboraciones open source. No dudes en escribirme, ¡respondo en menos de 24 horas!</p>
          <ul class="contact-info-list">
            <li><i class="bi bi-envelope-at-fill"></i><a href="mailto:<?= e($bio['email']) ?>"><?= e($bio['email']) ?></a></li>
            <li><i class="bi bi-telephone-fill"></i><a href="tel:<?= e(preg_replace('/\s+/', '', $bio['telefono'])) ?>"><?= e($bio['telefono']) ?></a></li>
            <li><i class="bi bi-geo-alt-fill"></i><?= e($bio['ubicacion']) ?></li>
          </ul>
          <div class="contact-socials">
            <a href="<?= e($bio['github_url'] ?: '#') ?>" class="social-link" aria-label="GitHub" target="_blank" rel="noopener"><i class="bi bi-github"></i></a>
            <a href="<?= e($bio['linkedin_url'] ?: '#') ?>" class="social-link" aria-label="LinkedIn" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-7" data-animate="fade-left">
          <form id="contactForm" class="contact-form" method="POST" action="api/contacto.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <div class="row g-3">
              <div class="col-sm-6">
                <div class="form-group-custom">
                  <label for="contactName" class="form-label-custom">Nombre *</label>
                  <input type="text" id="contactName" name="nombre" class="form-input-custom" placeholder="Tu nombre completo" required minlength="2" />
                  <div class="form-error" id="nameError"></div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group-custom">
                  <label for="contactEmail" class="form-label-custom">Correo *</label>
                  <input type="email" id="contactEmail" name="email" class="form-input-custom" placeholder="tu@correo.com" required />
                  <div class="form-error" id="emailError"></div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group-custom">
                  <label for="contactSubject" class="form-label-custom">Asunto</label>
                  <input type="text" id="contactSubject" name="asunto" class="form-input-custom" placeholder="¿En qué puedo ayudarte?" />
                </div>
              </div>
              <div class="col-12">
                <div class="form-group-custom">
                  <label for="contactMessage" class="form-label-custom">Mensaje *</label>
                  <textarea id="contactMessage" name="mensaje" class="form-input-custom" rows="5" placeholder="Cuéntame sobre tu proyecto..." required minlength="10"></textarea>
                  <div class="form-error" id="messageError"></div>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-submit" id="submitBtn">
                  <span id="submitText">Enviar Mensaje</span>
                  <span id="submitLoader" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Enviando...
                  </span>
                  <i class="bi bi-send-fill ms-2" id="submitIcon"></i>
                </button>
              </div>
            </div>
          </form>
          <div id="formSuccess" class="d-none mt-3 alert alert-success" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            ¡Mensaje enviado! Me pondré en contacto contigo pronto.
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ================================================
       FOOTER
  ================================================ -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-inner">
        <div class="footer-brand">
          <span class="brand-bracket">&lt;</span>CS<span class="brand-bracket">/&gt;</span>
        </div>
        <div class="contact-socials">
         <a href="https://github.com" class="social-link" target="_blank" rel="noopener" aria-label="GitHub"><i class="bi bi-github"></i></a>
         <a href="https://linkedin.com" class="social-link" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
         <a href="https://x.com" class="social-link" target="_blank" rel="noopener" aria-label="Twitter/X"><i class="bi bi-twitter-x"></i></a>
         <a href="https://instagram.com" class="social-link" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        </div>
        <p class="footer-copy">
          © <span id="currentYear"></span> <?= e($bio['nombre_completo']) ?>.
        </p>
      </div>
    </div>
    <button id="backToTop" class="back-to-top" aria-label="Volver al inicio">
      <i class="bi bi-chevron-up"></i>
    </button>
  </footer>

  <!-- ================================================
       SCRIPTS
  ================================================ -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
  <script src="js/animations.js"></script>
  <script src="js/navbar.js"></script>
  <script src="js/form-validation.js"></script>

</body>

</body>
</html>
