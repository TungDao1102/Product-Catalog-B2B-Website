---
phase: 04-i18n-seo
plan: 02
subsystem: routing
tags: [laravel, middleware, i18n, locale, routing, seo]

requires:
  - phase: 03-frontend
    provides: Frontend routes, controllers, and views
  - phase: 04-01
    provides: Translatable models, JSON column migration

provides:
  - SetLocale middleware that detects locale from URL prefix and sets app locale
  - Locale-prefixed route group for all frontend routes (/{locale}/...)
  - Root / redirect to /vi or /en based on Accept-Language header
  - robots.txt route returning Allow: /
  - Middleware alias registration in bootstrap/app.php

affects: [04-03, 04-04, 04-05]

tech-stack:
  added: []
  patterns:
    - Locale prefix routing for multi-language URL structure
    - SetLocale middleware with whitelist validation
    - URL::defaults() for automatic locale parameter in route() helper

key-files:
  created:
    - app/Http/Middleware/SetLocale.php
  modified:
    - routes/web.php
    - bootstrap/app.php

key-decisions:
  - "SetLocale middleware validates locale against whitelist ['vi', 'en'], falls back to 'vi' for invalid values"
  - "Root redirect uses browser Accept-Language header to determine initial locale, defaults to 'vi'"
  - "SetLocale class FQCN used in routes/web.php middleware array, not string alias"
  - "robots.txt implemented as route closure (not static file) for dynamic Sitemap URL generation"
  - "URL::defaults(['locale' => $locale]) set in middleware so route() helper automatically includes locale prefix"

patterns-established:
  - "Route prefix pattern: Route::prefix('{locale}')->where(['locale' => '[a-z]{2}'])->middleware([SetLocale::class])"

requirements-completed:
  - REQ-i18n-03

duration: 3min
completed: 2026-06-28
status: complete
---

# Phase 4 Plan 2: Locale-based Routing Infrastructure Summary

**SetLocale middleware that reads locale from URL prefix with whitelist validation, all frontend routes wrapped in `/{locale}` group, root `/` redirect to `/vi` or `/en`, and robots.txt route**

## Performance

- **Duration:** 3 min
- **Started:** 2026-06-28T02:24:35Z
- **Completed:** 2026-06-28T02:26:26Z
- **Tasks:** 3
- **Files modified:** 3

## Accomplishments

- Created `SetLocale` middleware with locale detection from URL segment(1), whitelist validation `['vi', 'en']`, app locale setting, and URL defaults configuration
- Restructured `routes/web.php` with all frontend routes inside `Route::prefix('{locale}')` group, added root redirect based on Accept-Language header, added robots.txt route
- Registered `setlocale` middleware alias in `bootstrap/app.php` via `$middleware->alias()`

## Task Commits

Each task was committed atomically:

1. **Task 1: Create SetLocale middleware** - `e53f5ab` (feat)
2. **Task 2: Restructure routes/web.php** - `42d9bee` (feat)
3. **Task 3: Register middleware in bootstrap/app.php** - `e846476` (feat)

## Files Created/Modified

- `app/Http/Middleware/SetLocale.php` - New middleware: reads locale from URL segment(1), validates against `['vi', 'en']`, sets app locale and URL defaults
- `routes/web.php` - Restructured: root redirect, robots.txt, all frontend routes inside `/{locale}` prefix group with SetLocale middleware
- `bootstrap/app.php` - Updated: registered `SetLocale` as `'setlocale'` middleware alias

## Decisions Made

- Used `SetLocale::class` FQCN in route middleware array rather than string alias for type safety
- Root redirect uses simple closure (prevents full route caching but acceptable per research finding that locale prefix also prevents caching)
- robots.txt as route closure enables dynamic Sitemap URL generation via `url('/sitemap.xml')`

## Deviations from Plan

None - plan executed exactly as written.

### Noteworthy Implementation Details

- The plan specified `->middleware(['web', SetLocale::class])` but the `web` middleware group is already applied by Laravel 11's `->withRouting(web: ...)` configuration in `bootstrap/app.php`. Adding it explicitly would duplicate the middleware group. Used `->middleware([SetLocale::class])` instead — the `web` group is automatically applied to all routes in `web.php`.

## Issues Encountered

None

## User Setup Required

None - no external service configuration required.

## Next Phase Readiness

- Locale routing infrastructure is complete
- Ready for language switcher component (Plan 04-03) and Blade UI string translation (Plan 04-04)
- Admin routes confirmed unaffected — all `admin/*` routes remain accessible without locale prefix

## Self-Check: PASSED

- [x] `app/Http/Middleware/SetLocale.php` — exists
- [x] `.planning/phases/04/04-02-SUMMARY.md` — exists
- [x] `e53f5ab` — commit: SetLocale middleware created
- [x] `42d9bee` — commit: routes restructured with locale prefix
- [x] `e846476` — commit: middleware registered in bootstrap/app.php
- [x] `7a4e1ab` — commit: summary created

---

*Phase: 04-i18n-seo*
*Completed: 2026-06-28*
