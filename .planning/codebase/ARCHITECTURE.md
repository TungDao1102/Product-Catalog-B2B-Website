<!-- refreshed: 2026-06-20 -->
# Architecture

**Analysis Date:** 2026-06-20

## System Overview

The project is a **multi-industry B2B product catalog website** currently in the template/frontend prototyping phase. The codebase contains a static HTML/CSS/JS frontend template (derived from the "Tea House" HTML Codex template) alongside a requirements document specifying the full intended architecture (Laravel + MySQL backend).

```text
┌───────────────────────────────────────────────────────────┐
│                   PRESENTATION LAYER                       │
│         Static HTML Templates (template-frontend/)         │
├──────────────┬──────────────┬──────────────┬───────────────┤
│  Core Pages  │  Product     │  Content     │  Utility      │
│  index.html  │  store.html  │  about.html  │  404.html     │
│  contact.html│  product.html│  blog.html   │  feature.html │
│              │              │  testimonial  │               │
└──────┬───────┴──────┬───────┴──────┬───────┴──────┬────────┘
       │              │              │              │
       ▼              ▼              ▼              ▼
┌───────────────────────────────────────────────────────────┐
│                      STYLE LAYER                           │
│  CSS ───────── style.css (506 lines, custom)               │
│  SCSS ───────── bootstrap.scss (Bootstrap 5 variables)     │
│  CDN ────────── Bootstrap 5, FontAwesome, Bootstrap Icons   │
└───────────────────────┬───────────────────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────────────────┐
│                    BEHAVIOR LAYER                          │
│  jQuery 3.6.1 ──── main.js (98 lines, template behavior)  │
│  lib/ ──────────── WOW.js, OWL Carousel, Waypoints,       │
│                    Easing, Animate.css                     │
└───────────────────────────────────────────────────────────┘
```

## Future Architecture (per `general-requirement.md`)

The requirements specify a Laravel + MySQL backend with:

```
┌───────────────────────────────────────────────────────────────┐
│                  LARAVEL MVC BACKEND (Planned)                 │
├──────────────────┬──────────────────┬─────────────────────────┤
│   Routes/Web     │   Controllers    │      Views (Blade)      │
│   (Not built)    │   (Not built)    │   (Migrate from HTML)   │
├────────┬─────────┴─────────┬────────┴──────────┬──────────────┤
│ Model  │  Category (3+     │  Product          │  Inquiry     │
│ Layer  │  levels, nested)  │  (brand, specs,   │  (RFQ form)  │
│        │                   │  brochure PDF)    │              │
├────────┴───────────────────┴───────────────────┴──────────────┤
│                     MySQL Database                            │
└───────────────────────────────────────────────────────────────┘
```

## Component Responsibilities

| Component | Responsibility | File |
|-----------|----------------|------|
| Home Page | Landing page with carousel, product showcase, about, video, store, testimonials, contact form | `template-frontend/index.html` |
| About Page | Company profile, history, images | `template-frontend/about.html` |
| Product Listing | Product category browsing with carousel | `template-frontend/product.html` |
| Store Page | Product cards with overlay actions, star ratings, pricing | `template-frontend/store.html` |
| Blog Page | Article display with image and excerpt | `template-frontend/blog.html` |
| Contact Page | Contact info cards, Google Maps embed, contact form (inactive) | `template-frontend/contact.html` |
| Testimonial | Client testimonials carousel | `template-frontend/testimonial.html` |
| Feature/Video | Video showcase with modal | `template-frontend/feature.html` |
| Error Page | 404 error display | `template-frontend/404.html` |
| Custom Styles | All template-specific CSS (506 lines) | `template-frontend/css/style.css` |
| Bootstrap SCSS | Custom Bootstrap 5 variable overrides | `template-frontend/scss/bootstrap.scss` |
| Template JS | Spinner, sticky navbar, back-to-top, video modal, carousels | `template-frontend/js/main.js` |

## Pattern Overview

**Overall:** Multi-page static HTML template with centralized shared resources.

**Key Characteristics:**
- Every page is a self-contained HTML file with duplicated `<head>` sections (no templating engine yet — Blade migration planned)
- Shared CSS/JS resources loaded identically across all pages via `<link>` and `<script>` tags
- All pages share identical Navbar, Footer, Spinner, Back-to-Top UI components
- JavaScript is a single `js/main.js` file loaded by every page
- Image assets centralized in `img/` directory
- jQuery-based DOM manipulation (no frontend framework)

## Layers

**Page Layer:**
- Purpose: Individual HTML pages serving as distinct routes/sections
- Location: `template-frontend/*.html`
- Contains: Full HTML5 documents with inline content, SEO meta tags, breadcrumbs
- Depends on: All CSS/JS resources loaded in `<head>` and before `</body>`
- Used by: Browser direct navigation

**Style Layer:**
- Purpose: Visual presentation and responsive layout
- Location: `template-frontend/css/style.css`, `template-frontend/scss/`, CDN stylesheets
- Contains: Custom CSS, Bootstrap 5 compiled CSS, Font Awesome icons, Google Fonts
- Depends on: Bootstrap 5 as foundational grid/component system
- Used by: All .html pages

**Behavior Layer:**
- Purpose: Interactive functionality and animations
- Location: `template-frontend/js/main.js`, `template-frontend/lib/`
- Contains: jQuery plugins (OWL Carousel, WOW.js, Waypoints, Easing)
- Depends on: jQuery 3.6.1, Bootstrap 5 JS bundle
- Used by: All .html pages

## Data Flow

### Static Template Browsing Flow

1. Browser requests `.html` file directly
2. HTML parsed, resources loaded (CSS in `<head>`, JS before `</body>`)
3. DOMContentLoaded → `main.js` initializes spinner, WOW.js, carousels
4. Interactions: scroll (sticky nav, back-to-top), click (navbar toggle, video modal, carousel navigation)
5. Contact form submits to `#` (no backend — currently inactive placeholder)

### Planned RFQ Flow (from requirements)

1. Customer fills quote request form on product detail page
2. Form data saved to database via Laravel backend
3. Email notification sent to Admin
4. Email confirmation sent to Customer

## Key Abstractions

**Section-based Page Layout:**
- Purpose: Every page follows `Spinner → Navbar → [Page Header/Breadcrumbs] → [Content Sections] → Footer → Copyright → Back-to-Top → Scripts`
- Files: All `template-frontend/*.html`
- Pattern: HTML comment-delimited sections (`<!-- Component Start -->` / `<!-- Component End -->`)

**Section Title Component:**
- Purpose: Consistent heading pattern used across sections
- Files: `index.html` (lines 144-146, 177-179, 321-323, 401-403), `contact.html` (lines 98-100), etc.
- Pattern: `<div class="section-title">` containing `<p>` (italic subtitle) + `<h1>` (display heading)

**Carousel/Grid Pattern:**
- Purpose: Repeating display items (products, store items, testimonials)
- Files: `index.html` (owl-carousel, grid items), `store.html` (grid), `product.html` (owl-carousel)
- Pattern: Wrapper container → OWL Carousel or Bootstrap grid → repeated items

## Entry Points

**Home Page:**
- Location: `template-frontend/index.html` (562 lines)
- Triggers: Browser navigation to root
- Responsibilities: Main landing with 8 distinct content sections (Carousel, About, Products, Article, Video, Store, Testimonial, Contact)

**Sub-pages:**
- Location: `template-frontend/{about,product,store,blog,contact,feature,testimonial,404}.html`
- Triggers: Browser navigation
- Pattern: Page header with breadcrumb + specific content sections + footer

## Architectural Constraints

- **No backend yet:** All HTML files are static. Forms submit to `#` placeholder URLs. No server-side processing exists.
- **No templating engine:** HTML sections (Navbar, Footer, head resources) are duplicated across every page. Migration to Laravel Blade is planned but not started.
- **No build system:** jQuery and JS libraries loaded via CDN and local `lib/` files. SCSS present but no build pipeline configured.
- **No routing:** Page navigation is direct HTML file links. No URL rewriting.
- **No database:** MySQL planned but not implemented.
- **jQuery dependency:** All interactive behavior requires jQuery 3.6.1.
- **Single JS file:** All template behavior is in `js/main.js` (98 lines). No module system.

## Anti-Patterns

### Duplicated Template Shell

**What happens:** Every HTML page duplicates the full `<head>`, Navbar, Footer, Spinner, Back-to-Top, and Script sections (e.g., Navbar is ~35 lines × 9 pages = ~315 redundant lines).
**Why it's wrong:** Changes to navigation or footer must be replicated across all pages — a maintenance burden.
**Do this instead:** When migrating to Laravel Blade, create `layouts/app.blade.php` with the shell and use `@extends('layouts.app')` + `@section('content')` for page-specific content.

### Inactive Contact Form

**What happens:** `contact.html` (lines 128-159) includes a contact form with no action URL and an explicit "contact form is currently inactive" message. Similarly, all `mailto:` or form submissions use `#` placeholders.
**Why it's wrong:** The primary lead generation mechanism (RFQ) is non-functional in the template.
**Do this instead:** Wire the form to a Laravel `ContactController@store` route with validation, database storage, and email notification as specified in the requirements.

## Error Handling

**Strategy:** Client-side only (no backend error handling yet)
**Patterns:**
- 404 page at `template-frontend/404.html`
- No JavaScript error handling patterns observed in `main.js`
- Console log statement at `main.js` line 48 (`console.log($videoSrc);`) — likely a development artifact

## Cross-Cutting Concerns

**Logging:** Not implemented (only `console.log` at `main.js:48`)
**Validation:** Not implemented (forms are inactive placeholders)
**Authentication:** Not implemented (no login/user system yet)
**SEO:** Basic meta tags (keywords, description) present but empty in all pages. Semantic HTML5 used. Breadcrumb navigation on sub-pages.
**Responsive Design:** Bootstrap 5 grid system used throughout. Custom media queries at breakpoints 768px and 992px in `style.css`.

---

*Architecture analysis: 2026-06-20*
