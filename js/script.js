/**
 * ============================================================
 * PORTAFOLIO — script.js
 * Carlos Sepúlveda | Ingeniería Informática
 * ============================================================
 *  Utilidades compartidas ($, $$) + funciones exclusivas:
 *  1. Modo oscuro / claro
 *  2. Filtro de proyectos
 *  3. Botón volver arriba
 *  4. Año actual en footer
 *
 *  Loader, animaciones, typing, navbar y smooth scroll
 *  están en sus archivos dedicados (animations.js, navbar.js).
 * ============================================================
*/

'use strict';

const $  = (selector, parent = document) => parent.querySelector(selector);
const $$ = (selector, parent = document) => parent.querySelectorAll(selector);


/* ============================================================
   1. MODO OSCURO / CLARO
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
   2. FILTRO DE PROYECTOS
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
   3. BOTÓN VOLVER ARRIBA
   ============================================================ */
window.addEventListener('scroll', () => {
  $('#backToTop')?.classList.toggle('visible', window.scrollY > 400);
}, { passive: true });

$('#backToTop')?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});


/* ============================================================
   4. AÑO ACTUAL EN FOOTER
   ============================================================ */
const yearEl = $('#currentYear');
if (yearEl) yearEl.textContent = new Date().getFullYear();


/* ============================================================
   EASTER EGG
   ============================================================ */
console.log('%c< Carlos Sepúlveda />', 'color:#0ff7c8;font-family:monospace;font-size:1.2rem;font-weight:bold;');
console.log('%cPortafolio v1.0 | HTML5 + CSS3 + JS + Bootstrap 5', 'color:#8b949e;font-family:monospace;font-size:0.85rem;');