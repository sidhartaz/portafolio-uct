/**
 * ============================================================
 * PORTAFOLIO — animations.js
 * Animaciones controladas por JavaScript:
 *   1. Loader inicial
 *   2. Animaciones al scroll (Intersection Observer)
 *   3. Contadores animados (stats)
 *   4. Barras de habilidades animadas
 *   5. Efecto typing
 *   6. Canvas de partículas (fondo hero)
 * ============================================================
 */

'use strict';


/* ============================================================
   1. LOADER INICIAL
   ============================================================ */
(function initLoader() {
  const loader   = $('#loader');
  const loaderTx = $('#loaderText');

  const loadMessages = ['Iniciando', 'Cargando recursos', 'Preparando portafolio', 'Listo'];
  let msgIndex = 0;

  const msgInterval = setInterval(() => {
    msgIndex++;
    if (msgIndex < loadMessages.length && loaderTx) {
      loaderTx.textContent = loadMessages[msgIndex];
    } else {
      clearInterval(msgInterval);
    }
  }, 500);

  window.addEventListener('load', () => {
    setTimeout(() => {
      if (loader) {
        loader.classList.add('hidden');
        loader.addEventListener('transitionend', () => loader.remove(), { once: true });
      }
    }, 2200);
  });
})();


/* ============================================================
   2. ANIMACIONES AL SCROLL (Intersection Observer)
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
  }, {
    threshold:  0.12,
    rootMargin: '0px 0px -50px 0px'
  });

  elements.forEach(el => observer.observe(el));
})();


/* ============================================================
   3. CONTADORES ANIMADOS
   ============================================================ */
(function initCounters() {
  const counters = $$('.stat-number[data-target]');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;

      const el       = entry.target;
      const target   = parseInt(el.getAttribute('data-target'), 10);
      const duration = 1500;
      const step     = target / (duration / 16);
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
   4. BARRAS DE HABILIDADES ANIMADAS
   ============================================================ */
(function initSkillBars() {
  const bars = $$('.skill-fill[data-width]');
  if (!bars.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const bar       = entry.target;
      const targetPct = bar.getAttribute('data-width') + '%';
      setTimeout(() => { bar.style.width = targetPct; }, 100);
      observer.unobserve(bar);
    });
  }, { threshold: 0.3 });

  bars.forEach(bar => observer.observe(bar));
})();


/* ============================================================
   5. EFECTO TYPING
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

  let phraseIndex  = 0;
  let charIndex    = 0;
  let isDeleting   = false;
  const typingSpeed  = 95;
  const deleteSpeed  = 50;
  const pauseDelay   = 1800;

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
      setTimeout(type, deleteSpeed);
    } else {
      el.textContent = current.substring(0, charIndex + 1);
      charIndex++;

      if (charIndex === current.length) {
        isDeleting = true;
        setTimeout(type, pauseDelay);
        return;
      }
      setTimeout(type, typingSpeed);
    }
  }

  setTimeout(type, 2400);
})();


/* ============================================================
   6. CANVAS DE PARTÍCULAS
   ============================================================ */
(function initParticles() {
  const canvas = $('#particleCanvas');
  if (!canvas) return;

  const ctx    = canvas.getContext('2d');
  let particles = [];

  const CONFIG = {
    count:       80,
    maxRadius:   2.5,
    minRadius:   0.8,
    speed:       0.3,
    connectDist: 130,
    color:       '15, 247, 200',
    colorAlt:    '0, 196, 255',
  };

  function resize() {
    canvas.width  = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
  }

  function createParticle() {
    return {
      x:        Math.random() * canvas.width,
      y:        Math.random() * canvas.height,
      vx:       (Math.random() - 0.5) * CONFIG.speed,
      vy:       (Math.random() - 0.5) * CONFIG.speed,
      r:        Math.random() * (CONFIG.maxRadius - CONFIG.minRadius) + CONFIG.minRadius,
      opacity:  Math.random() * 0.5 + 0.2,
      colorKey: Math.random() > 0.6 ? CONFIG.colorAlt : CONFIG.color,
    };
  }

  function init() {
    particles = Array.from({ length: CONFIG.count }, createParticle);
  }

  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    particles.forEach((p, i) => {
      p.x += p.vx;
      p.y += p.vy;

      if (p.x < 0 || p.x > canvas.width)  p.vx *= -1;
      if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${p.colorKey}, ${p.opacity})`;
      ctx.fill();

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

  const resizeObserver = new ResizeObserver(() => {
    resize();
    init();
  });
  resizeObserver.observe(canvas.parentElement);

  resize();
  init();
  draw();
})();