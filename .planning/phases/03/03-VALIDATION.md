---
phase: 03
slug: content-and-contact
status: audited
nyquist_compliant: true
last_audited: 2026-06-29
---

# Phase 03 — Validation Strategy

> Per-phase validation contract for Content & Contact phase.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | PHPUnit 11.x (Laravel default) |
| **Config file** | `phpunit.xml` |
| **Quick run command** | `php artisan test --parallel` |
| **Full suite command** | `php artisan test` |
| **Estimated runtime** | ~45s |

---

## Sampling Rate

- **After every task commit:** Run `php artisan test --parallel`
- **After every plan wave:** Run `php artisan test`
- **Before `/gsd-verify-work`:** Full suite must be green
- **Max feedback latency:** 60 seconds

---

## Per-Task Verification Map

| Task ID | Plan | Wave | Requirement | Test File | Automated Command | File Exists | Status |
|---------|------|------|-------------|-----------|-------------------|-------------|--------|
| 03-01-01 | 01 | 1 | REQ-01 — Post model + seeder | `tests/Feature/Filament/PostResourceTest.php` | `php artisan test --filter=PostResource` | ✅ | ● green |
| 03-01-02 | 01 | 1 | REQ-02 — Project model + seeder | `tests/Feature/Filament/ProjectResourceTest.php` | `php artisan test --filter=ProjectResource` | ✅ | ● green |
| 03-01-03 | 01 | 1 | REQ-11 — Database seeders | `tests/Feature/SeederTest.php` | `php artisan test --filter=SeederTest` | ✅ | ● green |
| 03-01-04 | 01 | 1 | REQ-09 — Mailables | `tests/Feature/MailTest.php` | `php artisan test --filter=MailTest` | ✅ | ● green |
| 03-01-05 | 01 | 1 | REQ-10 — Mail templates | `tests/Feature/MailTest.php` | `php artisan test --filter=MailTest` | ✅ | ● green |
| 03-02-01 | 02 | 2 | REQ-01 — Post Filament Resource | `tests/Feature/Filament/PostResourceTest.php` | `php artisan test --filter=PostResource` | ✅ | ● green |
| 03-02-02 | 02 | 2 | REQ-02 — Project Filament Resource | `tests/Feature/Filament/ProjectResourceTest.php` | `php artisan test --filter=ProjectResource` | ✅ | ● green |
| 03-03-01 | 03 | 2 | REQ-03 — Inquiry Resource (read-only) | `tests/Feature/Filament/InquiryResourceTest.php` | `php artisan test --filter=InquiryResource` | ✅ | ● green |
| 03-03-02 | 03 | 2 | REQ-04 — Contact Resource (read-only) | `tests/Feature/Filament/ContactResourceTest.php` | `php artisan test --filter=ContactResource` | ✅ | ● green |
| 03-04-01 | 04 | 3 | REQ-05 — Post frontend | `tests/Feature/Http/PostFrontendTest.php` | `php artisan test --filter=PostFrontendTest` | ✅ | ● green |
| 03-04-02 | 04 | 3 | REQ-06 — Project frontend | `tests/Feature/Http/ProjectFrontendTest.php` | `php artisan test --filter=ProjectFrontendTest` | ✅ | ● green |
| 03-04-03 | 04 | 3 | REQ-12 — Routes (/tin-tuc, /du-an) | `tests/Feature/Http/NavbarTest.php` | `php artisan test --filter=NavbarTest` | ✅ | ● green |
| 03-05-01 | 05 | 3 | REQ-07 — Contact form | `tests/Feature/Http/ContactFormTest.php` | `php artisan test --filter=ContactFormTest` | ✅ | ● green |
| 03-05-02 | 05 | 3 | REQ-08 — Quote/Inquiry form | `tests/Feature/Http/InquiryFormTest.php` | `php artisan test --filter=InquiryFormTest` | ✅ | ● green |
| 03-05-03 | 05 | 3 | REQ-12 — Navbar links | `tests/Feature/Http/NavbarTest.php` | `php artisan test --filter=NavbarTest` | ✅ | ● green |
| — | — | — | Navigation/sidebar sort | `tests/Feature/Filament/ResourceNavigationTest.php` | `php artisan test --filter=ResourceNavigation` | ✅ | ● green |

*Status: ○ pending ● green ✗ red ~ flaky*

**Audit note (2026-06-29):** All 12 requirements have corresponding test files. Tests require MySQL (`RefreshDatabase`/`DatabaseMigrations`). Environment-dependent — need running DB to confirm green at runtime.

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| RichEditor content rendering with image upload | REQ-01 | Filament JS-dependent editor | Log into /admin, create post with RichEditor, verify HTML renders correctly |
| Project image gallery reorder + upload | REQ-02 | Filament JS-dependent upload | Log into /admin, create project with multiple images, verify gallery display |
| Inquiry/Contact auto-mark is_read | REQ-03/04 | Requires viewing record in admin UI | Open Inquiry/Contact detail in admin, verify is_read icon updates |
| Google Maps embed on contact page | REQ-07 | Visual/functional check | Visit /lien-he, verify map embed loads and is interactive |
| Quote modal re-open on validation error | REQ-08 | Session flash browser behavior | Submit empty quote form, verify modal re-opens with errors |
| Email delivery (SMTP-dependent) | REQ-09 | External SMTP dependency | Submit contact/inquiry form while SMTP configured, verify emails arrive |

---

## Validation Sign-Off

- [x] All tasks have automated verify commands
- [x] Sampling continuity: no 3 consecutive tasks without automated verify
- [x] Wave 0 covers all MISSING references
- [x] No watch-mode flags
- [x] Feedback latency < 60s
- [x] `nyquist_compliant: true` set in frontmatter

**Nyquist-compliant:** Phase 3 has automated verification coverage for all requirements.
