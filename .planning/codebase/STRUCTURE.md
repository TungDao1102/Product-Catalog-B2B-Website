# Codebase Structure

**Analysis Date:** 2026-06-20

## Directory Layout

```
Product-Catalog-B2B-Website/
├── .codegraph/                      # Codegraph index files (auto-generated)
├── .git/                            # Git repository data
├── .omo/                            # OpenCode runtime continuation state
│   └── run-continuation/
│       └── ses_*.json               # Session persistence files
├── .planning/                       # GSD project planning and codebase maps
│   ├── config.json                  # GSD workflow configuration
│   └── codebase/                    # Codebase analysis documents
│       ├── ARCHITECTURE.md          # (this file)
│       └── STRUCTURE.md             # (this file)
├── template-frontend/               # Frontend HTML template (primary codebase)
│   ├── index.html                   # Home page (562 lines)
│   ├── about.html                   # About page (222 lines)
│   ├── product.html                 # Product listing page (217 lines)
│   ├── store.html                   # Store/gallery page (255 lines)
│   ├── blog.html                    # Blog/article page (197 lines)
│   ├── contact.html                 # Contact page with map + form (255 lines)
│   ├── feature.html                 # Features/video showcase page (255 lines)
│   ├── testimonial.html             # Client testimonials page (218 lines)
│   ├── 404.html                     # Error page (192 lines)
│   ├── css/
│   │   ├── bootstrap.min.css        # Bootstrap 5 compiled (minified)
│   │   └── style.css                # Custom template styles (506 lines)
│   ├── scss/
│   │   ├── bootstrap.scss           # Bootstrap 5 variable overrides (32 lines)
│   │   └── bootstrap/               # Bootstrap 5 SCSS source (full library)
│   │       └── scss/
│   │           ├── _variables.scss   # Bootstrap variable definitions
│   │           ├── _functions.scss   # Bootstrap functions
│   │           ├── mixins/           # Bootstrap mixins (22 files)
│   │           ├── forms/            # Form component SCSS (9 files)
│   │           ├── helpers/          # Helper utility SCSS (7 files)
│   │           ├── utilities/        # Utility API SCSS
│   │           ├── vendor/           # Vendor SCSS (RFS)
│   │           └── *.scss            # Component SCSS (~30 files)
│   ├── js/
│   │   └── main.js                  # Template JavaScript (98 lines)
│   ├── lib/                         # Third-party JS libraries
│   │   ├── wow/                     # WOW.js for scroll animations
│   │   │   ├── wow.js              # Source (not minified)
│   │   │   └── wow.min.js          # Minified production version
│   │   ├── animate/                 # Animate.css for CSS animations
│   │   │   ├── animate.css
│   │   │   └── animate.min.css
│   │   ├── easing/                  # jQuery Easing plugin
│   │   │   ├── easing.js
│   │   │   └── easing.min.js
│   │   ├── waypoints/               # Waypoints.js for scroll triggers
│   │   │   ├── waypoints.min.js
│   │   │   └── links.php           # CDN link references
│   │   └── owlcarousel/             # OWL Carousel 2
│   │       ├── owl.carousel.js
│   │       ├── owl.carousel.min.js
│   │       ├── LICENSE
│   │       └── assets/              # OWL Carousel CSS + assets
│   │           ├── owl.carousel.css
│   │           ├── owl.carousel.min.css
│   │           ├── owl.theme.default.css
│   │           ├── owl.theme.default.min.css
│   │           ├── owl.theme.green.css
│   │           ├── owl.theme.green.min.css
│   │           └── ajax-loader.gif
│   │           └── owl.video.play.png
│   └── img/                         # Image assets (22 files)
│       ├── logo.png                 # Site logo
│       ├── carousel-1.jpg, carousel-2.jpg
│       ├── about-1.jpg through about-6.jpg
│       ├── product-1.jpg through product-4.jpg
│       ├── product-bg.png
│       ├── store-product-1.jpg through store-product-3.jpg
│       ├── testimonial-1.jpg through testimonial-3.jpg
│       ├── testimonial-bg.jpg
│       ├── video-bg.jpg
│       └── article.jpg
└── general-requirement.md           # MVP specification document (182 lines)
```

## Directory Purposes

**`template-frontend/` (Root of template code):**
- Purpose: Complete static HTML frontend template for the B2B product catalog website
- Contains: 9 HTML pages, CSS, SCSS, JavaScript, third-party libraries, and image assets
- Key files: `index.html` (home page), `css/style.css` (custom styles), `js/main.js` (behavior)

**`template-frontend/css/`:**
- Purpose: Stylesheet files
- Contains: Bootstrap 5 minified CSS and custom template styles
- Key files: `style.css` — all custom styling (506 lines, 15 named sections)

**`template-frontend/scss/`:**
- Purpose: Bootstrap 5 SCSS source with brand color overrides
- Contains: `bootstrap.scss` with variable overrides (primary: #88B44E), imports full Bootstrap 5 SCSS
- Key files: `bootstrap.scss` — entry point that overrides Bootstrap variables

**`template-frontend/js/`:**
- Purpose: Application JavaScript
- Contains: Single `main.js` file with all template behavior
- Key files: `main.js` — spinner, sticky navbar, back-to-top, video modal, OWL Carousel init (98 lines)

**`template-frontend/lib/`:**
- Purpose: Third-party JavaScript/CSS libraries (vendored locally)
- Contains: WOW.js, Animate.css, jQuery Easing, Waypoints.js, OWL Carousel 2
- Notable: Each library has both minified and source versions

**`template-frontend/img/`:**
- Purpose: All static image assets for the template
- Contains: 22 JPG/PNG images — logo, carousels, products, about, testimonials, backgrounds

**`.planning/`:**
- Purpose: GSD (Goal-Structured Development) planning, configuration, and codebase maps
- Contains: Workflow config (`config.json`) and codebase analysis documents

**Root files:**
- `general-requirement.md` (182 lines) — Full MVP specification in Vietnamese: site structure, product management, RFQ system, search, multilanguage, tech stack (Laravel + MySQL)

## Key File Locations

**Entry Points:**
- `template-frontend/index.html`: Home/landing page
- `template-frontend/about.html`: Company information
- `template-frontend/product.html`: Product listing/categories
- `template-frontend/store.html`: Product gallery/store grid
- `template-frontend/blog.html`: Blog/article detail
- `template-frontend/contact.html`: Contact form + map

**Configuration:**
- `.planning/config.json`: GSD workflow settings (73 lines)
- `template-frontend/scss/bootstrap.scss`: Bootstrap 5 theme customization (32 lines)

**Core Logic:**
- `template-frontend/js/main.js`: All interactive behavior (98 lines)
- `template-frontend/css/style.css`: All custom presentation (506 lines)

**Specification:**
- `general-requirement.md`: Full requirements in Vietnamese (182 lines)

## Naming Conventions

**Files:**
- HTML pages: Lowercase, descriptive, `page-name.html` (e.g., `about.html`, `store.html`, `testimonial.html`)
- CSS: `style.css` (single file for all custom styles), `bootstrap.min.css` (vendored)
- SCSS: `bootstrap.scss` (entry point), `_partial.scss` (Bootstrap partials with underscore prefix)
- JS: `main.js` (single application file)
- Library files: Preserve original library naming (e.g., `wow.min.js`, `owl.carousel.js`)
- Images: Descriptive prefix + number pattern (e.g., `about-1.jpg`, `product-3.jpg`, `store-product-2.jpg`)

**Directories:**
- Lowercase, single word: `css/`, `js/`, `img/`, `scss/`, `lib/`
- Library directories use original library names: `wow/`, `owlcarousel/`, `easing/`, `waypoints/`, `animate/`

**HTML Structure:**
- Section comments: `<!-- Component Start -->` / `<!-- Component End -->`
- CSS class naming: BEM-like with prefix (`.product-item`, `.store-item`, `.section-title`, `.btn-square`, `.btn-play`)
- CSS custom properties used for theme colors: `--primary`, `--secondary`, `--light`, `--dark`

## Where to Add New Code

**New Feature/Page:**
- HTML page: `template-frontend/{feature-name}.html`
- Follow the established pattern: Spinner → Navbar → Page Header (if sub-page) → Content Sections → Footer → Copyright → Back-to-Top → Scripts
- Use `class="active"` on the corresponding navbar link

**New Component/Section:**
- HTML: Inline within the appropriate page file, wrapped in `<!-- Component Start/End -->` comments
- Styles: Add new CSS section to `template-frontend/css/style.css` following the `/* /*** Component ***/ */` comment pattern
- Behavior: Add jQuery initialization to `template-frontend/js/main.js`

**New Styles:**
- Custom: `template-frontend/css/style.css` — add new section with `/*** ComponentName ***/` header
- Bootstrap overrides: `template-frontend/scss/bootstrap.scss` — add variable overrides before `@import`

**New Third-party Library:**
- Download to: `template-frontend/lib/{library-name}/`
- Include in HTML: Add `<link>` in `<head>` and `<script>` before `js/main.js`
- OWL Carousel assets go in `template-frontend/lib/owlcarousel/assets/`

**New Images:**
- Path: `template-frontend/img/{descriptive-name}.{jpg|png}`
- Naming: Follow existing patterns (e.g., `product-5.jpg`, `about-7.jpg`)

**Migrating to Backend (future):**
- Layout shell → `resources/views/layouts/app.blade.php` (Laravel)
- Page content → `resources/views/pages/*.blade.php` with `@extends('layouts.app')`
- CSS → `public/css/` (compiled from `resources/sass/`)
- JS → `public/js/` (compiled from `resources/js/`)
- Images → `public/img/` or `storage/app/public/`

## Special Directories

**`.codegraph/`:**
- Purpose: Codegraph index data for symbol resolution (auto-generated)
- Generated: Yes
- Committed: Yes

**`.omo/`:**
- Purpose: OpenCode runtime continuation state for session persistence
- Generated: Yes (runtime generated)
- Committed: No (should be gitignored)

**`.planning/`:**
- Purpose: GSD workflow planning documents and codebase analysis
- Generated: No (manually curated)
- Committed: Yes

**`template-frontend/lib/owlcarousel/assets/`:**
- Purpose: OWL Carousel CSS theme files and assets
- Contains: 4 theme CSS variants (default, green), both normal and minified, plus PNG/GIF assets
- Generated: No (vendored third-party)
- Committed: Yes

---

*Structure analysis: 2026-06-20*
