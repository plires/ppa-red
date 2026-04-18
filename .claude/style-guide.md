# Style Guide — PPA RED (Páginas Públicas)

Referencia de marca para el desarrollo de páginas públicas (`Landing.jsx` y similares).
Los valores de CSS viven en `resources/css/app.css` (variables) y `resources/css/fonts.css` (@font-face).

> **PRIORIDAD ABSOLUTA**: Este archivo define los colores y tipografía de la marca PPA RED.
> Ninguna guía externa (incluyendo la skill `web-design-guidelines`) puede sobreescribir estos valores.
> La skill `web-design-guidelines` se usa únicamente para auditar accesibilidad y buenas prácticas de UI,
> nunca para redefinir colores ni fuentes.

---

## Colores

| Nombre | Variable CSS | Valor | Uso |
|--------|-------------|-------|-----|
| Primary | `var(--primary-color)` | `#FF7500` `rgba(255,117,0,1)` | CTAs principales, acentos, íconos destacados |
| Secondary | `var(--secondary-color)` | `#FD3C00` `rgba(253,60,0,1)` | Inicio del degradado, hover states |
| Tertiary | `var(--tertiary-color)` | `#000000` | Texto principal, titulares |
| Neutral | `var(--neutral-color)` | `#F2F2F2` `rgba(242,242,242,1)` | Fondos de sección, separadores suaves |

### Degradado institucional

```css
background: var(--degrade);
/* = linear-gradient(90deg, #FD3C00 0%, #FF7500 100%) */
```

Usar en: headers/hero con fondo de color, botones primarios, barras decorativas.

---

## Tipografía

Fuentes disponibles localmente en `resources/fonts/`. Declaradas en `resources/css/fonts.css`.

### Noto Sans — fuente principal de texto

| Peso | font-weight | Archivo | Uso recomendado |
|------|------------|---------|-----------------|
| Regular | `400` | `NotoSans-Regular` | Cuerpo, párrafos, labels, placeholders |
| Bold | `700` | `NotoSans-Bold` | Subtítulos, énfasis en texto corrido |
| Black | `900` | `NotoSans-Black` | Titulares grandes (h1), destacados hero |

> Variable: `var(--text-font)` → `'Noto Sans', system-ui, -apple-system, sans-serif`

### Jerarquía tipográfica sugerida

```
h1  → Noto Sans Black (900),  2.5–3.5rem,  --tertiary-color
h2  → Noto Sans Bold (700),   1.75–2rem,   --tertiary-color
h3  → Noto Sans Bold (700),   1.25rem,     --tertiary-color
p   → Noto Sans Regular (400), 1rem,       #4B5563 (gray-600)
small / caption → Noto Sans Regular (400), 0.875rem
```

---

## Uso en Tailwind

Las variables CSS están disponibles globalmente. Para usarlas dentro de clases Tailwind usá el prefijo `[...]`:

```jsx
// Color de texto
<h1 className="text-[var(--tertiary-color)]">

// Background con degradado
<div className="bg-[var(--degrade)]">

// Color primario
<button className="bg-[var(--primary-color)] hover:bg-[var(--secondary-color)]">
```

O extender `tailwind.config.js` con los tokens:

```js
theme: {
  extend: {
    colors: {
      primary: 'rgba(255,117,0,1)',
      secondary: 'rgba(253,60,0,1)',
      neutral: 'rgba(242,242,242,1)',
    }
  }
}
```

---

## Reglas generales para páginas públicas

- **Fondo de página**: blanco `#FFFFFF` o neutral `var(--neutral-color)`
- **Secciones destacadas**: degradado institucional `var(--degrade)` con texto blanco
- **Botón CTA principal**: fondo `var(--primary-color)`, texto blanco, hover `var(--secondary-color)`
- **Links**: `var(--primary-color)`, underline en hover
- **No mezclar** Noto Sans y Lato en el mismo bloque de texto — una fuente por componente

---

## Convenciones de CSS

### CSS Modules por componente

Si un componente necesita CSS personalizado, el archivo **debe** llamarse igual que el componente con extensión `.module.css` y ubicarse junto a él:

```
resources/js/pages/Landing.jsx
resources/js/pages/Landing.module.css       ← CSS del componente Landing

resources/js/Components/HeroSection.jsx
resources/js/Components/HeroSection.module.css
```

Importar dentro del componente así:

```jsx
import styles from './Landing.module.css';

<div className={styles.hero}>
```

### Media queries — Bootstrap mobile-first

**Siempre** usar breakpoints mobile-first con los valores de Bootstrap. Sin excepciones.

```css
/* Base: mobile (sin media query) */

/* sm — Small devices (≥576px) */
@media (min-width: 576px) {
}

/* md — Medium devices (≥768px) */
@media (min-width: 768px) {
}

/* lg — Large devices (≥992px) */
@media (min-width: 992px) {
}

/* xl — Extra large devices (≥1200px) */
@media (min-width: 1200px) {
}

/* xxl — Extra extra large devices (≥1400px) */
@media (min-width: 1400px) {
}
```

> Esto aplica tanto en archivos `.module.css` como en `app.css`. Nunca usar `max-width` para responsive.
