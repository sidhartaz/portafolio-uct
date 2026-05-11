/**
 * ============================================================
 * PORTAFOLIO — script.js
 * Funcionalidades JS:
 *   1. Loader inicial
 *   2. Modo oscuro / claro
 *   3. Navbar (scroll + active link)
 *   4. Efecto typing
 *   5. Partículas en canvas
 *   6. Animaciones al scroll (Intersection Observer)
 *   7. Contadores animados (stats)
 *   8. Barras de habilidades animadas
 *   9. Filtro de proyectos
 *  10. Validación y envío del formulario
 *  11. Botón volver arriba
 *  12. Año actual en footer
 * ============================================================
 */

'use strict';

/* ============================================================
   UTILIDADES
   ============================================================ */

/**
 * Selecciona un elemento del DOM.
 * @param {string} selector - CSS selector
 * @param {Element} [parent=document]
 * @returns {Element|null}
 */
const $ = (selector, parent = document) => parent.querySelector(selector);

/**
 * Selecciona todos los elementos que coincidan.
 * @param {string} selector
 * @param {Element} [parent=document]
 * @returns {NodeList}
 */
const $$ = (selector, parent = document) => parent.querySelectorAll(selector);


/* ============================================================
   1. LOADER INICIAL
   Simula carga y oculta el loader al terminar.
   ============================================================ */
(function initLoader() {
  const loader   = $('#loader');
  const loaderTx = $('#loaderText');

  // Mensajes que se van mostrando mientras carga
  const loadMessages = ['Iniciando', 'Cargando recursos', 'Preparando portafolio', 'Listo'];
  let msgIndex = 0;

  // Cambiar texto cada 500ms
  const msgInterval = setInterval(() => {
    msgIndex++;
    if (msgIndex < loadMessages.length && loaderTx) {
      loaderTx.textContent = loadMessages[msgIndex];
    } else {
      clearInterval(msgInterval);
    }
  }, 500);

  // Ocultar loader cuando termine la carga (mínimo 2.2s para ver la animación)
  window.addEventListener('load', () => {
    setTimeout(() => {
      if (loader) {
        loader.classList.add('hidden');
        // Remover del DOM para no bloquear eventos
        loader.addEventListener('transitionend', () => loader.remove(), { once: true });
      }
    }, 2200);
  });
})();


/* ============================================================
   2. MODO OSCURO / CLARO
   Persiste la preferencia en localStorage.
   ============================================================ */
(function initThemeToggle() {
  const toggleBtn = $('#themeToggle');
  const themeIcon = $('#themeIcon');
  const html      = document.documentElement;

  /**
   * Aplica un tema al documento.
   * @param {'dark'|'light'} theme
   */
  function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    if (themeIcon) {
      themeIcon.className = theme === 'dark'
        ? 'bi bi-sun-fill'          // Mostrar sol (para pasar a claro)
        : 'bi bi-moon-stars-fill';  // Mostrar luna (para pasar a oscuro)
    }
  }

  // Leer preferencia guardada (o preferir del sistema)
  const savedTheme = localStorage.getItem('portfolioTheme') ||
    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

  applyTheme(savedTheme);

  // Click en el botón de tema
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const current = html.getAttribute('data-theme');
      const next    = current === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      localStorage.setItem('portfolioTheme', next);
    });
  }
})();


/* ============================================================
   3. NAVBAR
   - Añade clase 'scrolled' al hacer scroll.
   - Resalta el link activo según la sección visible.
   ============================================================ */
(function initNavbar() {
  const nav      = $('#mainNav');
  const navLinks = $$('.nav-link');

  // Scroll: añadir clase al navbar
  window.addEventListener('scroll', () => {
    if (!nav) return;
    nav.classList.toggle('scrolled', window.scrollY > 60);
    updateActiveLink();
    toggleBackToTop();
  }, { passive: true });

  /**
   * Calcula qué sección está actualmente en la pantalla
   * y activa el link correspondiente en el navbar.
   */
  function updateActiveLink() {
    const sections  = $$('section[id]');
    const scrollPos = window.scrollY + 100;

    sections.forEach(section => {
      const top    = section.offsetTop;
      const height = section.offsetHeight;
      const id     = section.getAttribute('id');

      if (scrollPos >= top && scrollPos < top + height) {
        navLinks.forEach(link => {
          link.classList.toggle(
            'active',
            link.getAttribute('href') === `#${id}`
          );
        });
      }
    });
  }

  // Cerrar menú móvil al hacer click en un link
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      const collapse = $('#navbarNav');
      if (collapse?.classList.contains('show')) {
        // Bootstrap API para cerrar el collapse
        bootstrap.Collapse.getInstance(collapse)?.hide();
      }
    });
  });
})();


/* ============================================================
   4. EFECTO TYPING
   Escribe y borra frases en un loop.
   ============================================================ */
(function initTypingEffect() {
  const el     = $('#typingText');
  if (!el) return;

  const phrases = [
    'Desarrollador Web',
    'Estudiante de Informática',
    'Amante del Open Source',
    'Entusiasta de Linux',
    'Aprendiz constante'
  ];

  let phraseIndex  = 0;  // Índice de la frase actual
  let charIndex    = 0;  // Índice del carácter actual
  let isDeleting   = false;
  let typingSpeed  = 95; // ms entre caracteres al escribir
  let deleteSpeed  = 50; // ms entre caracteres al borrar
  let pauseDelay   = 1800; // ms de pausa antes de borrar

  function type() {
    const current = phrases[phraseIndex];

    if (isDeleting) {
      // Borrar un carácter
      el.textContent = current.substring(0, charIndex - 1);
      charIndex--;

      if (charIndex === 0) {
        isDeleting  = false;
        phraseIndex = (phraseIndex + 1) % phrases.length;
        setTimeout(type, 400);
        return;
      }
      setTimeout(type, deleteSpeed);

    } else {
      // Escribir un carácter
      el.textContent = current.substring(0, charIndex + 1);
      charIndex++;

      if (charIndex === current.length) {
        // Pausa al terminar de escribir
        isDeleting = true;
        setTimeout(type, pauseDelay);
        return;
      }
      setTimeout(type, typingSpeed);
    }
  }

  // Iniciar con un delay para que el loader desaparezca primero
  setTimeout(type, 2400);
})();


/* ============================================================
   5. CANVAS DE PARTÍCULAS (Fondo Hero)
   Puntos que flotan y se conectan con líneas si están cerca.
   ============================================================ */
(function initParticles() {
  const canvas = $('#particleCanvas');
  if (!canvas) return;

  const ctx    = canvas.getContext('2d');
  let particles = [];

  // Configuración de partículas
  const CONFIG = {
    count:        80,
    maxRadius:    2.5,
    minRadius:    0.8,
    speed:        0.3,
    connectDist:  130,
    color:        '15, 247, 200',   // RGB del accent-1
    colorAlt:     '0, 196, 255',    // RGB del accent-2
  };

  // Redimensionar canvas al tamaño de la sección hero
  function resize() {
    canvas.width  = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
  }

  // Crear una partícula con propiedades aleatorias
  function createParticle() {
    return {
      x:   Math.random() * canvas.width,
      y:   Math.random() * canvas.height,
      vx:  (Math.random() - 0.5) * CONFIG.speed,
      vy:  (Math.random() - 0.5) * CONFIG.speed,
      r:   Math.random() * (CONFIG.maxRadius - CONFIG.minRadius) + CONFIG.minRadius,
      opacity: Math.random() * 0.5 + 0.2,
      colorKey: Math.random() > 0.6 ? CONFIG.colorAlt : CONFIG.color,
    };
  }

  // Inicializar partículas
  function init() {
    particles = Array.from({ length: CONFIG.count }, createParticle);
  }

  // Dibujar y mover en cada frame
  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    particles.forEach((p, i) => {
      // Mover
      p.x += p.vx;
      p.y += p.vy;

      // Rebotar en bordes
      if (p.x < 0 || p.x > canvas.width)  p.vx *= -1;
      if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

      // Dibujar círculo
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${p.colorKey}, ${p.opacity})`;
      ctx.fill();

      // Conectar con otras partículas cercanas
      for (let j = i + 1; j < particles.length; j++) {
        const q    = particles[j];
        const dist = Math.hypot(p.x - q.x, p.y - q.y);

        if (dist < CONFIG.connectDist) {
          const alpha = (1 - dist / CONFIG.connectDist) * 0.25;
          ctx.beginPath();
          ctx.moveTo(p.x, p.y);
          ctx.lineTo(q.x, q.y);
          ctx.strokeStyle = `rgba(${CONFIG.color}, ${alpha})`;
          ctx.lineWidth   = 0.8;
          ctx.stroke();
        }
      }
    });

    requestAnimationFrame(draw);
  }

  // Responder al resize de la ventana
  const resizeObserver = new ResizeObserver(() => {
    resize();
    init();
  });
  resizeObserver.observe(canvas.parentElement);

  resize();
  init();
  draw();
})();


/* ============================================================
   6. ANIMACIONES AL SCROLL
   Usa IntersectionObserver para activar clase 'animated'
   en elementos con atributo [data-animate].
   ============================================================ */
(function initScrollAnimations() {
  const elements = $$('[data-animate]');
  if (!elements.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animated');
        // Una vez animado, dejar de observar para rendimiento
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold:  0.12,
    rootMargin: '0px 0px -50px 0px'
  });

  elements.forEach(el => observer.observe(el));
})();


/* ============================================================
   7. CONTADORES ANIMADOS (stats del hero)
   ============================================================ */
(function initCounters() {
  const counters = $$('.stat-number[data-target]');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;

      const el     = entry.target;
      const target = parseInt(el.getAttribute('data-target'), 10);
      const duration = 1500; // ms
      const step     = target / (duration / 16); // 60fps
      let current    = 0;

      const timer = setInterval(() => {
        current += step;
        if (current >= target) {
          el.textContent = target + '+';
          clearInterval(timer);
        } else {
          el.textContent = Math.floor(current);
        }
      }, 16);

      observer.unobserve(el);
    });
  }, { threshold: 0.5 });

  counters.forEach(counter => observer.observe(counter));
})();


/* ============================================================
   8. BARRAS DE HABILIDADES ANIMADAS
   Se activan con IntersectionObserver al entrar al viewport.
   ============================================================ */
(function initSkillBars() {
  const bars = $$('.skill-fill[data-width]');
  if (!bars.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const bar       = entry.target;
      const targetPct = bar.getAttribute('data-width') + '%';
      // Pequeño delay para que la animación se vea después de entrar
      setTimeout(() => { bar.style.width = targetPct; }, 100);
      observer.unobserve(bar);
    });
  }, { threshold: 0.3 });

  bars.forEach(bar => observer.observe(bar));
})();


/* ============================================================
   9. FILTRO DE PROYECTOS
   Muestra/oculta cards según la categoría seleccionada.
   ============================================================ */
(function initProjectFilter() {
  const filterBtns  = $$('.filter-btn');
  const projectItems = $$('.project-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      // Actualizar botón activo
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.getAttribute('data-filter');

      projectItems.forEach(item => {
        const category = item.getAttribute('data-category');

        // Mostrar si coincide con el filtro o si es "all"
        if (filter === 'all' || category === filter) {
          item.classList.remove('hidden');
          // Pequeña animación de reaparición
          item.style.animation = 'none';
          item.offsetHeight;  // forzar reflow
          item.style.animation = 'fadeInUp 0.4s ease forwards';
        } else {
          item.classList.add('hidden');
        }
      });
    });
  });
})();


/* ============================================================
   10. FORMULARIO DE CONTACTO
   Validación en tiempo real + simulación de envío.
   ============================================================ */
(function initContactForm() {
  const form        = $('#contactForm');
  if (!form) return;

  const nameInput    = $('#contactName');
  const emailInput   = $('#contactEmail');
  const messageInput = $('#contactMessage');
  const submitBtn    = $('#submitBtn');
  const submitText   = $('#submitText');
  const submitLoader = $('#submitLoader');
  const submitIcon   = $('#submitIcon');
  const successMsg   = $('#formSuccess');

  /* ── Validaciones ── */

  /**
   * Valida el campo nombre.
   * @returns {boolean}
   */
  function validateName() {
    const val = nameInput.value.trim();
    const err = $('#nameError');

    if (!val) {
      showError(nameInput, err, 'El nombre es obligatorio.');
      return false;
    }
    if (val.length < 2) {
      showError(nameInput, err, 'Mínimo 2 caracteres.');
      return false;
    }
    showValid(nameInput, err);
    return true;
  }

  /**
   * Valida el campo email con regex básico.
   * @returns {boolean}
   */
  function validateEmail() {
    const val  = emailInput.value.trim();
    const err  = $('#emailError');
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!val) {
      showError(emailInput, err, 'El correo es obligatorio.');
      return false;
    }
    if (!regex.test(val)) {
      showError(emailInput, err, 'Ingresa un correo válido.');
      return false;
    }
    showValid(emailInput, err);
    return true;
  }

  /**
   * Valida el campo mensaje.
   * @returns {boolean}
   */
  function validateMessage() {
    const val = messageInput.value.trim();
    const err = $('#messageError');

    if (!val) {
      showError(messageInput, err, 'El mensaje es obligatorio.');
      return false;
    }
    if (val.length < 10) {
      showError(messageInput, err, 'Mínimo 10 caracteres.');
      return false;
    }
    showValid(messageInput, err);
    return true;
  }

  /* ── Helpers de estado visual ── */

  function showError(input, errorEl, msg) {
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    if (errorEl) errorEl.textContent = msg;
  }

  function showValid(input, errorEl) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    if (errorEl) errorEl.textContent = '';
  }

  /* ── Validación en tiempo real (al escribir) ── */
  nameInput?.addEventListener('input', validateName);
  emailInput?.addEventListener('input', validateEmail);
  messageInput?.addEventListener('input', validateMessage);

  /* ── Submit del formulario ── */
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Validar todos los campos
    const isNameOk    = validateName();
    const isEmailOk   = validateEmail();
    const isMessageOk = validateMessage();

    if (!isNameOk || !isEmailOk || !isMessageOk) return;

    // Mostrar estado de carga
    setSubmitState('loading');

    try {
      // SIMULACIÓN: en producción aquí va el fetch() a tu API o servicio
      await simulateSend();

      // Éxito
      setSubmitState('success');
      form.reset();
      // Quitar clases de validación
      [nameInput, emailInput, messageInput].forEach(inp => {
        inp.classList.remove('is-valid', 'is-invalid');
      });
      successMsg?.classList.remove('d-none');

      // Ocultar mensaje de éxito después de 5s
      setTimeout(() => successMsg?.classList.add('d-none'), 5000);

    } catch (err) {
      console.error('Error al enviar formulario:', err);
      setSubmitState('error');
    }
  });

  /**
   * Simula una solicitud de red con un delay de 1.5s.
   * Reemplazar con fetch() real en producción.
   */
  function simulateSend() {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        // 90% de éxito en la simulación
        Math.random() > 0.1 ? resolve() : reject(new Error('Simulated error'));
      }, 1500);
    });
  }

  /**
   * Cambia el estado visual del botón de enviar.
   * @param {'loading'|'success'|'error'} state
   */
  function setSubmitState(state) {
    if (!submitBtn) return;

    if (state === 'loading') {
      submitBtn.disabled = true;
      submitText?.classList.add('d-none');
      submitLoader?.classList.remove('d-none');
      if (submitIcon) submitIcon.className = '';
    } else {
      submitBtn.disabled = false;
      submitText?.classList.remove('d-none');
      submitLoader?.classList.add('d-none');

      if (state === 'success') {
        submitText.textContent = 'Enviado ✓';
        if (submitIcon) submitIcon.className = '';
        setTimeout(() => {
          submitText.textContent = 'Enviar Mensaje';
          if (submitIcon) submitIcon.className = 'bi bi-send-fill ms-2';
        }, 3000);
      } else {
        submitText.textContent = 'Error. Intenta de nuevo.';
        if (submitIcon) submitIcon.className = 'bi bi-exclamation-circle ms-2';
        setTimeout(() => {
          submitText.textContent = 'Enviar Mensaje';
          if (submitIcon) submitIcon.className = 'bi bi-send-fill ms-2';
        }, 4000);
      }
    }
  }
})();


/* ============================================================
   11. BOTÓN VOLVER ARRIBA
   ============================================================ */
function toggleBackToTop() {
  const btn = $('#backToTop');
  if (!btn) return;
  btn.classList.toggle('visible', window.scrollY > 400);
}

$('#backToTop')?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});


/* ============================================================
   12. AÑO ACTUAL EN FOOTER
   ============================================================ */
const yearEl = $('#currentYear');
if (yearEl) yearEl.textContent = new Date().getFullYear();


/* ============================================================
   13. SMOOTH SCROLL para links internos (refuerzo)
   Bootstrap + CSS scroll-behavior lo manejan, pero
   este código añade control adicional.
   ============================================================ */
$$('a[href^="#"]').forEach(link => {
  link.addEventListener('click', (e) => {
    const href = link.getAttribute('href');
    if (href === '#') {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    const target = $(href);
    if (target) {
      e.preventDefault();
      const navHeight = $('#mainNav')?.offsetHeight || 72;
      const top       = target.getBoundingClientRect().top + window.scrollY - navHeight;
      window.scrollTo({ top, behavior: 'smooth' });
    }
  });
});


/* ============================================================
   14. EASTER EGG EN CONSOLA
   Un pequeño detalle para devs que inspeccionen el código.
   ============================================================ */
console.log(
  '%c< Alejandro Reyes /> ',
  'color: #0ff7c8; font-family: monospace; font-size: 1.2rem; font-weight: bold;'
);
console.log(
  '%cPortafolio v1.0 | HTML5 + CSS3 + JS + Bootstrap 5',
  'color: #8b949e; font-family: monospace; font-size: 0.85rem;'
);
console.log(
  '%c¡Hola, dev curioso! Si quieres charlar, contáctame :)',
  'color: #00c4ff; font-family: monospace; font-size: 0.9rem;'
);
