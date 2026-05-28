# Documento de Uso de Inteligencia Artificial

**Proyecto:** Portafolio Web Profesional Autoadministrable
**Autor:** Carlos Sepúlveda
**Asignatura:** Desarrollo Web — Evaluación N°3
**Universidad:** Universidad Católica de Temuco (UCT)

---

## 1. Herramientas de IA utilizadas

| Herramienta | Uso principal | Frecuencia |
|---|---|---|
| **Claude (Anthropic)** | Asistente principal de programación, revisión de código, generación de estructura PHP/SQL, debugging | Alta |
| **ChatGPT** | Consultas puntuales y comparación de soluciones | Media |
| **GitHub Copilot** | Autocompletado durante edición en VS Code | Baja |

La herramienta principal fue **Claude**, utilizada de forma conversacional para iterar sobre el código y recibir retroalimentación crítica.

---

## 2. Prompts utilizados (ejemplos reales)

###  Prompt 1 — Revisión inicial del frontend


**Resultado:** Claude detectó múltiples problemas:
- Login con credenciales en texto plano (`admin/1234` en JavaScript).
- Inconsistencia universidad (UCT vs UFRO en distintas secciones).
- Todos los enlaces de proyectos apuntaban a `#`.
- Numeración rota de secciones (01, 02, 03, 03, 05).
- Bootstrap cargado dos veces.
- Error de tipeo: *"estás disfrutando"* en lugar de *"estoy disfrutando"*.

**Ajuste realizado:** Apliqué todas las correcciones señaladas y unifiqué el nombre de la universidad a UCT.

---

###  Prompt 2 — Análisis contra los requerimientos del profesor


**Resultado:** Claude contrastó mi proyecto contra los requerimientos y detectó que faltaba:
- Todo el backend (PHP + MySQL).
- Script `bd.sql`.
- Dashboard administrativo con CRUD real.
- Login funcional con sesiones.
- Formulario de contacto que guarde en BD.
- Documento de uso de IA.
- Wireframe y archivo Figma.
- Despliegue en teclab.

**Ajuste realizado:** A partir de esa lista priorizada, planificamos el trabajo restante en orden de impacto.

---

### Prompt 3 — Generación del script SQL

**Resultado:** Claude generó:
- Script `bd.sql` con 5 tablas (usuarios, proyectos, habilidades, mensajes_contacto, biografia).
- Datos de ejemplo precargados.
- Hash de contraseña ya generado 

---

### Prompt 4 — Login seguro

> **Prompt implícito:** *(parte del plan de trabajo)*

**Resultado:** Claude generó `pages/login.php` con:
- Prepared statements para evitar SQL injection.
- `password_verify()` para comparar contraseñas.
- Token CSRF en el formulario.
- `session_regenerate_id(true)` tras login exitoso (anti session fixation).
- Mensajes de error genéricos (no revela si falló el usuario o la contraseña).

**Ajuste realizado:** Probé el login en local. Verifiqué que las credenciales del usuario `admin` no estuvieran expuestas en el código (solo el hash en la BD).

---

### Prompt 5 — Sobre el wireframe

> **Prompt:** *"el wireframe lo hice sacando una captura de pantalla al html index pero lo puedo dejar como una imagen png"*

**Resultado:** Claude me explicó por qué eso era riesgoso (la rúbrica dice "previamente desarrollado"), y me ofreció tres opciones con distinto nivel de esfuerzo: rehacerlo en Figma, disimularlo con filtros, o asumirlo honestamente en la presentación.

**Ajuste realizado:** Decidí ser transparente sobre el orden de trabajo durante la defensa técnica y, en paralelo, hacer una versión simplificada en Figma para tener ambos entregables.

---

## 3. Resultados generados por la IA

La IA contribuyó a generar:

- **Script SQL completo** con 5 tablas, datos de prueba y configuración de charset.
- **Estructura de carpetas** del proyecto separando responsabilidades (config, includes, admin, api).
- **Helpers reutilizables** (`funciones.php`): CSRF, sesión, escape HTML, flash messages.
- **CRUD completo** de proyectos y habilidades siguiendo el patrón listado → formulario → guardar → eliminar.
- **Dashboard con estadísticas** que consulta totales y últimos mensajes.
- **CSS del panel admin** alineado con el design system del sitio público.
- **README detallado** con instalación, estructura, deploy y advertencias de seguridad.

---

## 4. Ajustes y decisiones tomadas por mí

Aunque la IA aceleró mucho el desarrollo, **todas las decisiones de diseño, contenido y arquitectura las tomé yo**. Algunos ajustes manuales:

1. **Personalización de datos:** Reemplacé todos los datos genéricos por información real (nombre, correo UCT, ubicación, descripción personal).
2. **Verificación de seguridad:** Revisé manualmente que ninguna consulta usara concatenación de strings y que todas pasaran por `prepare()` + `bind_param()`.
3. **Validación cruzada:** Cada vez que la IA proponía código, lo leía para entenderlo antes de pegarlo. Si algo no me cuadraba, pedía explicación o lo cambiaba.
4. **Pruebas:** Verifiqué la sintaxis PHP con `php -l` antes de subir a producción.
5. **Decisión arquitectónica:** Opté por formularios PHP tradicionales en vez de AJAX para simplificar el código y enfocarme en que todo funcione bien antes de añadir complejidad.
6. **Contenido real:** Las descripciones de los proyectos las redacté yo. La IA solo me ayudó a estructurarlas.

---

## 5. Reflexión crítica

###  Ventajas observadas

- **Velocidad de desarrollo:** Lo que habría tomado días lo terminé en horas. Especialmente útil para tareas repetitivas como los formularios CRUD, donde la estructura se repite con pequeñas variaciones.
- **Detección de errores:** La IA encontró bugs y problemas de seguridad que probablemente yo habría dejado pasar (login en cliente, SQL sin escapar, numeración rota).
- **Aprendizaje activo:** Pude preguntar "¿por qué esto y no aquello?" en cualquier momento. Aprendí más sobre prepared statements, CSRF y `password_hash()` en esta tarea que en clase.
- **Honestidad técnica:** Cuando le pregunté qué hacer con el wireframe, no me dijo "haz lo que quieras", sino que me explicó las implicancias de cada opción.

### Limitaciones observadas

- **No reemplaza la comprensión:** Si copiaba código sin entenderlo, no podía explicarlo en la defensa técnica. Tuve que invertir tiempo en *leer* lo generado.
- **Errores ocasionales:** En un par de ocasiones, la IA generó código que tenía pequeños desajustes con el resto del proyecto (por ejemplo, asumir un nombre de variable distinto). Tuve que revisar siempre.
- **Contexto limitado:** A veces tenía que recordarle decisiones previas, especialmente cuando la conversación se alargaba.
- **No conoce mi entorno real:** Tuve que ajustar manualmente las credenciales de BD para teclab; la IA solo podía sugerir valores genéricos.

### Aprendizaje obtenido

El uso ético de IA en programación, para mí, se trata de tres cosas:

1. **Usarla como copiloto, no como piloto.** Las decisiones importantes (qué construir, cómo estructurar, qué priorizar) siguen siendo mías.
2. **Verificar siempre.** Probar el código, leerlo, entenderlo. La IA puede cometer errores con confianza.
3. **Documentar el proceso.** Reconocer cuándo la IA ayudó es parte de la transparencia académica.

Mi conclusión es que la IA es una herramienta poderosísima para programadores en formación, pero solo si se usa de forma activa y crítica. Si la usara para copiar y pegar sin entender, estaría engañándome a mí mismo y reduciendo mi aprendizaje. Usada como interlocutor técnico, en cambio, multiplica lo que puedo aprender y construir.

---

**Carlos Sepúlveda**
Estudiante de Informática — UCT

