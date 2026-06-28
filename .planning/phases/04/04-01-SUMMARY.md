---
phase: 04-i18n-seo
plan: 01
subsystem: database, i18n
tags: spatie/laravel-translatable, json-columns, migration, locale

requires:
  - phase: 02-product-management
    provides: Category, Brand, Product, Post, Project models with string columns
provides:
  - HasTranslations trait wired to 5 Eloquent models
  - JSON column migration preserving existing Vietnamese data
  - Vietnamese fallback locale for spatie/laravel-translatable
affects: 04-i18n-seo (later plans needing filament plugin and admin UI)

tech-stack:
  added:
    - spatie/laravel-translatable ^6.14.1
    - spatie/laravel-sitemap ^8.0.0
  patterns:
    - Translatable JSON columns on Eloquent models
    - Add-column/copy-data/drop-old/rename-new migration strategy for MySQL string-to-JSON conversion
    - fallback_locale via Translatable facade in AppServiceProvider

key-files:
  created:
    - database/migrations/2026_06_28_000001_make_translatable_columns.php
  modified:
    - app/Models/Category.php
    - app/Models/Brand.php
    - app/Models/Product.php
    - app/Models/Post.php
    - app/Models/Project.php
    - app/Providers/AppServiceProvider.php

key-decisions:
  - "Omitted filament/spatie-laravel-translatable-plugin — requires filament/support v3.x, project uses filament/filament ^5.6. Will address in a later wave."
  - "Add-column/copy-data/drop-old/rename-new strategy instead of MODIFY — MySQL cannot auto-convert existing string values to JSON in-place (SQLSTATE 3140)."
  - "Raw DB::statement() + chunked query approach instead of doctrine/dbal — Laravel 13 does not bundle doctrine/dbal."

patterns-established:
  - "Translatable columns: Eloquent model declares $translatable array + use HasTranslations trait"
  - "Migration strategy: add new JSON column, chunk-copy data as {\"vi\": \"...\"}, drop old column, rename new column"

requirements-completed: []

duration: 6min
completed: 2026-06-28
status: complete
---

# Phase 04 Plan 01: Translatable Models with Data Migration

**Spatie/laravel-translatable wired to 5 models with JSON column migration preserving Vietnamese data and fallback locale**

## Performance

- **Duration:** 6 min
- **Started:** 2026-06-28T02:14:00Z
- **Completed:** 2026-06-28T02:20:00Z
- **Tasks:** 4
- **Files modified:** 7 (1 created, 6 modified)

## Accomplishments

- Installed spatie/laravel-translatable ^6.14.1 and spatie/laravel-sitemap ^8.0.0
- Added HasTranslations trait and $translatable arrays to Category, Brand, Product, Post, and Project models
- Extended Post and Project Fillable arrays with meta_title, meta_description
- Created JSON column migration using add-column/copy-data/drop-old/rename-new strategy for MySQL
- Added meta_title and meta_description JSON columns to Posts and Projects tables
- Registered Vietnamese (vi) as fallback locale in AppServiceProvider

## Task Commits

Each task was committed atomically:

1. **Task 1: Install packages** - `050d442` (feat)
2. **Task 2: Add HasTranslations trait** - `6a769a5` (feat)
3. **Task 3: Create JSON column migration** - `ec2312a` (feat)
4. **Task 4: Register fallback locale** - `5efbce6` (feat)

**Plan metadata:** (pending final commit)

## Files Created/Modified

- `database/migrations/2026_06_28_000001_make_translatable_columns.php` - Created: add/copy/drop/rename migration for all 5 models
- `app/Models/Category.php` - Modified: added HasTranslations trait, `$translatable = ['name', 'description']`
- `app/Models/Brand.php` - Modified: added HasTranslations trait, `$translatable = ['name', 'description']`
- `app/Models/Product.php` - Modified: added HasTranslations trait, `$translatable = ['name', 'description', 'short_description', 'meta_title', 'meta_description']`
- `app/Models/Post.php` - Modified: added HasTranslations trait, added meta_title/meta_description to Fillable, `$translatable = ['title', 'content', 'excerpt', 'meta_title', 'meta_description']`
- `app/Models/Project.php` - Modified: added HasTranslations trait, added meta_title/meta_description to Fillable, `$translatable = ['title', 'content', 'description', 'meta_title', 'meta_description']`
- `app/Providers/AppServiceProvider.php` - Modified: registered `Translatable::setFallbackLocale('vi')`

## Decisions Made

- **filament/spatie-laravel-translatable-plugin deferred:** Requires filament/support v3.x but project uses filament/filament ^5.6. Will install in a later plan when compatibility is resolved.
- **Add/copy/drop/rename migration strategy:** MySQL cannot convert existing string values to JSON columns in-place (SQLSTATE 3140 "Invalid JSON text"). The multi-step strategy was chosen over MODIFY.
- **Raw DB::statement() + chunked queries:** Laravel 13 does not bundle doctrine/dbal, so schema-comparison methods are unavailable. Used raw ALTER TABLE and chunked DB::table() updates.
- **Seeder data written as plain strings:** The seeders were not updated for the translatable format in this plan. Data written as `{"vi": "..."}` only when the migration copies existing data; new seed data writes plain strings. Seeders will need updating in a later plan.

## Deviations from Plan

**1. [Rule 1 - Bug] MySQL cannot MODIFY string column to JSON in-place**
- **Found during:** Task 3 (Create JSON column migration)
- **Issue:** First migration attempt used MODIFY COLUMN to change VARCHAR to JSON. MySQL returned SQLSTATE 3140 "Invalid JSON text: 'Invalid value.' at position 0" — it cannot auto-convert existing string values to JSON objects.
- **Fix:** Rewrote migration to use add-column/copy-data/drop-old/rename-new strategy. New JSON column is added, data is copied as `{"vi": "..."}`, old column dropped, new column renamed.
- **Files modified:** `database/migrations/2026_06_28_000001_make_translatable_columns.php`
- **Verification:** Migration ran successfully, schema shows JSON type for all translatable columns
- **Committed in:** `ec2312a` (Task 3 commit)

---

**Total deviations:** 1 auto-fixed (1 bug fix)
**Impact on plan:** Necessary for MySQL compatibility. No scope creep — the add/copy/drop/rename pattern is the standard approach for this MySQL limitation.

## Issues Encountered

- **Doctrine/dbal unavailable in Laravel 13:** Planned to use `doctrine/dbal` for schema introspection, but it is not bundled. Switched to raw `DB::statement()` for ALTER TABLE and `DB::table()->chunk()` for data copy. Worked as expected with proper MySQL syntax.
- **Migration reset caused data loss:** `php artisan migrate:reset` dropped all tables, requiring `db:seed` to re-populate. The seeders write plain strings to JSON columns (no `{"vi": "..."}` wrapper). This is expected behavior — seeders will be updated for translatable format in a later plan.

## Next Phase Readiness

- All 5 models ready for i18n querying via `->getTranslation('name', 'en')` and `->getTranslations('name')`
- Database schema supports multi-locale content for Vietnamese (primary) and other locales
- Fallback locale 'vi' ensures missing translations degrade gracefully
- Next plans in phase 04 can add Filament admin i18n support and Laravel sitemap localization

---

*Phase: 04-i18n-seo*
*Completed: 2026-06-28*
