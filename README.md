# Portafolio Web Profesional Autoadministrable

**Autor:** Carlos Sepúlveda
**Universidad:** Universidad Católica de Temuco (UCT)
**Asignatura:** Desarrollo Web — Evaluación N°3
**Año:** 2026

---

## Proyecto en producción

**https://teclab.uct.cl/~csepulveda/**


---

## Descripción

Aplicación web dinámica que permite presentar información profesional del estudiante de forma autoadministrable. El sistema cuenta con un sitio público (portafolio) y un panel administrativo protegido por autenticación, donde el dueño del portafolio puede gestionar proyectos, habilidades y mensajes de contacto sin tocar el código.

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| **Frontend** | HTML5, CSS3, Bootstrap 5.3.8, JavaScript ES6+ |
| **Backend** | PHP 8.4 (mysqli, sessions, password_hash) |
| **Base de datos** | MySQL 8 / MariaDB |
| **Iconos** | Bootstrap Icons 1.11.3 |
| **Fuentes** | Google Fonts (Space Mono + DM Sans) |
| **Control de versiones** | Git + GitHub |
| **IA** | Claude (Anthropic) como apoyo de desarrollo |

## Estructura del proyecto

```
portafolio-uct/
├── index.php                  ← Página principal pública (dinámica)
│
├── config/
│   └── db.php                 ← Conexión MySQL
│
├── includes/
│   ├── funciones.php          ← Helpers (CSRF, sesión, sanitización)
│   ├── admin_header.php       ← Cabecera del panel admin
│   ├── admin_footer.php       ← Pie del panel admin
│   └── admin_sidebar.php      ← Menú lateral del admin
│
├── pages/
│   ├── login.php              ← Login del administrador
│   └── logout.php             ← Cierre de sesión
│
├── admin/                     ← Zona protegida (requiere sesión)
│   ├── dashboard.php          ← Panel con estadísticas
│   ├── proyectos.php          ← Listado de proyectos
│   ├── proyecto_form.php      ← Crear / editar proyecto
│   ├── proyecto_guardar.php   ← Procesa INSERT / UPDATE
│   ├── proyecto_eliminar.php  ← Procesa DELETE
│   ├── habilidades.php        ← Listado de habilidades
│   ├── habilidad_form.php     ← Crear / editar habilidad
│   ├── habilidad_guardar.php  ← Procesa INSERT / UPDATE
│   ├── habilidad_eliminar.php ← Procesa DELETE
│   ├── mensajes.php           ← Bandeja de mensajes recibidos
│   └── mensaje_eliminar.php   ← Eliminar mensaje
│
├── api/
│   └── contacto.php           ← Procesa formulario de contacto público
│
├── sql/
│   └── bd.sql                 ← Script de creación de la base de datos
│
├── css/
│   ├── style.css              ← Estilos principales y design tokens
│   ├── dashboard.css          ← Estilos del panel admin
│   ├── responsive.css         ← Media queries
│   └── animations.css         ← Animaciones
│
├── js/
│   ├── script.js              ← Script principal
│   ├── navbar.js              ← Navbar y smooth scroll
│   ├── animations.js          ← Loader, partículas, typing
│   └── form-validation.js     ← Validación del formulario público
│
├── assets/
│   └── img/                   ← Imágenes
│
├── docs/
│   ├── uso-ia.md              ← Documento de uso de IA
│   ├── wireframe.png          ← Wireframe original
│   └── figma-link.txt         ← Enlace al diseño Figma
│
└── README.md
```

## Instalación local

### 1. Requisitos previos

- PHP 8.0 o superior (probado en 8.4)
- MySQL 8 o MariaDB
- Servidor web (Apache, Nginx, o el servidor embebido de PHP)
- Git

### 2. Clonar el repositorio

```bash
git clone https://github.com/sidhartaz/portafolio-uct.git
cd portafolio-uct
```

### 3. Crear la base de datos

Abre phpMyAdmin (o consola MySQL) y ejecuta el script:

```bash
mysql -u root -p < sql/bd.sql
```

O en phpMyAdmin: pestaña **Importar** → seleccionar `sql/bd.sql` → **Continuar**.

Esto crea la base `portafolio_uct` con todas las tablas y datos de ejemplo.

### 4. Configurar credenciales

Edita `config/db.php` y ajusta:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portafolio_uct');
```

### 5. Levantar el servidor

**Opción A — Servidor embebido de PHP:**
```bash
php -S localhost:8000
```
Luego abre `http://localhost:8000`

**Opción B — XAMPP / Laragon / WAMP:**
Copia la carpeta a `htdocs/` y abre `http://localhost/portafolio-uct/`



## Funcionalidades

### Sitio público
- Hero con efecto typing y estadísticas dinámicas
- Modo oscuro / claro con persistencia
- Biografía editable desde la BD
- Habilidades con barras de progreso animadas
- Sección de tecnologías dominadas con niveles
- Grid de proyectos con filtro por categoría
- Formulario de contacto funcional (guarda en BD)
- Animaciones al scroll con IntersectionObserver
- Diseño responsive

### Panel administrativo
- Login funcional con `password_hash()` y sesiones PHP
- Protección CSRF en todos los formularios
- Dashboard con estadísticas en tiempo real
- **CRUD completo** de proyectos (crear, editar, eliminar, ocultar, destacar)
- **CRUD completo** de habilidades (crear, editar, eliminar, ordenar)
- Bandeja de mensajes con marcar como leído / eliminar
- Sidebar con indicador de sección activa

## Seguridad implementada

- Contraseñas hasheadas con `password_hash()` (bcrypt)
- Prepared statements en TODAS las consultas (anti-SQL injection)
- Escape HTML con `htmlspecialchars()` en todas las salidas (anti-XSS)
- Token CSRF en todos los formularios POST
- `session_regenerate_id()` tras login (anti session fixation)
- Validación tanto en cliente (JS) como en servidor (PHP)
- Errores de PHP ocultos en producción (solo log)



## Uso de Inteligencia Artificial

Este proyecto fue desarrollado con apoyo de **Claude (Anthropic)** como asistente de programación. La documentación completa de prompts, decisiones y reflexión crítica está en [`docs/uso-ia.md`](docs/uso-ia.md).

## Diseño

- Wireframe: `docs/wireframe.png`
- Figma: ver `docs/figma-link.txt`

## Licencia

Proyecto académico — Universidad Católica de Temuco, 2026.

---

**Carlos Sepúlveda**
csepulveda2025@alu.uct.cl
Temuco, Chile
