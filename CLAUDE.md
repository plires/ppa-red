# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Descripción del Proyecto

PPA RED — sistema de gestión de formularios construido con Laravel 11. Permite gestionar envíos de formularios con jerarquía geográfica (provincias/zonas/localidades), administración de partners y sistema de notificaciones. Dos roles de usuario: `admin` y `partner`.

## Stack Tecnológico

- **Backend**: Laravel 11, PHP 8.2+, SQLite
- **Frontend (admin)**: Blade templates, Alpine.js, jQuery, Bootstrap 4.6 + AdminLTE 3.2, Chart.js, DataTables
- **Frontend (landing pública)**: React 18 montado en vistas Blade via `resources/js/landing/`
- **Build**: Vite 6.0 (unificado — compila tanto Alpine.js como React)

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

# Build de producción (incluye admin + landing)
npm run build
```

## Arquitectura

### Flujo de Requests

Las rutas están distribuidas en tres archivos:
- `routes/web.php` — Rutas principales (~135), protegidas por middleware de autenticación. Las rutas de admin usan `AdminMiddleware`.
- `routes/api.php` — 4 endpoints públicos de datos geográficos (provincias, zonas, localidades) con throttling.
- `routes/auth.php` — Rutas de autenticación (Breeze).

### Conceptos Principales del Dominio

- **FormSubmission**: Entidad central. Almacena los datos del formulario en JSON, tiene un token seguro para acceso público, registra el estado y pertenece a una ubicación geográfica (provincia/zona/localidad) y a un partner (User).
- **FormResponse**: Comentarios/mensajes sobre los envíos. Tiene el flag `is_system` para mensajes generados por el sistema.
- **Jerarquía geográfica**: Provincia → Zona → Localidad (todas con soft deletes).
- **Usuarios**: Rol `admin` o `partner`. Los partners se asignan a localidades.

### Modelos y Relaciones

- `User` hasMany `FormSubmission`, `FormResponse`, `Locality`
- `FormSubmission` belongsTo `User`, `Province`, `Zone`, `Locality`; hasMany `FormResponse`, `FormSubmissionNotification`
- `Province` hasMany `Zone`; `Zone` hasMany `Locality`

### Procesamiento Asíncrono

Las notificaciones por email se despachan como jobs encolados (driver database):
- `SendFormResponseEmailToPartner` — notifica al partner sobre nuevas respuestas
- `SendFormResponseEmailToUser` — notifica al usuario sobre respuestas del partner
- `SendFormStatusChange` — notifica cuando cambia el estado de un formulario

Las plantillas de email están en `resources/views/emails/`.

### Organización del Frontend

**Enfoque híbrido:** El admin usa Blade + Alpine.js, la landing pública usa React montado en vistas Blade.

- `resources/views/` — Blade templates organizados por funcionalidad (form_submissions, provinces, zones, localities, partners, reports, public-forms, landing)
- `resources/css/` — Estilos personalizados divididos en `parts/` (aside, nav, header, etc.)
- `resources/js/` — Scripts Alpine.js y utilitarios
- `resources/js/landing/` — Aplicación React para la landing pública (entry point: `app.jsx`)
- `public/` — Assets compilados y archivos estáticos

Vite compila ambos entry points (`resources/js/app.js` para admin, `resources/js/landing/app.jsx` para landing). Las vistas Blade cargan el entry point correspondiente via `@vite()`.

### Configuración

- `config/form_submission_closure_reasons.php` — Definición de razones de cierre personalizadas
- Locale de la app: `es` (español), fallback `en`
- Los tests usan SQLite `:memory:` (configurado en `phpunit.xml`)
