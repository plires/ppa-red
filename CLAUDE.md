# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Descripción del Proyecto

PPA RED — sistema de gestión de formularios construido con Laravel 11. Permite gestionar envíos de formularios con jerarquía geográfica (provincias/zonas/localidades), administración de partners y sistema de notificaciones. Dos roles de usuario: `admin` y `partner`.

## Migración de Frontend

Migración de Blade + AdminLTE + jQuery a Inertia.js + React 18 + Tailwind CSS **completada**.

- Todos los controladores usan `Inertia::render()` — no quedan `view()` en el flujo principal
- Todas las páginas React en `resources/js/pages/` (auth, dashboard, CRUD, reportes, formularios públicos)
- Las vistas Blade legacy en `resources/views/` se conservan pero no se usan en el flujo principal

## Diseño y Marca

Style guide de páginas públicas en `.claude/style-guide.md` — colores institucionales, tipografía (Noto Sans) y reglas de uso en Tailwind.

**Jerarquía de diseño — SIEMPRE respetar este orden:**
1. `.claude/style-guide.md` — colores y tipografía de marca. Tienen prioridad absoluta. Nunca sobreescribir con valores externos.
2. Skill `web-design-guidelines` (`.agents/skills/web-design-guidelines/SKILL.md`) — auditoría de buenas prácticas UI (accesibilidad, contraste, UX). Usar para revisar páginas, no para definir marca.

## Stack Tecnológico

- **Backend**: Laravel 11, PHP 8.2+, SQLite
- **Frontend**: Inertia.js + React 18, Tailwind CSS, @headlessui/react
- **Build**: Vite 6.0 con `@vitejs/plugin-react`
- **Auth**: Laravel Breeze (stack React/Inertia)
- **Rutas JS**: Ziggy (helper `route()` disponible en React)

## Comandos Principales

```bash
# Iniciar entorno de desarrollo completo (servidor + queue + logs + vite)
composer dev

# Servicios individuales
php artisan serve              # Servidor PHP de desarrollo
php artisan queue:listen       # Procesar jobs encolados
npm run dev                    # Servidor de desarrollo Vite

# Base de datos
php artisan migrate            # Ejecutar migraciones
php artisan migrate:fresh --seed  # Resetear y poblar la base de datos

# Tests
./vendor/bin/pest              # Ejecutar todos los tests
./vendor/bin/pest tests/Feature/SomeTest.php           # Ejecutar un archivo de test
./vendor/bin/pest --filter="nombre del test"            # Ejecutar un test por nombre

# Formato de código
./vendor/bin/pint              # Estilo de código PHP (PSR-12)
npm run lint                   # ESLint
npm run format                 # Prettier

# Build de producción
npm run build
```

## Arquitectura

### Flujo de Requests

- `routes/web.php` — Landing pública en `/`, dashboard admin en `/dashboard/*` (protegido con `auth` + `AdminMiddleware`), rutas públicas de formularios.
- `routes/api.php` — 4 endpoints públicos de datos geográficos con throttling.
- `routes/auth.php` — Autenticación (Breeze React/Inertia). Register protegido con `AdminMiddleware`.

### Conceptos Principales del Dominio

- **FormSubmission**: Entidad central. Datos en JSON, token seguro, estado, ubicación geográfica, partner.
- **FormResponse**: Comentarios/mensajes sobre envíos. Flag `is_system` para mensajes del sistema.
- **Jerarquía geográfica**: Provincia → Zona → Localidad (soft deletes).
- **Usuarios**: `admin` o `partner`. Partners asignados a localidades.

### Modelos y Relaciones

- `User` hasMany `FormSubmission`, `FormResponse`, `Locality`
- `FormSubmission` belongsTo `User`, `Province`, `Zone`, `Locality`; hasMany `FormResponse`, `FormSubmissionNotification`
- `Province` hasMany `Zone`; `Zone` hasMany `Locality`

### Frontend (Inertia + React)

- `resources/js/Pages/` — Páginas React renderizadas por Inertia
- `resources/js/Components/` — Componentes reutilizables (Breeze: InputError, Modal, Dropdown, etc.)
- `resources/js/Layouts/` — AuthenticatedLayout (dashboard), GuestLayout (auth)
- `resources/js/landing/components/` — Componentes React para la landing pública
- Alias `@` → `resources/js/` (configurado en `vite.config.js`)

### Procesamiento Asíncrono

Jobs encolados (driver database): `SendFormResponseEmailToPartner`, `SendFormResponseEmailToUser`, `SendFormStatusChange`. Plantillas email en `resources/views/emails/`.

### Configuración

- `config/form_submission_closure_reasons.php` — Razones de cierre personalizadas
- Locale: `es` (español), fallback `en`
- Tests usan SQLite `:memory:` (configurado en `phpunit.xml`)
