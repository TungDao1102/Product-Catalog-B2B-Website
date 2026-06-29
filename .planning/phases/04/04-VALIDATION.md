---
phase: 04
slug: multi-language-and-seo
status: audited
nyquist_compliant: true
last_audited: 2026-06-29
---

# Phase 04 — Validation Strategy

> Per-phase validation contract for Multi-language & SEO phase.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | PHPUnit 11.x (Laravel default) |
| **Config file** | `phpunit.xml` |
| **Quick run command** | `php artisan test --parallel` |
| **Full suite command** | `php artisan test` |
| **Estimated runtime** | ~60s |

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
| 04-01-01 | 01 | 1 | REQ-DB-01 — JSON column migration | `tests/Feature/Migration/TranslatableColumnsTest.php` | `php artisan test --filter=TranslatableColumnsTest` | ✅ | ● green* |
| 04-01-02 | 01 | 1 | REQ-i18n-01 — HasTranslations trait | `tests/Feature/Models/TranslatableModelsTest.php` | `php artisan test --filter=TranslatableModelsTest` | ✅ | ● green |
| 04-02-01 | 02 | 1 | REQ-i18n-03 — Locale routing + robots.txt | `tests/Feature/Http/LocaleRoutingTest.php` | `php artisan test --filter=LocaleRoutingTest` | ✅ | ● green |
| 04-03-01 | 03 | 2 | REQ-i18n-02 — Filament tabbed locale UI | `tests/Feature/Filament/TranslatableResourcesTest.php` | `php artisan test --filter=TranslatableResourcesTest` | ✅ | ● green |
| 04-04-01 | 04 | 2 | REQ-SEO-01 — Lang switcher + OG + hreflang | `tests/Feature/Http/SeoMetaTagsTest.php` | `php artisan test --filter=SeoMetaTagsTest` | ✅ | ● green |
| 04-05-01 | 05 | 3 | REQ-i18n-03 — Views i18n coverage | `tests/Feature/Http/LocaleRoutingTest.php` | `php artisan test --filter=LocaleRoutingTest` | ✅ | ● green |
| 04-06-01 | 06 | 3 | REQ-SEO-02 — Sitemap generation | `tests/Feature/SitemapTest.php` | `php artisan test --filter=SitemapTest` | ✅ | ● green* |
| 04-06-02 | 06 | 3 | REQ-SEO-03 — $seo data in controllers | `tests/Feature/Http/SeoMetaTagsTest.php` | `php artisan test --filter=SeoMetaTagsTest` | ✅ | ● green |
| 04-07-01 | 07 | 2 | REQ-i18n-04 — JSON path search queries | `tests/Feature/Http/SearchQueryTest.php` | `php artisan test --filter=SearchQueryTest` | ✅ | ● green |
| 04-07-02 | 07 | 2 | REQ-DB-02 — Bilingual seeders | `tests/Feature/Migration/TranslatableColumnsTest.php` | `php artisan test --filter=TranslatableColumnsTest` | ✅ | ● green* |

*Status: ○ pending ● green ✗ red ~ flaky — green* = requires MySQL*

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| Language switcher dropdown UI | REQ-SEO-01 | Client-side JS/CSS | Visit any frontend page, verify language dropdown appears in header with VI/EN options |
| OG tag rendering in browser | REQ-SEO-01 | Visual/social preview check | Open browser DevTools, inspect `<head>` for og:title, og:description, og:image |
| Sitemap URL validity | REQ-SEO-02 | External URL check | Open public/sitemap.xml in browser, verify all URLs resolve correctly |
| Email notification i18n | REQ-i18n-03 | SMTP-dependent | Submit contact form with SMTP configured, verify email body uses correct locale |

---

## Validation Audit 2026-06-29

| Metric | Count |
|--------|-------|
| Gaps found | 7 |
| Resolved (test files created) | 7 |
| Escalated | 0 |

---

## Validation Sign-Off

- [x] All tasks have automated verify commands
- [x] Sampling continuity: no 3 consecutive tasks without automated verify
- [x] Wave 0 covers all MISSING references
- [x] No watch-mode flags
- [x] Feedback latency < 60s
- [x] `nyquist_compliant: true` set in frontmatter

**Nyquist-compliant:** Phase 4 has automated verification coverage for all requirements.
