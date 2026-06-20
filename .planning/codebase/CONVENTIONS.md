# Coding Conventions

**Analysis Date:** 2026-06-20

## Naming Patterns

**Files:**
- HTML: `kebab-case.html` — all page files follow this convention: `index.html`, `about.html`, `product.html`, `store.html`, `blog.html`, `contact.html`, `feature.html`, `testimonial.html`, `404.html`
- CSS: `style.css` — single custom stylesheet; vendor libs use their own naming (e.g. `bootstrap.min.css`, `owl.carousel.min.css`)
- JavaScript: `main.js` — single custom script; vendor libs use their own naming
- SCSS: kebab-case Bootstrap source directory under `scss/bootstrap/scss/` and `scss/bootstrap.scss` entry point

**Classes (CSS):**
- BEM-like utility classes composed of lowercase hyphenated words: `.btn-square`, `.btn-sm-square`, `.btn-lg-square`, `.fw-medium`, `.fw-bold`, `.fw-black`, `.page-header`, `.section-title`, `.product-item`, `.store-item`, `.store-overlay`, `.testimonial-item`
- State modifiers via adjacent class: `.store-item:hover .store-overlay`, `#spinner.show`
- Component prefixes for scoping: `.navbar .navbar-brand`, `.navbar .navbar-nav .nav-link`, `.footer .btn.btn-link`, `.carousel-caption`, `.product-carousel .owl-nav`
- Vendor utility classes via Bootstrap: `.container`, `.row`, `.col-lg-*`, `.d-flex`, `.text-center`, `.bg-white`, `.btn-primary`, `.rounded-circle`, `.wow`, `.fadeIn`

**IDs (HTML):**
- kebab-case: `#spinner`, `#header-carousel`, `#videoModal`, `#video`, `#navbarCollapse`, `#exampleModalLabel`

**Functions (JavaScript):**
- `camelCase` function expressions assigned to `var`: `var spinner = function () { ... }`
- Variables use `camelCase` with `$` prefix for jQuery-wrapped elements: `$videoSrc`

## Code Style

**Formatting:**
- Not detected — no Prettier, Biome, or other formatter config present
- HTML: 4-space indentation (consistent across all pages)
- CSS: 4-space indentation, opening brace on same line as selector, properties indented inside
- JavaScript: 4-space indentation, opening brace on same line, semicolons at end of statements

**Linting:**
- Not detected — no ESLint, JSHint, or other linter config present
- No `tsconfig.json`, `jsconfig.json`, or any TypeScript config present

## HTML Conventions

**Structure:**
```html
<!-- Section Name Start -->
<div class="container-fluid">
    ...
</div>
<!-- Section Name End -->
```
Every major page section is enclosed in Start/End HTML comments for clear delineation.

**Meta tags:**
```html
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="" name="keywords">
<meta content="" name="description">
```

**Resource loading order in `<head>`:**
1. Favicon
2. Google Web Fonts (preconnect + stylesheet)
3. Icon Font Stylesheets (Font Awesome, Bootstrap Icons)
4. Library Stylesheets (Animate.css, OwlCarousel)
5. Customized Bootstrap Stylesheet
6. Template Stylesheet (`css/style.css`)

**Resource loading order before `</body>`:**
1. jQuery (CDN, always first)
2. Bootstrap JS Bundle (CDN)
3. Library JS (WOW, easing, waypoints, OwlCarousel)
4. Template JS (`js/main.js`, always last)

**Accessibility:**
- `alt` attributes present on all `<img>` tags
- `aria-label`, `aria-hidden`, `aria-labelledby` used on interactive elements
- `role="status"` on spinner, `role="button"` on button elements
- `visually-hidden` class used for screen-reader-only text

## HTML Template Pattern

All pages follow an identical head block and bootstrap section structure. Every page includes:
- Spinner, Navbar, Footer, Copyright, Back-to-Top button
- Page-specific content sections between Navbar and Footer
- Same CSS/JS assets loaded in the same order

## Import Organization (Frontend)

Not applicable — no ES module or import system. Libraries loaded via `<script>` tags in dependency order:
1. jQuery (required by Bootstrap, custom JS)
2. Bootstrap JS (depends on jQuery)
3. WOW.js, easing, waypoints, OwlCarousel (animation/carousel)
4. `main.js` (custom code, depends on all above)

## Error Handling

**JavaScript:**
- No try/catch blocks present
- No error monitoring or logging framework
- Single `console.log($videoSrc)` call at line 48 of `main.js` — uses `console.log` directly for debugging
- No defensive null checks on DOM elements before manipulation
- No fallback behavior when libraries fail to load

## Logging

**Framework:** Not detected — uses native `console.log` directly in `main.js:48`

**Pattern:**
```javascript
console.log($videoSrc);
```
No structured logging, log levels, or logger abstraction present.

## Comments

**CSS:**
- Section headers using `/** Section Name **/` pattern: `/*** Spinner ***/`, `/*** Button ***/`, `/*** Navbar ***/`, `/*** Header ***/`, `/*** Section Title ***/`, `/*** Products ***/`, `/*** About ***/`, `/*** Store ***/`, `/*** Contact ***/`, `/*** Testimonial ***/`, `/*** Footer ***/`
- Top of file: `/********** Template CSS **********/`

**JavaScript:**
- Inline comments using `// Comment` pattern to label logical blocks
- Examples: `// Spinner`, `// Initiate the wowjs`, `// Sticky Navbar`, `// Back to top button`, `// Modal Video`, `// Product carousel`, `// Testimonial carousel`
- Comments are short, single-line labels for sections

**HTML:**
- Standard `<!-- Section Name Start -->` and `<!-- Section Name End -->` paired comments for each major section

## Function Design

**Size:** All functions in `main.js` are small (1-15 lines), single-purpose, invoked inline.

**Parameters:**
- jQuery event handlers use `function (e) { ... }` pattern, accessing `e` only for event context
- IIFE wrapper: `(function ($) { "use strict"; ... })(jQuery);`

**Return Values:**
- Event handlers return `false` to prevent default: `return false;`

## Module Design

**Exports:** Not applicable — no module system used. Custom code is a single `main.js` with an IIFE wrapping jQuery.

**Pattern:**
```javascript
(function ($) {
    "use strict";
    // all code
})(jQuery);
```

**Barrel Files:** Not applicable. No `index.js` or barrel pattern.

## Vendor Library Patterns

| Library | Version | Location |
|---------|---------|----------|
| jQuery | 3.6.1 | CDN (`ajax.googleapis.com`) |
| Bootstrap | 5.x | CDN + local CSS `css/bootstrap.min.css` + SCSS sources in `scss/bootstrap/` |
| Font Awesome | 5.10.0 | CDN |
| Bootstrap Icons | 1.4.1 | CDN |
| Google Fonts | Open Sans + Playfair Display | CDN |
| WOW.js | bundled | `lib/wow/wow.min.js` |
| jQuery Easing | bundled | `lib/easing/easing.min.js` |
| Waypoints | bundled | `lib/waypoints/waypoints.min.js` |
| OwlCarousel | bundled | `lib/owlcarousel/` |

## CSS Property Conventions

- Use CSS custom properties for theme colors: `--primary: #88B44E`, `--secondary: #FB9F38`, `--light: #F5F8F2`, `--dark: #252C30` in `:root` at `css/style.css:2-7`
- Shorthand properties preferred: `transition: .5s;`, `margin: 25px;`, `padding: 25px 0;`
- No CSS preprocessor custom functions beyond Bootstrap's SCSS
- Color values: hex codes (`#FFFFFF`, `var(--primary)`, `rgba(136, 180, 78, .7)`)
- Media queries at breakpoints `max-width: 991.98px`, `max-width: 768px`, `min-width: 992px`
- `!important` used sparingly: only `width: 60px !important;` and `height: 60px !important;` in Testimonial section, and one color override in copyright

---

*Convention analysis: 2026-06-20*
