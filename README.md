# 🚀 Portafolio — Alejandro Reyes

Portafolio personal desarrollado con HTML5, CSS3, JavaScript y Bootstrap 5.

## 📁 Estructura del Proyecto

```
portfolio/
│
├── index.html                  ← Página principal (todas las secciones)
│
├── css/
│   ├── style.css               ← Estilos principales y design tokens
│   ├── responsive.css          ← Media queries y adaptaciones móviles
│   └── animations.css          ← Keyframes y clases de animación
│
├── js/
│   ├── script.js               ← Script principal (modo oscuro, filtros, footer)
│   ├── navbar.js               ← Lógica de la navbar y smooth scroll
│   ├── animations.js           ← Loader, partículas, typing, contadores
│   └── form-validation.js      ← Validación y envío del formulario
│
├── assets/
│   ├── img/
│   │   ├── profile/            ← Foto de perfil (reemplazar SVG placeholder)
│   │   ├── projects/           ← Screenshots de proyectos
│   │   ├── icons/              ← Íconos personalizados
│   │   └── backgrounds/        ← Imágenes de fondo
│   ├── fonts/                  ← Fuentes locales (si no se usan CDN)
│   └── documents/
│       └── cv.pdf              ← CV para descarga
│
├── components/
│   ├── navbar.html             ← Fragmento HTML del navbar
│   ├── footer.html             ← Fragmento HTML del footer
│   └── cards.html              ← Templates de cards reutilizables
│
├── pages/
│   ├── projects.html           ← Página completa de proyectos
│   ├── contact.html            ← Página de contacto
│   └── about.html              ← Página "Sobre Mí"
│
├── libs/
│   └── bootstrap/              ← Bootstrap local (opcional, actualmente CDN)
│
├── README.md
└── .gitignore
```

## 🛠️ Tecnologías

- **HTML5** — Semántica y accesibilidad
- **CSS3** — Variables CSS, animaciones, Grid/Flexbox
- **JavaScript** (Vanilla ES6+) — Sin dependencias adicionales
- **Bootstrap 5** — Sistema de grid y componentes base
- **Bootstrap Icons** — Íconos SVG

## ✨ Funcionalidades

- Loader inicial animado
- Modo oscuro / claro con persistencia en `localStorage`
- Navbar fija con link activo dinámico y smooth scroll
- Efecto typing en el hero
- Canvas de partículas (fondo hero)
- Animaciones al scroll con `IntersectionObserver`
- Contadores animados de estadísticas
- Barras de habilidades animadas
- Filtro de proyectos por categoría
- Formulario de contacto con validación en tiempo real
- Botón "volver arriba"
- Soporte responsive completo
- Respeta `prefers-reduced-motion`

## 🚀 Uso

1. Clona o descarga el repositorio
2. Abre `index.html` en tu navegador (o usa un servidor local)
3. Para desarrollo, puedes usar Live Server en VS Code

```bash
# Con Python (servidor simple)
python -m http.server 3000

# Con Node.js
npx serve .
```

## 📝 Personalización

1. **Datos personales** — Busca y reemplaza `Alejandro Reyes`, `alejandro@example.com`, etc.
2. **Foto de perfil** — Reemplaza el SVG en el hero con `<img src="assets/img/profile/foto.jpg">`
3. **CV** — Coloca tu CV en `assets/documents/cv.pdf`
4. **Proyectos** — Edita las cards en `index.html` o `pages/projects.html`
5. **Formulario** — En `js/form-validation.js`, reemplaza `simulateSend()` con un `fetch()` real a tu API o Formspree

## 📄 Licencia

MIT — libre para uso personal y comercial.
