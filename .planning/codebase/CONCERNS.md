# Codebase Concerns

**Analysis Date:** 2026-06-20

## Tech Debt

### No Backend Implementation (Foundational Gap)
- **Issue:** The requirements document (`general-requirement.md`) specifies a full-stack Laravel + MySQL application with product catalog management, multi-level categories, quote request system, search, and bilingual support. However, the repository contains only a static HTML/CSS/JS template with zero backend code.
- **Files:** `general-requirement.md`, `template-frontend/`
- **Impact:** The entire backend — database schema, models, controllers, migrations, authentication, admin panel, API routes, email integration — is absent. No progress toward the actual application has been made.
- **Fix approach:** Initialize a Laravel project, design the MySQL schema, and build the backend incrementally per the requirements document.

### Wrong Template Domain (Tea House vs B2B Catalog)
- **Issue:** The frontend template (`template-frontend/`) is a generic "Tea House — Tea Shop" template from HTML Codex. All pages reference tea products, tea-house branding, and tea shop e-commerce patterns (pricing, cart, store). The actual project requires a B2B multi-industry product catalog (security screening, construction machinery, medical equipment).
- **Files:** `template-frontend/index.html`, `template-frontend/product.html`, `template-frontend/store.html`, `template-frontend/css/style.css`
- **Impact:** The template content, imagery, color scheme, and page structure do not match the B2B industrial catalog use case. Every HTML page and CSS section must be rewritten or heavily customized.
- **Fix approach:** Replace all template branding, hero content, product sections, and imagery with B2B-appropriate content. Remove e-commerce elements (Add to Cart, pricing), replace with quote-request flows.

### Massive Duplicated HTML (No Template Inheritance)
- **Issue:** Every HTML page (`index.html`, `about.html`, `product.html`, `store.html`, `feature.html`, `blog.html`, `testimonial.html`, `contact.html`, `404.html`) contains a full copy of the navbar (~35 lines) and footer (~55 lines). There are 9 pages × ~90 duplicated lines = ~810 lines of redundant HTML. Any navbar/footer change requires editing all files.
- **Files:** All `*.html` files under `template-frontend/`
- **Impact:** Extremely fragile — updating navigation links or footer content requires touching every HTML file. Prone to inconsistencies.
- **Fix approach:** Adopt a server-side templating engine (Laravel Blade) or a build-time include mechanism. Centralize layout in a single template.

### Placeholder/Lorem Ipsum Content Across All Pages
- **Issue:** Every page uses generic Latin filler text ("Tempor erat elitr rebum at clita", "Diam dolor diam ipsum sit"), placeholder client names ("Client Name"), demo contact info ("info@example.com", "+012 345 67890", "123 Street, New York, USA"), and placeholder imagery (tea photos).
- **Files:** All `*.html` files under `template-frontend/`
- **Impact:** The site is not usable for any real purpose. All content must be replaced with actual business data. The current state creates a misleading impression if accidentally deployed.
- **Fix approach:** Replace all placeholder text, images, and contact info with the actual company's content before any deployment.

### Bloat from Unused Template Assets
- **Issue:** The repository includes both minified and unminified versions of third-party libraries: `owl.carousel.js` + `owl.carousel.min.js`, `wow.js` + `wow.min.js`, `easing.js` + `easing.min.js`, and the full Bootstrap 5 SCSS source tree (`scss/bootstrap/scss/` with ~90+ partial files). The compiled `bootstrap.min.css` is also committed separately from the SCSS source.
- **Files:** `template-frontend/lib/owlcarousel/owl.carousel.js`, `template-frontend/lib/wow/wow.js`, `template-frontend/lib/easing/easing.js`, `template-frontend/scss/bootstrap/`
- **Impact:** Unnecessarily large repository size. Includes development sources that serve no purpose in production.
- **Fix approach:** Remove unminified sources (keep only minified for production), remove unused Bootstrap SCSS source unless it's being actively customized.

### No Package Manager or Build Process
- **Issue:** The project has no `package.json`, no `composer.json`, no build tools, no bundler (Webpack, Vite, Gulp), and no dependency lockfile. JavaScript libraries are loaded directly from `lib/` (vendored) or from CDNs with no version pinning except what's hardcoded in HTML.
- **Files:** Root directory (missing `package.json`), `template-frontend/*.html` (CDN URLs)
- **Impact:** No reproducible builds. No dependency version management. Teams cannot reliably install, update, or audit dependencies. CDN links may break or serve malicious code if compromised.
- **Fix approach:** Initialize npm/composer, move to package-managed dependencies, set up a build pipeline.

### Outdated Third-Party Libraries
- **Issue:** Bootstrap 5.0.0 (via CDN) — latest is 5.3.x with security fixes. Font Awesome 5.10.0 (via CDN) — latest is 6.x. jQuery 3.6.1 — released 2022, minor updates exist. Owl Carousel 2 — project has been unmaintained since 2018.
- **Files:** `template-frontend/*.html` (CDN script/link tags)
- **Impact:** Potential known CVEs in older library versions. Missing features and performance improvements. Owl Carousel is effectively abandoned.
- **Fix approach:** Update to latest stable versions. Replace Owl Carousel with a maintained alternative (Swiper, Glide.js) or a lightweight custom carousel.

## Known Bugs

### Video Modal Logs Undefined Variable
- **Symptoms:** `main.js` line 48 logs `$videoSrc` to console, but the variable is only assigned inside a `.btn-play` click handler. On page load, `$videoSrc` is `undefined` because no button click has occurred yet. The `console.log` at line 48 executes synchronously on page load.
- **Files:** `template-frontend/js/main.js` (lines 44–48)
- **Trigger:** Loading any page with the video modal. Open browser console.
- **Workaround:** Remove the stray `console.log($videoSrc)` on line 48. Move logging inside the click handler if logging is desired.

### Contact Form Is Non-Functional
- **Symptoms:** The contact form (`contact.html` lines 129–159) has a `<form>` element but no `action` attribute, no JavaScript handler, and no backend endpoint. The page explicitly states: "The contact form is currently inactive. Get a functional and working contact form with Ajax & PHP..."
- **Files:** `template-frontend/contact.html`
- **Trigger:** Submitting the contact form does nothing (page reloads to current URL).
- **Workaround:** The form needs a backend endpoint and either a proper `action` URL with server handling or an AJAX submit handler wired to the Laravel backend.

### "Add to Cart" Buttons with No Cart System
- **Symptoms:** `store.html` and `index.html` contain "Add to Cart" buttons (`<a href="" class="btn btn-dark rounded-pill py-2 px-4 m-2">Add to Cart</a>`). These links point to `""` (current page) and there is no cart, checkout, or e-commerce system. The B2B requirements explicitly state "Website không có chức năng bán hàng trực tuyến" (no online sales functionality).
- **Files:** `template-frontend/store.html` (lines 120, 141, 162), `template-frontend/index.html` (lines 343, 364, 385)
- **Trigger:** The e-commerce elements directly contradict the project requirements. They are leftover template features that must be removed.

### Typo: "Acticle" Instead of "Article"
- **Symptoms:** `blog.html` uses "Acticle" (a misspelling of "Article") in two places: page header title (line 82) and breadcrumb text (line 87). The same typo appears in `index.html` line 225 ("Featured Acticle").
- **Files:** `template-frontend/blog.html` (lines 82, 87), `template-frontend/index.html` (line 225)
- **Trigger:** Navigate to Blog page or view home page Featured Article section.

## Security Considerations

### No Input Validation or Sanitization (Absent Backend)
- **Risk:** The contact form and quote request system (per requirements) will collect PII (name, company, phone, email). Since there is no backend yet, no validation, sanitization, or CSRF protection exists. When implemented, these must be added correctly.
- **Files:** `template-frontend/contact.html` (form), `general-requirement.md` (quote request spec)
- **Current mitigation:** None — the form is inactive.
- **Recommendations:** Implement server-side validation in Laravel, use CSRF tokens, apply output escaping, and set up rate limiting on form submissions.

### CDN Dependency for Core Libraries
- **Risk:** jQuery, Bootstrap JS, Font Awesome, and Google Fonts are loaded from third-party CDNs with `integrity` attributes absent. A compromised CDN or MITM attack could serve malicious scripts.
- **Files:** `template-frontend/index.html` (lines 15-21, 551-556), replicated across all HTML files
- **Current mitigation:** Uses CDN URLs only. No SRI (Subresource Integrity) hashes.
- **Recommendations:** Add SRI hashes to all CDN script/link tags, OR vendor the assets locally, OR use a package manager with a build step.

### No Environment Variable Management
- **Risk:** When the Laravel backend is implemented, credentials (database, email SMTP, API keys) must be handled via `.env`. The current project has no `.env.example` and no environment configuration documentation.
- **Files:** Entire project (missing `.env.example`)
- **Current mitigation:** None.
- **Recommendations:** Create `.env.example` with all required variables documented when initializing the Laravel project.

## Performance Bottlenecks

### Unoptimized Image Assets
- **Problem:** The 23 placeholder images in `template-frontend/img/` are JPEG files of unknown compression level. They are generic template images (tea-related) that will be replaced, but the current images lack WebP versions, responsive `srcset` attributes, and lazy loading.
- **Files:** `template-frontend/img/*.jpg`
- **Cause:** Template defaults with no optimization applied.
- **Improvement path:** Use modern formats (WebP/AVIF), implement responsive images with `srcset`, add `loading="lazy"` to below-fold images, and compress all assets. Replace with actual B2B product images.

### No JavaScript Bundling or Minification
- **Problem:** jQuery (via CDN), Bootstrap bundle (via CDN), Wow.js, Easing, Waypoints, Owl Carousel, and `main.js` are loaded as 7 separate HTTP requests. No bundling, no tree-shaking, no code splitting.
- **Files:** `template-frontend/*.html` (script tags)
- **Cause:** No build pipeline.
- **Improvement path:** Bundle and minify all JS into a single file. Remove unused libraries (Waypoints, Easing may not be needed without ScrollMagic/WOW dependency).

### Render-Blocking CSS Loading
- **Problem:** All CSS files (Bootstrap, Font Awesome, Animate, Owl Carousel, style.css) are loaded synchronously in `<head>`, blocking render. No critical CSS inlining, no `preload` or `media` attributes for non-critical stylesheets.
- **Files:** `template-frontend/*.html` (`<head>` sections)
- **Cause:** Template defaults.
- **Improvement path:** Inline critical above-fold CSS, defer non-critical stylesheets, use `preload` with `onload` for below-fold CSS.

## Fragile Areas

### All HTML Pages Are Independent, Static Files
- **Files:** `template-frontend/*.html` (9 files)
- **Why fragile:** Every page is a standalone HTML file with no shared partials, no inheritance, no components. Any structural change (nav link added, footer redesigned, script dependency changed) requires editing all 9 files identically. This is the single highest-risk structural issue in the codebase.
- **Safe modification:** Switch to Laravel Blade layout inheritance. Create a `layouts/app.blade.php` with the navbar/footer/scripts, and extend it from each page template.
- **Test coverage:** None — no automated regression tests exist.

### Duplicate CSS Rules Across style.css and Bootstrap
- **Files:** `template-frontend/css/style.css`, `template-frontend/css/bootstrap.min.css`
- **Why fragile:** Custom styles override Bootstrap in `style.css`, but the SCSS source (`scss/bootstrap/scss/`) contains Bootstrap source variables that could conflict. Custom `.fw-medium`, `.fw-bold`, `.fw-black` classes in style.css (lines 17-28) duplicate Bootstrap's utility classes.
- **Safe modification:** Use Bootstrap's utility classes directly (`.fw-semibold` instead of custom `.fw-medium`). Customize Bootstrap via SCSS variables rather than overriding. Remove the separate SCSS source tree if not actively being used for development.
- **Test coverage:** None.

### Stale GSD Planning Configuration
- **Files:** `.planning/config.json`
- **Why fragile:** The GSD planning config enables features like `ui_review`, `tdd_mode`, `security_enforcement`, and `code_review`, but the project has no tests, no CI/CD, no security review process, and no UI to review. Features enabled in config may trigger workflows that can't meaningfully execute.
- **Safe modification:** Audit `.planning/config.json` and disable features that don't apply until the project is further along.
- **Test coverage:** N/A — config infrastructure only.

### Hardcoded YouTube Video ID
- **Files:** `template-frontend/index.html` (line 286), `template-frontend/feature.html` (line 143)
- **Why fragile:** The video modal references a specific YouTube embed (`DWRcNpR6Kdc`). If the video is removed from YouTube or the channel is deleted, the video section breaks silently.
- **Safe modification:** Make the video URL configurable via a data attribute in the backend, or use a placeholder that degrades gracefully if the video is unavailable.
- **Test coverage:** None.

## Scaling Limits

**(The project is in its earliest stage — backend not yet built. Scaling limits cannot be meaningfully assessed for the full application. The static frontend has no scaling concerns since it's not server-rendered.)**

- **Current capacity:** Single static HTML site, no database, no server.
- **Limit:** N/A — the project must first be implemented before scaling analysis is relevant.
- **Scaling path:** Build the Laravel + MySQL backend per requirements, then assess database indexing, caching, query optimization, and CDN deployment.

## Dependencies at Risk

### Owl Carousel 2 (Unmaintained)
- **Risk:** Last updated 2018. No security patches, no bug fixes, no compatibility updates for modern browsers/jQuery versions.
- **Impact:** Carousels on product and testimonial sections will break if jQuery is updated or browsers change their API.
- **Migration plan:** Replace with Swiper (actively maintained, modular, lightweight) or a native CSS-based carousel.

### jQuery 3.6.1 (Aging but Maintained)
- **Risk:** The entire `main.js` is written as a jQuery IIFE. Heavy jQuery dependency for minor DOM manipulation. jQuery is no longer necessary for modern browser APIs.
- **Impact:** 87KB+ download for animation triggers, scroll listeners, and carousel initialization — all achievable with vanilla JS.
- **Migration plan:** Rewrite `main.js` in vanilla JavaScript during the Laravel implementation phase. Remove the jQuery CDN dependency.

### Font Awesome 5.10.0 (Outdated)
- **Risk:** Three major versions behind (current is Font Awesome 6). Missing icons, no security patches.
- **Impact:** If icons reference names that changed in v6, upgrade will break visual elements.
- **Migration plan:** Migrate to Font Awesome 6 or switch to a lighter alternative (Feather Icons, Tabler Icons, or inline SVGs).

## Missing Critical Features

**(Not yet implemented at all — these are requirements-to-be-built, not existing function gaps)**

- **Product management:** No database, no models, no admin CRUD for products, categories, manufacturers.
- **Multi-level category hierarchy:** Not implemented. The requirement specifies ≥3 category levels.
- **Quote request system:** Not implemented. The required quote form with lead capture, admin notification email, and customer confirmation email does not exist.
- **Search:** No search functionality exists.
- **Multi-language (Vietnamese + English):** Not implemented.
- **Admin panel:** Not implemented.
- **SEO framework:** Empty `<meta>` keywords and descriptions, no structured data, no sitemap, no canonical URLs, no Open Graph tags.
- **Responsive images:** Static `<img>` tags with no `srcset` or `sizes` for responsive breakpoints.

## Test Coverage Gaps

- **What's not tested:** Everything. The project has zero test files — no unit tests, no integration tests, no E2E tests.
- **Files:** Entire repository
- **Risk:** 100% of any future code changes will be untested. Regressions cannot be detected. The TDD mode enabled in `.planning/config.json` cannot function without a test suite.
- **Priority:** High — testing must be established before (or alongside) backend implementation.

---

*Concerns audit: 2026-06-20*
