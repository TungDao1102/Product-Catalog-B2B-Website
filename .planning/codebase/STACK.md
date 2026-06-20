# Technology Stack

**Analysis Date:** 2026-06-20

## Languages

**Primary:**
- **HTML5** - Page structure for all 9 template pages (`template-frontend/*.html`)
- **CSS3** - Styling with SCSS preprocessing (`template-frontend/css/style.css`, `template-frontend/scss/bootstrap.scss`)
- **JavaScript (ES5)** - Client-side interactivity via jQuery (`template-frontend/js/main.js`)

**Planned:**
- **PHP** - Backend via Laravel framework (per `general-requirement.md` line 181)
- **SQL** - MySQL database queries

## Runtime

**Environment:**
- **Browser runtime only** (current) — static HTML pages opened directly in browser
- **PHP 8.x** (planned) — Laravel backend for production

**Package Manager:**
- **Composer** (planned) — PHP dependency management for Laravel
- **No Node.js package manager** — no `package.json` detected; JavaScript libraries are vendored locally under `template-frontend/lib/` or loaded via CDN

## Frameworks

**CSS Framework:**
- **Bootstrap 5.0.0** (2021) — Customized via SCSS variable overrides (`template-frontend/scss/bootstrap.scss`) with brand colors (`--primary: #88B44E`, `--secondary: #FB9F38`). Compiled as `template-frontend/css/bootstrap.min.css`. Full SCSS source included at `template-frontend/scss/bootstrap/scss/`.

**Backend (planned):**
- **Laravel** — PHP full-stack framework (per `general-requirement.md` line 181)

**No JavaScript framework** — plain jQuery-based interactivity, no React/Vue/Angular.

## Key Dependencies

### CSS Libraries

| Library | Version | Source | Purpose |
|---------|---------|--------|---------|
| Bootstrap | 5.0.0 | `template-frontend/css/bootstrap.min.css` | Grid system, components, utilities |
| Font Awesome | 5.10.0 | CDN (`cdnjs.cloudflare.com`) | Icon set (social, UI, e-commerce icons) |
| Bootstrap Icons | 1.4.1 | CDN (`cdn.jsdelivr.net`) | Additional icon set (chevrons, arrows) |
| Owl Carousel | 2.x | `template-frontend/lib/owlcarousel/` | Product and testimonial carousels |
| Animate.css | 4.x | `template-frontend/lib/animate/` | CSS animations (paired with WOW.js) |

### JavaScript Libraries

| Library | Version | Source | Purpose |
|---------|---------|--------|---------|
| jQuery | 3.6.1 | CDN (`ajax.googleapis.com`) | DOM manipulation, event handling |
| Bootstrap JS | 5.0.0 | CDN (`cdn.jsdelivr.net`) | Bootstrap interactive components (modal, carousel, navbar toggle) |
| Owl Carousel | 2.x | `template-frontend/lib/owlcarousel/` | Product carousel, testimonial carousel |
| WOW.js | 1.1.2 | `template-frontend/lib/wow/` | Scroll-triggered reveal animations |
| jQuery Easing | 1.4.1 | `template-frontend/lib/easing/` | Custom easing for scroll animations |
| Waypoints | 4.0.1 | `template-frontend/lib/waypoints/` | Scroll position detection |

### Google Fonts
- **Open Sans** (weights 400, 600) — body text (`$font-family-base`)
- **Playfair Display** (weights 700, 900) — headings (`$headings-font-family`)

Loaded via: `https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playfair+Display:wght@700;900&display=swap`

## Build/Dev Tooling

**Current:** None detected. No build tools (Webpack, Vite, Gulp), no TypeScript compiler, no CSS post-processor. SCSS files are present but no build pipeline is configured.

**Planned:** No tooling specified in requirements.

## Configuration

**Environment:**
- No `.env` files present (not yet created)
- No `.nvmrc`, `.node-version`, `.python-version` files detected
- No `.gitignore` file detected
- `.planning/config.json` — GSD workflow configuration only (not application config)

**Build:**
- No build configuration files detected (no `webpack.config.js`, `vite.config.js`, `gulpfile.js`, etc.)

## Platform Requirements

**Development:**
- Any web browser (static HTML only in current state)
- No server runtime required (current state)
- Any text editor

**Production (planned):**
- Shared hosting with PHP 8.x support
- MySQL 8.x database
- Apache or Nginx web server
- PHP extensions: PDO, MySQL, OpenSSL, MBString, XML, cURL (Laravel requirements)

---

*Stack analysis: 2026-06-20*
