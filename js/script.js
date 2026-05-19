/**
 * ============================================================
 * PORTAFOLIO — script.js
 * Carlos Sepúlveda | Ingeniería Informática
 * ============================================================
 *  1. Loader inicial
 *  2. Modo oscuro / claro
 *  3. Navbar (scroll + active link)
 *  4. Efecto typing
 *  5. Animaciones al scroll (Intersection Observer)
 *  6. Contadores animados (stats)
 *  7. Barras de habilidades animadas
 *  8. Filtro de proyectos
 *  9. Formulario de contacto
 * 10. Modal de login
 * 11. Botón volver arriba
 * 12. Año actual en footer
 * 13. Smooth scroll
 * ============================================================
 */

'use strict';

/* ============================================================
   UTILIDADES
   ============================================================ */
const $  = (selector, parent = document) => parent.querySelector(selector);
const $$ = (selector, parent = document) => parent.querySelectorAll(selector);


/* ============================================================
   1. LOADER INICIAL
   ============================================================ */
(function initLoader() {
  const loader   = $('#loader');
  const loaderTx = $('#loaderText');
  const messages = ['Iniciando', 'Cargando recursos', 'Preparando portafolio', 'Listo'];
  let index = 0;

  const interval = setInterval(() => {
    index++;
    if (index < messages.length && loaderTx) {
      loaderTx.textContent = messages[index];
    } else {
      clearInterval(interval);
    }
  }, 500);

  window.addEventListener('load', () => {
    setTimeout(() => {
      if (!loader) return;
      loader.classList.add('hidden');
      loader.addEventListener('transitionend', () => loader.remove(), { once: true });
    }, 2200);
  });
})();


/* ============================================================
   2. MODO OSCURO / CLARO
   ============================================================ */
(function initThemeToggle() {
  const toggleBtn = $('#themeToggle');
  const themeIcon = $('#themeIcon');
  const html      = document.documentElement;

  function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    if (themeIcon) {
      themeIcon.className = theme === 'dark'
        ? 'bi bi-sun-fill'
        : 'bi bi-moon-stars-fill';
    }
  }

  const saved = localStorage.getItem('portfolioTheme') ||
    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

  applyTheme(saved);

  toggleBtn?.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    localStorage.setItem('portfolioTheme', next);
  });
})();


/* ============================================================
   3. NAVBAR
   ============================================================ */
(function initNavbar() {
  const nav      = $('#mainNav');
  const navLinks = $$('.nav-link');

  window.addEventListener('scroll', () => {
    nav?.classList.toggle('scrolled', window.scrollY > 60);
    updateActiveLink();
    toggleBackToTop();
  }, { passive: true });

  function updateActiveLink() {
    const scrollPos = window.scrollY + 100;
    $$('section[id]').forEach(section => {
      const top    = section.offsetTop;
      const height = section.offsetHeight;
      const id     = section.getAttribute('id');
      if (scrollPos >= top && scrollPos < top + height) {
        navLinks.forEach(link => {
          link.classList.toggle('active', link.getAttribute('href') === `#${id}`);
        });
      }
    });
  }

  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      const collapse = $('#navbarNav');
      if (collapse?.classList.contains('show')) {
        bootstrap.Collapse.getInstance(collapse)?.hide();
      }
    });
  });
})();


/* ============================================================
   4. EFECTO TYPING
   ============================================================ */
(function initTypingEffect() {
  const el = $('#typingText');
  if (!el) return;

  const phrases = [
    'Desarrollador Web',
    'Estudiante de Informática',
    'Amante del Open Source',
    'Entusiasta de Linux',
    'Aprendiz constante'
  ];

  let phraseIndex = 0;
  let charIndex   = 0;
  let isDeleting  = false;

  function type() {
    const current = phrases[phraseIndex];

    if (isDeleting) {
      el.textContent = current.substring(0, charIndex - 1);
      charIndex--;
      if (charIndex === 0) {
        isDeleting  = false;
        phraseIndex = (phraseIndex + 1) % phrases.length;
        setTimeout(type, 400);
        return;
      }
      setTimeout(type, 50);
    } else {
      el.textContent = current.substring(0, charIndex + 1);
      charIndex++;
      if (charIndex === current.length) {
        isDeleting = true;
        setTimeout(type, 1800);
        return;
      }
      setTimeout(type, 95);
    }
  }

  setTimeout(type, 2400);
})();


/* ============================================================
   5. ANIMACIONES AL SCROLL
   ============================================================ */
(function initScrollAnimations() {
  const elements = $$('[data-animate]');
  if (!elements.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animated');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

  elements.forEach(el => observer.observe(el));
})();


/* ============================================================
   6. CONTADORES ANIMADOS
   ============================================================ */
(function initCounters() {
  const counters = $$('.stat-number[data-target]');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el       = entry.target;
      const target   = parseInt(el.getAttribute('data-target'), 10);
      const step     = target / (1500 / 16);
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

  counters.forEach(c => observer.observe(c));
})();


/* ============================================================
   7. BARRAS DE HABILIDADES ANIMADAS
   ============================================================ */
(function initSkillBars() {
  const bars = $$('.skill-fill[data-width]');
  if (!bars.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      setTimeout(() => {
        entry.target.style.width = entry.target.getAttribute('data-width') + '%';
      }, 100);
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.3 });

  bars.forEach(bar => observer.observe(bar));
})();


/* ============================================================
   8. FILTRO DE PROYECTOS
   ============================================================ */
(function initProjectFilter() {
  const filterBtns   = $$('.filter-btn');
  const projectItems = $$('.project-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.getAttribute('data-filter');

      projectItems.forEach(item => {
        const match = filter === 'all' || item.getAttribute('data-category') === filter;
        item.classList.toggle('hidden', !match);
        if (match) {
          item.style.animation = 'none';
          item.offsetHeight;
          item.style.animation = 'fadeInUp 0.4s ease forwards';
        }
      });
    });
  });
})();


/* ============================================================
   9. FORMULARIO DE CONTACTO
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

  function validateName() {
    const val = nameInput.value.trim();
    const err = $('#nameError');
    if (!val)          { showError(nameInput, err, 'El nombre es obligatorio.'); return false; }
    if (val.length < 2){ showError(nameInput, err, 'Mínimo 2 caracteres.');      return false; }
    showValid(nameInput, err);
    return true;
  }

  function validateEmail() {
    const val = emailInput.value.trim();
    const err = $('#emailError');
    if (!val)                          { showError(emailInput, err, 'El correo es obligatorio.'); return false; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) { showError(emailInput, err, 'Correo inválido.'); return false; }
    showValid(emailInput, err);
    return true;
  }

  function validateMessage() {
    const val = messageInput.value.trim();
    const err = $('#messageError');
    if (!val)           { showError(messageInput, err, 'El mensaje es obligatorio.'); return false; }
    if (val.length < 10){ showError(messageInput, err, 'Mínimo 10 caracteres.');      return false; }
    showValid(messageInput, err);
    return true;
  }

  nameInput?.addEventListener('input', validateName);
  emailInput?.addEventListener('input', validateEmail);
  messageInput?.addEventListener('input', validateMessage);

  function setSubmitState(state) {
    if (!submitBtn) return;
    submitBtn.disabled = state === 'loading';
    submitText?.classList.toggle('d-none', state === 'loading');
    submitLoader?.classList.toggle('d-none', state !== 'loading');

    if (state === 'success') {
      submitText.textContent = 'Enviado ✓';
      if (submitIcon) submitIcon.className = '';
      setTimeout(() => {
        submitText.textContent = 'Enviar Mensaje';
        if (submitIcon) submitIcon.className = 'bi bi-send-fill ms-2';
      }, 3000);
    } else if (state === 'error') {
      submitText.textContent = 'Error. Intenta de nuevo.';
      setTimeout(() => {
        submitText.textContent = 'Enviar Mensaje';
        if (submitIcon) submitIcon.className = 'bi bi-send-fill ms-2';
      }, 4000);
    }
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!validateName() | !validateEmail() | !validateMessage()) return;

    setSubmitState('loading');
    try {
      await new Promise(resolve => setTimeout(resolve, 1500));
      setSubmitState('success');
      form.reset();
      [nameInput, emailInput, messageInput].forEach(i => i.classList.remove('is-valid', 'is-invalid'));
      successMsg?.classList.remove('d-none');
      setTimeout(() => successMsg?.classList.add('d-none'), 5000);
    } catch {
      setSubmitState('error');
    }
  });
})();


/* ============================================================
   10. MODAL DE LOGIN
   ============================================================ */
(function initLogin() {
  const form       = $('#loginForm');
  if (!form) return;

  const userInput  = $('#loginUser');
  const passInput  = $('#loginPass');
  const togglePass = $('#togglePass');
  const toggleIcon = $('#togglePassIcon');
  const loginError = $('#loginError');

  // Credenciales de prueba (en producción van en PHP)
  const ADMIN_USER = 'admin';
  const ADMIN_PASS = '1234';

  // Toggle ver/ocultar contraseña
  togglePass?.addEventListener('click', () => {
    const isPass = passInput.type === 'password';
    passInput.type       = isPass ? 'text' : 'password';
    toggleIcon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
  });

  // Ocultar error al escribir
  userInput?.addEventListener('input', () => loginError?.classList.add('d-none'));
  passInput?.addEventListener('input', () => loginError?.classList.add('d-none'));

  // Submit
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valid = true;

    if (!userInput.value.trim()) {
      $('#loginUserError').textContent = 'El usuario es obligatorio.';
      userInput.classList.add('is-invalid');
      valid = false;
    } else {
      $('#loginUserError').textContent = '';
      userInput.classList.remove('is-invalid');
    }

    if (!passInput.value.trim()) {
      $('#loginPassError').textContent = 'La contraseña es obligatoria.';
      passInput.classList.add('is-invalid');
      valid = false;
    } else {
      $('#loginPassError').textContent = '';
      passInput.classList.remove('is-invalid');
    }

    if (!valid) return;

    // Verificar credenciales y redirigir al dashboard
    if (userInput.value === ADMIN_USER && passInput.value === ADMIN_PASS) {
      window.location.href = 'dashboard.html';
    } else {
      loginError?.classList.remove('d-none');
    }
  });
})();


/* ============================================================
   11. BOTÓN VOLVER ARRIBA
   ============================================================ */
function toggleBackToTop() {
  $('#backToTop')?.classList.toggle('visible', window.scrollY > 400);
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
   13. SMOOTH SCROLL
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
      const top = target.getBoundingClientRect().top + window.scrollY - ($('#mainNav')?.offsetHeight || 72);
      window.scrollTo({ top, behavior: 'smooth' });
    }
  });
});


/* ============================================================
   EASTER EGG
   ============================================================ */
console.log('%c< Carlos Sepúlveda />', 'color:#0ff7c8;font-family:monospace;font-size:1.2rem;font-weight:bold;');
console.log('%cPortafolio v1.0 | HTML5 + CSS3 + JS + Bootstrap 5', 'color:#8b949e;font-family:monospace;font-size:0.85rem;');