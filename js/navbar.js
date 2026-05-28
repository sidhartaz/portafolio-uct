/**
 * ============================================================
 * PORTAFOLIO — navbar.js
 * Funcionalidades de la barra de navegación:
 *   - Clase 'scrolled' al hacer scroll
 *   - Link activo según sección visible
 *   - Cierre del menú móvil al hacer click en un link
 * ============================================================
 */

'use strict';

(function initNavbar() {
  const nav      = $('#mainNav');
  const navLinks = $$('.nav-link');

  // Scroll: añadir clase al navbar
  window.addEventListener('scroll', () => {
    if (!nav) return;
    nav.classList.toggle('scrolled', window.scrollY > 60);
    updateActiveLink();
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
        bootstrap.Collapse.getInstance(collapse)?.hide();
      }
    });
  });

  // Smooth scroll para links internos
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
        const navHeight = nav?.offsetHeight || 72;
        const top       = target.getBoundingClientRect().top + window.scrollY - navHeight;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });
})();