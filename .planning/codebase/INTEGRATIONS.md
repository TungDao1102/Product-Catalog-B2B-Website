# External Integrations

**Analysis Date:** 2026-06-20

## APIs & External Services

**Current (template-frontend only):**

| Service | Purpose | Integration Point |
|---------|---------|-------------------|
| Google Fonts | Web font delivery (`Open Sans`, `Playfair Display`) | `<link>` in all 9 HTML pages |
| Font Awesome CDN | Icon library (v5.10.0) | `<link>` in all HTML pages |
| Bootstrap Icons CDN | Icon library (v1.4.1) | `<link>` in all HTML pages |
| jQuery CDN | jQuery runtime (v3.6.1) | `<script>` in all HTML pages |
| Bootstrap JS CDN | Bootstrap interactive components (v5.0.0) | `<script>` in all HTML pages |
| YouTube | Embedded product video | `<iframe>` in video modal (`index.html` line 308), video ID: `DWRcNpR6Kdc` |

**Planned (per `general-requirement.md`):**

| Service | Purpose | Details |
|---------|---------|---------|
| Email Service | Quote request notifications (admin + customer confirmation) | Send email to admin when customer submits quote request; send confirmation to customer. Implementation: Laravel Mail (SMTP or Mailgun/SES). |
| Google Maps | Company location map on Contact page | Embed on contact page. Not yet implemented. |

## Data Storage

**Databases:**
- **MySQL** (planned per `general-requirement.md` line 181)
  - Connection: Configured in Laravel `.env` (`DB_CONNECTION=mysql`)
  - Client: Laravel Eloquent ORM (planned)
  - Tables expected: categories (multi-level), manufacturers, products, technical_specifications, quote_requests, users, projects, news/articles

**File Storage:**
- **Local filesystem only** (planned) — product images, brochure PDFs stored on shared hosting
  - `template-frontend/img/` currently holds 23 static image placeholders (JPG, PNG)

**Caching:**
- Not detected. No Redis, Memcached, or file-based caching configured.

## Authentication & Identity

**Auth Provider:**
- **Laravel Breeze/Jetstream** (expected) — for admin authentication to manage products, categories, and content
- No social login or OAuth providers configured (not applicable — B2B catalog without e-commerce)

## Monitoring & Observability

**Error Tracking:**
- Not configured

**Logs:**
- Not configured (Laravel log channel expected in production: `stack` → daily files)

## CI/CD & Deployment

**Hosting:**
- **Shared hosting** (planned per `general-requirement.md` line 180)
- No PaaS or cloud provider specified

**CI Pipeline:**
- Not configured

**Domain:**
- Not configured (placeholder text in template footer uses "Your Site Name", `info@example.com`, `+012 345 67890`)

## Environment Configuration

**Required env vars (planned for Laravel):**

| Variable | Purpose |
|----------|---------|
| `DB_HOST` | MySQL host |
| `DB_DATABASE` | MySQL database name |
| `DB_USERNAME` | MySQL username |
| `DB_PASSWORD` | MySQL password |
| `MAIL_MAILER` | Email driver (smtp/sendmail) |
| `MAIL_HOST` | SMTP host |
| `MAIL_PORT` | SMTP port |
| `MAIL_USERNAME` | SMTP username |
| `MAIL_PASSWORD` | SMTP password |
| `MAIL_FROM_ADDRESS` | Sender email for notifications |
| `APP_URL` | Application base URL |

**Secrets location:**
- Laravel `.env` file (not yet created — not present in repository)

## Webhooks & Callbacks

**Incoming:**
- None (not applicable)

**Outgoing:**
- None (planned: email notifications for quote requests via Laravel Mail, not webhooks)

## Third-Party Content (Static)

All vendor libraries in `template-frontend/lib/` are vendored locally (committed to repo):

| Library | Location |
|---------|----------|
| Owl Carousel | `template-frontend/lib/owlcarousel/` |
| WOW.js | `template-frontend/lib/wow/` |
| jQuery Easing | `template-frontend/lib/easing/` |
| Waypoints | `template-frontend/lib/waypoints/` |
| Animate.css | `template-frontend/lib/animate/` |

These are duplicated from their CDN sources and served locally — no external dependency at runtime for these libraries.

---

*Integration audit: 2026-06-20*
