---
phase: 04
plan: 04
subsystem: i18n-ui
tags: [lang-files, language-switcher, og-meta, hreflang, navbar-i18n]
requires:
  - 04-02
provides:
  - 04-05
  - 04-06
affects:
  - resources/views/layouts/app.blade.php
  - resources/views/partials/navbar.blade.php
tech-stack:
  added: []
  patterns: [Laravel lang files, Blade __() helper, OG meta @yield, hreflang alternate links]
key-files:
  created:
    - lang/vi/navigation.php
    - lang/vi/common.php
    - lang/vi/products.php
    - lang/vi/validation.php
    - lang/vi/pagination.php
    - lang/vi/seo.php
    - lang/en/navigation.php
    - lang/en/common.php
    - lang/en/products.php
    - lang/en/validation.php
    - lang/en/pagination.php
    - lang/en/seo.php
    - resources/views/partials/language-switcher.blade.php
  modified:
    - resources/views/partials/navbar.blade.php
    - resources/views/layouts/app.blade.php
decisions:
  - "Language switcher uses url() helper with path-based locale prefix switching (not route() helper) to handle all routes uniformly"
  - "OG meta tags use @yield with sensible defaults (config('app.name'), __('seo.home_description'), asset('img/og-default.jpg'))"
  - "hreflang x-default points to Vietnamese (/vi) as the default locale per D-02"
  - "html lang attribute uses vi_VN/en_US format with dash separator for BCP 47 compliance"
metrics:
  duration: 3 tasks
  completed_date: "2026-06-28"
status: complete
---

# Phase 4 Plan 4: Language Files, Switcher & OG Meta Structure Summary

**One-liner:** Created 12 bilingual lang files (VI/EN), language-switcher dropdown component, and updated layout with OG meta yields and hreflang alternate links.

## Tasks Completed

| # | Task | Commit | Files |
|---|------|--------|-------|
| 1 | Create lang/vi/ and lang/en/ files | cd46bec | 12 lang files (6 per locale) |
| 2 | Create language-switcher Blade component | 75b8eb0 | resources/views/partials/language-switcher.blade.php |
| 3 | Update navbar and layout with lang() calls + OG structure | bc68707 | navbar.blade.php, app.blade.php |

## What Was Built

### Task 1: Bilingual Language Files (12 files)
- **lang/vi/**: navigation, common, products, validation, pagination, seo — all Vietnamese
- **lang/en/**: navigation, common, products, validation, pagination, seo — all English
- Verified: `__('navigation.home', [], 'vi')` returns "Trang chủ", `__('navigation.home', [], 'en')` returns "Home"
- All keys match the plan specification exactly

### Task 2: Language Switcher Component
- Bootstrap 5 dropdown with current locale as button label (VI/EN)
- Dropdown menu shows the other locale option
- Path preservation: strips current locale prefix, reconstructs URL with new locale
- Uses `url()` helper for uniform path handling across all routes

### Task 3: Navbar & Layout Updates
- **Navbar**: All 6 hardcoded Vietnamese labels replaced with `__('navigation.*')` calls
- **Navbar**: `@include('partials.language-switcher')` added in right section before search icon
- **Layout**: 6 OG meta tags added (og:title, og:description, og:image, og:type, og:url, og:locale)
- **Layout**: 3 alternate hreflang links added (vi, en, x-default)
- **Layout**: `<html lang>` changed from static `vi` to dynamic locale-based attribute

## Verification

| Check | Status |
|-------|--------|
| 12 lang files exist (6 per locale) | ✓ |
| `__('navigation.home', [], 'vi')` returns Vietnamese | ✓ |
| `__('navigation.home', [], 'en')` returns English | ✓ |
| language-switcher.blade.php exists | ✓ |
| Navbar labels use `__()` calls | ✓ |
| Language switcher included in navbar | ✓ |
| OG meta tags in layout `<head>` | ✓ |
| Alternate hreflang links in layout `<head>` | ✓ |
| `<html lang>` reflects current locale | ✓ |

## Deviations from Plan

None — plan executed exactly as written.

## Known Stubs

None — all files contain complete, production-ready content.

## Threat Flags

None — no new security surface beyond what is documented in the plan's threat model (T-04-04-01: language-switcher path extraction, accepted).

## Self-Check: PASSED

- All 15 files confirmed on disk
- All 3 commits confirmed in git history (cd46bec, 75b8eb0, bc68707)
- Lang files verified via `php artisan tinker`
