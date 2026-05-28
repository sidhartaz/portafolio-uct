-- =====================================================================
-- BASE DE DATOS: Portafolio Web Profesional Autoadministrable
-- Autor: Carlos Sepúlveda (sidhartaz)
-- UCT - Evaluación N°3 — VERSIÓN PRODUCCIÓN (teclab)
--
-- INSTRUCCIONES:
-- 1. En el panel de teclab, crear la base de datos (te la dará el profe
--    o el panel; suele llamarse algo como "csepulveda_portafolio").
-- 2. Seleccionar esa base de datos en phpMyAdmin.
-- 3. Pestaña Importar → subir este archivo → Continuar.
--
-- ⚠️  La contraseña del usuario admin queda con un hash temporal.
--    DESPUÉS de importar, usa generar_hash.php para crear tu hash real
--    y ejecuta el UPDATE que te indique.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar tablas previas si existen (permite reimportar limpio)
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS proyectos;
DROP TABLE IF EXISTS habilidades;
DROP TABLE IF EXISTS mensajes_contacto;
DROP TABLE IF EXISTS biografia;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- TABLA: usuarios (login del admin)
-- =====================================================================
CREATE TABLE usuarios (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  usuario     VARCHAR(50)  NOT NULL UNIQUE,
  `password`  VARCHAR(255) NOT NULL,
  nombre      VARCHAR(100) NOT NULL,
  email       VARCHAR(120),
  creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuario admin temporal — reemplazar la contraseña después con generar_hash.php
INSERT INTO usuarios (usuario, `password`, nombre, email) VALUES
('admin',
 '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
 'Carlos Sepúlveda',
 'csepulveda2025@alu.uct.cl');

-- =====================================================================
-- TABLA: proyectos
-- =====================================================================
CREATE TABLE proyectos (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  titulo       VARCHAR(120) NOT NULL,
  descripcion  TEXT NOT NULL,
  categoria    ENUM('web','python','linux','otro') NOT NULL DEFAULT 'web',
  tags         VARCHAR(255),
  imagen       VARCHAR(255),
  icono        VARCHAR(60)  DEFAULT 'bi-folder',
  url_demo     VARCHAR(255),
  url_github   VARCHAR(255),
  destacado    TINYINT(1) DEFAULT 0,
  activo       TINYINT(1) DEFAULT 1,
  creado_en    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4 proyectos reales de GitHub (sidhartaz)
INSERT INTO proyectos (titulo, descripcion, categoria, tags, icono, url_demo, url_github, destacado) VALUES

('Card-Trader — Marketplace de Pokémon',
 'Plataforma de compra, venta y subasta de cartas Pokémon. Backend en Node.js + Express + MongoDB con autenticación JWT, sistema de roles (cliente/vendedor/admin), Docker y caché con Redis. Incluye validaciones de negocio complejas y flujo completo de órdenes y reservas con caducidad automática.',
 'web', 'Node.js, Express, MongoDB, Redis, Docker, JWT',
 'bi-shop',
 '', 'https://github.com/sidhartaz/CompraVenta_Pokemon',
 1),

('SteamStorm — Reseñas de Videojuegos',
 'Plataforma web colaborativa para que jugadores de Steam consulten reseñas y rankings antes de comprar un juego. Consume la Steam API para mostrar información actualizada. Desplegado en Vercel y desarrollado en equipo.',
 'web', 'HTML, CSS, JavaScript, Steam API, Vercel',
 'bi-controller',
 'https://steamstormbeta.vercel.app', 'https://github.com/sidhartaz/steamstormbeta',
 0),

('Portafolio Web Autoadministrable',
 'Portafolio profesional dinámico con panel administrativo. Login seguro con bcrypt y sesiones PHP, CRUD completo de proyectos y habilidades, formulario de contacto con BD y protección CSRF en todos los formularios. Frontend responsive con modo claro/oscuro.',
 'web', 'PHP, MySQL, Bootstrap 5, JavaScript',
 'bi-person-workspace',
 '', 'https://github.com/sidhartaz/portafolio-uct',
 0),

('API Computadora',
 'API REST en Node.js + Express para gestión de componentes de computadora. Implementa endpoints CRUD siguiendo el patrón de arquitectura modular, con estructura limpia en /src para separar rutas, controladores y modelos.',
 'web', 'Node.js, Express, REST API',
 'bi-cpu',
 '', 'https://github.com/sidhartaz/api-computadora',
 0);

-- =====================================================================
-- TABLA: habilidades
-- =====================================================================
CREATE TABLE habilidades (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(60) NOT NULL,
  icono       VARCHAR(60) DEFAULT 'bi-code-slash',
  porcentaje  TINYINT UNSIGNED NOT NULL DEFAULT 50,
  nivel       ENUM('basico','intermedio','avanzado') NOT NULL DEFAULT 'intermedio',
  categoria   ENUM('lenguaje','herramienta','blanda') NOT NULL DEFAULT 'lenguaje',
  orden       INT DEFAULT 0,
  activo      TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO habilidades (nombre, icono, porcentaje, nivel, categoria, orden) VALUES
('HTML5',           'bi-filetype-html', 90, 'avanzado',   'lenguaje', 1),
('CSS3 & Bootstrap','bi-filetype-css',  85, 'avanzado',   'lenguaje', 2),
('JavaScript',      'bi-filetype-js',   78, 'intermedio', 'lenguaje', 3),
('PHP',             'bi-filetype-php',  70, 'intermedio', 'lenguaje', 4),
('MySQL',           'bi-database',      68, 'intermedio', 'lenguaje', 5),
('Node.js',         'bi-hexagon-fill',  72, 'intermedio', 'lenguaje', 6),
('Python',          'bi-filetype-py',   75, 'intermedio', 'lenguaje', 7),
('Linux / Bash',    'bi-terminal-fill', 80, 'avanzado',   'herramienta', 8),
('Git & GitHub',    'bi-git',           82, 'avanzado',   'herramienta', 9),
('Docker',          'bi-box-seam',      65, 'intermedio', 'herramienta', 10);

-- =====================================================================
-- TABLA: mensajes_contacto
-- =====================================================================
CREATE TABLE mensajes_contacto (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(100) NOT NULL,
  email       VARCHAR(120) NOT NULL,
  asunto      VARCHAR(150),
  mensaje     TEXT NOT NULL,
  leido       TINYINT(1) DEFAULT 0,
  recibido_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- TABLA: biografia (singleton, una sola fila)
-- =====================================================================
CREATE TABLE biografia (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(100) NOT NULL,
  titulo          VARCHAR(120),
  descripcion     TEXT,
  email           VARCHAR(120),
  telefono        VARCHAR(30),
  ubicacion       VARCHAR(100),
  universidad     VARCHAR(100),
  github_url      VARCHAR(255),
  linkedin_url    VARCHAR(255),
  instagram_url   VARCHAR(255),
  twitter_url     VARCHAR(255),
  actualizado_en  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO biografia
  (nombre_completo, titulo, descripcion, email, telefono, ubicacion, universidad, github_url, linkedin_url)
VALUES
('Carlos Sepúlveda',
 'Estudiante de Informática',
 'Me apasiona el desarrollo de software moderno, la arquitectura de sistemas y el mundo del código abierto. Cuando no estoy programando, estoy disfrutando de la naturaleza de la Araucanía o explorando nuevas tecnologías.',
 'csepulveda2025@alu.uct.cl',
 '+56 9 1234 5678',
 'Temuco, Araucanía, Chile',
 'Universidad Católica de Temuco (UCT)',
 'https://github.com/sidhartaz',
 'https://linkedin.com');